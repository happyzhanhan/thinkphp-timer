<?php
namespace app\cgi\controller;

use app\index\model\Dbconllection;
use app\index\model\permissionconllection;
use think\Controller;
use think\Db;
use think\Request;

class index extends Controller
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
        $data= array(
            "success" =>true,
           );
            return truedata(0);
    }
    public function loginout(){
        $dbadd = new Dbconllection;
        session('user',null);
        return $dbadd->returnSuss('退出成功！',null,null);
    }
    public function login2()
    {
        return $this->fetch();

    }
    public function register()
    {
        return $this->fetch();

    }
    //注册新增用户
    public function adduser(Request $request )
    {
        $dbadd = new Dbconllection;
        header('Content-Type:application/json; charset=utf-8');
        header("Access-Control-Allow-Origin:*");
        header("Access-Control-Allow-Methods:POST");
        //dump(json_encode($request->param()));
        $adddata = $request->param();

        if(!isset($adddata['name']) || empty($adddata['name'])){
            return $dbadd->returnError('用户名不能为空',null,$request->param());
        }else if(!isset($adddata['password']) || empty($adddata['password'])){
            return $dbadd->returnError('密码不能为空',null,$request->param());
        }else if(!isset($adddata['repassword']) || empty($adddata['repassword'])){
            return $dbadd->returnError('重复密码不能为空',null,$request->param());
        }else if($adddata['repassword'] != $adddata['password']){
            return $dbadd->returnError('两次密码不同',null,$request->param());
        }else{
            $userinfo =Db::table('my_account')->where(['name'=>$adddata['name']])->select();
            if(count($userinfo)==0){
                $data= array(
                    "name" =>$adddata['name'],
                    "password"=>md5($adddata['password']),
                    'registertime'=>date('Y-m-d H:i:s', time()));
                $res = $dbadd->add("my_account",$data);

                if($res){
                    return $dbadd->returnSuss('操作成功！',$res,$request->param());
                }
            }else{
                return $dbadd->returnError('用户名已存在',null,$request->param());
            }


        };
    }
    //登录用户
    public function loginUser(Request $request){
        $dbadd = new Dbconllection;
        header('Content-Type:application/json; charset=utf-8');
        header("Access-Control-Allow-Origin:*");
        header("Access-Control-Allow-Methods:POST");
        $adddata = $request->param();
        if(!isset($adddata['name']) || empty($adddata['name'])){
            return $dbadd->returnError('用户名不能为空',null,$request->param());
        }else if(!isset($adddata['password']) || empty($adddata['password'])){
            return $dbadd->returnError('密码不能为空',null,$request->param());
        }else{
            $User = Db::table('my_account');
            $userinfo = $User->where(['name'=>$adddata['name']])->select(); //使用数组作为查询条件
            if(0==count($userinfo)){
                return $dbadd->returnError('用户名不存在',null,$request->param());
            }else if(md5($adddata['password'])!=$userinfo[0]['password']){
                return $dbadd->returnError('密码错误',null,$request->param());
            }else{
                $res = $adddata['name'];
                $data= array(
                    'logintime'=>date('Y-m-d H:i:s', time()));
                $res = $dbadd->up("my_account",$res,$data);
                session('user',$value=['name'=>$adddata['name'],'password'=>$adddata['password'],'id'=>$userinfo[0]['id']]);
                return $dbadd->returnSuss('登录成功',$res,$request->param());
            }
        }
    }
    //新增项目
    public function addProject(Request $request){
        $dbadd = new Dbconllection;
        /*header('Content-Type:application/json; charset=utf-8');
        header("Access-Control-Allow-Origin:*");
        header("Access-Control-Allow-Methods:POST");*/
        $adddata = $request->param();
        //dump($adddata);
        if(!isset($adddata['title']) || empty($adddata['title'])){
            return $dbadd->returnError('项目名称不能为空',null,$request->param());
        }else{
            $user = session('user');
            $data= array(
                "title" =>$adddata['title'],
                'createTime'=>date('Y-m-d H:i:s', time()),
                "userId" => $user['id'],
                );
           // dump($data);
            $res = $dbadd->addtable("my_project",$data);
           // dump($res);
            if($res){
                return $dbadd->returnSuss('操作成功！',$res,$request->param());
            }
        }
    }

    //新增任务
    public function addTask(Request $request){
        $dbadd = new Dbconllection;
        header('Content-Type:application/json; charset=utf-8');
        header("Access-Control-Allow-Origin:*");
        header("Access-Control-Allow-Methods:POST");
        $adddata = $request->param();

        if(!isset($adddata['taskName']) || empty($adddata['taskName'])){
            return $dbadd->returnError('任务名称不能为空',null,$request->param());
        }else{
            $user = session('user');
            $isThisUser = $this->isUser($adddata['projectId'],$user['id'],true);
           // dump($isThisUser);

            if($isThisUser){
                $data= array(
                    "projectId" =>$adddata['projectId'],
                    "taskName" =>$adddata['taskName'],
                    'creatTime'=>date('Y-m-d H:i:s', time()),
                );
                $res = $dbadd->add("my_tasklist",$data);

                if($res){
                    return $dbadd->returnSuss('操作成功！',$res,$request->param());
                }

            }else{
                return $dbadd->returnError('项目不存在',null,$request->param());
            }

        }
    }
    //任务的项目ID是否正确
    public function isUser($projectId,$userId,$add){

        $projecttable= Db::table('my_project');
        $info = $projecttable->where(['id'=>$projectId])->select(); //使用数组作为查询条件


        if(0==count($info)) {
            return false;
        }else if($info[0]['userId']==$userId){
            //同步一下项目中任务数量
            $tasktable =  Db::table('my_tasklist');
            $num = $tasktable->where(['projectId'=>$projectId,'flag'=>0])->select();

            if($add){
                $taskNum = count($num)+1;
            }else{
                $taskNum = count($num)-1;
            }
            //dump($taskNum);
            $data= array(
                "taskNum" => $taskNum,
            );
            $res =  Db::table('my_project')->where(['id'=>$projectId])->update($data);

            return true;

        }else{
            return false;
        }




    }
    //新增任务内容（编辑）
    public function addTaskcontent(Request $request){
        $dbadd = new Dbconllection;
        header('Content-Type:application/json; charset=utf-8');
        header("Access-Control-Allow-Origin:*");
        header("Access-Control-Allow-Methods:POST");
        $adddata = $request->param();

        $user = session('user');
        $taskdata = $dbadd->selecttable('my_tasklist',array("id"=>$adddata['id']) );
       // dump($taskdata);
        $isThisUser = $this->isUser($taskdata[0]['projectId'],$user['id'],true);

        if($isThisUser){
            $data= array(
                "taskName" => $adddata['taskName'],
                "taskContent" =>$adddata['taskContent'],
                'updateTime'=>date('Y-m-d H:i:s', time()),
            );
            $res = $dbadd->uptable("my_tasklist",array("id"=>$adddata['id']),$data);


            if($res){
                return $dbadd->returnSuss('操作成功！',$res,$request->param());
            }
        }else{
            return $dbadd->returnError('项目不存在',null,$request->param());
        }

    }

    //删除任务
    public function deleteTask(Request $request){
        $dbadd = new Dbconllection;
        header('Content-Type:application/json; charset=utf-8');
        header("Access-Control-Allow-Origin:*");
        header("Access-Control-Allow-Methods:POST");
        $adddata = $request->param();

        $user = session('user');
        $taskdata = $dbadd->selecttable('my_tasklist',array("id"=>$adddata['id']) );
        $isThisUser = $this->isUser($taskdata[0]['projectId'],$user['id'],false);

        if($isThisUser){
            $data= array(
                "flag" => 1,
                'updateTime'=>date('Y-m-d H:i:s', time()),
            );
            $res = $dbadd->uptable("my_tasklist",array("id"=>$adddata['id']),$data);

            if($res){
                return $dbadd->returnSuss('操作成功！',$res,$request->param());
            }
        }else{
            return $dbadd->returnError('项目不存在',null,$request->param());
        }

    }

    //任务状态改变
    public function statusTask(Request $request){
        $dbadd = new Dbconllection;
        header('Content-Type:application/json; charset=utf-8');
        header("Access-Control-Allow-Origin:*");
        header("Access-Control-Allow-Methods:POST");
        $adddata = $request->param();

        $data= array(
            "status" => $adddata['status'],
            'updateTime'=>date('Y-m-d H:i:s', time()),
        );
        $res = $dbadd->uptable("my_tasklist",array("id"=>$adddata['id']),$data);

        $taskdata = $dbadd->selecttable('my_tasklist',array("id"=>$adddata['id']) );
        $tasklist = $dbadd->selecttable('my_tasklist',array('projectId'=>$taskdata[0]['projectId'],"flag"=>0));
        sleep(1);

        $list = 0;
        $inArray = array();
        for($list; $list<count($tasklist);$list++){
            $inArray[$list] = $tasklist[$list]['status'];
        }
        /*dump($inArray);
        dump(in_array(0,$inArray));*/
        if(in_array(0,$inArray)){
            $dbadd->uptable("my_project",array('id'=>$taskdata[0]['projectId']),array('status'=>0));
        }else{
            $dbadd->uptable("my_project",array('id'=>$taskdata[0]['projectId']),array('status'=>1));
        }
       //  $projectdata = $dbadd->selecttable('my_project',array('id'=>$taskdata[0]['projectId']));
       // dump($projectdata[0]);

        if($res){
            return $dbadd->returnSuss('操作成功！',$res,$request->param());
        }else{
            return $dbadd->returnError('状态改变失败！',null,$request->param());
        }

    }



    //查询项目列表
    public function queryproject(Request $request){
        $dbadd = new Dbconllection;
        header('Content-Type:application/json; charset=utf-8');
        header("Access-Control-Allow-Origin:*");
        header("Access-Control-Allow-Methods:GET");
        $adddata = $request->param();
        $user = session('user');
        $taskdata = $dbadd->selecttable('my_project',array("userId"=>$user['id'],"status"=>$adddata['status'],"flag"=>0) );
        return $dbadd->returnSuss('请求成功！',$taskdata,null);

    }

    //查询任务列表
    public function querytasklist(Request $request){
        $dbadd = new Dbconllection;
        header('Content-Type:application/json; charset=utf-8');
        header("Access-Control-Allow-Origin:*");
        header("Access-Control-Allow-Methods:POST");
        $adddata = $request->param();
        $taskdata = $dbadd->selecttable('my_tasklist',array('projectId'=>$adddata['projectId'],"flag"=>0) );
        return $dbadd->returnSuss('请求成功！',$taskdata,$request->param());


    }

    public function gettime(){
        dump(date('Y-m-d G:i:s', time()));
    }
    public function dbtest(Request $request )
    {
        header('Content-Type:text/html; charset=utf-8');
        header("Access-Control-Allow-Origin:*");
        header("Access-Control-Allow-Methods:POST");
        dump(json_encode($request->param()));

        $data=Db::table('my_account')->select();
         var_dump(json_encode($data));
       // return  json(['status'=>200, 'msg'=>'成功','data'=>$data,'require'=>$request->param()]);
    }

}
