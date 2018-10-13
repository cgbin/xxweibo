<?php
namespace Index\Controller;
use Index\Controller\CommonController;

class IndexController extends CommonController {
    public function index(){

        $db=D('WeiboView');
        $uid=array($_SESSION['uid']);
        $where=array('fans'=>$_SESSION['uid']);
        if(isset($_GET['gid'])){
            $where=array('gid'=>$_GET['gid']);
            $uid=array();
        }
        $result=M('follow')->field('follow')->where($where)->select();
        if($result){
            foreach ($result as $v){
                $uid[]=$v['follow'];
            }
        }
        //显示自身和关注好友发的微博
        if(empty($uid)){
            $where = array('uid' => 0);  //点击空分组
        }else{
            $where = array('uid' => array('IN', $uid));
        }
        $count = $db->where($where)->count();
        // 查询满足要求的总记录数
        $Page       = new \Think\Page($count,5);
        $limit=$Page->firstRow.','.$Page->listRows;// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $show       = $Page->show();// 分页显示输出
// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $result = $db->getAll($where,$limit);
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('weibo',$result);
        $this->display();
    }

    public function loginout(){
        session(null);
        cookie('auto',null);
        redirect(U('Login/index'),2,'正在安全退出...');
    }

         //发布微博
    public function sendWeibo(){
        if(!IS_POST) E('页面不存在');
        //
        $this->_upload('Pic','800,380,120','800,380,120');
    }

    private function _upload($path, $width, $height) {
        // 文件上传
        $config = array(
            'maxSize'    => C('UPLOAD_MAX_SIZE'),            // 设置附件上传大小
            'exts'        => C('UPLOAD_EXTS'),                // 设置附件上传类型
            'rootPath'    => C('UPLOAD_PATH').$path.'/',     // 设置附件上传目录
            'replace'    => true,                    // 覆盖同名文件
            'saveName'    => array('uniqid',''),        // 文件名创建规则
            'autoSub'    => true,                    // 自动使用子目录保存上传文件
            'subName'    => array('date','Ymd'),        // 子目录创建规则
        );
        $upload = new \Think\Upload($config);

        $info = $upload->upload();
        if (!$info) {
            $this->error($upload->getError());
        } else {
            // 生成缩略图
            foreach ($info as $file) {
                // 获取原图地址
                $img = $config['rootPath'] . $file['savepath'] . $file['savename'];
                $image = new \Think\Image();            // GD库
                $image->open($img);                     // 打开原图
                $thumbWidth  = explode(',', $width);    // 获取宽度
                $thumbHeight = explode(',', $height);    // 获取高度
                // 设置缩略图宽、高、前缀
                $thumb = array(
                    1 => array('w' => $thumbWidth[0], 'h' => $thumbHeight[0], 'n' => 'max_'),
                    2 => array('w' => $thumbWidth[1], 'h' => $thumbHeight[1], 'n' => 'medium_'),
                    3 => array('w' => $thumbWidth[2], 'h' => $thumbHeight[2], 'n' => 'mini_'),
                );
                foreach ($thumb as $value){
                    // 生成缩略图保存路径,并命名
                    $save_path = $config['rootPath'] . $file['savepath'] . $value['n'] . $file['savename'];
                    // 设置宽高和缩略类型,并保存缩略图
                    $image->thumb($value['w'], $value['h'], \Think\Image::IMAGE_THUMB_CENTER)->save($save_path);
                }
                unlink($img);        //上传生成缩略图以后删除源文件
                $date= array(
                    'savepath' => array(
                        'max'     => $file['savepath'] . 'max_'.  $file['savename'],
                        'medium'=> $file['savepath'] . 'medium_' . $file['savename'],
                        'mini'    => $file['savepath'] . 'mini_' . $file['savename'],
                    ),
                );

                //
                $data=array(
                    'content'=>I('content'),
                    'time'=>time(),
                    'uid'=>$_SESSION['uid']
                );
                if ($wid=M('weibo')->add($data)){
                    if (!empty($date['savepath']['max'])){
                        $pic=array(
                            'max'=>$date['savepath']['max'],
                            'medium'=>$date['savepath']['medium'],
                            'mini'=>$date['savepath']['mini'],
                            'wid'=>$wid
                        );
                        M('picture')->add($pic);
                    }
                    M('userinfo')->where(array('uid' => session('uid')))->setInc('weibo');
                    //***处理@用户
                    $this->_atmeHandel($data['content'], $wid);
                    $this->success('添加成功');
                }else{
                    $this->error('添加失败');
                }
            }
            
        }
    }

          //删除微博
    public function delWeibo(){
        if (!IS_AJAX) {
            E('页面不存在');
        }
        $wid=I('wid','','int');
        if (M('weibo')->delete($wid)) {
            //如果删除的微博含有图片
            $db = M('picture');
            $img = $db->where(array('wid' => $wid))->find();

            //对图片表记录进行删除
            if ($img) {
                $db->delete($img['id']);

                //删除图片文件
                @unlink('./Uploads/Pic/' . $img['mini']);
                @unlink('./Uploads/Pic/' . $img['medium']);
                @unlink('./Uploads/Pic/' . $img['max']);
            }

            //对有@用户表记录进行删除
            $atme=M('atme')->where(array('wid'=>$wid))->find();
            if ($atme) {
                $db->delete($atme['id']);
            }

            M('userinfo')->where(array('uid' => session('uid')))->setDec('weibo');
            M('comment')->where(array('wid' => $wid))->delete();

            echo 1;
        } else {
            echo 0;
        }
    }

    /**
     * 收藏微博
     */
    Public function keep () {
        if (!IS_AJAX) {
            E('页面不存在');
        }

        $wid = I('wid','','int');
        $uid = session('uid');

        $db = M('keep');

        //检测用户是否已经收藏该微博
        $where = array('wid' => $wid, 'uid' => $uid);
        if ($db->where($where)->getField('id')) {
            echo -1;
            exit();
        }

        //添加收藏
        $data = array(
            'uid' => $uid,
            'time' => $_SERVER['REQUEST_TIME'],
            'wid' => $wid
        );

        if ($db->data($data)->add()) {
            //收藏成功时对该微博的收藏数+1
            M('weibo')->where(array('id' => $wid))->setInc('keep');
            echo 1;
        } else {
            echo 0;
        }
    }

    /**
     * @用户处理
     */
    Private function _atmeHandel ($content, $wid) {
        $preg = '/@(\S+?)\s/is';
        preg_match_all($preg, $content, $arr);

        if (!empty($arr[1])) {
            $db = M('userinfo');
            $atme = M('atme');
            foreach ($arr[1] as $v) {
                $uid = $db->where(array('username' => $v))->getField('uid');
                if ($uid) {
                    $data = array(
                        'wid' => $wid,
                        'uid' => $uid
                    );

                    //写入消息推送
                    set_msg($uid, 3);
                    $atme->data($data)->add();
                }
            }
        }
    }


    /**
     * 转发微博
     */
    Public function turn () {
        if (!IS_POST) {
            E('页面不存在');
        }
        //原微博ID
        $id = I('id','', 'int');
        $tid = I('tid','', 'int');
        //转发内容
        $content = I('content');

        //提取插入数据
        $data = array(
            'content' => $content,
            'isturn' => $tid ? $tid : $id,
            'time' => time(),
            'uid' => session('uid')
        );

        //插入数据至微博表
        $db = M('weibo');
        if ($wid = $db->data($data)->add()) {
            //原微博转发数+1
            $db->where(array('id' => $id))->setInc('turn');

            if ($tid) {
                $db->where(array('id' => $tid))->setInc('turn');
            }

            //用户发布微博数+1
            M('userinfo')->where(array('uid' => session('uid')))->setInc('weibo');

            //处理@用户
            $this->_atmeHandel($data['content'], $wid);

            //如果点击了同时评论插入内容到评论表
            if (isset($_POST['becomment'])) {
                $data = array(
                    'content' => $content,
                    'time' => time(),
                    'uid' => session('uid'),
                    'wid' => $id
                );
                //插入评论数据后给原微博评论次数+1
                if (M('comment')->data($data)->add()) {
                    $db->where(array('id' => $id))->setInc('comment');
                }
            }

            $this->success('转发成功', $_SERVER['HTTP_REFERER']);
        } else {
            $this->error('转发失败请重试...');
        }
    }


    /**
     * 评论处理
     */
    Public function comment () {
        if (!IS_AJAX) {
            E('页面不存在');
        }
        //提取评论数据
        $data = array(
            'content' => I('content'),
            'time' => time(),
            'uid' => session('uid'),
            'wid' => I('wid','', 'int')
        );

        //读取微博被评论用户的id
        $wuid=I('uid','','int');

        if (M('comment')->data($data)->add()) {
            //读取评论用户信息
            $field = array('username', 'face50' => 'face', 'uid');
            $where = array('uid' => $data['uid']);
            $user = M('userinfo')->where($where)->field($field)->find();
            //评论微博的发布者用户名
            $username = $user['username'];

            $db = M('weibo');
            //评论数+1
            $db->where(array('id' => $data['wid']))->setInc('comment');

            //评论同时转发时处理
            if ($_POST['isturn']) {
                //读取转发微博ID与内容
                $field = array('id', 'content', 'isturn');
                $weibo = $db->field($field)->find($data['wid']);
                $content = $weibo['isturn'] ? $data['content'] . ' // @' .
                    $username . ' : ' . $weibo['content'] : $data['content'];

                //同时转发到微博的数据
                $cons = array(
                    'content' => $content,
                    'isturn' => $weibo['isturn'] ? $weibo['isturn'] : $data['wid'],
                    'time' => $data['time'],
                    'uid' => $data['uid']
                );

                if ($db->data($cons)->add()) {
                    $db->where(array('id' => $weibo['id']))->setInc('turn');
                }

                echo 1;
                exit();
            }

            //组合评论样式字符串返回
            $str = '';
            $str .= '<dl class="comment_content">';
            $str .= '<dt><a href="' . U('/' . $data['uid']) . '">';
            $str .= '<img src="';
            $str .= __ROOT__;
            if ($user['face']) {
                $str .= '/Uploads/Face/' . $user['face'];
            } else {
                $str .= '/Public/Images/noface.gif';
            }
            $str .= '" alt="' . $user['username'] . '" width="30" height="30"/>';
            $str .= '</a></dt><dd>';
            $str .= '<a href="' . U('/' . $data['uid']) . '" class="comment_name">';
            $str .= $user['username'] . '</a> : ' . replace_weibo($data['content']);
            $str .= '&nbsp;&nbsp;( ' . time_format($data['time']) . ' )';
            $str .= '<div class="reply">';
            $str .= '<a href="">回复</a>';
            $str .= '</div></dd></dl>';

                set_msg($wuid, 1);

            echo $str;

        }else{
            echo false;
        }

    }

    /**
     * 异步获取评论内容
     */
    Public function getComment () {
        if (!IS_AJAX) {
            E('页面不存在');
        }
        $wid = I('wid','', 'int');
        $where = array('wid' => $wid);

        //数据的总条数
        $count = M('comment')->where($where)->count();
        $pagesize=6;
        //数据可分的总页数
        $total = ceil($count / $pagesize);
        $page = isset($_POST['page']) ? I('page','', 'int') : 1;
        $limit = $page < 2 ? '0,'.$pagesize.'' : ($pagesize * ($page - 1)) . ','.$pagesize.'';

        $result = D('CommentView')->where($where)->order('time DESC')->limit($limit)->select();

        if ($result) {
            $str = '';
            foreach ($result as $v) {
                $str .= '<dl class="comment_content">';
                $str .= '<dt><a href="' . U('/' . $v['uid']) . '">';
                $str .= '<img src="';
                $str .= __ROOT__;
                if ($v['face']) {
                    $str .= '/Uploads/Face/' . $v['face'];
                } else {
                    $str .= '/Public/Images/noface.gif';
                }
                $str .= '" alt="' . $v['username'] . '" width="30" height="30"/>';
                $str .= '</a></dt><dd>';
                $str .= '<a href="' . U('/' . $v['uid']) . '" class="comment_name">';
                $str .= $v['username'] . '</a> : ' . replace_weibo($v['content']);
                $str .= '&nbsp;&nbsp;( ' . time_format($v['time']) . ' )';
                $str .= '<div class="reply">';
                $str .= '<a href="">回复</a>';
                $str .= '</div></dd></dl>';
            }

            if ($total > 1) {
                $str .= '<dl class="comment-page">';

                switch ($page) {
                    case $page > 1 && $page < $total :
                        $str .= '<dd page="' . ($page - 1) . '" wid="' . $wid . '">上一页</dd>';
                        $str .= '<dd page="' . ($page + 1) . '" wid="' . $wid . '">下一页</dd>';
                        break;

                    case $page < $total :
                        $str .= '<dd page="' . ($page + 1) . '" wid="' . $wid . '">下一页</dd>';
                        break;

                    case $page == $total :
                        $str .= '<dd page="' . ($page - 1) . '" wid="' . $wid . '">上一页</dd>';
                        break;
                }

                $str .= '</dl>';
            }

            echo $str;

        } else {
            echo 'false';
        }
    }



}