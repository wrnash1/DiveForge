<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
class InstallerNotInstalled
{
    public function handle(Request $request, Closure $next)
    {
        if (file_exists(storage_path('installed'))) {
            return redirect('/');
        }
        return $next($request);
    }
}
