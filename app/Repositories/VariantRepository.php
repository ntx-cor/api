<?php


namespace App\Repositories;


use App\Models\Variant;

class VariantRepository extends BaseRepository
{
    public function model()
    {
        return Variant::class;
    }
}
