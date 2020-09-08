<?php
/**
 * Created by PhpStorm.
 * User: 16597
 * Date: 2020/3/23
 * Time: 19:53
 */

namespace app\index\controller;

use think\Controller;


class Image extends Controller
{
    public function index(){

        $ip = get_client_ip();
        $this->assign('userIp',$ip);

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
            $url = 'img' . DS . 'goodsimg';
            foreach ($post as $key => $value) {
                $i++;
                if ($i!=1) {
                    $d = ",";
                }
                $str .= $d.  addimg($value,$url);
            }
            return jsonData(1,'图片上传成功',$str);
        }
    }
}
