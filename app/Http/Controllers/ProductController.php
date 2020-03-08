<?php


namespace App\Http\Controllers;


use App\Repositories\ProductRepository;

class ProductController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function create(){
        $params = $this->req;
//        dd($params->variants);
//        dd(json_decode($params->attrs));
        $res = ProductRepository::instance()->create($params);
        return $res;
    }
    public function update($id){
        $params = $this->req;
        $res = ProductRepository::instance()->update($id,$params);
        return $this->response($res);
    }
    public function detail($id){
        $res = ProductRepository::instance()->detail($id);
        return $this->response($res);
    }
    public function delete($id){
        $res = ProductRepository::instance()->delete($id);
        return $this->response($res);
    }
    public function getList(){
        $params = $this->req;
        $res = ProductRepository::instance()->getList($params);
        return $this->response($res);
    }
}
