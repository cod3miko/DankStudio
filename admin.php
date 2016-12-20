<?php
include_once 'connectDB.php';
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
			<a class="navbar-brand" href="">Dank Studio Admin Panel</a>
		</div>
		<!-- menu items -->
		<div class="collapse navbar-collapse" id="navbar1">
			<ul class="nav navbar-nav navbar-right">
				<li class="active"><a href="index.php">Logout</a></li>
			</ul>
		</div>
	</div>
</nav>
	<div align="center">
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="form">
		<button type="submit" name="btn-add" class="btn btn-success">ADD USER</button>
		<button type="submit" name="btn-change" class="btn btn-primary">CHANGE USER</button>
		<input type="text" name="id_submit1" placeholder="User ID" size="5">		
		<input type="text" name="change" placeholder="New Name" size="13"><br><br>
		<input type="text" name="id_submit2" placeholder="User ID" size="5">		
		<button type="submit" name="btn-delete" class="btn btn-danger">DELETE USER</button>
		<input type="text" name="id_submit3" placeholder="User ID" size="5">
		<button type="submit" name="btn-drop" class="btn btn-warning">DROP PLAYLIST</button>
		</form>
	</div>
		<span class="text-danger" style="float:right;"><?php if (isset($errormsg)) { echo $errormsg; } ?></span>
		<span class="text" style="float:right;"><?php if (isset($successmsg)) { echo $successmsg; } ?></span>
	
	<div id="content">
    <table>
    <?php
		$sql = "SELECT * FROM users";
		$result = $con->query($sql);
		//number of rows
		echo "<br/>Total records: $result->num_rows<br/> <br>";
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) { 
				if (strlen($row['playlist']) > 0) {	
					$playlist = 'Yes';
				}else{
					$playlist = 'Empty';
				}
				?>
			  <tr>
				<td>.....User ID: <?php echo $row['user_id'];?>...........</td>
				<td>Username: <?php echo $row['username'];?>...........</td>
				<td>Date Joined: <?php echo $row['joinDate'];?>...........</td>
				<td>Playlist: <?php echo $playlist ?>.....</td>
			  </tr>

		<?php } 
		}?>
    </table>
    </div>
<?php 
//add
if(isset($_POST['btn-add'])){
	header("Location: addUser.php");
}
//If deleting user
if(isset($_POST['btn-delete'])){
	if(!empty($_POST['id_submit2'])){
		$sql = "delete from users where user_id= '". $_POST['id_submit2'] . "'";
		if ($con->query($sql) === TRUE){
			$successmsg = "Successfully deleted user";
			header("Location: admin.php");
			
		}else {
			$errormsg = "Error in deleting user: " . $con->error;
		}
	}
}
//change
if(isset($_POST['btn-change'])){
	if(!empty($_POST['id_submit1']) && !empty($_POST['change'])){
		if ($con->query("select username from users where username = '". $_POST['change']. "'")->num_rows > 0){
			$error = true;
			$errormsg = "User Name Already Exists";
		}
		if (!preg_match("/^[a-zA-Z ]+$/",$_POST['change'])) {
			$error = true;
			$name_error = "Name must contain only alphabets and space";
		}
		$sql = "update users set username = '". $_POST['change']."' where user_id= '" . $_POST['id_submit1'] . "'";
		if ($con->query($sql) === TRUE){
			$successmsg = "Successfully changed user's name";
			header("Location: admin.php");
		}else {
			$errormsg = "Error in changing user's name: " . $con->error;
		}
	}
}
//drop playlist
if(isset($_POST['btn-drop'])){
	if(!empty($_POST['id_submit3'])){
		$sql = "update users set playlist = '' where user_id= '" . $_POST['id_submit3'] . "'";
		if ($con->query($sql) === TRUE){
			$successmsg = "Successfully dropped user's playlist";
			header("Location: admin.php");
		}else {
			$errormsg = "Error dropping playlist: " . $con->error;
		}
	}
}
//DROP EVERYTHING
if(isset($_POST['NUKE'])){
	$sql = "TRUNCATE TABLE USERS";
	if ($con->query($sql) === TRUE){
		header("Location: admin.php");
	}
}
?>
<br><br><br>
<br><br><br>
<div class="blink">
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="form">
	<input type="submit" name="NUKE" value="! DROP TABLE !"class="btn btn-danger">
	</form>
</div>

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
<script src="js/jquery.easing.min.js"></script>
<script type="text/javascript">
	function blink(selector){
		$(selector).fadeOut('slow', function(){
			$(this).fadeIn('slow', function(){
				blink(this);
			});
		});
		}

	blink('.blink');
</script>
</body>
</html>