<?php
/**
 * 收藏列表视图模型
 */
namespace Index\Model;
use Think\Model\ViewModel;
Class KeepViewModel extends ViewModel {

	Protected $viewFields = array(
		'keep' => array(
			'id' => 'kid', 'time' => 'ktime',
			'_type' => 'INNER'
			),
		'weibo' => array(
			'id', 'content', 'isturn', 'time', 'turn', 'comment', 'uid',
			'_on' => 'keep.wid = weibo.id',
			'_type' => 'LEFT'
			),
		'picture' => array(
			'mini', 'medium', 'max',
			'_on' => 'weibo.id = picture.wid',
			'_type' => 'LEFT'
			),
		'userinfo' => array(
			'username', 'face50' => 'face',
			'_on' => 'weibo.uid = userinfo.uid'
			)
		);

	Public function getAll ($where, $limit) {
		$result = $this->where($where)->order('ktime DESC')->limit($limti)->select();

		$db = D('WeiboView');
		foreach ($result as $k => $v) {
			if ($v['isturn'] > 0) {
				$result[$k]['isturn'] = $db->find($v['isturn']);
			}
		}

		return $result;
	}
}
?>