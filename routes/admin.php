<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::resource('users', Admin\UserController::class);
    Route::post('users/{user}/reset-password', [Admin\UserController::class, 'resetPassword'])->name('users.reset-password');
    
    // Dive Shop Management
    Route::resource('shops', Admin\DiveShopController::class);
    Route::post('shops/{shop}/toggle-status', [Admin\DiveShopController::class, 'toggleStatus'])->name('shops.toggle-status');
    
    // Agency Management
    Route::resource('agencies', Admin\AgencyController::class);
    Route::post('agencies/{agency}/sync', [Admin\AgencyController::class, 'syncData'])->name('agencies.sync');
    
    // Course Management
    Route::resource('courses', Admin\CourseController::class);
    Route::post('courses/{course}/duplicate', [Admin\CourseController::class, 'duplicate'])->name('courses.duplicate');
    
    // Equipment Management
    Route::resource('equipment', Admin\EquipmentController::class);
    Route::post('equipment/{equipment}/maintenance', [Admin\EquipmentController::class, 'scheduleMaintenance'])->name('equipment.maintenance');
    Route::get('equipment/categories', [Admin\EquipmentController::class, 'categories'])->name('equipment.categories');
    
    // Instructor Management
    Route::resource('instructors', Admin\InstructorController::class);
    Route::post('instructors/{instructor}/certifications', [Admin\InstructorController::class, 'addCertification'])->name('instructors.certifications');
    
    // Student Management
    Route::resource('students', Admin\StudentController::class);
    Route::get('students/{student}/progress', [Admin\StudentController::class, 'progress'])->name('students.progress');
    
    // Dive Site Management
    Route::resource('dive-sites', Admin\DiveSiteController::class);
    Route::post('dive-sites/{diveSite}/conditions', [Admin\DiveSiteController::class, 'updateConditions'])->name('dive-sites.conditions');
    
    // Trip Management
    Route::resource('trips', Admin\TripController::class);
    Route::get('trips/{trip}/participants', [Admin\TripController::class, 'participants'])->name('trips.participants');
    
    // Reports
    Route::get('reports', [Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/revenue', [Admin\ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('reports/equipment', [Admin\ReportController::class, 'equipment'])->name('reports.equipment');
    Route::get('reports/safety', [Admin\ReportController::class, 'safety'])->name('reports.safety');
    
    // Settings
    Route::get('settings', [Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [Admin\SettingsController::class, 'update'])->name('settings.update');
});
