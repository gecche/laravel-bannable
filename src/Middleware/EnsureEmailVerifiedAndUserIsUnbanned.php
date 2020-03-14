<?php

namespace Gecche\UserBanning\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Redirect;
use Gecche\UserBanning\Contract\Bannable;

class EnsureEmailVerifiedAndUserIsUnbanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, $redirectToLogin = null, $redirectToForBanned = null, $redirectToVerifyEmail = null)
    {
        if (! $request->user()) {
            return $request->expectsJson()
                ? abort(403, __('user-banning::banning.not_logged'))
                : Redirect::route($redirectToLogin ?: 'login');
        }

        if ($request->user() instanceof Bannable &&
                $request->user()->isBanned()
        ) {
            return $request->expectsJson()
                ? abort(403, __('user-banning::banning.banned'))
                : Redirect::route($redirectToForBanned ?: 'logout');
        }

        if ($request->user() instanceof MustVerifyEmail &&
            ! $request->user()->hasVerifiedEmail()
        ) {
            return $request->expectsJson()
                    ? abort(403, __('user-banning::banning.not_verified'))
                    : Redirect::route($redirectToVerifyEmail ?: 'verification.notice');
        }

        return $next($request);
    }


}
