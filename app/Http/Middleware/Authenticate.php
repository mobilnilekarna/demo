<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Inertia\Inertia;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, \Closure $next, ...$guards)
    {
        try {
            $this->authenticate($request, $guards);
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            // Pro Inertia požadavky použijeme Inertia location redirect
            if ($request->header('X-Inertia')) {
                $redirectTo = $this->getRedirectPath($request);
                return Inertia::location($redirectTo);
            }

            throw $e;
        }

        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        return $this->getRedirectPath($request);
    }

    /**
     * Determine the redirect path based on the route type.
     * Dashboard routes -> dashboard login
     * Client routes -> homepage
     */
    protected function getRedirectPath(Request $request): string
    {
        // Pokud je cesta pod /dashboard, přesměruj na dashboard login
        if ($request->is('dashboard*')) {
            return route('dashboard.login');
        }

        // Pro klientské route (jako /profile) přesměruj na homepage
        return route('index');
    }
}
