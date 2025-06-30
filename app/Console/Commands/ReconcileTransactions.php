<?php

namespace App\Console\Commands;

use App\Models\WalletTransaction;
use App\Services\MobiKwikService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ReconcileTransactions extends Command
{
    protected $signature = 'wallet:reconcile {--hours=24}';
    protected $description = 'Reconcile pending wallet transactions';

    private MobiKwikService $mobikwikService;

    public function __construct(MobiKwikService $mobikwikService)
    {
        parent::__construct();
        $this->mobikwikService = $mobikwikService;
    }

    public function handle()
    {
        $hours = $this->option('hours');
        $cutoffTime = Carbon::now()->subHours($hours);

        $pendingTransactions = WalletTransaction::where('status', 'pending')
            ->where('created_at', '>', $cutoffTime)
            ->where('payment_mode', '!=', 'wallet')
            ->get();

        $this->info("Found {$pendingTransactions->count()} pending transactions to reconcile");

        foreach ($pendingTransactions as $transaction) {
            $this->info("Processing transaction: {$transaction->transaction_id}");
            
            // Here you would typically call MobiKwik API to check transaction status
            // For now, we'll mark very old transactions as failed
            if ($transaction->created_at->diffInHours(now()) > 2) {
                $transaction->update(['status' => 'failed']);
                
                // Refund wallet amount if it was a mixed payment
                if ($transaction->payment_mode === 'mixed' && $transaction->wallet_amount > 0) {
                    $transaction->user->addBalance($transaction->wallet_amount);
                    $this->info("Refunded wallet amount for transaction: {$transaction->transaction_id}");
                }
            }
        }

        $this->info('Transaction reconciliation completed');
    }
}