<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/28
 * Time: 20:32
 */
/**
 * @param $value [需要加密字符串]
 * @param int $type 【0为加密，1为解密】
 * return  [加密或解密后的字符串]
 */
  function enctyption($value,$type=0){
            $key=md5(C('ENCTYPTION_KEY'));
           if(!$type){
               return str_replace('=','',base64_encode($key^$value));
           }else{
              $value=base64_decode($value);
              return $key^$value;
           }
  }


  function time_format($time){
      $now=time();
      //获取今天的零时零分零秒
      $today=strtotime(date('Y-m-d',$now));
      //传入时间与当前时间的时间差
      $diff=$now-$time;
      $str='';
      switch ($time){
          case $diff<60:
          $str=$diff.'秒前';
          break;
          case $diff<3600:
          $str=floor($diff/60).'分钟前';
          break;
          case $diff<(3600*8):
          $str=floor($diff/3600).'小时前';
          break;
          case $time > $today:
          $str='今天'. date('H:i:s',$time);
          break;
          default:
              $str=date('Y-m-d H:i:s',$time);
      }
      return $str;
  }


/**
 * 替换微博内容的URL地址、@用户与表情
 * @param  [String] $content [需要处理的微博字符串]
 * @return [String]          [处理完成后的字符串]
 */
function replace_weibo ($content) {
    if (empty($content)) return;

    //给URL地址加上 <a> 链接
    $preg = '/(?:http:\/\/)?([\w.]+[\w\/]*\.[\w.]+[\w\/]*\??[\w=\&\+\%]*)/is';
    $content = preg_replace($preg, '<a href="http://\\1" target="_blank">\\1</a>', $content);

    //给@用户加是 <a> 链接
    $preg = '/@(\S+)\s/is';
    $content = preg_replace($preg, '<a href="' . __APP__ . '/User/\\1">@\\1</a>', $content);

    //提取微博内容中所有表情文件
    $preg = '/\[(\S+?)\]/is';
    preg_match_all($preg, $content, $arr);
    //载入表情包数组文件
    $phiz = include './Public/Data/phiz.php';
    if (!empty($arr[1])) {
        foreach ($arr[1] as $k => $v) {
            $name = array_search($v, $phiz);
            if ($name) {
                $content = str_replace($arr[0][$k], '<img src="' . __ROOT__ . '/Public/Images/phiz/' . $name .
                    '.gif" title="' . $v . '"/>', $content);
            }
        }
    }
    return str_replace(C('FILTER'), '***', $content);
}

function set_msg ($uid, $type, $flush=false){
    $name = '';
    switch ($type) {
        case 1 :
            $name = 'comment';
            break;

        case 2 :
            $name = 'letter';
            break;

        case 3 :
            $name = 'atme';
            break;
    }

    if ($flush) {
        $data = S('usermsg' . $uid);
        $data[$name]['total'] = 0;
        $data[$name]['status'] = 0;
        S('usermsg' . $uid, $data, 0);
        return;
    }

    //内存数据已存时让相应数据+1
    if (S('usermsg' . $uid)) {
        $data = S('usermsg' . $uid);
        $data[$name]['total']++;
        $data[$name]['status'] = 1;
        S('usermsg' . $uid, $data, 0);

        //内存数据不存在时，初始化用户数据并写入到内存
    } else {
        $data = array(
            'comment' => array('total' => 0, 'status' => 0),
            'letter' => array('total' => 0, 'status' => 0),
            'atme' => array('total' => 0, 'status' => 0),
        );
        $data[$name]['total']++;
        $data[$name]['status'] = 1;
        S('usermsg' . $uid, $data, 0);
    }

}