<?php
/**
 * Created by PhpStorm.
 * User: 16597
 * Date: 2020/3/23
 * Time: 19:53
 */

namespace app\index\controller;

use app\index\model\Dbconllection;
use think\Request;
use think\Controller;


class Apppage extends Controller
{
    public function index()
    {
        return $this->fetch();

    }

    public function showlist()
    {
        return $this->fetch();

    }
    /**
     * [goods_addimg 图片上传]
     * @return [type] [description]
     */
    public function addimg(){
        if (request()->isPost()) {
            $post = request()->file();
            $str = '';
            $d = "";
            $i = 0;
            //自定义路径
            $url = 'img' . DS . 'resImages';
            $str .= $d.  addimg($post,$url);

            return jsonData(1,'图片上传成功',$str);

        }
    }
    /**
     * 新增一条数据
     */
    public function addonedata(Request $request){
        $dbadd = new Dbconllection;
        $adddata = $request->param();

        $user = session('user');
        $data= array(
            "res" =>$adddata['name'],
            "type" =>$adddata['type'],
            "img" =>implode(",",$adddata['img']),

            "remarks" =>$adddata['remarks'],
            "expireDate" =>$adddata['expireDate'],

            "createTime"=>date('Y-m-d H:i:s', time()),
            "createUser" => $user['id'],
            "delflag"=>0,
        );
        $res = $dbadd->addtable("my_expiretime",$data);
        if($res){
            return $dbadd->returnSuss('操作成功！',$res, $data);
        }
    }
    /**
     * 编辑
     */
    public function editonedata(Request $request){
        $dbadd = new Dbconllection;
        $adddata = $request->param();

        $user = session('user');
        $data= array(
            "res" =>$adddata['name'],
            "type" =>$adddata['type'],
            "img" =>implode(",",$adddata['img']),

            "remarks" =>$adddata['remarks'],
            "expireDate" =>$adddata['expireDate'],

            "createTime"=>date('Y-m-d H:i:s', time()),
            "createUser" => $user['id'],
            "delflag"=>0,
        );
        $res = $dbadd->uptable("my_expiretime",array("id"=>$adddata['id']),$data);
        if($res){
            return $dbadd->returnSuss('操作成功！',$res, $data);
        }
    }
    /**
     * 单条查询
     */
    public function selectOnedata(Request $request){
        $dbadd = new Dbconllection;
        header('Content-Type:application/json; charset=utf-8');
        header("Access-Control-Allow-Origin:*");
        header("Access-Control-Allow-Methods:GET");
        $adddata = $request->param();
        $user = session('user');
        $taskdata = $dbadd->selecttable('my_expiretime',array("id"=>$adddata['id'],"createUser"=>$user['id'],"delflag"=>0) );
        return $dbadd->returnSuss('请求成功！',$taskdata,null);
    }
    /**
     * 列表查询
     */
    public function querydata(Request $request){
        $dbadd = new Dbconllection;
        header('Content-Type:application/json; charset=utf-8');
        header("Access-Control-Allow-Origin:*");
        header("Access-Control-Allow-Methods:GET");
        $adddata = $request->param();
        $user = session('user');
        $taskdata = $dbadd->selecttable('my_expiretime',array("createUser"=>$user['id'],"delflag"=>0) );
        return $dbadd->returnSuss('请求成功！',$taskdata,null);
    }
    /**
     * 删除单条记录
     * */
    public function deleteTask(Request $request){
        $dbadd = new Dbconllection;
        header('Content-Type:application/json; charset=utf-8');
        header("Access-Control-Allow-Origin:*");
        header("Access-Control-Allow-Methods:POST");
        $adddata = $request->param();
        $user = session('user');

        $data= array(
            "delflag" => 1,
        );
        $res = $dbadd->uptable("my_expiretime",array("id"=>$adddata['id'],'createUser'=>$user['id']),$data);
        if($res){
            return $dbadd->returnSuss('操作成功！',$res,$request->param());
        }

    }



}
