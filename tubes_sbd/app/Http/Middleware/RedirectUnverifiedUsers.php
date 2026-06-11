<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectUnverifiedUsers
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (
            $user instanceof MustVerifyEmail
            && ! $user->hasVerifiedEmail()
            && ! $request->routeIs(
                'verification.notice',
                'verification.send',
                'verification.verify',
                'logout'
            )
        ) {
            return redirect()->route('verification.notice');
        }

        return $next($request);
    }
}
