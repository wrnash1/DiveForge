<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Include installer routes - these need to be loaded first
            if (file_exists(__DIR__.'/../routes/installer.php')) {
                require __DIR__.'/../routes/installer.php';
            }
            
            // Include admin routes
            if (file_exists(__DIR__.'/../routes/admin.php')) {
                require __DIR__.'/../routes/admin.php';
            }
            
            // Include customer routes
            if (file_exists(__DIR__.'/../routes/customer.php')) {
                require __DIR__.'/../routes/customer.php';
            }
            
            // Include dive shop specific routes
            if (file_exists(__DIR__.'/../routes/shop.php')) {
                require __DIR__.'/../routes/shop.php';
            }
            
            // Include API routes for specific modules
            if (file_exists(__DIR__.'/../routes/api/equipment.php')) {
                require __DIR__.'/../routes/api/equipment.php';
            }
            
            if (file_exists(__DIR__.'/../routes/api/courses.php')) {
                require __DIR__.'/../routes/api/courses.php';
            }
            
            if (file_exists(__DIR__.'/../routes/api/bookings.php')) {
                require __DIR__.'/../routes/api/bookings.php';
            }
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register middleware aliases for DiveForge
        $middleware->alias([
            // Admin middleware - ensures user is an administrator
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            
            // Installation middleware - prevents access to installer after installation
            'installer.not_installed' => \App\Http\Middleware\InstallerNotInstalled::class,
            
            // Shop access middleware - ensures user has access to specific dive shop
            'shop.access' => \App\Http\Middleware\EnsureShopAccess::class,
            
            // Instructor middleware - ensures user is a certified instructor
            'instructor' => \App\Http\Middleware\EnsureUserIsInstructor::class,
            
            // Active user middleware - ensures user account is active
            'active' => \App\Http\Middleware\EnsureUserIsActive::class,
            
            // Medical clearance middleware - ensures user has valid medical clearance
            'medical.clearance' => \App\Http\Middleware\EnsureMedicalClearance::class,
            
            // API rate limiting for different user types
            'api.customer' => \App\Http\Middleware\CustomerApiRateLimit::class,
            'api.shop' => \App\Http\Middleware\ShopApiRateLimit::class,
            
            // Certification validation middleware
            'cert.valid' => \App\Http\Middleware\EnsureValidCertification::class,
        ]);
        
        // Web middleware group configuration
        $middleware->web(append: [
            // Handle Inertia requests if using Inertia.js
            // \App\Http\Middleware\HandleInertiaRequests::class,
            
            // Add shop context to all web requests
            \App\Http\Middleware\AddShopContext::class,
            
            // Track user activity
            \App\Http\Middleware\TrackUserActivity::class,
            
            // Security headers
            \App\Http\Middleware\SecurityHeaders::class,
        ]);
        
        // API middleware group configuration
        $middleware->api(append: [
            // API versioning
            \App\Http\Middleware\ApiVersioning::class,
            
            // API response formatting
            \App\Http\Middleware\FormatApiResponse::class,
            
            // API logging
            \App\Http\Middleware\LogApiRequests::class,
        ]);
        
        // Global middleware (applies to all requests)
        $middleware->append([
            // Trust proxies for proper IP detection behind load balancers
            \App\Http\Middleware\TrustProxies::class,
            
            // Maintenance mode middleware
            \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        ]);
        
        // Middleware groups for specific functionality
        $middleware->group('installation', [
            'installer.not_installed',
            'throttle:10,1', // Rate limit installation attempts
        ]);
        
        $middleware->group('dive_shop', [
            'auth',
            'active',
            'shop.access',
        ]);
        
        $middleware->group('instructor_only', [
            'auth',
            'active',
            'instructor',
            'cert.valid',
        ]);
        
        $middleware->group('admin_only', [
            'auth',
            'active',
            'admin',
        ]);
        
        $middleware->group('customer_api', [
            'auth:sanctum',
            'active',
            'api.customer',
            'throttle:api',
        ]);
        
        $middleware->group('shop_api', [
            'auth:sanctum',
            'active',
            'shop.access',
            'api.shop',
            'throttle:api',
        ]);
        
        // Conditional middleware based on configuration
        if (config('diveforge.require_medical_clearance', true)) {
            $middleware->appendToGroup('dive_shop', 'medical.clearance');
            $middleware->appendToGroup('instructor_only', 'medical.clearance');
        }
        
        // Development middleware (only in debug mode)
        if (config('app.debug')) {
            $middleware->web(append: [
                // \App\Http\Middleware\DebugBar::class,
                // \App\Http\Middleware\QueryLogger::class,
            ]);
        }
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Custom exception handling for DiveForge
        
        // Handle installation-related exceptions
        $exceptions->map(\App\Exceptions\InstallationException::class, function ($e) {
            \Illuminate\Support\Facades\Log::error('Installation error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'error' => 'Installation error occurred',
                    'message' => config('app.debug') ? $e->getMessage() : 'Please check the logs for more details',
                    'type' => 'installation_error'
                ], 500);
            }
            
            return redirect()->route('installer.step1.show')
                ->with('error', 'Installation error: ' . $e->getMessage());
        });
        
        // Handle dive shop access exceptions
        $exceptions->map(\App\Exceptions\ShopAccessException::class, function ($e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'error' => 'Access denied',
                    'message' => $e->getMessage(),
                    'type' => 'shop_access_denied'
                ], 403);
            }
            
            return redirect()->route('dashboard')
                ->with('error', 'You do not have access to this dive shop.');
        });
        
        // Handle certification exceptions
        $exceptions->map(\App\Exceptions\CertificationException::class, function ($e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'error' => 'Certification required',
                    'message' => $e->getMessage(),
                    'type' => 'certification_required'
                ], 403);
            }
            
            return redirect()->route('dashboard')
                ->with('error', 'Valid certification required: ' . $e->getMessage());
        });
        
        // Handle medical clearance exceptions
        $exceptions->map(\App\Exceptions\MedicalClearanceException::class, function ($e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'error' => 'Medical clearance required',
                    'message' => $e->getMessage(),
                    'type' => 'medical_clearance_required'
                ], 403);
            }
            
            return redirect()->route('profile.medical')
                ->with('error', 'Medical clearance required: ' . $e->getMessage());
        });
        
        // Handle equipment availability exceptions
        $exceptions->map(\App\Exceptions\EquipmentUnavailableException::class, function ($e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'error' => 'Equipment unavailable',
                    'message' => $e->getMessage(),
                    'type' => 'equipment_unavailable',
                    'available_alternatives' => $e->getAlternatives()
                ], 409);
            }
            
            return redirect()->back()
                ->with('error', 'Equipment unavailable: ' . $e->getMessage());
        });
        
        // Handle booking exceptions
        $exceptions->map(\App\Exceptions\BookingException::class, function ($e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'error' => 'Booking error',
                    'message' => $e->getMessage(),
                    'type' => 'booking_error'
                ], 400);
            }
            
            return redirect()->back()
                ->with('error', 'Booking error: ' . $e->getMessage());
        });
        
        // Handle API validation errors
        $exceptions->map(\Illuminate\Validation\ValidationException::class, function ($e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'error' => 'Validation failed',
                    'message' => 'The given data was invalid.',
                    'errors' => $e->errors(),
                    'type' => 'validation_error'
                ], 422);
            }
            
            // Let Laravel handle non-API validation errors normally
            return null;
        });
        
        // Enhanced error logging for production
        $exceptions->reportable(function (Throwable $e) {
            if (app()->environment('production')) {
                \Illuminate\Support\Facades\Log::error('Production error', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'user_id' => auth()->id(),
                    'url' => request()->fullUrl(),
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            }
        });
        
        // Custom error pages for specific HTTP codes
        $exceptions->respond(function (Response $response, Throwable $exception, Request $request) {
            if ($response->getStatusCode() === 404 && !$request->expectsJson()) {
                return response()->view('errors.404', [], 404);
            }
            
            if ($response->getStatusCode() === 403 && !$request->expectsJson()) {
                return response()->view('errors.403', [], 403);
            }
            
            if ($response->getStatusCode() === 500 && !$request->expectsJson()) {
                return response()->view('errors.500', [], 500);
            }
            
            return $response;
        });
    })
    ->create();