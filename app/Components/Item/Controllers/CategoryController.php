<?php


namespace App\Components\Item\Controllers;


use App\Components\Item\Repositories\CategoryRepository;
use App\Helpers\Helper;
use App\Http\Controllers\BaseController;
use App\Http\Models\Category;

class CategoryController extends BaseController
{
    public function __construct(CategoryRepository $_repo)
    {
        parent::__construct();
        $this->repo = $_repo;
    }
    public function rules($id=null){
        $rules = [
            "name"=>"required|max:150|unique:".Category::table().",name,$id,id",
        ];
        return $rules;
    }
    public function messages($id=null){
        $message = [
            "name.required"=>trans("Name Cannot be null"),
            "name.unique"=>trans("Name was exist")
        ];
        return $message;
    }

    public function create(){
        try{
            $err = Helper::validate($this->request->all(),$this->rules(),$this->messages());
            if(!empty($err)){
                return $this->response([],$err,false,self::HTTP_BAD_REQUEST);
            }
            $data= $this->request->only([
                "name",
                "parent_id",
                "desc",
                "url_seo",
                "title",
                "icon",
                "thunbnail",
                "priority",
                "status",
            ]);
            $res = $this->repo->create($data);
            return $this->response($res);
        }
        catch (\Exception $e){
            return $this->response([],$e->getMessage(),false,self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function update($id){
        $err = Helper::validate($this->request->all(),$this->rules($id),$this->messages($id));
        if(!empty($err)){
            return $this->response([],$err,false,self::HTTP_BAD_REQUEST);
        }
        $data= $this->request->only([
            "name",
            "parent_id",
            "desc",
            "url_seo",
            "title",
            "icon",
            "thunbnail",
            "priority",
            "status",
        ]);
        $detail = $this->repo->findWhere(['id'=>$id])->first();
        if(empty($detail)){
            return $this->response([],trans('validation.Not Found'),false,self::HTTP_BAD_REQUEST);
        }
        $detail->update($data);
        return $this->response($detail);
    }
    public function delete($id){
        try{
            $detail = $this->repo->findWhere(['id'=>$id])->first();
            if(empty($detail)){
                return $this->response([],trans('Not Found'),false,self::HTTP_BAD_REQUEST);
            }
            $detail->delete();
            return $this->response([],trans("Deleted successfully"));
        }
        catch (\Exception $e){
            return $this->response([],$e->getMessage(),false,self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function detail($id){
        try{
            $res = $this->repo->detail($id);
            if(empty($res)){
                return $this->response([],trans('Not Found'),false,self::HTTP_BAD_REQUEST);
            }
            return $this->response($res);
        }
        catch (\Exception $e){
            return $this->response([],$e->getMessage(),false,self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function getList(){
        try{
            $params = $this->request->all();
            $res = $this->repo->getList($params);
            return $this->response($res);
        }
        catch (\Exception $e){
            return $this->response([],$e->getMessage(),false,self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function getOption(){
        try{
            $res = $this->repo->getOption();
            return $this->response($res);
        }
        catch (\Exception $e){
            return $this->response([],$e->getMessage(),false,self::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
