<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class InstallerNotInstalled
{
    /**
     * Handle an incoming request.
     * 
     * Prevents access to installation routes if DiveForge is already installed.
     * Also validates installation step progression to ensure users complete steps in order.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if installation marker exists
        $installedFile = storage_path('installed');
        
        if (File::exists($installedFile)) {
            // Application is already installed
            Log::info('Installation routes accessed after installation complete', [
                'route' => $request->route()?->getName(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            // If this is an AJAX request, return JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'DiveForge is already installed',
                    'installed' => true,
                    'redirect_url' => url('/'),
                    'installation_date' => $this->getInstallationDate()
                ], 403);
            }
            
            // For regular requests, redirect to home page with message
            return redirect('/')
                ->with('info', 'DiveForge is already installed and ready to use!')
                ->with('installation_complete', true);
        }
        
        // Validate installation step progression
        $this->validateInstallationStep($request);
        
        // Log installation step access for debugging
        $this->logInstallationStep($request);
        
        return $next($request);
    }
    
    /**
     * Validate that the user has completed previous installation steps
     */
    private function validateInstallationStep(Request $request): void
    {
        $currentRoute = $request->route()?->getName();
        
        if (!$currentRoute) {
            return;
        }
        
        // Define step dependencies - each step requires previous steps to be complete
        $stepDependencies = [
            // Step 2 requires Step 1
            'installer.step2.show' => 'installer.step1_complete',
            'installer.step2.process' => 'installer.step1_complete',
            'installer.ajax.test.database' => 'installer.step1_complete',
            
            // Step 3 requires Step 2
            'installer.step3.show' => 'installer.step2_complete',
            'installer.step3.process' => 'installer.step2_complete',
            'installer.ajax.validate.admin' => 'installer.step2_complete',
            
            // Step 4 requires Step 3
            'installer.step4.show' => 'installer.step3_complete',
            'installer.step4.process' => 'installer.step3_complete',
            'installer.ajax.check.shop_name' => 'installer.step3_complete',
            'installer.ajax.test.email' => 'installer.step3_complete',
            
            // Finish requires Step 4
            'installer.finish.show' => 'installer.step4_complete',
            'installer.finish.process' => 'installer.step4_complete',
            'installer.ajax.progress' => 'installer.step4_complete',
            
            // Complete page (no validation needed - can be accessed after installation)
        ];
        
        // Check if current route has dependencies
        if (isset($stepDependencies[$currentRoute])) {
            $requiredSession = $stepDependencies[$currentRoute];
            
            if (!session($requiredSession)) {
                // User is trying to skip steps
                Log::warning('Installation step skipped', [
                    'route' => $currentRoute,
                    'required_session' => $requiredSession,
                    'ip' => $request->ip(),
                    'session_data' => $this->getSessionDebugInfo()
                ]);
                
                // Redirect to the appropriate step
                $redirectRoute = $this->getRedirectRoute($requiredSession);
                
                if ($request->expectsJson()) {
                    abort(response()->json([
                        'error' => 'Previous installation steps must be completed first',
                        'required_step' => $requiredSession,
                        'redirect_route' => $redirectRoute,
                        'current_step' => $this->getCurrentStepNumber($requiredSession)
                    ], 403));
                }
                
                abort(redirect()->route($redirectRoute)
                    ->with('error', 'Please complete the previous installation steps first.')
                    ->with('required_step', $this->getCurrentStepNumber($requiredSession)));
            }
        }
        
        // Special validation for AJAX endpoints
        $this->validateAjaxEndpoints($request, $currentRoute);
    }
    
    /**
     * Validate AJAX endpoints have proper session data
     */
    private function validateAjaxEndpoints(Request $request, ?string $currentRoute): void
    {
        if (!$request->expectsJson() || !$currentRoute) {
            return;
        }
        
        // AJAX endpoints that require specific data in session
        $ajaxValidations = [
            'installer.ajax.test.database' => ['installer.step1_complete'],
            'installer.ajax.validate.admin' => ['installer.step1_complete', 'installer.step2_complete'],
            'installer.ajax.test.email' => ['installer.step1_complete', 'installer.step2_complete', 'installer.step3_complete'],
            'installer.ajax.progress' => ['installer.step1_complete', 'installer.step2_complete', 'installer.step3_complete', 'installer.step4_complete'],
        ];
        
        if (isset($ajaxValidations[$currentRoute])) {
            $requiredSessions = $ajaxValidations[$currentRoute];
            
            foreach ($requiredSessions as $sessionKey) {
                if (!session($sessionKey)) {
                    abort(response()->json([
                        'error' => 'Installation step not completed',
                        'required_session' => $sessionKey,
                        'message' => 'Please complete the installation steps in order'
                    ], 400));
                }
            }
        }
    }
    
    /**
     * Get the redirect route based on missing session
     */
    private function getRedirectRoute(string $missingSession): string
    {
        $routeMap = [
            'installer.step1_complete' => 'installer.step1.show',
            'installer.step2_complete' => 'installer.step2.show',
            'installer.step3_complete' => 'installer.step3.show',
            'installer.step4_complete' => 'installer.step4.show',
        ];
        
        return $routeMap[$missingSession] ?? 'installer.step1.show';
    }
    
    /**
     * Get current step number for user feedback
     */
    private function getCurrentStepNumber(string $sessionKey): int
    {
        $stepMap = [
            'installer.step1_complete' => 1,
            'installer.step2_complete' => 2,
            'installer.step3_complete' => 3,
            'installer.step4_complete' => 4,
        ];
        
        return $stepMap[$sessionKey] ?? 1;
    }
    
    /**
     * Get installation date from installed file
     */
    private function getInstallationDate(): ?string
    {
        $installedFile = storage_path('installed');
        
        if (!File::exists($installedFile)) {
            return null;
        }
        
        try {
            $data = json_decode(File::get($installedFile), true);
            return $data['completed_at'] ?? null;
        } catch (\Exception $e) {
            Log::error('Failed to read installation file', [
                'error' => $e->getMessage(),
                'file' => $installedFile
            ]);
            return null;
        }
    }
    
    /**
     * Log installation step access for debugging
     */
    private function logInstallationStep(Request $request): void
    {
        if (!config('app.debug')) {
            return;
        }
        
        $currentRoute = $request->route()?->getName();
        
        if (str_starts_with($currentRoute ?? '', 'installer.')) {
            Log::debug('Installation step accessed', [
                'route' => $currentRoute,
                'method' => $request->method(),
                'ip' => $request->ip(),
                'session_keys' => array_keys(session()->all()),
                'completed_steps' => $this->getCompletedSteps()
            ]);
        }
    }
    
    /**
     * Get completed installation steps for debugging
     */
    private function getCompletedSteps(): array
    {
        $steps = [
            'step1' => session('installer.step1_complete', false),
            'step2' => session('installer.step2_complete', false),
            'step3' => session('installer.step3_complete', false),
            'step4' => session('installer.step4_complete', false),
        ];
        
        return array_filter($steps);
    }
    
    /**
     * Get session debug info (non-sensitive data only)
     */
    private function getSessionDebugInfo(): array
    {
        return [
            'step1_complete' => session('installer.step1_complete', false),
            'step2_complete' => session('installer.step2_complete', false),
            'step3_complete' => session('installer.step3_complete', false),
            'step4_complete' => session('installer.step4_complete', false),
            'has_db_config' => session()->has('installer.db_config'),
            'has_admin_config' => session()->has('installer.admin_config'),
            'has_shop_config' => session()->has('installer.shop_config'),
            'start_time' => session('installer.start_time'),
        ];
    }
    
    /**
     * Check if installation is in progress
     */
    private function isInstallationInProgress(): bool
    {
        return session()->has('installer.step1_complete') ||
               session()->has('installer.step2_complete') ||
               session()->has('installer.step3_complete') ||
               session()->has('installer.step4_complete');
    }
    
    /**
     * Clean up installation session if corrupted
     */
    private function cleanupCorruptedSession(): void
    {
        if (!$this->isInstallationInProgress()) {
            return;
        }
        
        // Check if session data is corrupted (steps completed out of order)
        $step1 = session('installer.step1_complete', false);
        $step2 = session('installer.step2_complete', false);
        $step3 = session('installer.step3_complete', false);
        $step4 = session('installer.step4_complete', false);
        
        $corrupted = false;
        
        // Step 2 can't be complete without step 1
        if ($step2 && !$step1) $corrupted = true;
        
        // Step 3 can't be complete without steps 1 & 2
        if ($step3 && (!$step1 || !$step2)) $corrupted = true;
        
        // Step 4 can't be complete without steps 1, 2 & 3
        if ($step4 && (!$step1 || !$step2 || !$step3)) $corrupted = true;
        
        if ($corrupted) {
            Log::warning('Corrupted installation session detected, cleaning up', [
                'step_states' => [$step1, $step2, $step3, $step4],
                'ip' => request()->ip()
            ]);
            
            // Clear all installation session data
            $sessionKeys = [
                'installer.step1_complete',
                'installer.step2_complete',
                'installer.step3_complete',
                'installer.step4_complete',
                'installer.db_config',
                'installer.admin_config',
                'installer.shop_config',
                'installer.start_time'
            ];
            
            foreach ($sessionKeys as $key) {
                session()->forget($key);
            }
        }
    }
}