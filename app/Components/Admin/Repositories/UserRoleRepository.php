<?php


namespace App\Components\Admin\Repositories;


use App\Http\Models\UserRole;
use App\Http\Repositories\BaseRepository;

class UserRoleRepository extends BaseRepository
{
    public function model()
    {
        return UserRole::class;
    }
}
