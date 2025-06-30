<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WalletTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'wallet_id',
        'transaction_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'status',
        'payment_mode',
        'wallet_amount',
        'cash_amount',
        'description',
        'reference_id',
        'mobikwik_order_id',
        'mobikwik_transaction_id',
        'gateway_response',
        'response_data',
        'processed_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'wallet_amount' => 'decimal:2',
        'cash_amount' => 'decimal:2',
        'gateway_response' => 'array',
        'response_data' => 'array',
        'processed_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public static function createWallet(array $data)
    {
        return self::create($data);
    }
}
