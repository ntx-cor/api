<?php


namespace App\Components\Admin\Repositories;


use App\Http\Models\Role;
use App\Http\Repositories\BaseRepository;

class RoleRepository extends BaseRepository
{
    public function model()
    {
        return Role::class;
    }
}
