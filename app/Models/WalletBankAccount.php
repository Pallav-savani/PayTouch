<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class WalletBankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'account_holder_name',
        'account_number',
        'ifsc_code',
        'bank_name',
        'account_type',
        'is_verified',
        'is_primary',
        'verification_reference'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_primary' => 'boolean'
    ];

    protected $hidden = [
        'account_number'
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    protected function accountNumber(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => 'XXXX' . substr($value, -4),
            set: fn ($value) => encrypt($value)
        );
    }

    public function getFullAccountNumber()
    {
        return decrypt($this->attributes['account_number']);
    }
}
