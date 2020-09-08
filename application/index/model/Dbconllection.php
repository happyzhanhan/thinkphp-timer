<?php
namespace app\index\model;

use think\Model;
use think\Db;

class Dbconllection extends Model{
    //新增
    public function add($database,$data){
        $id = null;
        Db::startTrans();
        try{
            Db::table($database)->insert($data);
            $id = Db::table($database)->getlastInsID();
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }
        return $id;
    }
    //新增2
    public function addtable($database,$data){
        $id = null;
        Db::table($database)->insert($data);
        $id = Db::table($database)->getlastInsID();
        return $id;
    }
    //修改
    public function up($database,$name,$data){
        $id = null;
        Db::startTrans();
        try{
            Db::table($database)->where('name',$name)->update($data);
            $id = Db::table($database)->getlastInsID();
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }
        return $id;
    }
    //修改2
    public function uptable($database,$req,$data){
        return Db::table($database)->where($req)->update($data);
    }
    //查询
    public function selecttable($database,$data){
        $datatable = Db::table($database);
        $info = $datatable->where($data)->order('id desc')->select(); //使用数组作为查询条件
        return $info;
    }
    //假删除
    public function deletedata($database,$id){
        $datatable = Db::table($database);
        $info = $datatable->where('id',$id)->update('flag',1);
        return $info;
    }
    //返回成功
    public function returnSuss($msg,$res,$req){
        return  json(['status'=>200,'success'=>true, 'msg'=>$msg,'data'=>$res,'require'=>$req]);
    }
    //返回失败
    public function returnError($msg,$res,$req){
        return  json(['status'=>200,'success'=>false, 'msg'=>$msg,'data'=>$res,'require'=>$req]);
    }
}