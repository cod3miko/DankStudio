<?php
include_once 'connectDB.php';
//check if form is submitted
$error = false;
if (isset($_POST['register'])) {
	$name = mysqli_real_escape_string($con, $_POST['user']);
	$password = mysqli_real_escape_string($con, $_POST['pass']);
	
	//name can contain only alpha characters and space
	if (!preg_match("/^[a-zA-Z ]+$/",$name)) {
		$error = true;
		$name_error = "Name must contain only alphabets and space";
	}

	if ($con->query("select username from users where username = '". $_POST['user']. "'")->num_rows > 0){
		$error = true;
		$errormsg = "User Name Already Exists";
	}

	if (!$error) {
		if(mysqli_query($con, "INSERT INTO users(username,pass,playlist) VALUES('" . $name . "', '" . md5($password) . "','')")) {
			$successmsg = "Successfully Registered!";
		} else {
			$errormsg = "Error in registering...Please try again";
		}
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
					<li class="active"><a href="admin.php">Return to Admin Panel</a></li>
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
						<legend>Register A New User</legend>
						<div class="form-group">
							<label for="name">Username</label>
							<input type="text" name="user" required class="form-control" />
						</div>
						<div class="form-group">
							<label for="name">Password</label>
							<input type="password" name="pass" required class="form-control" />
						</div>
						<div class="form-group">
							<input type="submit" name="register" value="Register" class="btn btn-primary" />
						</div>
					</fieldset>
				</form>
				<span class="text-danger"><?php if (isset($errormsg)) { echo $errormsg; } ?></span>
				<span class="text-danger" style="float:right;"><?php if (isset($name_error)) { echo $name_error; } ?></span>
				<span class="text" style="float:right;"><?php if (isset($successmsg)) { echo $successmsg; } ?></span>
			</div>
		</div>
	</div>

<script src="js/jquery-1.10.2.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>