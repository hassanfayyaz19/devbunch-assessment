<?php

namespace App\Http\Middleware;

use App\Models\UserType;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateUserType
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user_types = UserType::all()->pluck('id')->toArray();
        $user = $request->user();
        if (!in_array($user->user_type_id, $user_types)) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
