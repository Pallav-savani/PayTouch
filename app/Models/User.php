<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mobile',
        'email',
        'password',
        'email_verified_at',
        'wallet_balance',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', 
        'wallet_balance' => 'decimal:2',
    ];

    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function hasEnoughBalance(float $amount): bool
    {
        return $this->wallet_balance >= $amount;
    }

    public function deductBalance(float $amount): bool
    {
        if ($this->hasEnoughBalance($amount)) {
            $this->decrement('wallet_balance', $amount);
            return true;
        }
        return false;
    }

    public function addBalance(float $amount): void
    {
        $this->increment('wallet_balance', $amount);
    }

    public function findForPassport($identifier)
    {
        return $this->where('mobile', $identifier)->first();
    }

    public function getMobileAttribute($value)
    {
        return $value;
    }

    public function setMobileAttribute($value)
    {
        $this->attributes['mobile'] = preg_replace('/\D/', '', $value);
    }

    public function ccBillPayments()
    {
        return $this->hasMany(CcBillPayment::class);
    }

    /**
     * Get user's successful CC bill payments
     */
    public function successfulCcPayments()
    {
        return $this->ccBillPayments()->where('status', 'success');
    }

    /**
     * Get user's pending CC bill payments
     */
    public function pendingCcPayments()
    {
        return $this->ccBillPayments()->where('status', 'pending');
    }

    /**
     * Get user's failed CC bill payments
     */
    public function failedCcPayments()
    {
        return $this->ccBillPayments()->where('status', 'failed');
    }
}