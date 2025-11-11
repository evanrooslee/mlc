<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SeoRobotsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // List of paths that should not be indexed by search engines
        $noindexPaths = [
            '/login',
            '/register',
            '/admin',
            '/user-profile',
            '/user-kelas',
            '/beli-konfirmasi',
            '/beli-paket',
            '/packets/filter',
            '/proses-pembayaran',
            '/validate-discount',
            '/get-available-discounts',
            '/track-discount-click',
        ];

        // Check if current path should be noindexed
        $currentPath = $request->path();
        $shouldNoindex = false;

        foreach ($noindexPaths as $path) {
            if (str_starts_with('/' . $currentPath, $path)) {
                $shouldNoindex = true;
                break;
            }
        }

        // Add X-Robots-Tag header if path should not be indexed
        if ($shouldNoindex) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
        }

        return $response;
    }
}
