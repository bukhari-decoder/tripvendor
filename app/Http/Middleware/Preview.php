<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Preview
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $themesConfig = config('basic.themes');
        if ($request->has('theme') && array_key_exists($request->input('theme'), $themesConfig)) {
            session(['theme' => $request->input('theme')]);
        }

        if ($request->has('home_version')) {
            $homeVersion = $request->input('home_version');
            $isValidHomeVersion = collect($themesConfig)->flatten()->contains($homeVersion);

            if ($isValidHomeVersion) {
                session(['home_version' => $homeVersion]);
            }
        }
        if ($request->has('tour_list_version')) {
            $listVersion = config('basic.list');
            $listStyle = $request->input('tour_list_version');

            if (in_array($listStyle, $listVersion)) {
                session(['tour_list_version' => $listStyle]);
            }
        }

        return $next($request);
    }
}
