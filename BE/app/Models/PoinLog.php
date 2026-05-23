<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PoinLog extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The table associated with the model.
     */
    protected $table = 'poin_logs';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'order_id',
        'tipe',
        'poin',
        'description',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'poin' => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** The user whose points were affected */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** The order that triggered this poin movement (optional) */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /** Only point additions */
    public function scopePlus($query)
    {
        return $query->where('tipe', 'plus');
    }

    /** Only point deductions */
    public function scopeMinus($query)
    {
        return $query->where('tipe', 'minus');
    }
}
