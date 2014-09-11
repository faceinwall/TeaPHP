<html>
<head>
	<title>TeaPHP 框架1.0</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8">
	<script type="text/javascript" src='/assets/js/jquery-1.7.2.min.js'></script>
	<link rel="stylesheet" type="text/css" href="/assets/css/core.css">
</head>
<body>
	<!-- BEGIN HEADER -->
	<div id="header">
		<h2>系统管理</h2>
	</div>
	<!-- END HEADER -->
	<hr>

	<!-- GEGIN MENU -->
	<div id="nav" class="clear">
		<ul class="ml">
			<li><a href="<?= new \engine\net\Url('index') ?>">首页</a></li>
			<li><a href="<?= new \engine\net\Url('menu/index') ?>">系统管理</a></li>
			<li><a href="<?= new \engine\net\Url('user/index') ?>">用户管理</a></li>
		</ul>	
		<ul class="mr">
			<li><a href="#">网站汇总</a></li>
			<li><a href="#">关闭侧栏</a></li>

			<?php if (isset($_SESSION['id'])) {?>
			<li>你好！<a href="<?= new \engine\net\Url('user/me') ?>"><?=$username ?></a></li>
			<li><a href="<?= new \engine\net\Url('public/logout') ?>">注销</a></li>
			<?php } ?>
		</ul>
	</div>
	<!-- END MENU -->
	<hr>

	<div id="main">
		<div id="sider">
			<div class="side-menu">
				<table>
					<tr><td><a href="">系统管理</a></td></tr>
					<tr><td><a href="">人员管理</a></td></tr>
					<tr><td><a href="">权限管理</a></td></tr>
					<tr><td><a href="">部门管理</a></td></tr>
				</table>
			</div>
			<div class="side-online">

			</div>
		</div>

		<!-- MAIN FRAME -->
		<div id="frame">
			<div id="top-alert" class="fixed alert alert-error" style="display: none;">
	            <button class="close fixed" style="margin-top: 4px;">&times;</button>
	            <div class="alert-content">这是内容</div>
        	</div>