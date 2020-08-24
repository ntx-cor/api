<?php


namespace App\Http\Repositories;


use Illuminate\Container\Container as Application;
use Illuminate\Pagination\LengthAwarePaginator;
use Prettus\Repository\Eloquent\BaseRepository as Repository;

abstract class BaseRepository extends Repository
{
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }
    /**
     * @return static
     */
    public static function instance(){
        return app(static::class);
    }
    public function formatPagination(LengthAwarePaginator $data)
    {
        $length = $data->perPage();
        $totalRecord = $data->total();
        $result = [
            'page'         => $data->currentPage(),
            'length'       => $length,
            'total_record' => $totalRecord,
            'total_page'   => ceil($totalRecord / $length),
            'rows'         => $data->items(),
        ];
        return $result;
    }

    public function pagination($query,$limit)
    {
        $result = $query->paginate($limit);
        return $this->formatPagination($result);
    }
    public function generateCode($prefixOrFormat="",$column="code"){
        $leadZ = 7;
        $lstNum = 1;
        $query = $this->model->newQuery();

        if(!empty($prefixOrFormat)){
            $query = $query->where($column,'like',"%$prefixOrFormat%");
        }
        $lstCd = $query->orderBy($column,'DESC')->first();
        if(!empty($lstCd)){
            $arrCd = explode('-',$lstCd->{$column});
            $lsSplit = count($arrCd);
            if($lsSplit>0){
                $lstNum = (int)$arrCd[$lsSplit-1]+1;
            }
        }
        if(!empty($prefixOrFormat)){
            $isFormat = count(explode('-',$prefixOrFormat))>1;
            if($isFormat){
                $cd = sprintf($prefixOrFormat,$lstNum);
            }
            else{
                $cd = sprintf($prefixOrFormat."-%0{$leadZ}d",$lstNum);
            }
        }else{
            $cd = sprintf("%0{$leadZ}d",$lstNum);
        }
        return $cd;
    }
}
