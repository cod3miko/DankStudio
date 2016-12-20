<?php
include_once 'connectDB.php';

//check if form is submitted
if (isset($_POST['login'])) {
	
    $admin_user = $_POST['admin_user'];
    $admin_pass = $_POST['admin_pass'];
	
	if ($admin_user == 'admin' AND $admin_pass == 'admin') {
		header("Location: admin.php");
    } else {
        $errormsg = "Incorrect Admin Login";
    }
}
?>

<html>
<head>
    <title>Admin Panel</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" >
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
</head>
<body>
	<nav class="navbar navbar-default" role="navigation">
		<div class="container-fluid">
			<!-- add header -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="adminLogin.php">Dank Studio Admin Panel</a>
			</div>
			<!-- menu items -->
			<div class="collapse navbar-collapse" id="navbar1">
				<ul class="nav navbar-nav navbar-right">
					<li class="active"><a href="index.php">Return to Dank Studio</a></li>
				</ul>
			</div>
		</div>
	</nav>
	<br><br><br><br><br><br>
	<div class="container">
		<div class="row">
			<div class="col-md-4 col-md-offset-4 well">
				<form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="loginform">
					<fieldset>
						<legend>Admin Login</legend>
						<div class="form-group">
							<label for="name">Username</label>
							<input type="text" name="admin_user" required class="form-control" />
						</div>
						<div class="form-group">
							<label for="name">Password</label>
							<input type="password" name="admin_pass" required class="form-control" />
						</div>
						<div class="form-group">
							<input type="submit" name="login" value="Login" class="btn btn-primary" />
						</div>
					</fieldset>
				</form>
				<span class="text-danger"><?php if (isset($errormsg)) { echo $errormsg; } ?></span>
			</div>
		</div>
	</div>

<script src="js/jquery-1.10.2.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>