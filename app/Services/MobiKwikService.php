<?php

namespace App\Services;

use App\Models\User;
use App\Models\WalletTransaction;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MobiKwikService
{
    private Client $client;
    private array $config;

    public function __construct()
    {
        $this->client = new Client();
        $this->config = config('mobikwik');
    }

    public function processPayment(User $user, float $totalAmount, string $description = null): array
    {
        $walletBalance = $user->wallet_balance;
        
        // Determine payment mode
        if ($walletBalance >= $totalAmount) {
            return $this->processWalletPayment($user, $totalAmount, $description);
        } elseif ($walletBalance > 0) {
            return $this->processMixedPayment($user, $totalAmount, $walletBalance, $description);
        } else {
            return $this->processCashPayment($user, $totalAmount, $description);
        }
    }

    private function processWalletPayment(User $user, float $amount, string $description = null): array
    {
        $transactionId = $this->generateTransactionId();
        
        // Create transaction record
        $transaction = WalletTransaction::createWallet([
            'user_id' => $user->id,
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'type' => 'debit',
            'status' => 'pending',
            'payment_mode' => 'wallet',
            'wallet_amount' => $amount,
            'cash_amount' => 0,
            'description' => $description,
        ]);

        // Deduct from wallet
        if ($user->deductBalance($amount)) {
            $transaction->update(['status' => 'success']);
            
            return [
                'success' => true,
                'payment_mode' => 'wallet',
                'transaction_id' => $transactionId,
                'redirect_required' => false,
                'message' => 'Payment successful from wallet'
            ];
        }

        $transaction->update(['status' => 'failed']);
        return [
            'success' => false,
            'message' => 'Insufficient wallet balance'
        ];
    }

    private function processMixedPayment(User $user, float $totalAmount, float $walletBalance, string $description = null): array
    {
        $cashAmount = $totalAmount - $walletBalance;
        $transactionId = $this->generateTransactionId();
        
        // Create transaction record
        $transaction = WalletTransaction::create([
            'user_id' => $user->id,
            'transaction_id' => $transactionId,
            'amount' => $totalAmount,
            'type' => 'debit',
            'status' => 'pending',
            'payment_mode' => 'mixed',
            'wallet_amount' => $walletBalance,
            'cash_amount' => $cashAmount,
            'description' => $description,
        ]);

        // Deduct wallet amount immediately
        $user->deductBalance($walletBalance);

        // Create MobiKwik payment request for remaining amount
        $paymentUrl = $this->createMobiKwikPayment($user, $cashAmount, $transactionId, $description);
        
        if ($paymentUrl) {
            $transaction->update([
                'mobikwik_order_id' => $transactionId,
            ]);

            return [
                'success' => true,
                'payment_mode' => 'mixed',
                'transaction_id' => $transactionId,
                'redirect_required' => true,
                'redirect_url' => $paymentUrl,
                'wallet_amount' => $walletBalance,
                'cash_amount' => $cashAmount,
                'message' => 'Redirecting to complete payment'
            ];
        }

        // Refund wallet amount if MobiKwik payment creation failed
        $user->addBalance($walletBalance);
        $transaction->update(['status' => 'failed']);
        
        return [
            'success' => false,
            'message' => 'Failed to create payment gateway request'
        ];
    }

    private function processCashPayment(User $user, float $amount, string $description = null): array
    {
        $transactionId = $this->generateTransactionId();
        
        // Create transaction record
        $transaction = WalletTransaction::create([
            'user_id' => $user->id,
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'type' => 'debit',
            'status' => 'pending',
            'payment_mode' => 'cash',
            'wallet_amount' => 0,
            'cash_amount' => $amount,
            'description' => $description,
        ]);

        // Create MobiKwik payment request
        $paymentUrl = $this->createMobiKwikPayment($user, $amount, $transactionId, $description);
        
        if ($paymentUrl) {
            $transaction->update([
                'mobikwik_order_id' => $transactionId,
            ]);

            return [
                'success' => true,
                'payment_mode' => 'cash',
                'transaction_id' => $transactionId,
                'redirect_required' => true,
                'redirect_url' => $paymentUrl,
                'message' => 'Redirecting to payment gateway'
            ];
        }

        $transaction->update(['status' => 'failed']);
        return [
            'success' => false,
            'message' => 'Failed to create payment gateway request'
        ];
    }

    private function createMobiKwikPayment(User $user, float $amount, string $transactionId, string $description = null): ?string
    {
        try {
            $orderData = [
                'merchantid' => $this->config['merchant_id'],
                'orderid' => $transactionId,
                'amount' => number_format($amount, 2, '.', ''),
                'redirecturl' => $this->config['redirect_url'],
                'cancelurl' => $this->config['cancel_url'],
                'isrequestfromwalletpg' => 1,
                'wallet' => 0, // Force gateway payment
            ];

            // Generate checksum
            $checksum = $this->generateChecksum($orderData);
            $orderData['checksum'] = $checksum;

            // Create form URL
            $paymentUrl = $this->config['base_url'] . '/wallet?' . http_build_query($orderData);
            
            return $paymentUrl;

        } catch (\Exception $e) {
            Log::error('MobiKwik payment creation failed: ' . $e->getMessage());
            return null;
        }
    }

    public function handleCallback(array $responseData): array
    {
        try {
            // Verify checksum
            if (!$this->verifyChecksum($responseData)) {
                return ['success' => false, 'message' => 'Invalid checksum'];
            }

            $transactionId = $responseData['orderid'] ?? null;
            $status = $responseData['statuscode'] ?? null;
            
            if (!$transactionId) {
                return ['success' => false, 'message' => 'Transaction ID not found'];
            }

            $transaction = WalletTransaction::where('transaction_id', $transactionId)->first();
            
            if (!$transaction) {
                return ['success' => false, 'message' => 'Transaction not found'];
            }

            // Update transaction with response data
            $transaction->update([
                'response_data' => $responseData,
            ]);

            if ($status === 'SUCCESS') {
                $transaction->update(['status' => 'success']);
                
                return [
                    'success' => true,
                    'transaction_id' => $transactionId,
                    'message' => 'Payment successful'
                ];
            } else {
                $transaction->update(['status' => 'failed']);
                
                // Refund wallet amount if it was a mixed payment
                if ($transaction->payment_mode === 'mixed' && $transaction->wallet_amount > 0) {
                    $transaction->user->addBalance($transaction->wallet_amount);
                }
                
                return [
                    'success' => false,
                    'transaction_id' => $transactionId,
                    'message' => 'Payment failed'
                ];
            }

        } catch (\Exception $e) {
            Log::error('MobiKwik callback handling failed: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Callback processing failed'];
        }
    }

    public function addMoneyToWallet(User $user, float $amount): array
    {
        $transactionId = $this->generateTransactionId();
        
        // Create transaction record
        $transaction = WalletTransaction::create([
            'user_id' => $user->id,
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'type' => 'credit',
            'status' => 'pending',
            'payment_mode' => 'cash',
            'wallet_amount' => 0,
            'cash_amount' => $amount,
            'description' => 'Add money to wallet',
        ]);

        // Create MobiKwik payment request
        $paymentUrl = $this->createMobiKwikPayment($user, $amount, $transactionId, 'Add money to wallet');
        
        if ($paymentUrl) {
            $transaction->update([
                'mobikwik_order_id' => $transactionId,
            ]);

            return [
                'success' => true,
                'transaction_id' => $transactionId,
                'redirect_url' => $paymentUrl,
                'message' => 'Redirecting to add money'
            ];
        }

        $transaction->update(['status' => 'failed']);
        return [
            'success' => false,
            'message' => 'Failed to create add money request'
        ];
    }

    public function handleWalletTopupCallback(array $responseData): array
    {
        $result = $this->handleCallback($responseData);
        
        if ($result['success']) {
            $transactionId = $result['transaction_id'];
            $transaction = WalletTransaction::where('transaction_id', $transactionId)->first();
            
            if ($transaction && $transaction->type === 'credit') {
                // Add money to wallet
                $transaction->user->addBalance($transaction->amount);
            }
        }
        
        return $result;
    }

    private function generateTransactionId(): string
    {
        return 'TXN_' . time() . '_' . Str::random(10);
    }

    private function generateChecksum(array $data): string
    {
        $checksumString = '';
        foreach ($data as $key => $value) {
            if ($key !== 'checksum') {
                $checksumString .= $value;
            }
        }
        $checksumString .= $this->config['secret_key'];
        
        return hash('sha256', $checksumString);
    }

    private function verifyChecksum(array $data): bool
    {
        $receivedChecksum = $data['checksum'] ?? '';
        unset($data['checksum']);
        
        $generatedChecksum = $this->generateChecksum($data);
        
        return hash_equals($generatedChecksum, $receivedChecksum);
    }
}