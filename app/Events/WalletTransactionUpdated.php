<?php

namespace App\Events;

use App\Models\WalletTransaction;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WalletTransactionUpdated
{
    use Dispatchable, SerializesModels;

    public WalletTransaction $transaction;

    public function __construct(WalletTransaction $transaction)
    {
        $this->transaction = $transaction;
    }
}