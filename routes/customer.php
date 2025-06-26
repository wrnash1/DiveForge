<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer;

Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    // Profile Management
    Route::get('profile', [Customer\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [Customer\ProfileController::class, 'update'])->name('profile.update');
    Route::post('profile/avatar', [Customer\ProfileController::class, 'uploadAvatar'])->name('profile.avatar');
    
    // Course Bookings
    Route::get('courses', [Customer\BookingController::class, 'courses'])->name('courses.index');
    Route::get('courses/{course}', [Customer\BookingController::class, 'showCourse'])->name('courses.show');
    Route::post('courses/{course}/book', [Customer\BookingController::class, 'bookCourse'])->name('courses.book');
    
    // Equipment Rentals
    Route::get('equipment', [Customer\EquipmentRentalController::class, 'index'])->name('equipment.index');
    Route::get('equipment/{equipment}', [Customer\EquipmentRentalController::class, 'show'])->name('equipment.show');
    Route::post('equipment/{equipment}/rent', [Customer\EquipmentRentalController::class, 'rent'])->name('equipment.rent');
    Route::get('rentals', [Customer\EquipmentRentalController::class, 'myRentals'])->name('rentals.index');
    
    // Trip Bookings
    Route::get('trips', [Customer\TripController::class, 'index'])->name('trips.index');
    Route::get('trips/{trip}', [Customer\TripController::class, 'show'])->name('trips.show');
    Route::post('trips/{trip}/book', [Customer\TripController::class, 'book'])->name('trips.book');
    
    // Certifications
    Route::get('certifications', [Customer\CertificationController::class, 'index'])->name('certifications.index');
    Route::get('certifications/{certification}', [Customer\CertificationController::class, 'show'])->name('certifications.show');
    Route::get('certifications/{certification}/card', [Customer\CertificationController::class, 'downloadCard'])->name('certifications.card');
    
    // Bookings and History
    Route::get('bookings', [Customer\BookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{booking}', [Customer\BookingController::class, 'show'])->name('bookings.show');
    Route::post('bookings/{booking}/cancel', [Customer\BookingController::class, 'cancel'])->name('bookings.cancel');
});
