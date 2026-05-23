<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusLog extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The table associated with the model.
     */
    protected $table = 'status_log';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'order_id',
        'user_id',
        'status_old',
        'status_new',
        'note',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** The order whose status changed */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /** The user (admin/employee) who made the status change */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
