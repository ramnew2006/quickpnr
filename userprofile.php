<?php
session_start();
require_once 'database.php';

$dbobj = new database();
$dbobj->dbconnect();

if(isset($_SESSION['user'])){
	echo "Welcome to my profile<br>";
	echo "<a href=\"logout.php\">Logout</a><br>";	
	echo "<h1>PNR History</h1>";
	$query = "SELECT * FROM pnrdetails WHERE mobnum=" . $_SESSION['userName'];
	var_dump(mysql_query($query));
}else{
	header("Location:userlogin.php");
	$_SESSION['redirect_url']=$_SERVER["REQUEST_URI"];
}

$dbobj->dbdisconnect();
?>