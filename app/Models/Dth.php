<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'user_id',
        'wallet_id',
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
        'status' => 'pending'
    ];

    /**
     * Validation rules
     */
    public static $rules = [
        'service' => 'required|string|in:airtel,bigtv,dishtv,tatasky,videocon,suntv',
        'mobile_no' => 'required|string|regex:/^[0-9]{10}$/',
        'amount' => 'required|numeric|min:1|max:10000',
        'transaction_id' => 'nullable|string|unique:dth_recharges',
        'status' => 'in:pending,completed,failed'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class, 'reference_id');
    }

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
    public static function generateTransactionId(): string
    {
        do {
            $transactionId = 'DTH' . time() . rand(1000, 9999);
        } while (static::where('transaction_id', $transactionId)->exists());
        
        return $transactionId;
    }

    /**
     * Get service options for dropdown
     */
    public static function getServiceOptions(): array
    {
        return [
            'airtel' => 'AIRTEL DTH',
            'bigtv' => 'BIG TV DTH',
            'dishtv' => 'DISH TV DTH',
            'tatasky' => 'TATA SKY DTH',
            'videocon' => 'VIDEOCON DTH',
            'suntv' => 'SUN TV DTH'
        ];
    }

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

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate transaction ID if not provided
        static::creating(function ($model) {
            if (empty($model->transaction_id)) {
                $model->transaction_id = static::generateTransactionId();
            }
        });
    }
}