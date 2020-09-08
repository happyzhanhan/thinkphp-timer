<?php
/**
 * Created by PhpStorm.
 * User: 16597
 * Date: 2020/3/17
 * Time: 19:51
 */

namespace app\index\controller;

use PHPMailer\PHPMailer;

class Mail
{
    public function mail()
    {
        dump('mail');

    }
    public function email()
    {
        $mail = new PHPMailer();

        $mail->isSMTP();
        $mail->CharSet = "UTF-8";
        $mail->addAddress('2792891492@qq.com','HAPPY');
        $mail->Body = "给您发送新的验证码:abc123";
        $mail->From = "happy2020mail@163.com";
        $mail->FromName = 'HAPPY NEWS';
        $mail->Host = "smtp.163.com";
        $mail->SMTPAuth = true;
        $mail->Subject = "请查看";
        $mail->Username = "happy2020mail@163.com";
        $mail->Password = "BYWRLYEAFESTRYNS";
        $mail->addCC("happy2020mail@163.com");
        $mail->addBCC("happy2020mail@163.com");
        $mail->IsHTML(TRUE);
        $mail->SMTPSecure = "ssl";
        $mail->Port = 465;
       // $mail->addReplyTo("happy2020mail@163.com","HAPPY222");
       // $mail->addCC("happy2020mail@163.com");
       // $mail->addBCC("happy2020mail@163.com");
        //$mail->addAttachment("bug0.jpg");// 添加附件


       // $mail->AltBody = "I heard the echo, from the valleys and the heart";

       // dump($mail);

        if(!$mail->send()){// 发送邮件
            echo "Message could not be sent.";
            echo "Mailer Error: ".$mail->ErrorInfo;// 输出错误信息
        }else{
            echo '发送成功';
        }
    }
    public function mailNow($title='请注意查收',$message='新的方式是怎么样的呢？',$emailAddress='1659725767@qq.com')
    {
        $result = sendEmail($emailAddress,$message,$title);

        if($result){
            //发送成功的处理逻辑
            echo '发送成功';
        }else{
            //发送失败的处理逻辑
            echo '发送失败';
            dump($result);
        }
    }
    public function index()
    {
      dump('index');


    }

}
