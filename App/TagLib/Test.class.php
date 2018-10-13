<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/1
 * Time: 20:00
 */
namespace TagLib;
use Think\Template\TagLib;

class Test extends TagLib{
    protected $tags=array(
        'nav'=>array('attr'=>'','close'=>1),
        'userinfo'=>array('attr'=>'','close'=>1),
        'maybe'=>array('attr'=>'','close'=>1),
    );

     public function _nav($attr,$content){
         $str='<?php ';
       $str .= '$result1=M("group")->where("uid=$_SESSION[uid]")->select();';//查询语句
       $str .= 'foreach ($result1 as $vv): ';
       $str .= '$url=U("read/".$id);?>';//自定义文章生成路径$url
       $str .= $content;
       $str .='<?php endforeach ?>';

       return $str;
     }

    public function _userinfo($attr,$content){
        $str='<?php ';
        $str .= '$result1=M("userinfo")->where("uid=$_SESSION[uid]")->select();';//查询语句
        $str .= 'foreach ($result1 as $v): ';
        $str .= '$url=U("read/".$id);?>';//自定义文章生成路径$url
        $str .= $content;
        $str .='<?php endforeach ?>';

        return $str;
    }

    Public function _maybe ($attr, $content) {
        $uid = $_SESSION['uid'];
        $str = '';
        $str .= '<?php ';
        $str .= '$uid = ' . $uid . ';';
        $str .= '$db = M("follow");';
        $str .= '$where = array("fans" => $uid);';
        $str .= '$follow = $db->where($where)->field("follow")->select();';
        $str .= 'foreach ($follow as $k => $v) :';
        $str .= '$follow[$k] = $v["follow"];';
        $str .= 'endforeach;';
        $str .= '$sql = "SELECT `uid`,`username`,`face50` AS `face`,COUNT(f.`follow`) AS `count` 
        FROM `hd_follow` f LEFT JOIN `hd_userinfo` u ON f.`follow` = u.`uid` WHERE f.`fans` IN 
        (0) AND f.`follow` NOT IN (0) AND f.`follow` <>" . $uid . " GROUP BY f.`follow` ORDER 
        BY `count` DESC LIMIT 4";';
        $str.='if($follow):';
        $str .= '$sql = "SELECT `uid`,`username`,`face50` AS `face`,COUNT(f.`follow`) AS `count` 
        FROM `hd_follow` f LEFT JOIN `hd_userinfo` u ON f.`follow` = u.`uid` WHERE f.`fans` IN 
        (" . implode(\',\', $follow) . ") AND f.`follow` NOT IN (" . implode(\',\',$follow) . ") 
        AND f.`follow` <>" . $uid . " GROUP BY f.`follow` ORDER BY `count` DESC LIMIT 4";';
        $str .= 'endif;';
        $str .= '$friend = $db->query($sql);';
        $str .= 'foreach ($friend as $v) :';
        $str .= 'extract($v);?>';
        $str .= $content;
        $str .= '<?php endforeach;?>';

        return $str;
    }
}