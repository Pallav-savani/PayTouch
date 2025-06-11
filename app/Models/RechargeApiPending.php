<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class RechargeApiPending extends Model
{
     use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'recharge_api_pending';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'op',
        'operatorname',
        'category',
        'view_bill',
        'bbps_enabled',
        'regex',
        'name',
        'cn',
        'ad1_with_regex',
        'ad2',
        'ad3',
        'ad4',
        'ad9',
        'additional_parms_payment_api',
        'biller_id',
        'view_bill',

    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
    
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [];

    /**
     * Default values for attributes
     */
    protected $attributes = [
        'status' => 'pending',
    ];

    /**
     * Validation rules
     */
   

    /**
     * Get the service name in uppercase
     */
    public function getServiceNameAttribute(): string
    {
        return strtoupper($this->service);
    }

    /**
     * Get formatted amount with currency
     */
    public function getFormattedAmountAttribute(): string
    {
        return '₹' . number_format($this->amount, 2);
    }

    /**
     * Get status badge class for UI
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'completed' => 'bg-success',
            'failed' => 'bg-danger',
            'pending' => 'bg-warning',
            default => 'bg-secondary'
        };
    }

    /**
     * Get formatted created date
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('d/m/Y H:i:s');
    }

    /**
     * Scope for filtering by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for filtering by service
     */
    public function scopeByService($query, $service)
    {
        return $query->where('service', $service);
    }

    /**
     * Scope for filtering by mobile number
     */
    public function scopeByMobile($query, $mobile)
    {
        return $query->where('mobile_no', $mobile);
    }

    /**
     * Scope for today's recharges
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope for this month's recharges
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }

    /**
     * Scope for successful recharges
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for failed recharges
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for pending recharges
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Generate unique transaction ID
     */


    /**
     * Get service options for dropdown
     */
   
    /**
     * Get status options
     */
    public static function getStatusOptions(): array
    {
        return [
            'pending' => 'Pending',
            'completed' => 'Completed',
            'failed' => 'Failed'
        ];
    }
}
