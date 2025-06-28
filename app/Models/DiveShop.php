<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class DiveShop extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'website',
        'timezone',
        'currency',
        'owner_id',
        'is_active',
        'business_license',
        'padi_store_number',
        'ssi_center_number',
        'naui_center_number',
        'other_certifications',
        'operating_hours',
        'emergency_contact',
        'insurance_info',
        'equipment_count',
        'max_students_per_class',
        'booking_lead_time_hours',
        'cancellation_policy',
        'payment_methods',
        'logo_path',
        'banner_path',
        'settings',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'operating_hours' => 'json',
        'other_certifications' => 'json',
        'insurance_info' => 'json',
        'equipment_count' => 'integer',
        'max_students_per_class' => 'integer',
        'booking_lead_time_hours' => 'integer',
        'payment_methods' => 'json',
        'settings' => 'json',
    ];

    // ========================================
    // RELATIONSHIPS
    // ========================================

    /**
     * Get the owner of the dive shop
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get all users who have this as their primary shop
     */
    public function primaryUsers(): HasMany
    {
        return $this->hasMany(User::class, 'primary_shop_id');
    }

    /**
     * Get all users who have access to this shop
     */
    public function accessibleUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_shop_access')
                    ->withPivot(['role', 'permissions', 'granted_at'])
                    ->withTimestamps();
    }

    /**
     * Get all staff members (employees and contractors)
     */
    public function staff(): HasMany
    {
        return $this->hasMany(User::class, 'primary_shop_id')
                    ->whereIn('employment_status', ['employee', 'contractor']);
    }

    /**
     * Get all instructors
     */
    public function instructors(): HasMany
    {
        return $this->hasMany(User::class, 'primary_shop_id')
                    ->whereIn('employment_status', ['employee', 'contractor'])
                    ->whereHas('certifications', function ($query) {
                        $query->where('type', 'instructor')
                              ->where('is_active', true);
                    });
    }

    /**
     * Get all customers
     */
    public function customers(): HasMany
    {
        return $this->hasMany(User::class, 'primary_shop_id')
                    ->where('employment_status', 'customer');
    }

    /**
     * Get all courses offered by this shop
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    /**
     * Get all equipment owned by this shop
     */
    public function equipment(): HasMany
    {
        return $this->hasMany(Equipment::class);
    }

    /**
     * Get all dive sites accessible to this shop
     */
    public function diveSites(): BelongsToMany
    {
        return $this->belongsToMany(DiveSite::class, 'shop_dive_sites')
                    ->withPivot(['distance_km', 'travel_time_minutes', 'preferred'])
                    ->withTimestamps();
    }

    /**
     * Get all trips organized by this shop
     */
    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    /**
     * Get all bookings made at this shop
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get all certifications issued by this shop
     */
    public function certifications(): HasMany
    {
        return $this->hasMany(Certification::class);
    }

    /**
     * Get all inventory items
     */
    public function inventory(): HasMany
    {
        return $this->hasMany(InventoryItem::class);
    }

    /**
     * Get all boats owned by this shop
     */
    public function boats(): HasMany
    {
        return $this->hasMany(Boat::class);
    }

    /**
     * Get all compressors owned by this shop
     */
    public function compressors(): HasMany
    {
        return $this->hasMany(Compressor::class);
    }

    // ========================================
    // QUERY SCOPES
    // ========================================

    /**
     * Scope query to only active dive shops
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope query by timezone
     */
    public function scopeByTimezone($query, $timezone)
    {
        return $query->where('timezone', $timezone);
    }

    /**
     * Scope query by currency
     */
    public function scopeByCurrency($query, $currency)
    {
        return $query->where('currency', $currency);
    }

    /**
     * Scope query by certification agency
     */
    public function scopeWithAgency($query, $agency)
    {
        switch (strtolower($agency)) {
            case 'padi':
                return $query->whereNotNull('padi_store_number');
            case 'ssi':
                return $query->whereNotNull('ssi_center_number');
            case 'naui':
                return $query->whereNotNull('naui_center_number');
            default:
                return $query;
        }
    }

    /**
     * Scope query by location (city, state, country)
     */
    public function scopeInLocation($query, $location)
    {
        return $query->where(function ($q) use ($location) {
            $q->where('city', 'like', "%{$location}%")
              ->orWhere('state', 'like', "%{$location}%")
              ->orWhere('country', 'like', "%{$location}%");
        });
    }

    // ========================================
    // HELPER METHODS
    // ========================================

    /**
     * Check if shop is active
     */
    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }

    /**
     * Get full address as string
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
     * Get shop logo URL
     */
    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo_path) {
            return null;
        }

        return Storage::url($this->logo_path);
    }

    /**
     * Get shop banner URL
     */
    public function getBannerUrlAttribute(): ?string
    {
        if (!$this->banner_path) {
            return null;
        }

        return Storage::url($this->banner_path);
    }

    /**
     * Get operating hours for a specific day
     */
    public function getOperatingHoursForDay(string $day): ?array
    {
        $hours = $this->operating_hours ?? [];
        
        return $hours[strtolower($day)] ?? null;
    }

    /**
     * Check if shop is open on a specific day
     */
    public function isOpenOnDay(string $day): bool
    {
        $hours = $this->getOperatingHoursForDay($day);
        
        return $hours && !($hours['closed'] ?? false);
    }

    /**
     * Get shop settings with defaults
     */
    public function getSetting(string $key, $default = null)
    {
        $settings = $this->settings ?? [];
        
        return $settings[$key] ?? $default;
    }

    /**
     * Set shop setting
     */
    public function setSetting(string $key, $value): void
    {
        $settings = $this->settings ?? [];
        $settings[$key] = $value;
        
        $this->update(['settings' => $settings]);
    }

    /**
     * Get supported certification agencies
     */
    public function getSupportedAgenciesAttribute(): array
    {
        $agencies = [];
        
        if ($this->padi_store_number) {
            $agencies[] = 'PADI';
        }
        
        if ($this->ssi_center_number) {
            $agencies[] = 'SSI';
        }
        
        if ($this->naui_center_number) {
            $agencies[] = 'NAUI';
        }
        
        if ($this->other_certifications) {
            foreach ($this->other_certifications as $cert) {
                if (isset($cert['agency']) && !in_array($cert['agency'], $agencies)) {
                    $agencies[] = $cert['agency'];
                }
            }
        }
        
        return $agencies;
    }

    /**
     * Get total number of active staff
     */
    public function getActiveStaffCountAttribute(): int
    {
        return $this->staff()->where('is_active', true)->count();
    }

    /**
     * Get total number of active instructors
     */
    public function getActiveInstructorCountAttribute(): int
    {
        return $this->instructors()->where('is_active', true)->count();
    }

    /**
     * Get total number of customers
     */
    public function getTotalCustomersCountAttribute(): int
    {
        return $this->customers()->count();
    }

    /**
     * Check if shop has required insurance
     */
    public function hasValidInsurance(): bool
    {
        $insurance = $this->insurance_info ?? [];
        
        if (!isset($insurance['liability']) || !isset($insurance['expiry_date'])) {
            return false;
        }
        
        return now()->lt(new \DateTime($insurance['expiry_date']));
    }

    /**
     * Check if shop can accept new bookings
     */
    public function canAcceptBookings(): bool
    {
        return $this->is_active && 
               $this->hasValidInsurance() && 
               $this->active_instructor_count > 0;
    }

    /**
     * Get available time slots for a specific date
     */
    public function getAvailableTimeSlots(\DateTime $date): array
    {
        $dayOfWeek = strtolower($date->format('l'));
        $hours = $this->getOperatingHoursForDay($dayOfWeek);
        
        if (!$hours || ($hours['closed'] ?? false)) {
            return [];
        }
        
        // This is a simplified version - in practice, you'd check against existing bookings
        $slots = [];
        $start = new \DateTime($date->format('Y-m-d') . ' ' . $hours['open']);
        $end = new \DateTime($date->format('Y-m-d') . ' ' . $hours['close']);
        
        while ($start < $end) {
            $slots[] = $start->format('H:i');
            $start->add(new \DateInterval('PT1H')); // Add 1 hour
        }
        
        return $slots;
    }

    /**
     * Calculate distance to a dive site
     */
    public function calculateDistanceToSite(DiveSite $site): ?float
    {
        // This would typically use a GPS calculation service
        // For now, return the stored distance if available
        $pivotData = $this->diveSites->where('id', $site->id)->first()?->pivot;
        
        return $pivotData?->distance_km;
    }

    // ========================================
    // STATIC METHODS
    // ========================================

    /**
     * Get timezone options
     */
    public static function getTimezoneOptions(): array
    {
        return [
            'UTC' => 'UTC',
            'America/New_York' => 'Eastern Time (ET)',
            'America/Chicago' => 'Central Time (CT)',
            'America/Denver' => 'Mountain Time (MT)',
            'America/Los_Angeles' => 'Pacific Time (PT)',
            'Europe/London' => 'Greenwich Mean Time (GMT)',
            'Europe/Paris' => 'Central European Time (CET)',
            'Europe/Berlin' => 'Central European Time (CET)',
            'Asia/Tokyo' => 'Japan Standard Time (JST)',
            'Australia/Sydney' => 'Australian Eastern Time (AET)',
        ];
    }

    /**
     * Get currency options
     */
    public static function getCurrencyOptions(): array
    {
        return [
            'USD' => 'US Dollar ($)',
            'EUR' => 'Euro (€)',
            'GBP' => 'British Pound (£)',
            'JPY' => 'Japanese Yen (¥)',
            'CAD' => 'Canadian Dollar (C$)',
            'AUD' => 'Australian Dollar (A$)',
            'CHF' => 'Swiss Franc (CHF)',
            'CNY' => 'Chinese Yuan (¥)',
            'SEK' => 'Swedish Krona (kr)',
            'NZD' => 'New Zealand Dollar (NZ$)',
        ];
    }

    /**
     * Get payment method options
     */
    public static function getPaymentMethodOptions(): array
    {
        return [
            'cash' => 'Cash',
            'credit_card' => 'Credit Card',
            'debit_card' => 'Debit Card',
            'paypal' => 'PayPal',
            'bank_transfer' => 'Bank Transfer',
            'check' => 'Check',
            'gift_card' => 'Gift Card',
        ];
    }
}