<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Validator;

class Helper{
    public static function convertCombo($data){
        $res = [];
        foreach ($data as $k=>$v){
            $res[]=['value'=>$k,'text'=>$v];
        }
        return $res;
    }
    public static function uploadImage($image,$path,$pre='',$server=null){
        if(empty($server)){
            $server = LOCAL;
        }
        $result = [
            'status'=>false,
            'fileName'=>'',
            'id'=>''
        ];
        {
            $currentDate = date('YmdHis');
            $name = $pre.$currentDate.'.'.$image->getClientOriginalExtension();
            $id = '';
            switch ($server){
                //Upfile to GOOGLE
                case GOOGLE:
                    $result = GoogleAPI::uploadImage($image,$name);
                    $id = $result->getId();
                    break;
                //Up file to LOCAL
                default:
                    $destinationPath = public_path($path);
                    $image->move($destinationPath, $name);
                    break;
            }
            $result['status']=true;
            $result['url']= $path.$name;
            $result['id']=$id;
        }
        return $result;
    }
    public static function validate($params,$rule,$message){
        $validator = Validator::make($params,$rule, $message);
        if ($validator->fails()) {
            $result = $tmp = [];
            $errors = $validator->getMessageBag()->toArray();
            foreach ($errors as $field => $error) {
                $result[] = implode(',', $error);
//                $result[$field] = implode(',', $error);
            }
            return $result;
        }
        return null;
    }
}

function to_slug($str) {
    $str = trim(mb_strtolower($str));
    $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
    $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
    $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
    $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
    $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
    $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
    $str = preg_replace('/(đ)/', 'd', $str);
    $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
    $str = preg_replace('/([\s]+)/', '-', $str);
    return $str;
}
