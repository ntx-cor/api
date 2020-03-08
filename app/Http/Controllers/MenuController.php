<?php


namespace App\Http\Controllers;


use App\Repositories\MenuRepository;
use Illuminate\Support\Facades\Auth;

class MenuController extends BaseController
{
    public function __construct()
    {
    }
    public function getMenuByUser(){
        $menus = MenuRepository::instance()->getMenuByUser( Auth::id());
        $res = $this->recursiveMenu($menus);
        return $this->response($res);
    }
    public function recursiveMenu($array, $parent_id = 0)
    {
        $temp_array = array();
        foreach ($array as $element) {
            if ($element['parent_id'] == $parent_id) {
                $element['subs'] = $this->orderedMenu($array, $element['id']);
                $temp_array[] = $element;
            }
        }
        return $temp_array;
    }

}
