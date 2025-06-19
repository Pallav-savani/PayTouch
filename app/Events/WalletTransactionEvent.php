<?php

namespace App\Events;

use App\Models\WalletTransaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WalletTransactionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $transaction;

    public function __construct(WalletTransaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('wallet.' . $this->transaction->wallet->user_id);
    }
}
