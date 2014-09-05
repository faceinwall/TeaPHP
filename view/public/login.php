<html>
<head>
	<title>Admin Login</title>
</head>
<body>
	<form action="<?=(new \engine\net\Url('/public/login'))?>" method="post">
		<h2>Admin Login</h2>
		<hr>
		<p>
			<label>username:</label>
			<div><input type="text" name="username" value></div>
		</p>
		<p>
			<label>password:</label>
			<div><input type="password" name="password" value></div>
		</p>
		<p>
			<div><input type="submit" value="submit"></div>
		</p>
	</form>
</body>
</html>