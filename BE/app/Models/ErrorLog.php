<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ErrorLog extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The table associated with the model.
     */
    protected $table = 'error_logs';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'code_error',
        'message',
        'stack_trace',
        'url',
        'method',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** The user who encountered the error (null = guest) */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /** Filter by HTTP method */
    public function scopeMethod($query, string $method)
    {
        return $query->where('method', strtoupper($method));
    }

    /** Filter by error code */
    public function scopeCode($query, string $code)
    {
        return $query->where('code_error', $code);
    }
}
