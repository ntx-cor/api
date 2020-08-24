<?php


namespace App\Http\Models;


use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends BaseModel implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, SoftDeletes;
    protected $table = 'user';
    protected $dates = ['deleted_at'];

    protected $guarded = [];
//    protected $fillable = [
//        'username', 'email', 'password',
//    ];
    protected $hidden = [
        'password', 'remember_token','created_by','updated_by'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function roles(){
        return $this->belongsToMany(Role::class,UserRole::table());
    }
    public static function permissions($userId){
        $query = RoleMenuAction::select(
            DB::raw('CONCAT('.Menu::column('code').',"_",'.Action::column('code').') AS permission')
        )
            ->join(Action::table(),Action::column('id'),RoleMenuAction::column('action_id'))
            ->join(RoleMenu::table(),RoleMenu::column('id'),RoleMenuAction::column('role_menu_id'))
            ->join(Role::table(),Role::column('id'),RoleMenu::column('role_id'))
            ->join(Menu::table(),Menu::column('id'),RoleMenu::column('menu_id'))
            ->join(UserRole::table(),UserRole::column('role_id'),Role::column('id'))
            ->where(UserRole::column('user_id'),$userId)
        ;
        $result = $query->pluck("permission")->all();
        return $result;
    }
}
