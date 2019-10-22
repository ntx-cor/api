<?php


namespace App\Repositories;


use App\Models\User;

class UserRepository extends BaseRepository
{
    public function model()
    {
        return User::class;
    }
    public function getUser($id){
        $user = $this->model->find($id);
        return $user;
    }
}
