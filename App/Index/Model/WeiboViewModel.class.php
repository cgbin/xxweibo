<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/28
 * Time: 14:56
 */

namespace Index\Model;
use Think\Model\ViewModel;
class WeiboViewModel extends ViewModel {
    public $viewFields = array(
        'weibo'=>array(
            'id','content','isturn','turn','time','time','keep','comment','uid',
            '_type'=>'LEFT'),

        'userinfo'=>array(
            'face50'=>'face','username',
            '_on'=>'weibo.uid=userinfo.uid',
            '_type'=>'LEFT'
        ),

        'picture'=>array(
            'max','mini','medium',
            '_on'=>'weibo.id=picture.wid'),
    );

    Public function getAll ($where, $limit) {
        $result = $this->where($where)->order('time DESC')->limit($limit)->select();

        //重组结果集数组，得到转发微博
        if ($result) {
            foreach ($result as $k => $v) {
                if ($v['isturn']) {
                    $tmp = $this->find($v['isturn']);
                    $result[$k]['isturn'] = $tmp ? $tmp : -1;
                }
            }
        }
        return $result;
    }

}