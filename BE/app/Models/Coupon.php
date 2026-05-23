<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'code',
        'tipe',
        'discount_value',
        'quota_limit',
        'quota_used',
        'started_at',
        'expired_at',
        'active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'discount_value' => 'decimal:2',
        'quota_limit'    => 'integer',
        'quota_used'     => 'integer',
        'started_at'     => 'datetime',
        'expired_at'     => 'datetime',
        'active'         => 'boolean',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** Orders that used this coupon */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /** Check if coupon is currently valid and has quota remaining */
    public function isUsable(): bool
    {
        $now = now();

        return $this->active
            && ($this->started_at === null || $now->greaterThanOrEqualTo($this->started_at))
            && ($this->expired_at === null || $now->lessThan($this->expired_at))
            && $this->quota_used < $this->quota_limit;
    }

    /** Remaining quota */
    public function remainingQuota(): int
    {
        return max(0, $this->quota_limit - $this->quota_used);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeValid($query)
    {
        $now = now();

        return $query->where('active', true)
            ->where(fn ($q) => $q->whereNull('started_at')->orWhere('started_at', '<=', $now))
            ->where(fn ($q) => $q->whereNull('expired_at')->orWhere('expired_at', '>', $now))
            ->whereColumn('quota_used', '<', 'quota_limit');
    }
}
