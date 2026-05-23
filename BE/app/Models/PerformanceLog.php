<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceLog extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The table associated with the model.
     */
    protected $table = 'performance_logs';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'order_id',
        'rating',
        'feedback',
        'kategori',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'rating' => 'decimal:2',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** The employee being evaluated */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** The order this evaluation is based on */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeKategori($query, string $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /** Only reviews with a rating at or above a threshold */
    public function scopeMinRating($query, float $min)
    {
        return $query->where('rating', '>=', $min);
    }
}
