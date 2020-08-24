<?php


namespace App\Http\Models;


class Role extends BaseModel
{
    protected $table = 'role';
    protected $guarded = [];
    public function menus(){
        return $this->belongsTo(Menu::class,RoleMenu::table());
    }
    public function roleMenus(){
        return $this->hasMany(RoleMenu::class,'role_id','id');
    }
}
