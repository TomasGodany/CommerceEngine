<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password', 'role', 'is_active'])]
#[Hidden(['password', 'remember_token', 'two_factor_secret'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'is_active' => 'boolean',
            'two_factor_secret' => 'encrypted',
            'two_factor_enabled_at' => 'datetime',
        ];
    }

    /**
     * Get the customer record linked to this user.
     */
    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class);
    }

    /**
     * Determine if the user has the given role.
     */
    public function hasRole(UserRole $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Determine if the user is an administrator.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(UserRole::Admin);
    }

    /**
     * Determine if the user has two-factor authentication enabled.
     */
    public function hasTwoFactorEnabled(): bool
    {
        return ! is_null($this->two_factor_enabled_at);
    }
}
