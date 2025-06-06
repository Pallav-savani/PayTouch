<?php

namespace App\Models;

use App\Http\Controllers\API\DthController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SearchHistory extends Model
{
    use HasFactory;

    protected $table = 'search_history';

    protected $fillable = [
        'customer_id',
        'transaction_id',
        'status',
        'search_time'
    ];

    protected $casts = [
        'search_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $dates = [
        'search_time',
        'created_at',
        'updated_at',
    ];

    /**
     * Scope to get recent searches
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('search_time', '>=', Carbon::now()->subDays($days));
    }

    /**
     * Scope to get searches by customer ID
     */
    public function scopeByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    /**
     * Scope to get searches by transaction ID
     */
    public function scopeByTransaction($query, $transactionId)
    {
        return $query->where('transaction_id', $transactionId);
    }

    /**
     * Scope to get searches by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get the recharge record associated with this search (if exists)
     * Assuming you have a Recharge model
     */
    public function recharge()
    {
        return $this->belongsTo(DthController::class, 'transaction_id', 'transaction_id');
    }

    /**
     * Get formatted search time
     */
    public function getFormattedSearchTimeAttribute()
    {
        return $this->search_time->format('Y-m-d H:i:s');
    }

    /**
     * Get human readable search time
     */
    public function getHumanSearchTimeAttribute()
    {
        return $this->search_time->diffForHumans();
    }

    /**
     * Boot method to set search_time automatically
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->search_time) {
                $model->search_time = Carbon::now();
            }
        });
    }
}
