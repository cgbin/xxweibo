<?php
namespace Index\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function index(){
        $this->display();

    }
    //登录处理
    public function loginhandle(){
        if(!IS_POST) E('页面不存在');
        $account=I('account');
        $pwd=md5(I('pwd'));
        $user=M('user')->where(array('account'=>$account))->find();
        if ($user && $user['password']==$pwd){
            if(!$user['lock']) {
                if(isset($_POST['auto'])){
                    $ip=get_client_ip();
                    $vaule=$ip.'|'.$account;
                    $data=enctyption($vaule);
                    setcookie('auto',$data,C('AUTO_LOGIN_TIME'),'/');
                }
                session('uid', $user['id']);
                $this->success('登录成功', U('Index/index'));
            }else{
                $this->error('该账户已被禁用');
            }

        }else{
            $this->error('账号或密码错误');
        }


    }

    public function register(){
        if(!C('REGIS_ON')){
            $this->error('网站暂不开放注册',U('index'));
        }
        $this->display();

    }

    //注册表单处理
    public function registerhandle(){
        if(!IS_POST) E('页面不存在');
        $verify=I('verify');
        $Verify= new \Think\Verify();//掉方法
        if(!$Verify->check($verify)){
            $this->error('验证码错误');
        }

        if(I('pwd')!=I('pwded')){
            $this->error('2次密码输入不一致');
        }
        $data=array(
            'account'=>I('account'),
            'password'=>md5(I('pwd')),
            'registime'=>$_SERVER['REQUEST_TIME'],
            'userinfo'=>array(
                'username'=>I('uname')
            )
        );

        if($id=D('User')->relation(true)->add($data)){
            session('uid',$id);
            $this->redirect('Index/index',array(),3,'注册成功,正在跳转。。。');
        }else{
            $this->error('注册失败');
        }

    }

    //异步验证用户是否存在
    public function checkAccount(){
        if(!IS_AJAX) E('页面不存在');
        $account=I('account');

        if(M('user')->where(array('account'=>$account))->getField('id')){
                      echo json_encode(false);
        }else{
                      echo json_encode(true);
        }
    }

    //异步验证用户昵称是否存在
    public function checkUname(){
        if(!IS_AJAX) E('页面不存在');
        $username=I('uname');

        if(M('userinfo')->where(array('username'=>$username))->getField('id')){
            echo json_encode(false);
        }else{
            echo json_encode(true);
        }
    }


    //获取验证码
    public  function verifyImg(){
        $config =    array(
            'fontSize'    =>    120,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
            'useNoise'    =>    true, // 关闭验证码杂点
        );
        $Verify =     new \Think\Verify($config);
        $Verify->entry();
    }
}