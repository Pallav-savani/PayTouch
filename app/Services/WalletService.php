<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\WalletBankAccount;
use App\Events\WalletTransactionEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class WalletService
{
    protected $mobikwikService;

    public function __construct(MobiKwikService $mobikwikService)
    {
        $this->mobikwikService = $mobikwikService;
    }

    public function createWalletForUser(User $user)
    {
        try {
            DB::beginTransaction();

            // Create local wallet
            $wallet = Wallet::create([
                'user_id' => $user->id,
                'wallet_id' => $this->generateUniqueWalletId($user->id),
                'balance' => 0,
                'status' => 'active'
            ]);

            // Create MobiKwik wallet
            $mobikwikResponse = $this->mobikwikService->createWallet([
                'mobile' => $user->mobile,
                'email' => $user->email,
                'name' => $user->name
            ]);

            if (isset($mobikwikResponse['walletid'])) {
                $wallet->update([
                    'mobikwik_wallet_id' => $mobikwikResponse['walletid']
                ]);
            }

            DB::commit();

            Log::info('Wallet created successfully', [
                'user_id' => $user->id,
                'wallet_id' => $wallet->wallet_id,
                'mobikwik_wallet_id' => $wallet->mobikwik_wallet_id
            ]);

            return $wallet;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Wallet creation failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function addMoney(Wallet $wallet, $amount, $description = null)
    {
        try {
            DB::beginTransaction();

            $orderId = $this->generateOrderId();
            
            // Create pending transaction
            $transaction = WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'transaction_id' => $this->generateTransactionId(),
                'type' => 'credit',
                'amount' => $amount,
                'balance_before' => $wallet->balance,
                'balance_after' => $wallet->balance + $amount,
                'status' => 'pending',
                'description' => $description ?? 'Money added to wallet',
                'reference_id' => $orderId
            ]);

            // Call MobiKwik API
            $mobikwikResponse = $this->mobikwikService->addMoneyToWallet(
                $wallet->mobikwik_wallet_id,
                $amount,
                $orderId
            );

            if (isset($mobikwikResponse['paymenturl'])) {
                $transaction->update([
                    'gateway_response' => $mobikwikResponse
                ]);

                DB::commit();
                return [
                    'success' => true,
                    'payment_url' => $mobikwikResponse['paymenturl'],
                    'transaction_id' => $transaction->transaction_id,
                    'order_id' => $orderId
                ];
            }

            throw new Exception('Invalid MobiKwik response');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Add money failed', [
                'wallet_id' => $wallet->id,
                'amount' => $amount,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function transferMoney(Wallet $fromWallet, $toWalletId, $amount, $description = null)
    {
        try {
            DB::beginTransaction();

            $toWallet = Wallet::where('wallet_id', $toWalletId)->first();
            if (!$toWallet) {
                throw new Exception('Recipient wallet not found');
            }

            if (!$fromWallet->canTransact($amount)) {
                throw new Exception('Insufficient balance or wallet inactive');
            }

            $orderId = $this->generateOrderId();

            // Create debit transaction for sender
            $debitTransaction = WalletTransaction::create([
                'wallet_id' => $fromWallet->id,
                'transaction_id' => $this->generateTransactionId(),
                'type' => 'transfer_out',
                'amount' => $amount,
                'balance_before' => $fromWallet->balance,
                'balance_after' => $fromWallet->balance - $amount,
                'status' => 'pending',
                'description' => $description ?? "Transfer to {$toWallet->wallet_id}",
                'reference_id' => $orderId
            ]);

            // Create credit transaction for receiver
            $creditTransaction = WalletTransaction::create([
                'wallet_id' => $toWallet->id,
                'transaction_id' => $this->generateTransactionId(),
                'type' => 'transfer_in',
                'amount' => $amount,
                'balance_before' => $toWallet->balance,
                'balance_after' => $toWallet->balance + $amount,
                'status' => 'pending',
                'description' => $description ?? "Transfer from {$fromWallet->wallet_id}",
                'reference_id' => $orderId
            ]);

            // Call MobiKwik transfer API
            $mobikwikResponse = $this->mobikwikService->transferMoney(
                $fromWallet->mobikwik_wallet_id,
                $toWallet->mobikwik_wallet_id,
                $amount,
                $orderId
            );

            if (isset($mobikwikResponse['status']) && $mobikwikResponse['status'] === 'SUCCESS') {
                // Update balances
                $fromWallet->update([
                    'balance' => $fromWallet->balance - $amount,
                    'total_spent' => $fromWallet->total_spent + $amount,
                    'last_transaction_at' => now()
                ]);

                $toWallet->update([
                    'balance' => $toWallet->balance + $amount,
                    'total_loaded' => $toWallet->total_loaded + $amount,
                    'last_transaction_at' => now()
                ]);

                // Update transactions
                $debitTransaction->update([
                    'status' => 'completed',
                    'mobikwik_transaction_id' => $mobikwikResponse['transactionid'] ?? null,
                    'gateway_response' => $mobikwikResponse,
                    'processed_at' => now()
                ]);

                $creditTransaction->update([
                    'status' => 'completed',
                    'mobikwik_transaction_id' => $mobikwikResponse['transactionid'] ?? null,
                    'gateway_response' => $mobikwikResponse,
                    'processed_at' => now()
                ]);

                // Fire events
                event(new WalletTransactionEvent($debitTransaction));
                event(new WalletTransactionEvent($creditTransaction));

                DB::commit();

                return [
                    'success' => true,
                    'message' => 'Transfer completed successfully',
                    'transaction_id' => $debitTransaction->transaction_id
                ];
            }

            throw new Exception('Transfer failed: ' . ($mobikwikResponse['message'] ?? 'Unknown error'));

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Transfer failed', [
                'from_wallet' => $fromWallet->wallet_id,
                'to_wallet' => $toWalletId,
                'amount' => $amount,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function addBankAccount(Wallet $wallet, array $bankData)
    {
        try {
            // Set other accounts as non-primary if this is primary
            if ($bankData['is_primary'] ?? false) {
                WalletBankAccount::where('wallet_id', $wallet->id)
                    ->update(['is_primary' => false]);
            }

            $bankAccount = WalletBankAccount::create([
                'wallet_id' => $wallet->id,
                'account_holder_name' => $bankData['account_holder_name'],
                'account_number' => $bankData['account_number'],
                'ifsc_code' => $bankData['ifsc_code'],
                'bank_name' => $bankData['bank_name'],
                'account_type' => $bankData['account_type'] ?? 'savings',
                'is_primary' => $bankData['is_primary'] ?? false
            ]);

            Log::info('Bank account added', [
                'wallet_id' => $wallet->id,
                'bank_account_id' => $bankAccount->id
            ]);

            return $bankAccount;

        } catch (Exception $e) {
            Log::error('Add bank account failed', [
                'wallet_id' => $wallet->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function syncWalletBalance(Wallet $wallet)
    {
        try {
            if (!$wallet->mobikwik_wallet_id) {
                throw new Exception('MobiKwik wallet ID not found');
            }

            $balanceResponse = $this->mobikwikService->getWalletBalance($wallet->mobikwik_wallet_id);

            if (isset($balanceResponse['balance'])) {
                $wallet->update([
                    'balance' => $balanceResponse['balance']
                ]);

                return $balanceResponse['balance'];
            }

            throw new Exception('Invalid balance response');

        } catch (Exception $e) {
            Log::error('Balance sync failed', [
                'wallet_id' => $wallet->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    private function generateUniqueWalletId($userId)
    {
        do {
            $walletId = 'PWT' . str_pad($userId, 6, '0', STR_PAD_LEFT) . rand(1000, 9999);
        } while (Wallet::where('wallet_id', $walletId)->exists());

        return $walletId;
    }

    private function generateTransactionId()
    {
        do {
            $transactionId = 'TXN' . date('YmdHis') . rand(1000, 9999);
        } while (WalletTransaction::where('transaction_id', $transactionId)->exists());

        return $transactionId;
    }

    private function generateOrderId()
    {
        return 'ORD' . date('YmdHis') . rand(1000, 9999);
    }
}
