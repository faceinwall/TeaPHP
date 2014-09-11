<html>
<head>
	<title>Admin Login</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8">
	<script type="text/javascript" src='/assets/js/jquery-1.7.2.min.js'></script>
</head>
<body>
	<form action="<?=(new \engine\net\Url('/public/login'))?>" method="post">
		<h2>系统登录</h2>
		<hr>
		<p>
			<label>用户名:</label>
			<div><input type="text" name="username" value></div>
		</p>
		<p>
			<label>密码:</label>
			<div><input type="password" name="password" value></div>
		</p>
		<p>
			<div><input type="submit" value="登录"></div>
		</p>
	</form>
</body>
</html>