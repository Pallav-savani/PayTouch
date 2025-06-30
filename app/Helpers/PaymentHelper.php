<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\WalletTransaction;

class PaymentHelper
{
    public static function calculatePaymentBreakdown(User $user, float $amount): array
    {
        $walletBalance = $user->wallet_balance;
        
        if ($walletBalance >= $amount) {
            return [
                'payment_mode' => 'wallet',
                'wallet_amount' => $amount,
                'gateway_amount' => 0,
                'total_amount' => $amount,
                'sufficient_wallet_balance' => true
            ];
        } elseif ($walletBalance > 0) {
            return [
                'payment_mode' => 'mixed',
                'wallet_amount' => $walletBalance,
                'gateway_amount' => $amount - $walletBalance,
                'total_amount' => $amount,
                'sufficient_wallet_balance' => false
            ];
        } else {
            return [
                'payment_mode' => 'gateway',
                'wallet_amount' => 0,
                'gateway_amount' => $amount,
                'total_amount' => $amount,
                'sufficient_wallet_balance' => false
            ];
        }
    }

    public static function getTransactionStatus(string $transactionId): ?WalletTransaction
    {
        return WalletTransaction::where('transaction_id', $transactionId)->first();
    }

    public static function formatCurrency(float $amount): string
    {
        return '₹' . number_format($amount, 2);
    }
}