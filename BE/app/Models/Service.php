<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'unit',
        'is_active',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** Category this service belongs to */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** Price options for this service */
    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }

    /** Order items using this service */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /** Only return active prices for this service */
    public function activePrices(): HasMany
    {
        return $this->prices()->where('active', true);
    }
}
