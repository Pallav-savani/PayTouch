<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KycVerification extends Model
{
    use HasFactory;

    protected $table = 'kyc_verifications';

    protected $fillable = [
        'user_id',
        'member_id',
        'member_no',
        'mobile_no',
        'member_name',
        'birth_date',
        'age',
        'home_address',
        'city_name',
        'email',
        'status',
        'discount_pattern',
        'pan_card_no',
        'aadhaar_no',
        'gst_no',
        'registration_date',
        'activation_date',
        'password_change_date',
        'last_topup_date',
        'dmr_balance',
        'discount',
        'kyc_completed'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'registration_date' => 'datetime',
        'activation_date' => 'datetime',
        'password_change_date' => 'datetime',
        'last_topup_date' => 'datetime',
        'kyc_completed' => 'boolean',
        'age' => 'integer',
        'dmr_balance' => 'decimal:2',
        'discount' => 'decimal:2'
    ];

    /**
     * Get the user that owns the KYC verification.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get completed KYC records
     */
    public function scopeCompleted($query)
    {
        return $query->where('kyc_completed', true);
    }

    /**
     * Scope to get pending KYC records
     */
    public function scopePending($query)
    {
        return $query->where('kyc_completed', false);
    }
}