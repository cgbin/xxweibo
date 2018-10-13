<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/28
 * Time: 14:56
 */

namespace Index\Model;
use Think\Model\RelationModel;
class UserModel extends RelationModel{
    protected $tableName='user';
    protected $_link = array(
        'userinfo' => array(
            'mapping_type'  => self::HAS_ONE,
            'class_name'    => 'userinfo',
            'foreign_key'   => 'uid',
            // 定义更多的关联属性
        ),

    );
}