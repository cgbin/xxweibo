<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
class WeiboController extends CommonController {
    public function index(){
        //原创微博
        $where=array('isturn'=>0);
        $wid=M('weibo')->where($where)->select();
        $count = count($wid);
        $page       = new \Think\Page($count,20);
        $limit = $page->firstRow . ',' . $page->listRows;
        $this->page=$page->show();
        $this->weibo = D('WeiboView')->getAll($where,$limit);
        $this->display();
    }
         //利用关联模型删除微博
    public function delWeibo(){
        $uid=I('uid');
        $wid=I('id');
        if(D('WeiboRelation')->relation(true)->delete($wid)){
            M('userinfo')->where(array('uid' => $uid))->setDec('weibo');
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }

    }
    //转发的微博
    public function turn(){
        $where=array('isturn'=>array('NEQ',0));
        $wid=M('weibo')->where($where)->select();
        $count = count($wid);
        $page       = new \Think\Page($count,20);
        $limit = $page->firstRow . ',' . $page->listRows;
        $this->page=$page->show();
        $this->turn = D('WeiboView')->getAll($where,$limit);
        $this->display();
    }

    //检索微博
    public function sechWeibo(){
        if(!empty($_GET['sech'])){
            $sech=I('sech');
            $where=array('content'=>array('LIKE','%'.$sech.'%'));
            $wid=M('weibo')->where($where)->select();
            $count = count($wid);
            $page       = new \Think\Page($count,20);
            $limit = $page->firstRow . ',' . $page->listRows;
            $this->page=$page->show();
            $weibo = D('WeiboView')->getAll($where,$limit);
             $this->weibo=$weibo?$weibo:false;
        }
        $this->display();
    }

    //评论列表
    public function comment(){
        $cid=M('comment')->select();
        $count = count($cid);
        $page       = new \Think\Page($count,20);
        $limit = $page->firstRow . ',' . $page->listRows;
        $comment=M('comment')->limit($limit)->select();
        foreach ($comment as $k=>$v) {
            $comment[$k]['username']=M('userinfo')->where(array('uid'=>$v['uid']))->getField('username');
        }
        $this->page=$page->show();
        $this->comment=$comment;
        $this->display();
    }

    //删除评论
    public function delComment(){
        $cid=I('id');
        $wid=I('wid');
        if(M('comment')->delete($cid)){
            M('weibo')->where(array('id' => $wid))->setDec('comment');
            $this->success('删除成功');
        }
    }

    //评论检索
    public function sechComment(){
        if(!empty($_GET['sech'])){
            $sech=I('sech');
            $where=array('content'=>array('LIKE','%'.$sech.'%'));
            $cid=M('comment')->where($where)->select();
            $count = count($cid);
            $page       = new \Think\Page($count,20);
            $limit = $page->firstRow . ',' . $page->listRows;
            $this->page=$page->show();
            $comment = M('comment')->limit($limit)->select();
            foreach ($comment as $k=>$v) {
                $comment[$k]['username']=M('userinfo')->where(array('uid'=>$v['uid']))->getField('username');
            }
            $this->comment=$comment?$comment:false;
        }
        $this->display();
    }

}