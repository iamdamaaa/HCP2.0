<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasUuids, Notifiable;

    /**
     * The "type" of the primary key ID.
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'role',
        'is_verified',
        'poin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'password'    => 'hashed',
            'is_verified' => 'boolean',
            'poin'        => 'integer',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** Mitra/partner profile linked to this user */
    public function partner(): HasMany
    {
        return $this->hasMany(Partner::class);
    }

    /** Orders placed by this user (as customer) */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    /** Orders assigned to this user (as employee) */
    public function assignedOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'employee_id');
    }

    /** Point history log */
    public function poinLogs(): HasMany
    {
        return $this->hasMany(PoinLog::class);
    }

    /** Absence records */
    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class);
    }

    /** Performance reviews received */
    public function performanceLogs(): HasMany
    {
        return $this->hasMany(PerformanceLog::class);
    }

    /** Status changes made by this user */
    public function statusLogs(): HasMany
    {
        return $this->hasMany(StatusLog::class);
    }

    /** App activity logs */
    public function appLogs(): HasMany
    {
        return $this->hasMany(AppLog::class);
    }

    /** Error logs associated with this user */
    public function errorLogs(): HasMany
    {
        return $this->hasMany(ErrorLog::class);
    }
}
