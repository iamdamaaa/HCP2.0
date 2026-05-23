<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partner extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'tipe',
        'name',
        'address',
        'pick_up_delivery_same',
        'commission_percent',
        'active',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'pick_up_delivery_same' => 'boolean',
        'commission_percent'    => 'decimal:2',
        'active'                => 'boolean',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** The user account this partner is linked to */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Orders that came through this partner */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /** Commission records for this partner */
    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class);
    }

    /** Affiliate sessions created by this partner */
    public function affiliateSessions(): HasMany
    {
        return $this->hasMany(AffiliateSession::class);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeTipe($query, string $tipe)
    {
        return $query->where('tipe', $tipe);
    }
}
