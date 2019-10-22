<?php


namespace App\Http\Controllers;


use App\Repositories\UserRepository;

class UserController extends BaseController
{
    public function __construct()
    {
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
}
