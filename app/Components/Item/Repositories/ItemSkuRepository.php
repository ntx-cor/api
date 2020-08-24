<?php


namespace App\Components\Item\Repositories;


use App\Http\Models\ItemSku;
use App\Http\Repositories\BaseRepository;

class ItemSkuRepository extends BaseRepository
{
    public function model()
    {
        return ItemSku::class;
    }
}
