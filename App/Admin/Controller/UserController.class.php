<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
class UserController extends CommonController {
    public function index(){
        $uid=M('userinfo')->select();
        $count = count($uid);
        $page       = new \Think\Page($count,8);
        $limit = $page->firstRow . ',' . $page->listRows;
        $this->page=$page->show();
        $this->users = D('UserView')->getAll($limit);
        $this->display();
    }

    //锁定或解除用户
   public function lockUser(){
       $data=array(
           'id'=>I('id'),
           'lock'=>I('lock')
       );
        $msg=I('lock')?'锁定':'解除';
        if(M('user')->save($data)){
            $this->success($msg.'成功',U('index'));
        }
   }
              //检索用户
    public function sechUser(){
       $sech=I('sech'); $type=I('type');
       if(isset($_GET['sech']) && isset($_GET['type'])){

           $where=$type? array('id'=>$sech): array('username'=>array('LIKE','%'.$sech.'%'));
           $uid=M('userinfo')->where($where)->select();
           $count = count($uid);
           $page       = new \Think\Page($count,8);
           $limit = $page->firstRow . ',' . $page->listRows;
           $this->page=$page->show();
           $this->user = D('UserView')->limit($limit)->where($where)->select();
       }


       $this->display();
    }

    public function admin(){
       $this->admin= M('admin')->select();

        $this->display();
    }

    public function lockAdmin(){
        $data=array(
            'id'=>I('id'),
            'lock'=>I('lock')
        );
        $msg=I('lock')?'锁定':'解除';
        if(M('admin')->save($data)){
            $this->success($msg.'成功',U('admin'));
        }
    }

    public function delAdmin(){
            $id=I('id');
        if(M('admin')->delete($id)){
            $this->success('删除成功',U('admin'));
        }
    }

    public function addAdmin(){
        $this->display();
    }

    public function runAddAdmin(){
        $password=I('pwd');
        $pwded=I('pwded');
        if($password!==$pwded){
            $this->error('两次密码输入不一致');
        }
        $data=array(
            'username'=>I('username'),
            'password'=>md5($pwded),
            'admin'=>I('admin'),
        );

        if(M('admin')->add($data)){
            $this->success('添加成功',U('admin'));
        }
    }


    public function editPwd(){
        $this->display();
    }
    public function runEditPwd(){
        $old=md5(I('old'));
        $pwd=I('pwd');
        $pwded=I('pwded');
        if($old!=M('admin')->where(array('id'=>session('uid')))->getField('password')){
            $this->error('旧密码输入错误');
        }
        if($pwd!==$pwded){
            $this->error('两次密码输入不一致');
        }
        $data=array(
            'id'=>session('uid'),
            'password'=>md5($pwded)
        );

        if(M('admin')->save($data)){
            $this->success('修改成功',U('Index/copy'));
        }

    }


}