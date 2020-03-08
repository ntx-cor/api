<?php


namespace App\Repositories;


use App\Libraries\Helpers;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductDesc;
use App\Models\ProductImage;
use App\Models\ProductPrice;
use App\Models\ProductSKU;
use App\Models\ProductVariant;

class ProductRepository extends BaseRepository
{
    public function model()
    {
        return Product::class;
    }
    public function getList($params){
        $limit = $params->get('limit');
        $query = $this->model->select('*');
        $res = $query->paginate($limit);
        return $res;
    }
    public function detail($id){
        $product = $this->model->find($id);
        if(empty($product)){
            return null;
        }
        return $product;
    }
    public function create($params)
    {
        $desc = $params->get('desc');
        $variants = $params->get('variants');
        $attrs = $params->get('attrs');
        $dataProduct = [
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
        $product = $this->model->create($dataProduct);
        $productId = $product->id;
        $product->desc = [];
        $product->attrs = [];
        $product->skus = [];
        $product->variants = [];
        $product->prices = [];
        if(!empty($desc)){
            $desc = json_decode($desc);
            $dataDesc = [
                'short_desc'=>$desc->short_desc??null,
                'long_desc'=>$desc->long_desc??null,
                'product_id'=>$productId
            ];
            $product->desc = ProductDesc::create($dataDesc);
        }
        if(!empty($attrs)){
            $attrs = json_decode($attrs);
            $attrRes = [];
            foreach ($attrs as $attr){
                $attrRes[]=ProductAttribute::create([
                    "product_id"=>$productId,
                    "name"=>$attr->name??"",
                    "desc"=>$attr->desc??"",
                ]);
            }
            $product->attrs = $attrRes;
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
                $skus[] = $sku = ProductSKU::create([
                    'product_id'=>$productId,
                    'sku'       =>self::generateSku(),
                    'status'    =>ENABLE
                ]);
                foreach ($variant as $v){
                    $variants[]= ProductVariant::create([
                        'product_id'        =>$productId,
                        'product_sku_id'    =>$sku->id,
                        'variant_value_id'  =>$v,
                        'status'            =>ENABLE
                    ]);
                }
                $prices[]=ProductPrice::create([
                    'product_id'        =>$productId,
                    'product_sku_id'    =>$sku->id,
                    'status'            =>ENABLE
                ]);
                /*$inventories[]=Inventory::create([
                    'product_id'        =>$productId,
                    'product_sku_id'    =>$sku->id,
                    'status'            =>ENABLE
                ]);*/
            }
            $product->skus = $skus;
            $product->variants = $variants;
            $product->prices = $prices;
        }
        $files = $params->files;
        self::uploadProductImage($productId, $files);
        return $product;
    }
    public function update($id,$params){
        $result = ["success"=>false,'message'=>''];
        $product = $this->model->find($id);
        if(empty($product)){
            $result['message']="Product not found";
            return $result;
        }

        $desc = $params->get('desc');
        $variants = $params->get('variants');
        $attrs = $params->get('attrs');
        $dataProduct = [
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
        $product->update($dataProduct);
        $product->desc = [];
        $product->attrs = [];
        $product->skus = [];
        $product->variants = [];
        $product->prices = [];
        if(!empty($desc)){
            $desc = json_decode($desc);
            $dataDesc = [
                'short_desc'=>$desc->short_desc??null,
                'long_desc'=>$desc->long_desc??null,
                'product_id'=>$id
            ];
            $product->desc = ProductDesc::create($dataDesc);
        }
        if(!empty($attrs)){
            $attrs = json_decode($attrs);
            $attrRes = [];
            foreach ($attrs as $attr){
                $attrRes[]=ProductAttribute::create([
                    "product_id"=>$id,
                    "name"=>$attr->name??"",
                    "desc"=>$attr->desc??"",
                ]);
            }
            $product->attrs = $attrRes;
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
                $skus[] = $sku = ProductSKU::create([
                    'product_id'=>$id,
                    'sku'       =>self::generateSku(),
                    'status'    =>ENABLE
                ]);
                foreach ($variant as $v){
                    $variants[]= ProductVariant::create([
                        'product_id'        =>$id,
                        'product_sku_id'    =>$sku->id,
                        'variant_value_id'  =>$v,
                        'status'            =>ENABLE
                    ]);
                }
                $prices[]=ProductPrice::create([
                    'product_id'        =>$id,
                    'product_sku_id'    =>$sku->id,
                    'status'            =>ENABLE
                ]);
                /*$inventories[]=Inventory::create([
                    'product_id'        =>$productId,
                    'product_sku_id'    =>$sku->id,
                    'status'            =>ENABLE
                ]);*/
            }
            $product->skus = $skus;
            $product->variants = $variants;
            $product->prices = $prices;
        }
        $files = $params->files;
        self::uploadProductImage($id, $files);
        return $product;

        $result['success']=true;
        $result['message'] = 'Product was updated successfully';
        $item = json_decode(json_encode($product),true);
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
        ProductDesc::where('product_id',$id)->delete();
        ProductAttribute::where('product_id',$id)->delete();
        ProductSKU::where('product_id',$id)->delete();
        ProductVariant::where('product_id',$id)->delete();
        ProductPrice::where('product_id',$id)->delete();
        $item->delete();
        $result['success']=true;
        $result['message']='Item deleted successfully';
        return $result;
    }
    public function generateSku(){
        $date = date('YmdHis');
        return $date;
    }
    public function uploadProductImage($id,$files,$imgDel=[]){
        $urlFiles = [];
        if($files){
            foreach ($files as $k=>$v){
                if(!empty($v['file'])){
                    $url = Helpers::uploadImage($v['file'],PATH_IMAGE_ITEM,$id.'_'.$k.'_');
                    if($url['status']){
                        if(!empty($v['id']))
                            ProductImage::where('product_id',$id)->where('id',$v['id'])->delete();
                        $urlFiles[]=[
                            'product_id'=>$id,
                            'product_sku_id'=>$v['product_sku_id'],
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
                $image = ProductImage::where('product_id',$id)->where('id',$v)->first();
                try{
                    if($image){
                        ProductImage::where('product_id',$id)->where('id',$v)->delete();
                        unlink('../public'.$image['url']);
                    }
                }
                catch (\Exception $e){}
            }
        }
        if(count($urlFiles)>0){
            ProductImage::insert($urlFiles);
        }
    }
}
