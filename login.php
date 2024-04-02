<?php
include("include/db.php");
include("include/session.php");
include("include/functions.php");

?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ADMIN DASHBOARD | WEBSITE NAME</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>

	<div class="logo text-center">
		<a href="http://www.webtrickshome.com"><img src="images/logo-big.png" width="50%"></a>
	</div>
	<div class="login-box">
		<form action="login.php" method="post">
			<div class="form-group">
				<?php echo user_login(); ?>
				<label>Email Address</label>
				<input type="text" class="form-control" name="email" autocomplete="off">
			</div>
			<div class="form-group">
				<label>Password</label>
				<input type="password" class="form-control" name="password" autocomplete="off">
			</div>
			<div class="form-group">
				<label for="remember"><input type="checkbox" name="remember" id="remember"> <span class="remember">Remember Me</span></label>
				<input type="submit" name="login" class="btn btn-info pull-right" value="Log In">
			</div>
		</form>
	</div>

	<div class="more-links">
		<p>
			<a href="#">Lost your password?</a>
			<a href="#" class="pull-right">← Back to Site</a>
		</p>
	</div>

<style>


</style>

</body>
</html>
