<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class PermMiddleware
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, ...$perm)
    {
//        $perm = array_slice(func_get_args(),2);
//        dd($this->auth->guard());
        $auth = JWTAuth::user();
        $userPerm = User::permissions($auth->id);
        $hasPerm = false;
        foreach ($perm as $p){
            if(in_array($p,$userPerm)){
                $hasPerm = true;
                break;
            }
        }
        if(!$hasPerm){
            return response('Permission denied.', 403);
        }
        return $next($request);
    }
}
