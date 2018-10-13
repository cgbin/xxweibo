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
		<span>微博检索</span>
	</div>
	<div style='width:600px;text-align:center;margin : 20px auto;'>
		<form action="/weibo/Admin/Weibo/sechWeibo" method='get'>
			检索关键字：
			<input type="text" name='sech'/>
			<input type="submit" value='' class='see'/>
		</form>
	</div>
	<table class="table">
		<?php if(isset($weibo) && !$weibo): ?><tr>
				<td align='center'>没有检索到相关微博</td>
			</tr>
		<?php else: ?>
			<tr>
				<th>ID</th>
				<th>发布者</th>
				<th>内容</th>
				<th>类型</th>
				<th>统计信息</th>
				<th>发布时间</th>
				<th>操作</th>
			</tr>
			<?php if(is_array($weibo)): foreach($weibo as $key=>$v): ?><tr>
					<td align='center' width='50'><?php echo ($v["id"]); ?></td>
					<td width='100'><?php echo ($v["username"]); ?></td>
					<td><?php echo ($v["content"]); ?></td>
					<td align='center' width='80'>
						<?php if($v["isturn"]): ?>转发
						<?php elseif($v["pic"]): ?>
							<a href="/weibo/Uploads/Pic/<?php echo ($v["pic"]); ?>" target='_blank'>查看图片</a><?php endif; ?>	
					</td>
					<td align='center'>
						<ul>
							<li>转发：<?php echo ($v["turn"]); ?></li>
							<li>收藏：<?php echo ($v["keep"]); ?></li>
							<li>评论：<?php echo ($v["comment"]); ?></li>
						</ul>
					</td>
					<td align='center' width='100'><?php echo (date('y-m-d H:i', $v["time"])); ?></td>
					<td width='60'>
						<a href="<?php echo U('delWeibo', array('id' => $v['id'], 'uid' => $v['uid']));?>" class='del'></a>
					</td>
				</tr><?php endforeach; endif; ?>
			<tr>
				<td colspan='7' align='center' height='60'><?php echo ($page); ?></td>
			</tr><?php endif; ?>
	</table>
</body>
</html>