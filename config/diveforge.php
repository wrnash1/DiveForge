<?php

return [
    'version' => '1.0.0',
    
    'equipment' => [
        'default_rental_duration' => 7, // days
        'max_rental_duration' => 30, // days
        'damage_deposit_percentage' => 0.1, // 10% of retail price
        'late_fee_daily' => 25.00,
        'maintenance_interval_days' => 90,
    ],
    
    'booking' => [
        'advance_booking_days' => 30,
        'cancellation_hours' => 24,
        'reminder_hours' => 24,
        'max_group_size' => 12,
    ],
    
    'certification' => [
        'temporary_card_valid_days' => 90,
        'processing_time_days' => 5,
        'renewal_reminder_days' => 30,
    ],
    
    'safety' => [
        'medical_clearance_valid_months' => 12,
        'max_depth_open_water' => 18, // meters
        'max_depth_advanced' => 30, // meters
        'emergency_contact_required' => true,
    ],
    
    'financial' => [
        'payment_terms_days' => 30,
        'late_fee_percentage' => 1.5, // monthly
        'refund_period_days' => 7,
        'deposit_percentage' => 0.3, // 30%
    ],
    
    'agencies' => [
        'supported' => ['PADI', 'SSI', 'TDI', 'NAUI', 'BSAC', 'GUE'],
        'api_timeout' => 30, // seconds
        'sync_interval_hours' => 24,
    ],
];
