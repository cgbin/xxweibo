<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function index(){
        $this->display();
    }
      //登录验证
    public function login(){
        if(!IS_POST) E('页面不存在');
        $username=I('uname');
        $pwd=I('pwd','');
        $verify=I('verify');
        $Verify= new \Think\Verify();//掉方法
        if(!$Verify->check($verify)){
            $this->error('验证码错误');
       }
        $user=M('admin')->where(array('username'=>$username))->find();

        if(!$user || $user['password']!=md5($pwd)  ){
            $this->error('账号或密码错误');
        }
        if($user['lock']){
            $this->error('账号已被锁定');
        }

        $data=array(
            'logintime'=>time(),
            'loginip'=>get_client_ip(),
        );

        if(M('admin')->where(array('id'=>$user['id']))->save($data)){
            session('uid',$user['id']);
            session('username',$user['username']);
            session('logintime',date('Y-m-d,H:i:s',$user['logintime']));
            session('now',date('Y-m-d,H:i',time()));
            session('loginip',$user['loginip']);
            session('admin',$user['admin']);
            $this->success('登录成功',U('Index/index'));
        }


    }

    public function verify(){
        $config =    array(
            'fontSize'    =>    130,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
            'useNoise'    =>    true, // 关闭验证码杂点
        );
        $Verify =     new \Think\Verify($config);
        $Verify->entry();
    }
}