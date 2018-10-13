<?php
namespace Index\Controller;
use Index\Controller\CommonController;

Class UserController extends CommonController {

	/**
	 * 用户个人页视图
	 */
	Public function index () {
		$id = I('id','','intval');

		//读取用户个人信息
		$where = array('uid' => $id);
		$userinfo = M('userinfo')->where($where)->field('truename,face50,face80,style', true)->find();

		if (!$userinfo) {
			header('Content-Type:text/html;Charset=UTF-8');
			redirect(__ROOT__, 3, '用户不存在，正在为您跳转至首页...');
			exit();
		}

		$this->userinfo = $userinfo;

		//导入分页处理页


		//统计分页
		$where = array('uid' => $id);
		$count = M('weibo')->where($where)->count();
        $page       = new \Think\Page($count,5);
		$limit = $page->firstRow . ',' . $page->listRows;

		//读取用户发布的微博
		$this->page = $page->show();
		$this->weibo = D('WeiboView')->getAll($where, $limit);
		//我的关注
		if (S('follow_' . $id)) {
			//缓存已成功并且缓存未过期
			$follow = S('follow_' . $id);
		} else {
			//生成缓存
			$where = array('fans' => $id);
			$follow = M('follow')->where($where)->field('follow')->select();
			foreach ($follow as $k => $v) {
				$follow[$k] = $v['follow'];
			}

            if(empty($follow)){
                $where = array('uid' => 0);  //点击空分组
            }else{
                $where = array('uid' => array('IN', $follow));
            }
			$field = array('username', 'face50' => 'face', 'uid');
			$follow = M('userinfo')->field($field)->where($where)->select();

			S('follow_' . $id, $follow, 3600);
		}

		//我的粉丝
		if (S('fans_' . $id)) {
			//缓存已成功并且缓存未过期
			$fans = S('fans_' . $id);
		} else {
			//生成缓存
			$where = array('follow' => $id);
			$fans = M('follow')->where($where)->field('fans')->select();
			foreach ($fans as $k => $v) {
				$fans[$k] = $v['fans'];
			}
            if(empty($fans)){
                $where = array('uid' => 0);  //点击空分组
            }else{
                $where = array('uid' => array('IN', $fans));
            }

			$field = array('username', 'face50' => 'face', 'uid');
			$fans = M('userinfo')->field($field)->where($where)->select();

			S('fans_' . $id, $fans, 3600);
		}
		$this->follow = $follow;
		$this->fans = $fans;
		$this->display();
	}

	/**
	 * 用户关注与粉丝列表
	 */
	Public function followList () {
		$uid = I('uid','', 'intval');

		//区分关注 与 粉丝(1：关注，2：粉丝)
		$type = I('type','', 'intval');

		//导入分页类
		$db = M('follow');

		//根据type参数不同，读取用户关注与粉丝ID
		$where = $type ? array('fans' => $uid) : array('follow' => $uid);
		$field = $type ? 'follow' : 'fans';
		$count = $db->where($where)->count();
        $page       = new \Think\Page($count,5);
		$limit = $page->firstRow . ',' . $page->listRows;

		$uids = $db->field($field)->where($where)->limit($limit)->select();

		if ($uids) {
			//把用户关注或者粉丝ID重组为一维数组
			foreach ($uids as $k => $v) {
				$uids[$k] = $type ? $v['follow'] : $v['fans'];
			}
            //提取用户个人信息
            if(empty($uids)){
                $where = array('uid' => 0);  //点击空分组
            }else{
                $where = array('uid' => array('IN', $uids));
            }

			//提取用户个人信息
			$field = array('face50' => 'face', 'username', 'sex', 'location', 'follow', 'fans', 'weibo', 'uid');

			$users =M('userinfo')->where($where)->field($field)->select();

			//分配置用户信息到视图
			$this->users = $users;
		}

		$where = array('fans' => session('uid'));
		$follow = $db->field('follow')->where($where)->select();
		
		if ($follow) {
			foreach ($follow as $k => $v) {
				$follow[$k] = $v['follow'];
			}
		}

		$where = array('follow' => session('uid'));
		$fans = $db->field('fans')->where($where)->select();

		if ($fans) {
			foreach ($fans as $k => $v) {
				$fans[$k] = $v['fans'];
			}
		}

		$this->type = $type;
		$this->count = $count;
		$this->follow = $follow;
		$this->fans = $fans;
		$this->display();
	}

	/**
	 * 收藏列表
	 */
	Public function keep () {
		$uid  = session('uid');

		$count = M('keep')->where(array('uid' => $uid))->count();
        $page       = new \Think\Page($count,20);
		$limit = $page->firstRow . ',' . $page->listRows;

		$where = array('keep.uid' => $uid);
		$weibo = D('KeepView')->getAll($where, $limit);
		// p($weibo);die;
		$this->weibo = $weibo;
		$this->page = $page->show();
		$this->display('weiboList');
	}

	/**
	 * 异步取消收藏
	 */
	Public function cancelKeep () {
		if (!IS_AJAX) {
			E('页面不存在');
		}

		$kid = I('kid','', 'intval');
		$wid = I('wid','', 'intval');

		if (M('keep')->delete($kid)) {
			M('weibo')->where(array('id' => $wid))->setDec('keep');

			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * 私信列表
	 */
	Public function letter () {
		$uid = session('uid');

		set_msg($uid, 2, true);

		$count = M('letter')->where(array('uid' => $uid))->count();
        $page       = new \Think\Page($count,20);
		$limit = $page->firstRow . ',' . $page->listRows;

		$where = array('letter.uid' => $uid);
		$letter = D('LetterView')->where($where)->order('time DESC')->limit($limit)->select();

		$this->letter = $letter;
		$this->count = $count;
		$this->page = $page->show();
		$this->display();
	}

	/**
	 * 异步删除私信
	 */
	Public function delLetter () {
		if (!IS_AJAX) {
			E('页面不存在');
		}

		$lid = I('lid','', 'intval');

		if (M('letter')->delete($lid)) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * 私信发送表单处理
	 */
	Public function letterSend () {
		if (!IS_POST) {
			E('页面不存在');
		}
		$name = I('name');
		$where = array('username' => $name);
		$uid = M('userinfo')->where($where)->getField('uid');

		if (!$uid) {
			$this->error('用户不存在');
		}

		$data = array(
			'from' => session('uid'),
			'content' => I('content'),
			'time' => time(),
			'uid' => $uid
			);

		if (M('letter')->data($data)->add()) {

			set_msg($uid, 2);

			$this->success('私信已发送', U('letter'));
		} else {
			$this->error('发送失败请重试...');
		}
	}

	/**
	 * 评论展示列表
	 */
	Public function comment () {
	    set_msg(session('uid'), 1, true);

		$uid =session('uid');
		$wid=M('weibo')->where($uid)->field('id')->select();
		foreach ($wid as $k=>$v){
		    $wid[$k]=$v['id'];
        }

        if(empty($wid)){
            $where = array('wid'=>0,'uid'=>array('NEQ',$uid));  //点击空分组
        }else{
            $where = array('wid'=>array('IN',$wid),'uid'=>array('NEQ',$uid));
        }
		$count = M('comment')->where($where)->count();
        $page       = new \Think\Page($count,8);
		$limit = $page->firstRow . ',' . $page->listRows;
		
		$comment = D('CommentView')->where($where)->limit($limit)->order('time DESC')->select();
		$this->count = $count;
		$this->page = $page->show();
		$this->comment = $comment;
		$this->display();
	}

	/**
	 * 评论回复
	 */
	Public function reply () {
		if (!IS_AJAX) {
			E('页面不存在');
		}

		$data = array(
			'content' => I('content'),
			'time' => time(),
			'uid' => session('uid'),
			'wid' => I('wid','','intval')
			);
        //读取微博被评论用户的id
        $wuid=I('uid','','int');

		if (M('comment')->data($data)->add()) {
            set_msg($wuid, 1);
			M('weibo')->where(array('id' => $data['wid']))->setInc('comment');
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * 删除评论
	 */
	Public function delComment () {
		if (!IS_AJAX) {
			E('页面不存在');
		}
		$cid = I('cid','', 'intval');
		$wid = I('wid','', 'intval');

		if (M('comment')->delete($cid)) {
			M('weibo')->where(array('id' => $wid))->setDec('comment');
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * @提到我的
	 */
	Public function atme () {
		set_msg(session('uid'), 3, true);

		$where = array('uid' => session('uid'));
		$wid = M('atme')->where($where)->field('wid')->select();

		if ($wid) {
			foreach ($wid as $k => $v) {
				$wid[$k] = $v['wid'];
			}
		}

		$count = count($wid);
        $page       = new \Think\Page($count,8);
		$limit = $page->firstRow . ',' . $page->listRows;
           if(empty($wid)){
               $where = array('id' => 0);
           }else{
               $where = array('id' => array('IN', $wid));
           }
		$weibo = D('WeiboView')->getAll($where, $limit);

		$this->weibo = $weibo;
		$this->page = $page->show();
		$this->atme = 1;
		$this->display('weiboList');
	}

	/**
	 * 空操作
	 */
	Public function _empty ($name) {
		$this->_getUrl($name);
	}

	/**
	 * 处理用户名空操作，获得用户ID 跳转至用户个人页
	 */
	Private function _getUrl ($name) {
		$name = htmlspecialchars($name);
		$where = array('username' => $name);
		$uid = M('userinfo')->where($where)->getField('uid');

		if (!$uid) {
			redirect(U('Index/index'));
		} else {
			redirect(U('/' . $uid));
		}
	}
}
?>