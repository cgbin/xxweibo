<?php
namespace Index\Controller;
use Index\Controller\CommonController;
class SearchController extends CommonController {

    /**
     * 搜索微博
     */
    Public function sechWeibo () {
        $keyword = $this->_getKeyword();

        if ($keyword) {
            //检索含有关键字的微博
            $where = array('content' => array('LIKE', '%' . $keyword . '%'));

            $db = D('WeiboView');

            $count = M('weibo')->where($where)->count('id');
            $page       = new \Think\Page($count,6);
            $limit = $page->firstRow . ',' . $page->listRows;
            $weibo = $db->getAll($where, $limit);

            $this->weibo = $weibo ? $weibo : false;
            //页码
            $this->page = $page->show();
        }

        $this->keyword = $keyword;
        $this->display();
    }

    /**
     * 搜索人
     */
    public function sechUser(){
        $keyword=$this->_getkeyword();
        //设置模糊查询，并排除自己
        if ($keyword) {
            $where = array(
                'username' => array('LIKE', '%' . $keyword . '%'),
                'uid' => array('NEQ', $_SESSION['uid'])
            );
            $field = array('username', 'sex', 'location', 'constellation'
            , 'intro', 'face80', 'follow', 'fans', 'weibo', 'uid');

            $User = M('userinfo'); // 实例化对象
            $count = $User->where($where)->count();// 查询满足要求的总记录数
            $Page = new \Think\Page($count, 6);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $Page->setConfig('prev', '上一页');
            $Page->setConfig('next', '下一页');
            $show = $Page->show();// 分页显示输出
// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $result = $User->where($where)->field($field)->limit($Page->firstRow . ',' . $Page->listRows)->select();
            $result = $this->_getmualt($result);
            $this->result = $result ? $result : false;
            $this->assign('page', $show);// 赋值分页输出
            //重组结果集获取是否互相关注
        }
        $this->assign('keyword',$keyword);
        $this->display();

    }


    private function _getkeyword(){
        return $_GET['keyword']=='搜索微博、找人' ? null : $_GET['keyword'];
    }

    private function _getmualt($result){
        if(!$result) return false;
        $db=M('follow');
         foreach($result as $k=>$v) {
             $sql = '(select `follow` from hd_follow where `follow`=' . $v['uid'] . ' and `fans`='.$_SESSION['uid'].') union
             (select `follow` from hd_follow where `follow`=' .$_SESSION['uid']. ' and `fans`='. $v['uid'] .' )';

             $mualt=$db->query($sql);

             if(count($mualt)==2){
                 $result[$k]['mutual']=1;
                 $result[$k]['followed']=1;
         }else{
                 $result[$k]['mutual']=0;
                 //未互相关注检索是否已关注
                 $where=array('follow'=>$v['uid'],'fans'=>$_SESSION['uid']);
                 $result[$k]['followed']=$db->where($where)->count();
             }
         }

         return $result;




    }





}