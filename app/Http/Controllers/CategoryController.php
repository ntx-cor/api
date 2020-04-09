<?php


namespace App\Http\Controllers;


use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends BaseController
{
    public function __construct(CategoryRepository $_repo)
    {
        parent::__construct();
        $this->repo = $_repo;
    }
    public function create(){
        $params = $this->req;
        $data = [
            "name"=>$params->get('name'),
            "parent_id"=>$params->get('parent_id'),
            "desc"=>$params->get('desc'),
            "url_seo"=>$params->get('url_seo'),
            "title"=>$params->get('title'),
            "icon"=>$params->get('icon'),
            "thunbnail"=>$params->get('thunbnail'),
            "priority"=>$params->get('priority'),
            "status"=>$params->get('status'),
        ];
        $res = $this->repo->create($data);
        return $this->response($res);
    }
    public function update($id){
        $params = $this->req;
        $data = [
            "name"=>$params->get('name'),
            "parent_id"=>$params->get('parent_id'),
            "desc"=>$params->get('desc'),
            "url_seo"=>$params->get('url_seo'),
            "title"=>$params->get('title'),
            "icon"=>$params->get('icon'),
            "thunbnail"=>$params->get('thunbnail'),
            "priority"=>$params->get('priority'),
            "status"=>$params->get('status'),
        ];
        $res = $this->repo->update($id,$data);
        return $this->response($res);
    }
    public function delete($id){
        $res = $this->repo->delete($id);
        if($res){
            return $this->response([],"Delete Category success");
        }else{
            return $this->response([],"Category Not found",false);
        }
    }
    public function detail($id){
        $res = $this->repo->detail($id);
        if(!empty($res))
            return $this->response($res);
        else{
            return $this->response([],"Category Not found",false);
        }
    }
    public function getList(){
        $params = $this->req;
        $res = $this->repo->getList($params);
        return $this->response($res);
    }
    public function getOption(){
        $res = $this->repo->getOption();
        return $this->response($res);
    }
}
