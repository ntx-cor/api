<?php


namespace App\Http\Models;


class RoleMenuAction extends BaseModel
{
    protected $table = 'role_menu_action';
    protected $guarded = [];
    public function actions(){
        return $this->hasMany(Action::class,'id','action_id');
    }
}
