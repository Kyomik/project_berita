<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class CheckRole
{
    protected $auth;

    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next, ...$roles)
    {
        try {
            $user = $this->auth->parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if (!in_array($user->hak_akses, $roles)) {
            return response()->json(['error' => 'Forbidden'], 403);
        }
        $request->attributes->set('user', $user);
        return $next($request);
    }
}
