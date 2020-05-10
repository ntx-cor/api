<?php


namespace App\Http\Controllers;


use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{
    public function __construct(
        Request $_request,
        UserRepository $_repo
    ){
        $this->request = $_request;
        $this->repo = $_repo;
        $this->middleware('auth:api');
    }
    public function detail($id){
        $user = $this->repo->detail($id);
        return $this->response($user);
    }
    public function getListUser(){
        $user = UserRepository::instance()->getList($this->request->all());
        return $this->response($user);
    }
    public function getPermission(){
        $res = UserRepository::instance()->getPermission(Auth::id());
        return $this->response($res);
    }
    public function create(){
        $data = $this->request->only([
            'username',
            'first_name',
            'last_name',
            'phone',
            'email',
            'ac',
            'password'
        ]);
        if($data['password']){
            $data['password'] = Hash::make($data['password']);
        }
        else{
            $data['password'] = Hash::make(12346);
        }
        $res = $this->repo->create($data);
        return $this->response($res);
    }
    public function update($id){
        $data = $this->request->only([
            'username',
            'first_name',
            'last_name',
            'phone',
            'email',
            'ac'
        ]);
        if(!empty($this->request->get('password'))){
            $data['password'] = Hash::make($this->request->get('password'));
        }
        $res = $this->repo->update($data,$id);
        return $this->response($res);
    }
}
