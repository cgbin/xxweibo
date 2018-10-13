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
		<span>后台管理员列表</span>
	</div>
	<table class="table">
		<tr>
			<th>ID</th>
			<th>管理员名称</th>
			<th>级别</th>
			<th>最后登录时间</th>
			<th>最后登录IP</th>
			<th>账号状态</th>
			<?php if(!$_SESSION["admin"]): ?><th>操作</th><?php endif; ?>
		</tr>
		<?php if(is_array($admin)): foreach($admin as $key=>$v): ?><tr>
				<td width='50' align='center'><?php echo ($v["id"]); ?></td>
				<td width='120' align='center'><?php echo ($v["username"]); ?></td>
				<td align='center'>
					<?php if($v["admin"]): ?>管理员
					<?php else: ?>
						超级管理员<?php endif; ?>
				</td>
				<td align='center'><?php echo (date('y-m-d H:i', $v["logintime"])); ?></td>
				<td align='center'><?php echo ($v["loginip"]); ?></td>
				<td width='60' align='center'>
					<?php if($v["lock"]): ?>锁定<?php endif; ?>
				</td>
				<?php if(!$_SESSION["admin"]): ?><td width='240'>
						<?php if($v["admin"]): if($v["lock"]): ?><a href="<?php echo U('lockAdmin', array('id' => $v['id'], 'lock' => 0));?>" class='add lock'>解除锁定</a>
							<?php else: ?>
								<a href="<?php echo U('lockAdmin', array('id' => $v['id'], 'lock' => 1));?>" class='add lock'>锁定用户</a><?php endif; ?>
							<a href='<?php echo U("delAdmin", array("id" => $v["id"]));?>' class='add lock'>删除管理员</a><?php endif; ?>
					</td><?php endif; ?>
			</tr><?php endforeach; endif; ?>
	</table>
</body>
</html>