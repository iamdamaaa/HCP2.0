<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absence extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The table associated with the model.
     */
    protected $table = 'absences';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'date',
        'checkin_time',
        'checkout_time',
        'status',
        'note',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'date' => 'date',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** The employee this absence record belongs to */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /** Check if employee has already checked in today */
    public function hasCheckedIn(): bool
    {
        return $this->checkin_time !== null;
    }

    /** Check if employee has already checked out */
    public function hasCheckedOut(): bool
    {
        return $this->checkout_time !== null;
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeForDate($query, string $date)
    {
        return $query->where('date', $date);
    }

    public function scopeToday($query)
    {
        return $query->where('date', now()->toDateString());
    }
}
