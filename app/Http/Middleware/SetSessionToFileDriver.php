<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
class SetSessionToFileDriver
{
    public function handle(Request $request, Closure $next)
    {
        Config::set('session.driver', 'file');
        return $next($request);
    }
}
