<?php
namespace Index\Controller;
use Think\Controller;
//自动登录方法
class CommonController extends Controller {
    public function __construct(){
        parent::__construct();
        //处理自动登录
        //判断是否勾选自动登录与是否登录过
        if(isset($_COOKIE['auto']) && !isset($_SESSION['uid'])){

            $value=explode('|',enctyption($_COOKIE['auto'],1));
            $ip=get_client_ip();
            //判断与上次登录主机是否一致
            if($ip==$value['0'] ){
                $account=$value['1'];
                $user=M('user')->where(array('account'=>$account))->field(array('id','lock'))->find();
               //判断用户是否为锁定状态
                if($user && !$user['lock']){
                    session('uid',$user['id']);
                }
            }
        }
        //判断用户是否登录
        if(!isset($_SESSION['uid'])) {
            $this->redirect('Login/index');
        }
    }


     //添加分组
    public function addGroup(){
        if(!IS_AJAX) E('页面不存在');
        $data=array(
            'name'=>I('name'),
            'uid'=>$_SESSION['uid']
        );

       if($id=M('group')->add($data)){
           echo json_encode(array('status'=>1,'msg'=>'添加成功','gid'=>$id));
       }else{
           echo json_encode(array('status'=>0,'msg'=>'添加失败'));
       }

    }

      //添加关注
    public function addFollow(){
        if(!IS_AJAX) E('页面不存在');
        $data=array(
            'follow'=>I('follow','','int'),
            'fans'=> (int) $_SESSION['uid'],
            'gid'=>I('gid','','int')
        );

        if(M('follow')->add($data)){
            $db=M('userinfo');
            $db->where(array('uid'=>$data['follow']))->setInc('fans');
            $db->where(array('uid'=>$data['fans']))->setInc('follow');
            echo json_encode(array('status'=>1,'msg'=>'添加成功'));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'添加失败'));
        }

    }

    //删除关注
    public function delFollow(){
        if(!IS_AJAX) E('页面不存在');
        $uid=I('uid','','int');
        $type=I('type','','int');
        $suid=$_SESSION['uid'];
        $db=M('follow');
        $where=$type? array('follow'=>$uid,'fans'=>$suid) :array('follow'=>$suid,'fans'=>$uid);
        if($db->where($where)->delete()){
           $db= M('userinfo');
           if($type){
               $db->where(array('uid'=>$suid))->setDec('follow');
               $db->where(array('uid'=>$uid))->setDec('fans');
           }else{
               $db->where(array('uid'=>$suid))->setDec('fans');
               $db->where(array('uid'=>$uid))->setDec('follow');
           }
            echo 1;

        }else{
            echo 0;
        }

    }

    /**
     * 异步修改模版风格
     */
    Public function editStyle () {
        if (!IS_AJAX) {
            E('页面不存在');
        }

        $style = I('style');
        $where = array('uid' => session('uid'));

        if (M('userinfo')->where($where)->save(array('style' => $style))) {
            echo 1;
        } else {
            echo 0;
        }
    }
        //异步轮询消息推送
    Public function getMsg () {
        if (!IS_AJAX) E('页面不存在');
        $uid = session('uid');
        $msg = S('usermsg' . $uid);
        if ($msg) {
            if ($msg['comment']['status']) {
                S('usermsg' . $uid, $msg, 0);
                echo $data=json_encode(array(
                    'status' => 1,
                    'total' => $msg['comment']['total'],
                    'type' => 1 ));
                exit();
            }
            if ($msg['letter']['status']) {
                S('usermsg' . $uid, $msg, 0);
                echo json_encode(array(
                    'status' => 1,
                    'total' => $msg['letter']['total'],
                    'type' => 2 ));
                exit();
            }
            if ($msg['atme']['status']) {
                S('usermsg' . $uid, $msg, 0);
                echo json_encode(array(
                    'status' => 1,
                    'total' => $msg['atme']['total'],
                    'type' => 3 ));
                exit();
            }
        }
        echo json_encode(array('status' => 0));
    }






}