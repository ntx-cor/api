<?php


namespace App\Repositories;


use App\Libraries\Helpers;
use App\Models\Inventory;
use App\Models\Item;
use App\Models\ItemAttribute;
use App\Models\ItemDesc;
use App\Models\ItemImage;
use App\Models\ItemInfo;
use App\Models\ItemPrice;
use App\Models\ItemSku;
use App\Models\ItemVariant;
use App\Models\Variant;
use App\Models\VariantValue;

class ItemRepository extends BaseRepository
{
    public function model()
    {
        return Item::class;
    }
    public function getList($params){
        $limit = $params->get('limit')??1000;
        $query = $this->model->select('*')
            ->orderBy(Item::column('id'),'DESC');
        return $this->pagination($query,$limit);
    }
    public function detail($id){
        $item = $this->model->find($id);
        if(empty($item)){
            return null;
        }
        $attributes = ItemAttribute::where('item_id',$id)->get();
        $desc = ItemDesc::where('item_id',$id)->first();
        $info = ItemInfo::where('item_id',$id)->first();
        $images = ItemImage::where('item_id',$id)->get();
        $skus = ItemSku::where('item_id',$id)->get()->keyBy('id');
        $variants = ItemVariant::select([
                ItemVariant::column('*'),
                VariantValue::column('name').' AS variant_value_name',
                VariantValue::column('code').' AS variant_value_code',
                Variant::column('id'). ' AS variant_id',
                Variant::column('name').' AS variant_name',
                Variant::column('code').' AS variant_code'
            ])
            ->leftJoin(VariantValue::table(),VariantValue::column('id'),ItemVariant::column('variant_value_id'))
            ->leftJoin(Variant::table(),Variant::column('id'),VariantValue::column('variant_id'))
            ->groupBy(ItemVariant::column('id'))
            ->where(ItemVariant::column('item_id'),$id)
            ->get();
        $variantRes = [];
        if(!empty($variants)){
            foreach ($variants as $variant){
                if(!isset($variantRes[$variant->variant_id])){
                    $variantRes[$variant->variant_id] = [
                        'id'=>$variant->id,
                        'variant_id'=>$variant->variant_id,
                        'item_id'=>$variant->item_id,
                        'values'=>[]
                    ];
                }
                if(!in_array($variant->variant_value_id,$variantRes[$variant->variant_id]['values'])){
                    $variantRes[$variant->variant_id]['values'][]=$variant->variant_value_id;
                }
                if(isset($skus[$variant->item_sku_id])){
                    if(empty($skus[$variant->item_sku_id]->variant_value_name)){
                        $skus[$variant->item_sku_id]->variant_value_name = $variant->variant_value_name;
                    }else{
                        $skus[$variant->item_sku_id]->variant_value_name = $skus[$variant->item_sku_id]->variant_value_name.", ".$variant->variant_value_name;
                    }

                }
            }
        }
        $item->attrs = $attributes;
        $item->desc = $desc;
        $item->variants = array_values($variantRes);
        $item->images = $images;
        $item->info = $info;
        $item->skus = array_values($skus->toArray());
        return $item;
    }
    public function create($params)
    {
        $desc = $params->get('desc');
        $variants = $params->get('variants');
        $attrs = $params->get('attrs');
        $dataItem = [
            'name'=>$params->get('name'),
            'category_id'=>$params->get('category_id'),
            'status'=>$params->get('status'),
            'code'=>$params->get('code'),
            'title'=>$params->get('title'),
            'tag'=>$params->get('tag'),
            'url_seo'=>$params->get('url_seo'),
            'priority'=>$params->get('priority'),
            'manufacturer_id'=>$params->get('manufacturer_id'),
        ];
        $item = $this->model->create($dataItem);
        $itemId = $item->id;
        $item->desc = [];
        $item->attrs = [];
        $item->skus = [];
        $item->variants = [];
        $item->prices = [];
        if(!empty($desc)){
            $desc = json_decode($desc);
            $dataDesc = [
                'short_desc'=>$desc->short_desc??null,
                'long_desc'=>$desc->long_desc??null,
                'item_id'=>$itemId
            ];
            $item->desc = ItemDesc::create($dataDesc);
        }
        if(!empty($attrs)){
            $attrs = json_decode($attrs);
            $attrRes = [];
            foreach ($attrs as $attr){
                $attrRes[]=ItemAttribute::create([
                    "item_id"=>$itemId,
                    "name"=>$attr->name??"",
                    "desc"=>$attr->desc??"",
                ]);
            }
            $item->attrs = $attrRes;
        }
        if(!empty($variants)){
            $numV = 1;
            $arrV = [];
            foreach ($variants as $variant){
                if(count($variant['values'])>0){
                    $numV *= count($variant['values']);
                    $arrV[]= $variant['values'];
                }
            }
            $res = [];
            foreach ($arrV[0] as $v0){
                if(!empty($arrV[1])){
                    foreach ($arrV[1] as $v1){
                        if(!empty($arrV[2])){
                            foreach ($arrV[2] as $v2){
                                if(!empty($arrV[3])){
                                    foreach ($arrV[3] as $v3){
                                        $res[]=[$v0,$v1,$v2,$v3];
                                    }
                                }
                                else{
                                    $res[]=[$v0,$v1,$v2];
                                }
                            }
                        }
                        else{
                            $res[]=[$v0,$v1];
                        }
                    }
                }
                else{
                    $res[]=[$v0];
                }
            }
            $variants = [];
            $skus = [];
            $prices = [];
            foreach ($res as $variant){
                $skus[] = $sku = ItemSku::create([
                    'item_id'=>$itemId,
                    'sku'       =>ItemSkuRepository::instance()->generateCode('ITM','sku'),
                    'status'    =>ENABLE
                ]);
                foreach ($variant as $v){
                    $variants[]= ItemVariant::create([
                        'item_id'        =>$itemId,
                        'item_sku_id'    =>$sku->id,
                        'variant_value_id'  =>$v,
                        'status'            =>ENABLE
                    ]);
                }
                $prices[]=ItemPrice::create([
                    'item_id'        =>$itemId,
                    'item_sku_id'    =>$sku->id,
                    'status'            =>ENABLE
                ]);
                /*$inventories[]=Inventory::create([
                    'item_id'        =>$itemId,
                    'item_sku_id'    =>$sku->id,
                    'status'            =>ENABLE
                ]);*/
            }
            $item->skus = $skus;
            $item->variants = $variants;
            $item->prices = $prices;
        }
        $files = $params->files;
//        self::uploadItemImage($itemId, $files);
        return $item;
    }
    public function update($id,$params){
        $result = ["success"=>false,'message'=>''];
        $item = $this->model->find($id);
        if(empty($item)){
            $result['message']="Item not found";
            return $result;
        }

        $desc = $params->get('desc');
        $variants = $params->get('variants');
        $attrs = $params->get('attrs');
        $dataItem = [
            'name'=>$params->get('name'),
            'category_id'=>$params->get('category_id'),
            'status'=>$params->get('status'),
            'code'=>$params->get('code'),
            'title'=>$params->get('title'),
            'tag'=>$params->get('tag'),
            'url_seo'=>$params->get('url_seo'),
            'priority'=>$params->get('priority'),
            'manufacturer_id'=>$params->get('manufacturer_id'),
        ];
        $item->update($dataItem);
        $item->desc = [];
        $item->attrs = [];
        $item->skus = [];
        $item->variants = [];
        $item->prices = [];
        if(!empty($desc)){
            $desc = json_decode($desc);
            $dataDesc = [
                'short_desc'=>$desc->short_desc??null,
                'long_desc'=>$desc->long_desc??null,
                'item_id'=>$id
            ];
            $item->desc = ItemDesc::create($dataDesc);
        }
        if(!empty($attrs)){
            $attrs = json_decode($attrs);
            $attrRes = [];
            foreach ($attrs as $attr){
                $attrRes[]=ItemAttribute::create([
                    "item_id"=>$id,
                    "name"=>$attr->name??"",
                    "desc"=>$attr->desc??"",
                ]);
            }
            $item->attrs = $attrRes;
        }
        if(!empty($variants)){
            $variants = json_decode($variants);
            $numV = 1;
            $arrV = [];
            foreach ($variants as $variant){
                if(count($variant->values)>0){
                    $numV *= count($variant->values);
                    $arrV[]= $variant->values;
                }
            }
            $res = [];
            foreach ($arrV[0] as $v0){
                if(!empty($arrV[1])){
                    foreach ($arrV[1] as $v1){
                        if(!empty($arrV[2])){
                            foreach ($arrV[2] as $v2){
                                if(!empty($arrV[3])){
                                    foreach ($arrV[3] as $v3){
                                        $res[]=[$v0,$v1,$v2,$v3];
                                    }
                                }
                                else{
                                    $res[]=[$v0,$v1,$v2];
                                }
                            }
                        }
                        else{
                            $res[]=[$v0,$v1];
                        }
                    }
                }
                else{
                    $res[]=[$v0];
                }
            }
            $variants = [];
            $skus = [];
            $prices = [];
            foreach ($res as $variant){
                $skus[] = $sku = ItemSku::create([
                    'item_id'=>$id,
                    'sku'       =>self::generateSku(),
                    'status'    =>ENABLE
                ]);
                foreach ($variant as $v){
                    $variants[]= ItemVariant::create([
                        'item_id'        =>$id,
                        'item_sku_id'    =>$sku->id,
                        'variant_value_id'  =>$v,
                        'status'            =>ENABLE
                    ]);
                }
                $prices[]=ItemPrice::create([
                    'item_id'        =>$id,
                    'item_sku_id'    =>$sku->id,
                    'status'            =>ENABLE
                ]);
                /*$inventories[]=Inventory::create([
                    'item_id'        =>$itemId,
                    'item_sku_id'    =>$sku->id,
                    'status'            =>ENABLE
                ]);*/
            }
            $item->skus = $skus;
            $item->variants = $variants;
            $item->prices = $prices;
        }
        $files = $params->files;
        self::uploadItemImage($id, $files);
        return $item;

        $result['success']=true;
        $result['message'] = 'Item was updated successfully';
        $item = json_decode(json_encode($item),true);
        $result = array_merge($result,$item);
        return $result;
    }
    public function delete($id){
        $result = ["success"=>false,'message'=>''];
        $item = $this->model->find($id);
        if(empty($item)){
            $result['message'] = "Item not found";
            return $result;
        }
        ItemDesc::where('item_id',$id)->delete();
        ItemAttribute::where('item_id',$id)->delete();
        ItemSku::where('item_id',$id)->delete();
        ItemVariant::where('item_id',$id)->delete();
        ItemPrice::where('item_id',$id)->delete();
        $item->delete();
        $result['success']=true;
        $result['message']='Item deleted successfully';
        return $result;
    }
    public function generateSku(){
        $date = $this->model->generateCode("ITM");
        return $date;
    }
    public function uploadItemImage($id,$files,$imgDel=[]){
        $urlFiles = [];
        if($files){
            foreach ($files as $k=>$v){
                if(!empty($v['file'])){
                    $url = Helpers::uploadImage($v['file'],PATH_IMAGE_ITEM,$id.'_'.$k.'_');
                    if($url['status']){
                        if(!empty($v['id']))
                            ItemImage::where('item_id',$id)->where('id',$v['id'])->delete();
                        $urlFiles[]=[
                            'item_id'=>$id,
                            'item_sku_id'=>$v['item_sku_id'],
                            'url'=>$url['url'],
                            'status'=>ENABLE,
                            'priority'=>$v['priority']??0
                        ];
                    }
                }
            }
        }
        if(!empty($imgDel)){
//            $imgDel = explode(',',$imgDel);
            foreach ($imgDel as $v){
                $image = ItemImage::where('item_id',$id)->where('id',$v)->first();
                try{
                    if($image){
                        ItemImage::where('item_id',$id)->where('id',$v)->delete();
                        unlink('../public'.$image['url']);
                    }
                }
                catch (\Exception $e){}
            }
        }
        if(count($urlFiles)>0){
            ItemImage::insert($urlFiles);
        }
    }
}
