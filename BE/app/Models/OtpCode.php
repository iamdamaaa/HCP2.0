<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OtpCode extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'otp_codes';

    /**
     * Disable updated_at (table only has created_at).
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'phone',
        'otp_code',
        'expired_at',
        'is_used',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'expired_at' => 'datetime',
        'is_used'    => 'boolean',
        'created_at' => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /** Check if this OTP has expired */
    public function isExpired(): bool
    {
        return now()->greaterThan($this->expired_at);
    }

    /** Check if this OTP is still valid (not used and not expired) */
    public function isValid(): bool
    {
        return ! $this->is_used && ! $this->isExpired();
    }
}
