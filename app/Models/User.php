<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Services\WalletService;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'wallet_balance',
        'mobile',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'wallet_balance' => 'decimal:2',
    ];

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function mobileRecharges()
    {
        return $this->hasMany(MobileRecharge::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            try {
                $walletService = app(WalletService::class);
                $walletService->createWalletForUser($user);
                
                Log::info('Wallet created for new user', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to create wallet for new user', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'error' => $e->getMessage()
                ]);
            }
        });
    }

    public function addBalance(float $amount): bool
    {
        $this->wallet_balance = ($this->wallet_balance ?? 0) + $amount;
        return $this->save();
    }

    public function deductBalance(float $amount): bool
    {
        if (($this->wallet_balance ?? 0) >= $amount) {
            $this->wallet_balance = ($this->wallet_balance ?? 0) - $amount;
            return $this->save();
        }
        return false;
    }

    public function hasBalance(float $amount): bool
    {
        return ($this->wallet_balance ?? 0) >= $amount;
    }
}
