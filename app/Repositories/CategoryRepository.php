<?php


namespace App\Repositories;


use App\Models\Category;

class CategoryRepository extends BaseRepository
{
    public function model()
    {
        return Category::class;
    }
    public function detail($id){
        $res = $this->model->find($id);
        return $res;
    }
    public function update($id,$params){
        $res = $this->model->find($id);
        if(empty($res)){
            return null;
        }
        $res->update($params);
        return $res;
    }
    public function delete($id){
        $res = $this->model->find($id);
        if(!empty($res)){
            return false;
        }
        $res->delete();
        return true;
    }
    public function getList($params){
        $limit = $params->get('limit');
        $query = $this->model->select("*")
            ->paginate($limit);
        return $query;
    }
}
