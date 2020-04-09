<?php


namespace App\Repositories;


use App\Models\ItemSku;

class ItemSkuRepository extends BaseRepository
{
    public function model()
    {
        return ItemSku::class;
    }
}
