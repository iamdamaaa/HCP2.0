<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AffiliateSession extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'partner_id',
        'unique_code',
        'start',
        'end',
        'active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'start'  => 'datetime',
        'end'    => 'datetime',
        'active' => 'boolean',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** Partner who owns this affiliate session */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    /** Referrals generated under this session */
    public function referrals(): HasMany
    {
        return $this->hasMany(AffiliateReferral::class, 'session_id');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /** Check if this affiliate session is currently active and within its validity window */
    public function isRunning(): bool
    {
        $now = now();

        return $this->active
            && ($this->start === null || $now->greaterThanOrEqualTo($this->start))
            && ($this->end === null || $now->lessThan($this->end));
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
