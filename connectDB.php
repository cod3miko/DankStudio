<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "musicdb";

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
else{	
	//echo "Successfully connected";
}
//terminate connection
//$con->close();
?>