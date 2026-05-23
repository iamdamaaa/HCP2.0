<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppLog extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The table associated with the model.
     */
    protected $table = 'app_logs';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'level',
        'action',
        'description',
        'context',
        'ip_address',
        'user_agent',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'context' => 'array',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** The user who performed the action (null = guest) */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeLevel($query, string $level)
    {
        return $query->where('level', $level);
    }

    public function scopeInfo($query)
    {
        return $query->where('level', 'info');
    }

    public function scopeWarning($query)
    {
        return $query->where('level', 'warning');
    }
}
