<?php


namespace App\Models;


class RoleMenu extends BaseModel
{
    protected $table = 'role_menu';
    protected $guarded = [];
    public function menus(){
        return $this->hasMany(Menu::class,'id','menu_id');
    }
    public function roleMenuAction(){
        return $this->hasMany(RoleMenuAction::class,'role_menu_id','id');
    }
}
