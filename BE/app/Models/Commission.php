<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commission extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'partner_id',
        'order_id',
        'commission',
        'is_paid',
        'paid_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'commission' => 'decimal:2',
        'is_paid'    => 'boolean',
        'paid_at'    => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** The partner who earns this commission */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    /** The order that generated this commission */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /** Only unpaid commissions */
    public function scopeUnpaid($query)
    {
        return $query->where('is_paid', false);
    }

    /** Only paid commissions */
    public function scopePaid($query)
    {
        return $query->where('is_paid', true);
    }
}
