<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

use PHPMailer\PHPMailer;


//发送邮件
 function sendEmail($email, $content, $title){
     $mail = new PHPMailer();

     $mail->isSMTP();
     $mail->CharSet = "UTF-8";
     $mail->addAddress($email,'HAPPY');
     $mail->Body = $content;
     $mail->From = "happy2020mail@163.com";
     $mail->FromName = 'HAPPY';
     $mail->Host = "smtp.163.com";
     $mail->SMTPAuth = true;
     $mail->Subject = $title;
     $mail->Username = "happy2020mail@163.com";
     $mail->Password = "BYWRLYEAFESTRYNS";
     $mail->addCC("happy2020mail@163.com");
     $mail->addBCC("happy2020mail@163.com");
     $mail->IsHTML(TRUE);
     $result = $mail -> send();
     if($result){
         return true;
     }
     else{ return $mail -> ErrorInfo; }
 }

 //上传图片
function addimg($pic,$url){

    // 获取表单上传文件 例如上传了001.jpg
    // $file = request()->file($filename);
    // 移动到框架应用根目录/public/uploads/ 目录下
    // $file = request()->file($pic);
    if (is_array($pic)) {

        foreach($pic as $file){
            // 'img' . DS . 'goodsimg'
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->validate(['size'=> 1024 * 1000 ,'ext'=>'jpeg,jpg,png,gif'])->move(ROOT_PATH . 'public'. DS .$url);
            $data[] = str_replace('\\', '/',$url . '/' . $info->getSaveName());
        }

        return implode(',',$data);

    } elseif (is_object($pic)) {

        $info = $pic->validate(['size'=> 1024 * 1000 ,'ext'=>'jpeg,jpg,png,gif'])->move(ROOT_PATH . 'public'. DS . $url);
        if($info){
            //返回文件位置信息 如：20180620/42a79759f284b767dfcb2a0197904287.jpg
            return  str_replace('\\', '/',$url . '/' . $info->getSaveName());

        }else{
            // 上传失败获取错误信息
            return false;
            // return $file->getError();
        }
        //单文件上传
    }
}


/*
 * 返回统一格式
 */
function jsonData($code = 1, $msg = '', $data = [])
{
    //code 0代表错误，1代表成功
    $codeV = ['error','success'];
    return json_encode(['code' => $codeV[$code],'msg'=>$msg,'data'=>$data],JSON_UNESCAPED_UNICODE);
}
function truedata( $code = 1,$data = [])
{
    //code 0代表成功 1代表失败
    $success =[true,false];
    return json_encode(['success' => $success[$code],'lang'=>'zh-cn'],JSON_UNESCAPED_UNICODE);
}
function truedata2( $code = 1,$data = [])
{
    //code 0代表成功 1代表失败
    $success =[true,false];
    return json_encode(['success' => $success[$code],'lang'=>'zh-cn'],JSON_UNESCAPED_UNICODE);
}
function truedata3( $code = 1,$data = [])
{
    //code 0代表成功 1代表失败
    $success =[true,false];
    return json_encode(['success' => $success[$code],'name'=>'','logourl'=>''],JSON_UNESCAPED_UNICODE);
}
/*
 * 获取用户IP
 * */
function get_client_ip() {
    $ip = $_SERVER['REMOTE_ADDR'];
    if (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
        foreach ($matches[0] AS $xip) {
            if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                $ip = $xip;
                break;
            }
        }
    }
    return $ip;
}

