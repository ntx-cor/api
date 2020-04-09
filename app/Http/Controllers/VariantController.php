<?php


namespace App\Http\Controllers;


use App\Repositories\VariantRepository;

class VariantController extends BaseController
{
    public function __construct()
    {
    }
    public function getOption(){
        $res = VariantRepository::instance()->getOption();
        return $this->response($res);
    }
    public function getValueByVariant($id){
        $res = VariantRepository::instance()->getValueByVariant($id);
        return $this->response($res);
    }
}
