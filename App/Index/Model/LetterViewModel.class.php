<?php
/**
 * 私信视图模型
 */
namespace Index\Model;
use Think\Model\ViewModel;
Class LetterViewModel extends ViewModel {

	Protected $viewFields = array(
		'letter' => array(
			'id', 'content', 'time',
			'_type' => 'LEFT'
			),
		'userinfo' => array(
			'username', 'face50' => 'face', 'uid',
			'_on' => 'letter.from = userinfo.uid'
			)
		);
}
?>