<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

use Closure;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // if (! $request->expectsJson()) {
        //     return route('login');
        // }
    }

    public function handle($request, Closure $next, ...$guards) {
      // if (!Session()->has('loginId')) {
      //   return redirect('/')->with('fail', __('validation.required_login'));
      // }
      return $next($request);
    }
}
