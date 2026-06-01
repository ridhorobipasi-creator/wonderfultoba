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
        $role = str_replace('_', '', strtolower($this->role ?? ''));
        return in_array($role, ['superadmin', 'adminumum']);
    }

    // Check if user is tour admin
    public function isTourAdmin(): bool
    {
        $role = str_replace('_', '', strtolower($this->role ?? ''));
        return in_array($role, ['admin', 'admintour', 'superadmin', 'adminumum']);
    }

    // Legacy check
    public function isAdmin(): bool
    {
        $role = str_replace('_', '', strtolower($this->role ?? ''));
        return in_array($role, ['admin', 'superadmin', 'adminumum', 'admintour']);
    }
}
