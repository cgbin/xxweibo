<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
class IndexController extends CommonController {
    public function index(){

        $this->display();
    }

    public function copy(){
        $db = M('user');
        $this->user = $db->count();
        $this->lock = $db->where(array('lock' => 1))->count();

        $db = M('weibo');
        $this->weibo = $db->where(array('isturn' => 0))->count();
        $this->turn = $db->where(array('isturn' => array('GT', 0)))->count();
        $this->comment = M('comment')->count();

        $this->display();
    }

    /**
     * 退出登录
     */
    Public function loginOut () {
        session_unset();
        session_destroy();
        redirect(U('Login/index'));
    }
}