<?php

namespace App\Http\Middleware;

use App\Support\PublicUrl;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (
            PublicUrl::shouldForceHttps() &&
            ! $request->isSecure() &&
            $request->getMethod() !== 'OPTIONS'
        ) {
            return redirect()->to(PublicUrl::normalize($request->fullUrl()), 301);
        }

        return $next($request);
    }
}
