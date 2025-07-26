<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->cookie('locale')) {
            app()->setLocale($request->cookie('locale'));
        } else {
            app()->setLocale(config('app.locale'));
        }

        return $next($request);
    }

}
