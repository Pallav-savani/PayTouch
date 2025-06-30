<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class CcBillPayment extends Model
{
    use HasFactory;

    protected $table = 'cc_bill_payments';

    protected $fillable = [
        'user_id',
        'uid',
        'pwd',
        'cn',
        'op',
        'cir',
        'amt',
        'reqid',
        'ad9',
        'ad3',
        'status',
        'transaction_id',
        'operator_ref',
        'response_message',
        'api_response',
        'processed_at'
    ];

    protected $hidden = [
        'pwd',
        'cn'
    ];

    protected $casts = [
        'amt' => 'decimal:2',
        'api_response' => 'array',
        'processed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $dates = [
        'processed_at',
        'created_at',
        'updated_at'
    ];

    /**
     * Relationship with User model
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get masked credit card number
     */
    public function getMaskedCnAttribute()
    {
        if (!$this->cn) return null;
        
        $decryptedCn = decrypt($this->cn);
        $length = strlen($decryptedCn);
        if ($length <= 4) return str_repeat('*', $length);
        
        return str_repeat('*', $length - 4) . substr($decryptedCn, -4);
    }

    /**
     * Get decrypted credit card number (use carefully)
     */
    public function getDecryptedCnAttribute()
    {
        return $this->cn ? decrypt($this->cn) : null;
    }

    /**
     * Scope for filtering by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for filtering by user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for filtering by uid
     */
    public function scopeByUid($query, $uid)
    {
        return $query->where('uid', $uid);
    }

    /**
     * Scope for filtering by operator
     */
    public function scopeByOperator($query, $operator)
    {
        return $query->where('op', $operator);
    }

    /**
     * Scope for filtering by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope for search functionality
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('reqid', 'like', '%' . $search . '%')
              ->orWhere('op', 'like', '%' . $search . '%')
              ->orWhere('transaction_id', 'like', '%' . $search . '%')
              ->orWhere('operator_ref', 'like', '%' . $search . '%')
              ->orWhere('ad9', 'like', '%' . $search . '%')
              ->orWhere('ad3', 'like', '%' . $search . '%')
              ->orWhereHas('user', function($userQuery) use ($search) {
                  $userQuery->where('email', 'like', '%' . $search . '%');
              });
        });
    }

    /**
     * Check if payment is successful
     */
    public function isSuccessful()
    {
        return $this->status === 'success';
    }

    /**
     * Check if payment is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payment is failed
     */
    public function isFailed()
    {
        return $this->status === 'failed';
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return '₹' . number_format($this->amt, 2);
    }
}
