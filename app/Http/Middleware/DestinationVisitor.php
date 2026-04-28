<?php

namespace App\Http\Middleware;

use App\Helpers\UserSystemInfo;
use App\Models\Destination;
use App\Models\DestinationVisitor as Visitor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DestinationVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $destination = Destination::where('slug', $request->slug)->first();
        if ($destination) {
            $destinationId = $destination->id;

            $ipAddress = $request->ip();
            $userAgent = $request->header('User-Agent');

            $key = "bouncing_time_{$destinationId}_{$ipAddress}_{$userAgent}";

            if (!\Cache::has($key)) {
                $bouncingTime = now();

                Visitor::create([
                    'destination_id' => $destinationId,
                    'ip_address' => $ipAddress,
                    'bouncing_time' => $bouncingTime,
                    'browser_info' => UserSystemInfo::get_browsers(),
                    'os' => UserSystemInfo::get_os(),
                    'device' => UserSystemInfo::get_device()
                ]);

                $destination->update([
                    'total_visited' => $destination->total_visited + 1,
                ]);

                \Cache::put($key, $bouncingTime, now()->addMinutes(30));
            }
        }

        return $next($request);
    }
}
