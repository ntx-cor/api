<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWT;

class AuthController extends  BaseController
{
    public function __construct()
    {
    }

    public function login(Request $request){
        $this->validate($request,[
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        $credentials  = $request->only(['username','password']);
        $remember = $request->get('remember',0);

        if($remember){
            $token = Auth::attempt($credentials,1);
        }
        else{
            $token = Auth::attempt($credentials);
        }
        if(!$token){
            return $this->response([],'Unauthorized',self::HTTP_UNAUTHORIZED);
        }
        return $this->response([
            'token' => $token,
            'expires_in' => Auth::factory()->getTTL() * 60
        ],'Login Success');
    }

    public function logout(){
        /*Auth::logout();
        JWTAuth::invalidate(JWTAuth::getToken());
        JWTAuth::parseToken()->invalidate();*/
        JWTAuth::invalidate(JWTAuth::getToken());
        return $this->response([],'Logout Success');
    }
    public function info(){
        $user = Auth::user();
        return $this->response($user);
    }
}
