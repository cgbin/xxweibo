<?php
namespace Index\Controller;
use Index\Controller\CommonController;
class UserSettingController extends CommonController {
    public function index(){
        $uid=$_SESSION['uid'];
        $this->user=M('userinfo')->where(array('uid'=>$uid))->find();
        $this->display();
    }

    public function editBasic(){
        if(!IS_POST) E('页面不存在');
        $data=array(
            'truename'=>I('truename'),
            'sex'=>I('sex',0,'int'),
            'location'=>I('province').' '.I('city'),
            'constellation'=>I('night'),
            'intro'=>I('intro')
        );
        if(M('userinfo')->where(array('id'=>$_SESSION['uid']))->save($data)){
            $this->success('修改资料成功',U('UserSetting/index'));
        }else{
            $this->error('修改失败');
        }
    }


    public function editFace(){
        if(!IS_POST) E('页面不存在');
        $this->_upload('Face','180,80,50','180,80,50');

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
                $data= array(
                    'savepath' => array(
                        'max'     => $file['savepath'] . 'max_'.  $file['savename'],
                        'medium'=> $file['savepath'] . 'medium_' . $file['savename'],
                        'mini'    => $file['savepath'] . 'mini_' . $file['savename'],
                    ),
                );

                $data=array(
                    'face180'=>$data['savepath']['max'],
                    'face80'=>$data['savepath']['medium'],
                    'face50'=>$data['savepath']['mini'],
                );
                if(M('userinfo')->where(array('id'=>$_SESSION['uid']))->save($data)){
                    $this->success('修改头像成功');
                }else{
                    $this->error('修改失败');
                }

            }
        }
    }


    public function editPwd(){
        if(!IS_POST) E('页面不存在');
        $oldpwd=I('old','','md5');
        $pwd=M('user')->where(array('id'=>$_SESSION['uid']))->getField('password');
        if($pwd != $oldpwd){
            $this->error('旧密码错误');
        }
        $data=array(
            'password'=>md5(I('newed'))
        );
        if(M('user')->where(array('id'=>$_SESSION['uid']))->save($data)){
            $this->success('修改密码成功',U('UserSetting/index'));
        }else{
            $this->error('修改失败');
        }

    }



}