<?php

namespace App\Notifications;

use App\Models\WalletTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WalletTransactionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $transaction;

    public function __construct(WalletTransaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = 'Wallet Transaction - ' . ucfirst($this->transaction->type);
        
        return (new MailMessage)
            ->subject($subject)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new transaction has been processed in your wallet.')
            ->line('Transaction Details:')
            ->line('Type: ' . ucfirst($this->transaction->type))
            ->line('Amount: ₹' . number_format($this->transaction->amount, 2))
            ->line('Description: ' . $this->transaction->description)
            ->line('Current Balance: ₹' . number_format($this->transaction->balance_after, 2))
            ->line('Transaction ID: ' . $this->transaction->transaction_id)
            ->action('View Wallet', url('/wallet'))
            ->line('Thank you for using our wallet service!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'transaction_id' => $this->transaction->transaction_id,
            'type' => $this->transaction->type,
            'amount' => $this->transaction->amount,
            'balance_after' => $this->transaction->balance_after,
            'description' => $this->transaction->description,
            'status' => $this->transaction->status
        ];
    }
}