<?php


namespace App\Http\Repositories;


use App\Http\Models\Variant;
use App\Http\Models\VariantValue;

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
