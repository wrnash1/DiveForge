<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Installer\InstallationController;

Route::get('/', function () {
    return view('welcome');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::resource('shops', App\Http\Controllers\Admin\DiveShopController::class);
});

// Redirect to appropriate dashboard after login
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return view('dashboard'); // Regular user dashboard
    })->name('dashboard');
});

// Authentication routes
require __DIR__.'/auth.php';

// Installation routes - only active if not installed
Route::middleware(['web'])->prefix('install')->name('installer.')->group(function () {
    Route::get('/', [InstallationController::class, 'showStep1'])->name('step1.show');
    Route::post('/step1', [InstallationController::class, 'processStep1'])->name('step1.process');
    Route::get('/step2', [InstallationController::class, 'showStep2'])->name('step2.show');
    Route::post('/step2', [InstallationController::class, 'processStep2'])->name('step2.process');
    Route::get('/step3', [InstallationController::class, 'showStep3'])->name('step3.show');
    Route::post('/step3', [InstallationController::class, 'processStep3'])->name('step3.process');
    Route::get('/step4', [InstallationController::class, 'showStep4'])->name('step4.show');
    Route::post('/step4', [InstallationController::class, 'processStep4'])->name('step4.process');
    Route::get('/step5', [InstallationController::class, 'showFinalStep'])->name('step5.show');
    Route::post('/finish', [InstallationController::class, 'finishInstallation'])->name('finish');
    Route::get('/complete', [InstallationController::class, 'showCompletionPage'])->name('complete');
    
    // AJAX endpoints
    Route::post('/test-database', [InstallationController::class, 'testDatabase'])->name('test.database');
    Route::get('/check-requirements', [InstallationController::class, 'checkRequirements'])->name('check.requirements');
});

// Include additional route files
if (file_exists(__DIR__ . '/admin.php')) {
    require __DIR__ . '/admin.php';
}

if (file_exists(__DIR__ . '/customer.php')) {
    require __DIR__ . '/customer.php';
}

// API Routes
Route::prefix('api/v1')->name('api.v1.')->group(function () {
    Route::apiResource('courses', App\Http\Controllers\API\V1\CourseController::class);
    Route::apiResource('equipment', App\Http\Controllers\API\V1\EquipmentController::class);
    Route::apiResource('dive-sites', App\Http\Controllers\API\V1\DiveSiteController::class);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('customers', App\Http\Controllers\API\V1\CustomerController::class);
        Route::post('bookings', [App\Http\Controllers\API\V1\BookingController::class, 'store']);
        Route::get('bookings', [App\Http\Controllers\API\V1\BookingController::class, 'index']);
    });
});

// Public API endpoints
Route::prefix('api/public')->name('api.public.')->group(function () {
    Route::get('courses', [App\Http\Controllers\API\V1\CourseController::class, 'index']);
    Route::get('dive-sites', [App\Http\Controllers\API\V1\DiveSiteController::class, 'index']);
    Route::get('equipment/available', [App\Http\Controllers\API\V1\EquipmentController::class, 'available']);
});
