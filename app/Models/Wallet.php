<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'wallet_id',
        'balance',
        'status',
        'mobikwik_wallet_id',
        'is_kyc_verified',
        'daily_limit',
        'monthly_limit',
        'total_loaded',
        'total_spent',
        'last_transaction_at'
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'daily_limit' => 'decimal:2',
        'monthly_limit' => 'decimal:2',
        'total_loaded' => 'decimal:2',
        'total_spent' => 'decimal:2',
        'is_kyc_verified' => 'boolean',
        'last_transaction_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function bankAccounts()
    {
        return $this->hasMany(WalletBankAccount::class);
    }

    public function generateWalletId()
    {
        return 'PWT' . str_pad($this->user_id, 6, '0', STR_PAD_LEFT) . rand(1000, 9999);
    }

    public function canTransact($amount)
    {
        return $this->balance >= $amount && $this->status === 'active';
    }
}
