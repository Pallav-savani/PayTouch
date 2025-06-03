<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Dth extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'recharges';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'service',
        'mobile_no',
        'amount',
        'transaction_id',
        'status'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [];

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        // Set default status when creating
        static::creating(function ($model) {
            if (empty($model->status)) {
                $model->status = 'Pending';
            }
        });
    }

    /**
     * Scope to filter by service
     */
    public function scopeByService($query, $service)
    {
        return $query->where('service', $service);
    }

    /**
     * Scope to filter by mobile number
     */
    public function scopeByMobile($query, $mobile)
    {
        return $query->where('mobile_no', $mobile);
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get pending recharges
     */
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    /**
     * Scope to get successful recharges
     */
    public function scopeSuccess($query)
    {
        return $query->where('status', 'Success');
    }

    /**
     * Scope to get failed recharges
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'Failed');
    }

    /**
     * Scope to get today's recharges
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', Carbon::today());
    }

    /**
     * Scope to get recharges within date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return '₹' . number_format($this->amount, 2);
    }

    /**
     * Get status badge class for UI
     */
    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case 'Success':
                return 'badge-success';
            case 'Failed':
                return 'badge-danger';
            case 'Pending':
            default:
                return 'badge-warning';
        }
    }

    /**
     * Get formatted created date
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d M Y, h:i A');
    }

    /**
     * Check if recharge is successful
     */
    public function isSuccessful()
    {
        return $this->status === 'Success';
    }

    /**
     * Check if recharge is pending
     */
    public function isPending()
    {
        return $this->status === 'Pending';
    }

    /**
     * Check if recharge is failed
     */
    public function isFailed()
    {
        return $this->status === 'Failed';
    }

    /**
     * Get popular services
     */
    public static function getPopularServices()
    {
        return self::select('service')
                   ->selectRaw('COUNT(*) as recharge_count')
                   ->groupBy('service')
                   ->orderBy('recharge_count', 'desc')
                   ->limit(10)
                   ->get();
    }

    /**
     * Get monthly statistics
     */
    public static function getMonthlyStats($year, $month)
    {
        return self::whereYear('created_at', $year)
                   ->whereMonth('created_at', $month)
                   ->selectRaw('
                       COUNT(*) as total_recharges,
                       SUM(CASE WHEN status = "Success" THEN amount ELSE 0 END) as total_amount,
                       COUNT(CASE WHEN status = "Success" THEN 1 END) as successful_recharges,
                       COUNT(CASE WHEN status = "Failed" THEN 1 END) as failed_recharges,
                       COUNT(CASE WHEN status = "Pending" THEN 1 END) as pending_recharges
                   ')
                   ->first();
    }

    /**
     * Search recharges
     */
    public static function search($query)
    {
        return self::where('mobile_no', 'LIKE', "%{$query}%")
                   ->orWhere('service', 'LIKE', "%{$query}%")
                   ->orWhere('transaction_id', 'LIKE', "%{$query}%")
                   ->orderBy('created_at', 'desc');
    }
}