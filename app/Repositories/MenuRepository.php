<?php


namespace App\Repositories;


use App\Models\Menu;

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
            ->where('menu.status', ENABLE)
            ->where('role_menu.status', ENABLE);
        $res = $query->get();
        return $res;
    }
}
