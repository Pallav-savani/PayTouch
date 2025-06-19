<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'mobikwik_order_id',
        'amount',
        'type',
        'status',
        'payment_mode',
        'wallet_amount',
        'cash_amount',
        'response_data',
        'description',
    ];

    protected $casts = [
        'response_data' => 'array',
        'amount' => 'decimal:2',
        'wallet_amount' => 'decimal:2',
        'cash_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}