<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @mixin \Eloquent
 */

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'photoURL', 'metadata', 'role',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'metadata' => 'json',
        ];
    }

    // Check if user is superadmin (Full Access)
    public function isSuperAdmin(): bool
    {
        return in_array($this->role, ['superadmin', 'admin_umum']);
    }

    // Check if user is tour admin
    public function isTourAdmin(): bool
    {
        return in_array($this->role, ['admin', 'admin_tour', 'superadmin', 'admin_umum']);
    }

    // Legacy check
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'superadmin', 'admin_umum', 'admin_tour']);
    }
}
