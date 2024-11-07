<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Libraries\JsonResponse;
use Illuminate\Support\Facades\Log;

class JwtMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $user->roles_name = $user->role == 1 ? 'admin' : ($user->role == 2 ? 'moderator' : 'member');
            $request->user = $user;
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return JsonResponse::BadDynamic('Invalid Token', 401);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return JsonResponse::BadDynamic('Token Expired', 401);
            } else {
                return JsonResponse::BadDynamic('Token not found', 401);
            }
        }

        return $next($request);
    }
}
