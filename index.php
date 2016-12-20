<?php
session_start();

//Spotify API
require 'spotify/Request.php';
require 'spotify/Session.php';
require 'spotify/SpotifyWebAPI.php';
require 'spotify/SpotifyWebAPIException.php';

$session = new SpotifyWebAPI\Session(
    '07440f7a294747adb22a1d1f028097e2',
    'a1bf5bacb6ec4153804dbb7abccf8353',
    'http://localhost/Music%20Database/'
);
$api = new SpotifyWebAPI\SpotifyWebAPI();
?>

<html>
<head>

<title>Dank</title>

<!-- Bootstrap Core CSS -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="css/grayscale.css" rel="stylesheet">
<!-- Custom Fonts -->
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">

</head>

<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

    <!-- Navigation -->
    <nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand page-scroll" href="#page-top">
                    <i class="fa fa-play-circle"></i>  <span class="light">Home</span>
                </a>
            </div>

            <!-- Nav Content -->
            <div class="collapse navbar-collapse navbar-right navbar-main-collapse">
                <ul class="nav navbar-nav">
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#search">Quick Search</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#info">Discover</a>
                    </li>
                </ul>
				
				<?php if (isset($_SESSION['usr_id'])) { ?>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><b><?php echo $_SESSION['usr_name']; ?></b> <span class="caret"></span></a>
					<ul id="login-dp" class="dropdown-menu">
						<li>
							<div class="row">
								<div class="col-md-12">
									 <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="logoutform">
										<li>
											<a class="page-scroll" href="#playlist">Your Playlist</a><br><br>
										</li>
										<input type="submit" class="btn btn-default btn-block" value="Log Out <?php echo $_SESSION['usr_name']; ?> ?" name="btn-logout" >
										<?php
										//session_start();
										if (isset($_POST['btn-logout'])) {
											if(isset($_SESSION['usr_id'])) {
												session_destroy();
												unset($_SESSION['usr_id']);
												unset($_SESSION['usr_name']);
												header("Location: index.php");
											} else {
												header("Location: index.php");
											}
										}
										?>
									</form>
								</div>
							</div>		
						</li>
					</ul>
				</ul>	
				<?php } else { ?>
				<ul class="nav navbar-nav navbar-right">
				<li class="dropdown">
				  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b>Login</b> <span class="caret"></span></a>
					<ul id="login-dp" class="dropdown-menu">
						<li>
							 <div class="row">
									<div class="col-md-12">
										 <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="loginform">
											<div class="form-group">
												 <input type="username" required class="form-control" id="username" placeholder="Username" name="username" autofocus required>
											</div>
											<div class="form-group">
												 <input type="password" required class="form-control" id="password" placeholder="Password" name="password" autofocus required>
											</div>
											<div class="form-group">
												 <input type="submit" name="btn-login" value="Sign In" class="btn btn-default btn-block">
												<?php
												//session_start();
												
												if(isset($_SESSION['usr_id'])!="") {
													header("Location: index.php");
												}

												include_once 'connectDB.php';

												//check if form is submitted
												if (isset($_POST['btn-login'])) {
													$user = mysqli_real_escape_string($con, $_POST['username']);
													$password = mysqli_real_escape_string($con, $_POST['password']);
													$result = mysqli_query($con, "SELECT * FROM users WHERE username = '" . $user. "' and pass = '" . md5($password) . "'");

													if ($row = mysqli_fetch_array($result)) {
														$_SESSION['usr_id'] = $row['user_id'];
														$_SESSION['usr_name'] = $row['username'];
														header("Location: index.php");
													} else {
														$errormsg = "Incorrect Username or Password";
													}
												}
												?>
											</div>
											<div class="form-group">
												 <input type="submit" name="btn-register" value="Sign Up" class="btn btn-default btn-block">
												<?php
												//session_start();

												if(isset($_SESSION['usr_id'])) {
													header("Location: index.php");
												}

												include_once 'connectDB.php';

												//set validation error flag as false
												$error = false;

												//check if form is submitted
												if (isset($_POST['btn-register'])) {
													$name = mysqli_real_escape_string($con, $_POST['username']);
													$password = mysqli_real_escape_string($con, $_POST['password']);
													
													//name can contain only alpha characters and space
													if (!preg_match("/^[a-zA-Z ]+$/",$name)) {
														$error = true;
														$name_error = "Name must contain only alphabets and space";
													}
													
													
													
													if ($con->query("select username from users where username = '". $_POST['username']. "'")->num_rows > 0){
														$error = true;
														$errormsg = "User Name Already Exists";
													}

													if (!$error) {
														if(mysqli_query($con, "INSERT INTO users(username,pass,playlist) VALUES('" . $name . "', '" . md5($password) . "','')")) {
															$successmsg = "Successfully Registered! You can now login";
														} else {
															$errormsg = "Error in registering...Please try again";
														}
													}
												}
												?>
											</div>
											<div class="bottom text-center">
												<a href="http://localhost/Music%20Database/adminLogin.php" ><b>Admin</b></a>
											</div>
										</form>
									</div>
							 </div>
						</li>
					</ul>
				</li>
			  </ul>
			  <?php } ?>
            </div>
        </div>
    </nav>  
	
	<span class="text-danger" style="float:right;"><?php if (isset($errormsg)) { echo $errormsg; } ?></span>
	<span class="text-danger" style="float:right;"><?php if (isset($name_error)) { echo $name_error; } ?></span>
	<span class="text" style="float:right;"><?php if (isset($successmsg)) { echo $successmsg; } ?></span>

    <!-- Intro Header -->
    <header class="intro">
        <div class="intro-body">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <h1 class="brand-heading">Dank Studio</h1>
                        <p class="intro-text">A Music Database and Media Player<br>Discover any artist, album, or song now.</p>
                        <a href="#search" class="btn btn-circle page-scroll">
                            <i class="fa fa-angle-double-down animated"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Search Section -->
    <section id="search" class="container content-section text-center">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <h2>Quick Search</h2>
                <p>Type an artist or album to play tracks</p>
				<form action="index.php#search" id="search" method="POST">
				<div class="row">
					<div class="col-lg-6 col-lg-offset-1">
						<input type="text" class="form-control" id="artist" name="artist">
					</div>
					<div class="col-sm-4 col-lg-offset-1">
						<input type="submit" class="btn btn-default" id="submit" name="submit" disabled="true" value="Search" />
						<a href="http://localhost/Music%20Database/#search" class="btn btn-default">Refresh</a>
					</div>	
				</div>						
				</form>
			</div>
		</div>
		<br>
		<div class="row"> 
			<?php
				//search method
				if(isset($_POST['submit'])){

					//Searching for artist with limit
					$query=$_POST['artist'];	
					$results = $api->search($query, 'album', array('limit' => 5));
					
					echo 'Top 5 Matches <br><br> <div class="row">';
					foreach ($results->albums->items as $album) {
						//album name
						echo '<div class="col-sm-2" style="text-align:left;font-size:10px">', $album->name, '<br>';
						//album image
						echo '<img src="' . $album->images[1]->url . '"> <br>';
						//album tracks
						$albumID = $album->id;
						$albumInfo = $api->getAlbum($albumID);
						foreach ($albumInfo->tracks->items as $tracks){
							echo $tracks->name, '<audio src="' . $tracks->preview_url . '" controls style="width: 200px"> </audio>', "<br>";
						}
						echo '</div>';
					}
					echo '</div>';
				}
			?>
		</div>
    </section>

    <!-- info Section -->
    <section id="info" class="content-section text-center">
        <div class="info-section">
            <div class="container">
                <div class="col-lg-8 col-lg-offset-2">
                    <h2>Discover</h2>
                    <p>All sorts of music information</p>
					<form action="index.php#info" id="info" method="POST">
						<div class="row">
						  <div class="col-lg-8">
							<div class="input-group">
							  <div class="input-group-btn">
								<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Artist <span class="caret"></span></button>
								<input type="hidden" class="form-control" id="search_type" name="search_type">
								<ul class="dropdown-menu">
								  <li onclick="$('#search_type').val('artist');"><a href="#info">Artist</a></li>
								  <li onclick="$('#search_type').val('album');"><a href="#info">Album</a></li>
								  <li onclick="$('#search_type').val('track');"><a href="#info">Track</a></li>
								</ul>
							  </div>
							  <input type="text" class="form-control" id="search_text" name="search_text" aria-label="...">
							</div>
						  </div>
						  <input type="submit" class="btn btn-default" id="submit2" disabled="true" name="submit2" value="Search" />	
						  <a href="http://localhost/Music%20Database/#info" class="btn btn-default">Refresh</a>
					</form>
                </div>
            </div>
        </div>
		<div class="row"> 
			<?php
				//gathering info method
				if(isset($_POST['submit2'])){
					//condition for dropdown menu
					if($_POST['search_type']==null){
						$type = 'artist';
					}else{
						$type =$_POST['search_type'];
					}
					//$type is artist/album/track and $query is user input
					//limited to just 1 search ie. not "smart"
					$query=$_POST['search_text'];
					$results = $api->search($query, $type, array('limit' => 1));
					if($type == 'artist'){
						$info = $results->artists->items[0];
						$id = $api->getArtist($info->id);
						echo 'Artist: ', $id->name, '<br>';
						echo 'Popularity: ', $id->popularity,'% <br>';
						echo 'Followers: ', $id->followers->total, '<br>';
						echo '<img src="' . $id->images[1]->url . '"> <br>';
						$albums = $api->getArtistAlbums($info->id, array('album_type'=>'single,album'));
						foreach ($albums->items as $images){
							echo '<img src="' . $images->images[2]->url . '"> </img>';
						}
					}
					if($type == 'album'){
						$info = $results->albums->items[0];
						$id = $api->getAlbum($info->id);
						echo 'Album: ', $id->name, '<br>';
						echo 'Artist: ', $id->artists[0]->name, '<br>';
						echo 'Copyright: ', $id->copyrights[0]->text, '<br>';
						echo 'Release Date: ', $id->release_date, '<br>';
						echo 'Popularity: ', $id->popularity, '% <br>';
						echo '<img src="' . $info->images[1]->url . '"> <br>';
						foreach ($id->tracks->items as $tracks){
							echo $tracks->name, '<audio src="' . $tracks->preview_url . '" controls style="width: 200px"> </audio>', "<br>";
						}	
					}
					if($type == 'track'){
						$info = $results->tracks->items[0];
						$id = $api->getTrack($info->id);
						if (isset($_SESSION['usr_id'])) {
							echo '<form method="post" action="index.php#playlist" name="form">';
							echo 'Track: ', $id->name, '  <button type="submit" name="btn-add" class="btn btn-default">
								<span class="glyphicon glyphicon-plus-sign"></span></button>   <br>';
							echo '<input type="hidden" name="track" id="track" value="'.$id->id.'">';;
							echo '</form>';
						}else{
							echo 'Track: ', $id->name, '<br>';
						}
						echo '<audio src="' . $id->preview_url . '" controls style="width: 200px"> </audio>', "<br>";
						echo 'By Artist: ', $id->artists[0]->name,'<br>';
						echo 'From Album: ', $id->album->name, '<br>';
						echo '<img src="' . $id->album->images[1]->url . '"> <br>';
					}

				}

			?>
		</div>
    </section>
	
	<!-- playlist Section -->
	<?php if (isset($_SESSION['usr_id'])) { ?>
    <section id="playlist" class="container content-section text-center">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <h2>Playlist</h2>
                <p>Click ( + ) beside a track</p>
				<?php
				//add a track to the playlist
				if (isset($_POST['btn-add'])) {
					$track = $_POST['track'];
					$user_id = $_SESSION['usr_id'];
					include_once 'connectDB.php';
					$sql = "update users set playlist = concat('\n$track',playlist) where user_id= $user_id";
					if ($con->query($sql) === TRUE){
						//echo $_POST['track'];
					}else {
						echo "Error in adding to the playlist" . $con->error;
					}
				}//remove a track from the playlist
				if (isset($_POST['btn-remove'])){
					$track = $_POST['track'];
					$user_id = $_SESSION['usr_id'];
					include_once 'connectDB.php';
					$sql = "update users set playlist = replace(playlist,'$track\n','') where user_id= $user_id";
					if ($con->query($sql) === TRUE){
						//echo $_POST['track'];
					}else {
						echo "Error in removing from the playlist" . $con->error;
					}
				}//display the playlist
				include_once 'connectDB.php';
				$user_id = $_SESSION['usr_id'];
				$sql = "SELECT playlist FROM users where user_id=$user_id";
				$result = $con->query($sql);
				$row = $result->fetch_assoc();
				if (strlen($row['playlist']) > 0) {	
					// output data of each row
					$playlist = implode("\n",$row);
					$track = explode("\n", $playlist);
					for ($i = 1; $i<=count($track)-1; $i++){
						$id = $api->getTrack($track[$i]);
						echo '<form method="post" action="index.php#playlist" name="form">';
						echo '<button type="submit" name="btn-remove" class="btn btn-default">
							<span class="glyphicon glyphicon-minus-sign"></span></button>   Track: ', $id->name, ' By ', $id->artists[0]->name;
						echo '<audio src="' . $id->preview_url . '" controls style="width: 200px"> </audio>', 'From Album: ', $id->album->name, '   <img src="' . $id->album->images[2]->url . '"> <br>';
						echo '<input type="hidden" name="track" id="track" value="'.$id->id.'">';;
						echo '</form>';
					}
				} else {
					echo "0 songs in the playlist";
				}				
				?>
            </div>
        </div>

    </section>
	<?php } ?>
	
	<br><br><br><br>
	<br><br><br><br>
    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <p>Copyright &copy; Dank Studio 2016</p>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="js/jquery.easing.min.js"></script>
	

    <script type="text/javascript">
		function hasWhiteSpaceOrEmpty(s) 
		{
			return s == "";
		}
		
		function validateInput()
		{
			var inputVal = $("#artist").val();
			if(hasWhiteSpaceOrEmpty(inputVal))
			{
				$("#submit").attr("disabled", "disabled");
			}
			else
			{
				$("#submit").removeAttr("disabled");
			}
			var inputVal2 = $("#search_text").val();
			if(hasWhiteSpaceOrEmpty(inputVal2))
			{
				$("#submit2").attr("disabled", "disabled");
			}
			else
			{
				$("#submit2").removeAttr("disabled");
			}
		}
		$("#artist").keyup(validateInput);
		$("#search_text").keyup(validateInput);
		
		$(".dropdown-menu li a").click(function(){
		  var selText = $(this).text();
		  $(this).parents('.input-group-btn').find('.dropdown-toggle').html(selText+' <span class="caret"></span>');
		});
		
		$(".dropdown-menu li a").each(function()
        {
            $('#info').append('<input type="hidden" name="search_text" value="'+$(this).html()+'">');
        });
		

    </script>

    <!-- Custom Theme JavaScript -->
    <script src="js/grayscale.js"></script>

</body>
</html>