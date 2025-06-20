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
use App\Services\MobiKwikService;

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

            // Check if wallet already exists
            $existingWallet = Wallet::where('user_id', $user->id)->first();
            if ($existingWallet) {
                DB::commit();
                return $existingWallet;
            }

            // Generate unique wallet ID
            $walletId = $this->generateUniqueWalletId($user->id);

            // Create MobiKwik wallet first
            $mobikwikData = [
                'mobile' => $user->mobile ?? $user->phone ?? $user->phone_number ?? null,
                'email' => $user->email,
                'name' => $user->name ?? ($user->first_name . ' ' . $user->last_name) ?? 'User'
            ];

            // Clean up name field
            $mobikwikData['name'] = trim($mobikwikData['name']);
            if (empty($mobikwikData['name']) || $mobikwikData['name'] === ' ') {
                $mobikwikData['name'] = 'User';
            }

            // Validate required fields before making API call
            if (empty($mobikwikData['email'])) {
                throw new Exception('User email is required for wallet creation');
            }

            if (empty($mobikwikData['mobile'])) {
                Log::warning('User mobile number not found, using default', ['user_id' => $user->id]);
                $mobikwikData['mobile'] = '9999999999'; // Default mobile for testing
            }

            $mobikwikResponse = $this->mobikwikService->createWallet($mobikwikData);
            $mobikwikWalletId = null;

            if (isset($mobikwikResponse['walletid'])) {
                $mobikwikWalletId = $mobikwikResponse['walletid'];
            }

            // Create local wallet with all fields from migration
            $wallet = Wallet::create([
                'user_id' => $user->id,
                'wallet_id' => $walletId,
                'balance' => $user->wallet_balance ?? 0.00,
                'status' => 'active',
                'mobikwik_wallet_id' => $mobikwikWalletId,
                'is_kyc_verified' => false,
                'daily_limit' => 10000.00,
                'monthly_limit' => 100000.00,
                'total_loaded' => 0.00,
                'total_spent' => 0.00,
                'last_transaction_at' => null
            ]);

            // Create initial transaction record if user has initial balance
            if ($user->wallet_balance > 0) {
                $this->createInitialTransaction($wallet, $user->wallet_balance);
            }

            DB::commit();

            Log::info('Wallet created successfully', [
                'user_id' => $user->id,
                'wallet_id' => $wallet->wallet_id,
                'mobikwik_wallet_id' => $wallet->mobikwik_wallet_id,
                'initial_balance' => $wallet->balance
            ]);

            return $wallet;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Wallet creation failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_data' => [
                    'email' => $user->email ?? 'not set',
                    'mobile' => $user->mobile ?? $user->phone ?? $user->phone_number ?? 'not set',
                    'name' => $user->name ?? 'not set'
                ]
            ]);
            throw $e;
        }
    }

    public function getOrCreateWallet(User $user): Wallet
    {
        $wallet = Wallet::where('user_id', $user->id)->first();
        
        if (!$wallet) {
            $wallet = $this->createWalletForUser($user);
        }
        
        return $wallet;
    }

    private function createInitialTransaction(Wallet $wallet, float $amount)
    {
        try {
            WalletTransaction::create([
                'user_id' => $wallet->user_id,
                'wallet_id' => $wallet->id,
                'transaction_id' => $this->generateTransactionId(),
                'type' => 'credit',
                'amount' => $amount,
                'balance_before' => 0.00,
                'balance_after' => $amount,
                'status' => 'success',
                'payment_mode' => 'system',
                'wallet_amount' => $amount,
                'cash_amount' => 0.00,
                'description' => 'Initial wallet balance',
                'reference_id' => 'INIT_' . $wallet->wallet_id,
                'processed_at' => now()
            ]);

            // Update wallet totals
            $wallet->update([
                'total_loaded' => $amount,
                'last_transaction_at' => now()
            ]);

        } catch (Exception $e) {
            Log::error('Failed to create initial transaction', [
                'wallet_id' => $wallet->id,
                'amount' => $amount,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function addMoney(Wallet $wallet, $amount, $description = null)
    {
        try {
            DB::beginTransaction();

            $orderId = $this->generateOrderId();
            
            // Create pending transaction
            $transaction = WalletTransaction::create([
                'user_id' => $wallet->user_id,
                'wallet_id' => $wallet->id,
                'transaction_id' => $this->generateTransactionId(),
                'type' => 'credit',
                'amount' => $amount,
                'balance_before' => $wallet->balance,
                'balance_after' => $wallet->balance + $amount,
                'status' => 'pending',
                'payment_mode' => 'cash',
                'wallet_amount' => 0,
                'cash_amount' => $amount,
                'description' => $description ?? 'Money added to wallet',
                'reference_id' => $orderId
            ]);

            // Call MobiKwik API
            $mobikwikResponse = $this->mobikwikService->addMoneyToWallet(
                $wallet->user,
                $amount
            );

            if ($mobikwikResponse['success']) {
                $transaction->update([
                    'gateway_response' => $mobikwikResponse
                ]);

                DB::commit();
                return [
                    'success' => true,
                    'payment_url' => $mobikwikResponse['redirect_url'],
                    'transaction_id' => $transaction->transaction_id,
                    'order_id' => $orderId
                ];
            }

            throw new Exception($mobikwikResponse['message'] ?? 'Invalid MobiKwik response');

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

    public function updateWalletBalance(Wallet $wallet, float $amount, string $type = 'credit')
    {
        try {
            DB::beginTransaction();

            $oldBalance = $wallet->balance;
            $newBalance = $type === 'credit' ? $oldBalance + $amount : $oldBalance - $amount;

            // Update wallet balance
            $wallet->update([
                'balance' => $newBalance,
                'total_loaded' => $type === 'credit' ? $wallet->total_loaded + $amount : $wallet->total_loaded,
                'total_spent' => $type === 'debit' ? $wallet->total_spent + $amount : $wallet->total_spent,
                'last_transaction_at' => now()
            ]);

            // Update user wallet balance
            $wallet->user->update([
                'wallet_balance' => $newBalance
            ]);

            DB::commit();
            return true;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update wallet balance', [
                'wallet_id' => $wallet->id,
                'amount' => $amount,
                'type' => $type,
                'error' => $e->getMessage()
            ]);
            return false;
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
