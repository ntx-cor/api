<?php


namespace App\Repositories;


use App\Models\Variant;
use App\Models\VariantValue;

class VariantRepository extends BaseRepository
{
    public function model()
    {
        return Variant::class;
    }
    public function getOption(){
        $query = $this->model->get();
        return $query;
    }
    public function getValueByVariant($variantId){
        $query = VariantValue::where('variant_id',$variantId)
            ->get();
        return $query;
    }
}
