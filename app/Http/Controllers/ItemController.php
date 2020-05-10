<?php


namespace App\Http\Controllers;


use App\Repositories\ItemRepository;
use App\Repositories\ItemSkuRepository;

class ItemController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function create(){
        $params = $this->request;
        $res = ItemRepository::instance()->create($params);
        return $this->response($res);
    }
    public function update($id){
        $params = $this->request;
        $res = ItemRepository::instance()->update($id,$params);
        return $this->response($res);
    }
    public function detail($id){
        $res = ItemRepository::instance()->detail($id);
        return $this->response($res);
    }
    public function delete($id){
        $res = ItemRepository::instance()->delete($id);
        return $this->response($res);
    }
    public function getList(){
        $params = $this->request;
        $res = ItemRepository::instance()->getList($params);
        return $this->response($res);
    }
}
