<?php

namespace App\Http\Middleware;

use App\Models\User_event;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ifCompleteMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $eventsToUpdate =User_event::where('completed', 0)
         ->where('created_at', '<=', Carbon::now()->subDays(3)
         ->toDateTimeString())
          ->get();

    foreach ($eventsToUpdate as $event) {

        $event->update(['completed' => 1]);
    }
        return $next($request);
    }
}
