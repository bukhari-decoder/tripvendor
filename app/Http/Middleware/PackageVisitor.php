<?php

namespace App\Http\Middleware;

use App\Helpers\UserSystemInfo;
use App\Models\Package;
use App\Models\PackageVisitor as visit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class PackageVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $package = Package::where('slug', $request->slug)->first();
        if ($package) {
            $packageId = $package->id;
            $ipAddress = $request->ip();
            $userAgent = $request->header('User-Agent');

            $key = "bouncing_time_{$packageId}_{$ipAddress}_{$userAgent}";

            if (!Cache::has($key)) {
                $bouncingTime = now();

                visit::create([
                    'package_id' => $packageId,
                    'ip_address' => $ipAddress,
                    'bouncing_time' => $bouncingTime,
                    'browser_info' => UserSystemInfo::get_browsers(),
                    'os' => UserSystemInfo::get_os(),
                    'device' => UserSystemInfo::get_device(),
                    'user_agent' => $userAgent,
                ]);

                $package->increment('view_count');

                Cache::put($key, $bouncingTime, now()->addMinutes(30));
            }
        }

        return $next($request);
    }
}
