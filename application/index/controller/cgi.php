<?php
namespace app\index\controller;

use app\index\model\permissionconllection;
use think\Controller;
use think\Request;

class cgi extends Controller
{
    public function index(Request $request)
    {
        $name = session('user');
        //dump($name);
        $authorition = new permissionconllection;
        $isPer = $authorition->isPermission($name);
        if($isPer){
            $this->assign('name',$name['name']);
            return $this->fetch();
        }else{
            // return $dbadd->returnError('权限不足，请重新登录',null,$request->param());
            $this->success('登录过期，权限不足，请重新登录', 'index.php/index/index/login');
        }

    }
    public function login(){

        return truedata(0);
    }
}
