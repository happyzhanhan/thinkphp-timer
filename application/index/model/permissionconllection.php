<?php
namespace app\index\model;

use think\Model;
use think\Db;

class permissionconllection extends Model{

    public function isPermission($data){

        $adddata = $data;
        if(!isset($adddata['name']) || empty($adddata['name'])){
            return false;
        }else if(!isset($adddata['password']) || empty($adddata['password'])){
            return false;
        }else{
            $User = Db::table('my_account');
            $userinfo = $User->where(['name'=>$adddata['name']])->select(); //使用数组作为查询条件
            if(0==count($userinfo)){
                return false;
            }else if(md5($adddata['password'])!=$userinfo[0]['password']){
                return false;
            }else{
                return true;
            }
        }
    }
}