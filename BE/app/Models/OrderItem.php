<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'order_id',
        'service_id',
        'price_id',
        'qty',
        'subtotal',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'qty'      => 'integer',
        'subtotal' => 'decimal:2',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** The parent order */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /** The service ordered */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /** The price tier selected */
    public function price(): BelongsTo
    {
        return $this->belongsTo(Price::class);
    }
}
