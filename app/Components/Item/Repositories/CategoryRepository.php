<?php


namespace App\Components\Item\Repositories;


use App\Http\Models\Category;
use App\Http\Models\User;
use App\Http\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

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
        $limit = $params['limit']??LIMIT;
        $query = $this->model->select([
                Category::column("*"),
                DB::raw('P.name as parent_name'),
                DB::raw('CONCAT(CU.first_name," ",CU.last_name) AS created_by_name'),
                DB::raw('CONCAT(UU.first_name," ",UU.last_name) AS updated_by_name')
            ])
            ->leftJoin(User::table().' as CU', 'CU.id',Category::column('created_by'))
            ->leftJoin(User::table().' as UU', 'UU.id',Category::column('updated_by'))
            ->leftJoin(Category::table()." as P", "P.id",Category::column('parent_id'));
        return $this->pagination($query,$limit);
    }
    public function getOption(){
        $query = $this->model->select("*")
            ->get();
        return $query;
    }
}
