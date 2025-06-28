<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
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
        'last_login_at',
        'emergency_contact_name',
        'emergency_contact_phone',
        'date_of_birth',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'medical_clearance_date',
        'medical_notes',
        'profile_photo_path',
        'preferences',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
            'date_of_birth' => 'date',
            'medical_clearance_date' => 'date',
            'preferences' => 'json',
        ];
    }

    // ========================================
    // RELATIONSHIPS
    // ========================================

    /**
     * Get the primary dive shop for this user
     */
    public function primaryShop(): BelongsTo
    {
        return $this->belongsTo(DiveShop::class, 'primary_shop_id');
    }

    /**
     * Get all dive shops owned by this user
     */
    public function ownedShops(): HasMany
    {
        return $this->hasMany(DiveShop::class, 'owner_id');
    }

    /**
     * Get all dive shops this user has access to
     */
    public function accessibleShops(): BelongsToMany
    {
        return $this->belongsToMany(DiveShop::class, 'user_shop_access')
                    ->withPivot(['role', 'permissions', 'granted_at'])
                    ->withTimestamps();
    }

    /**
     * Get all certifications for this user
     */
    public function certifications(): HasMany
    {
        return $this->hasMany(Certification::class);
    }

    /**
     * Get all course enrollments for this user
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    /**
     * Get all courses this user is instructing
     */
    public function instructingCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'instructor_id');
    }

    /**
     * Get all equipment rentals for this user
     */
    public function equipmentRentals(): HasMany
    {
        return $this->hasMany(EquipmentRental::class);
    }

    /**
     * Get all dive trips this user has booked
     */
    public function tripBookings(): HasMany
    {
        return $this->hasMany(TripBooking::class);
    }

    /**
     * Get all dive logs for this user
     */
    public function diveLogs(): HasMany
    {
        return $this->hasMany(DiveLog::class);
    }

    // ========================================
    // QUERY SCOPES
    // ========================================

    /**
     * Scope query to only admin users
     */
    public function scopeAdmins($query)
    {
        return $query->where('is_admin', true);
    }

    /**
     * Scope query to only active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope query to only customer (non-admin) users
     */
    public function scopeCustomers($query)
    {
        return $query->where('is_admin', false);
    }

    /**
     * Scope query to users with specific employment status
     */
    public function scopeByEmploymentStatus($query, $status)
    {
        return $query->where('employment_status', $status);
    }

    /**
     * Scope query to instructors
     */
    public function scopeInstructors($query)
    {
        return $query->whereIn('employment_status', ['employee', 'contractor'])
                    ->whereHas('certifications', function ($q) {
                        $q->where('type', 'instructor')
                          ->where('is_active', true);
                    });
    }

    /**
     * Scope query to users with medical clearance
     */
    public function scopeWithMedicalClearance($query)
    {
        return $query->whereNotNull('medical_clearance_date')
                    ->where('medical_clearance_date', '>=', now()->subYear());
    }

    // ========================================
    // HELPER METHODS
    // ========================================

    /**
     * Check if user is an administrator
     */
    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }

    /**
     * Check if user is an instructor
     */
    public function isInstructor(): bool
    {
        return $this->certifications()
                   ->where('type', 'instructor')
                   ->where('is_active', true)
                   ->exists();
    }

    /**
     * Check if user has valid medical clearance
     */
    public function hasValidMedicalClearance(): bool
    {
        if (!$this->medical_clearance_date) {
            return false;
        }

        return $this->medical_clearance_date->addYear()->isFuture();
    }

    /**
     * Get user's initials
     */
    public function getInitialsAttribute(): string
    {
        $names = explode(' ', trim($this->name));
        $initials = '';
        
        foreach ($names as $name) {
            if (!empty($name)) {
                $initials .= strtoupper(substr($name, 0, 1));
            }
        }
        
        return $initials ?: 'U';
    }

    /**
     * Get user's full address
     */
    public function getFullAddressAttribute(): string
    {
        $addressParts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country
        ]);

        return implode(', ', $addressParts);
    }

    /**
     * Get user's age
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->date_of_birth) {
            return null;
        }

        return $this->date_of_birth->age;
    }

    /**
     * Get user's highest certification level
     */
    public function getHighestCertificationAttribute(): ?string
    {
        $certification = $this->certifications()
                             ->where('is_active', true)
                             ->orderBy('level', 'desc')
                             ->first();

        return $certification?->level;
    }

    /**
     * Get user's display name with certification
     */
    public function getDisplayNameAttribute(): string
    {
        $name = $this->name;
        
        if ($this->isInstructor()) {
            $name .= ' (Instructor)';
        } elseif ($this->highest_certification) {
            $name .= " ({$this->highest_certification})";
        }

        return $name;
    }

    /**
     * Update last login timestamp
     */
    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }

    /**
     * Check if user has access to a specific dive shop
     */
    public function hasAccessToShop(DiveShop $shop): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->primary_shop_id === $shop->id) {
            return true;
        }

        return $this->accessibleShops->contains($shop);
    }

    /**
     * Get user's role in a specific shop
     */
    public function getRoleInShop(DiveShop $shop): ?string
    {
        if ($this->primary_shop_id === $shop->id) {
            return $this->employment_status;
        }

        $access = $this->accessibleShops->where('id', $shop->id)->first();
        
        return $access?->pivot?->role;
    }

    /**
     * Check if user can perform a specific action in a shop
     */
    public function canPerformAction(string $action, DiveShop $shop): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($shop->owner_id === $this->id) {
            return true;
        }

        $access = $this->accessibleShops->where('id', $shop->id)->first();
        if (!$access) {
            return false;
        }

        $permissions = $access->pivot->permissions ?? [];
        
        return in_array($action, $permissions);
    }

    /**
     * Get user preferences with defaults
     */
    public function getPreference(string $key, $default = null)
    {
        $preferences = $this->preferences ?? [];
        
        return $preferences[$key] ?? $default;
    }

    /**
     * Set user preference
     */
    public function setPreference(string $key, $value): void
    {
        $preferences = $this->preferences ?? [];
        $preferences[$key] = $value;
        
        $this->update(['preferences' => $preferences]);
    }

    // ========================================
    // STATIC METHODS
    // ========================================

    /**
     * Get employment status options
     */
    public static function getEmploymentStatusOptions(): array
    {
        return [
            'employee' => 'Employee',
            'contractor' => 'Contractor',
            'volunteer' => 'Volunteer',
            'customer' => 'Customer',
        ];
    }

    /**
     * Get certification level options
     */
    public static function getCertificationLevelOptions(): array
    {
        return [
            'open_water' => 'Open Water Diver',
            'advanced' => 'Advanced Open Water',
            'rescue' => 'Rescue Diver',
            'divemaster' => 'Divemaster',
            'instructor' => 'Instructor',
            'technical' => 'Technical Diver',
        ];
    }
}