<?php

namespace App\Listeners;

use App\Events\WalletTransactionEvent;
use App\Models\User;
use App\Notifications\WalletTransactionNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class WalletTransactionListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(WalletTransactionEvent $event): void
    {
        $transaction = $event->transaction;
        $user = $transaction->wallet->user;

        // Send notification to user
        $user->notify(new WalletTransactionNotification($transaction));

        // Log transaction for audit
        Log::info('Wallet transaction processed', [
            'user_id' => $user->id,
            'transaction_id' => $transaction->transaction_id,
            'type' => $transaction->type,
            'amount' => $transaction->amount,
            'status' => $transaction->status
        ]);
    }
}