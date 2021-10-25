<?php
namespace app\api\controller;

use app\index\model\Dbconllection;
use app\index\model\permissionconllection;
use think\Controller;
use think\Db;
use think\Request;

class index extends Controller
{
    public function index(Request $request)
    {
        dump(phpinfo());
    }
    public function gettime(){
        dump(date('Y-m-d G:i:s', time()));
    }

}
