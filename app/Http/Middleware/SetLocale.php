<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
{
    if (session('locale')) {
        app()->setLocale(session('locale'));
    } else {
        app()->setLocale('es');
    }
    return $next($request);
}
}