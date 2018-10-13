<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>微博用户列表</title>
	<link rel="stylesheet" href="/weibo/App/Admin/View/Public/Css/common.css" />
	<script type="text/javascript" src='/weibo/App/Admin/View/Public/Js/jquery-1.8.2.min.js'></script>
	<script type="text/javascript" src='/weibo/App/Admin/View/Public/Js/common.js'></script>
</head>
<body>
	<div class='status'>
		<span>原作微博列表</span>
	</div>
	<table class="table">
		<tr>
			<th>ID</th>
			<th>转发用户</th>
			<th>转发内容</th>
			<th>统计信息</th>
			<th>转发时间</th>
			<th>操作</th>
		</tr>
		<?php if(is_array($turn)): foreach($turn as $key=>$v): ?><tr>
				<td widht='50' align='center'><?php echo ($v["id"]); ?></td>
				<td width='100'><?php echo ($v["username"]); ?></td>
				<td><?php echo ($v["content"]); ?></td>
				<td align='center'>
					<ul>
						<li>转发：<?php echo ($v["turn"]); ?></li>
						<li>收藏：<?php echo ($v["keep"]); ?></li>
						<li>评论：<?php echo ($v["comment"]); ?></li>
					</ul>
				</td>
				<td width='100' align='center'><?php echo (date('y-m-d H:i', $v["time"])); ?></td>
				<td width='60'>
					<a href="<?php echo U('delWeibo', array('id' => $v['id'], 'uid' => $v['uid']));?>" class='del'></a>
				</td>
			</tr><?php endforeach; endif; ?>
		<tr>
			<td colspan='6' align='center' height='60'><?php echo ($page); ?></td>
		</tr>
	</table>
</body>
</html>