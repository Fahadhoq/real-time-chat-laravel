<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogIncomingRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Log the request details to the custom log file
        $logData = [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'time' => now()->toDateTimeString(),
        ];

        // Log to storage/logs/custom.log
        Log::channel('custom')->info('Incoming Request:', $logData);

        return $next($request);
    }
}
