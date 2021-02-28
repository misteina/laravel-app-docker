<?php

namespace App\Http\Middleware;

use Closure;
use \Firebase\JWT\JWT;
use \Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Cookie;

class JSONWebTokenAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $id = $request->cookie('id');
        $auth = $request->cookie('auth');
        $secret = env('JWT_SECRET', 'e8it8u');

        if ($id && $auth && $secret){
            try {
                $data = JWT::decode($auth, $secret, array('HS256'));

                if ($data->uid != $id){
                    return ['status' => 401, 'type' => 'error', 'message' => 'Authentication failed'];
                }
            } catch (ExpiredException $e) {
                return ['status' => 401, 'type' => 'error', 'message' => $e->getMessage()];
            }
        } else {
            return ['status' => 401, 'type' => 'error', 'message' => 'Unauthorized request'];
        }

        return $next($request);
    }
}
