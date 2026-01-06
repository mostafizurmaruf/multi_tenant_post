<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tenant;


class ResolveTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenantUuid = $request->header('X-Tenant-ID');

        if (!$tenantUuid) {
            return response()->json([
                'message' => 'X-Tenant-ID header missing'
            ], 400);
        }

        $tenant = Tenant::where('uuid', $tenantUuid)->first();

        if (!$tenant) {
            return response()->json([
                'message' => 'Invalid Tenant'
            ], 403);
        }

        // Global tenant context
        app()->instance('tenant', $tenant);

        return $next($request);
    }
}
