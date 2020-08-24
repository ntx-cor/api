<?php


namespace App\Components\Admin\Repositories;


use App\Http\Models\Menu;
use App\Http\Repositories\BaseRepository;

class MenuRepository extends BaseRepository
{
    public function model()
    {
        return Menu::class;
    }
    public function getMenuByUser($userId){
        $query =$this->model->select('menu.*')
            ->leftJoin('role_menu', 'role_menu.menu_id', 'menu.id')
            ->leftJoin('user_role', 'user_role.role_id', 'role_menu.role_id')
            ->where('user_role.user_id', $userId)
            ->where('menu.status', ACTIVE)
            ->where('role_menu.status', ACTIVE);
        $res = $query->get();
        return $res;
    }
}
