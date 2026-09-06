<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }

        $user = auth()->user();

        if (!in_array($user->role, $roles)) {
            // Redirect based on role
            return match($user->role) {
                'admin' => redirect()->route('superadmin.dashboard'),
                'store' => redirect()->route('store.dashboard'),
                'rider' => redirect()->route('riders.dashboard'),
                default => redirect()->route('home')->with('error', 'Unauthorized access.'),
            };
        }

        // Enforce approval check for Store
        if ($user->isStore()) {
            $store = $user->store;
            if (!$store || $store->status !== 'approved') {
                auth()->logout();
                return redirect()->route('login')->with('error', 'Kailangan munang ma-approve ng Super Admin ang iyong Store account bago ma-access ang dashboard.');
            }
        }

        // Enforce approval check for Rider
        if ($user->isRider()) {
            $rider = $user->rider;
            if (!$rider || $rider->status !== 'approved') {
                auth()->logout();
                return redirect()->route('login')->with('error', 'Kailangan munang ma-approve at ma-activate ng Super Admin ang iyong Rider account bago ma-access ang portal.');
            }
        }

        return $next($request);
    }
}
