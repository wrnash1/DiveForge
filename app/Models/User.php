<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'is_active',
        'phone',
        'primary_shop_id',
        'employment_status',
        'total_dives',
        'certification_level',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    // Relationships
    public function primaryShop(): BelongsTo
    {
        return $this->belongsTo(DiveShop::class, 'primary_shop_id');
    }

    // Scopes
    public function scopeAdmins($query)
    {
        return $query->where('is_admin', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCustomers($query)
    {
        return $query->where('is_admin', false);
    }

    // Helper methods
    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }

    public function getInitialsAttribute(): string
    {
        $names = explode(' ', $this->name);
        return substr($names[0], 0, 1) . (isset($names[1]) ? substr($names[1], 0, 1) : '');
    }
}
