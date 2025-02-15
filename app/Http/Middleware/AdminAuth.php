<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers;


class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $role = collect([1]);
$user_role = auth()->user()->is_admin;
if ($role->contains($user_role)) {
    return $next($request);
}
return response()->json(['msg'=> 'Access Denied,you are not an admin']);
    }
}
