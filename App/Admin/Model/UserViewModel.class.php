<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/28
 * Time: 14:56
 */

namespace Admin\Model;
use Think\Model\ViewModel;
class UserViewModel extends ViewModel
{
    public $viewFields = array(
        'user' => array(
            'id', 'registime', '`lock`',
            '_type' => 'LEFT'),

        'userinfo' => array(
            'face50' => 'face', 'username', 'follow', 'fans', 'weibo',
            '_on' => 'user.id=userinfo.uid',
        ),

    );

    Public function getAll( $limit){
       return $result = $this->limit($limit)->select();
    }



}