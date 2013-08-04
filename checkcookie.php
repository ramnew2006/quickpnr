<?php
session_start();

if(!empty($_COOKIE['userlogin'])){
	$_SESSION['user']="loggedin";
	$_SESSION['userName']=$_COOKIE['userName'];
}

?>
