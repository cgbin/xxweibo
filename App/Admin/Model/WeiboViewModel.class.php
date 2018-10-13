<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/28
 * Time: 14:56
 */

namespace Admin\Model;
use Think\Model\ViewModel;
class WeiboViewModel extends ViewModel
{
    public $viewFields = array(
        'weibo' => array(
            'id', 'content','time','turn','keep','comment','uid',
            '_type' => 'LEFT'),

        'userinfo' => array(
             'username',
            '_on' => 'weibo.uid=userinfo.uid',
            '_type' => 'LEFT'
        ),
        'picture' => array(
            'mini',
            '_on' => 'weibo.id=picture.wid'
        )


    );

    Public function getAll($where, $limit){
       return $result = $this->where($where)->limit($limit)->select();
    }



}