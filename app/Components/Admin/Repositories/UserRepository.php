<?php


namespace App\Components\Admin\Repositories;


use App\Http\Models\Role;
use App\Http\Models\User;
use App\Http\Models\UserRole;
use App\Http\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseRepository
{
    public function model()
    {
        return User::class;
    }
    public function detail($id){
        $user = $this->model->find($id);
        return $user;
    }
    public function getPermission($id){
        $res = $this->model->permissions($id);
        return $res;
    }
    public function getList($params){
        $limit = $params['limit']??LIMIT;
        $query = $this->model->select([
                User::column('*'),
                UserRole::column('role_id'),
                Role::column('name').' AS role_name',
                DB::raw('CU.username as created_by_name'),
                DB::raw('UU.username as updated_by_name'),
                DB::raw('DU.username as deleted_by_name'),
            ])
            ->leftJoin(User::table().' AS CU','CU.id',User::column('created_by'))
            ->leftJoin(User::table().' AS UU','UU.id',User::column('updated_by'))
            ->leftJoin(User::table().' AS DU','UU.id',User::column('deleted_by'))
            ->leftJoin(UserRole::table(),UserRole::column('user_id'),User::column('id'))
            ->leftJoin(Role::table(),Role::column('id'),UserRole::column('role_id'));
        if(!empty($params['username'])){
            $query->where(User::column('username'),"like","%{$params['username']}%");
        }
        return $this->pagination($query,$limit);
    }
}
