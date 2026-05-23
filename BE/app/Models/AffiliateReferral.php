<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateReferral extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'session_id',
        'user_id',
        'order_id',
        'referral_code',
        'visited_at',
        'converted',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'visited_at' => 'datetime',
        'converted'  => 'boolean',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** The affiliate session this referral belongs to */
    public function session(): BelongsTo
    {
        return $this->belongsTo(AffiliateSession::class, 'session_id');
    }

    /** The user who clicked the referral link */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** The order created from this referral (if converted) */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /** Only converted referrals (those that resulted in an order) */
    public function scopeConverted($query)
    {
        return $query->where('converted', true);
    }
}
