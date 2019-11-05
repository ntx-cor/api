<?php


namespace App\Http\Controllers;


use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function getUser($id){
        $userRepo = UserRepository::instance();
        $user = $userRepo->find($id,['id','username','first_name','last_name']);
        return $this->response($user);
    }
    public function getListUser(){
        $userRepo = UserRepository::instance();
        $user = $userRepo->paginate();
        return $this->response($user);
    }
    public function getPermission(){
        $res = UserRepository::instance()->getPermission(Auth::id());
        return $this->response($res);
    }
}
