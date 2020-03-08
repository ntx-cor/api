<?php


namespace App\Repositories;


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
}
