<?php
session_start();
require_once 'database.php';

//If userCookie is set in a session check whether its correct and continue the user
if(isset($_SESSION['userCookieSession'])){
	if(isset($_COOKIE['usercookie']) && isset($_COOKIE['userName'])){
		if($_SESSION['userCookieSession']==$_COOKIE['usercookie'] . $_COOKIE['userName']){
			
		}else{ //If session variables and cookies doesn't match sign out the user
			unset($_SESSION['userName']);
			unset($_SESSION['userCookieSession']);
		}
	}else{
		unset($_SESSION['userName']);
		unset($_SESSION['userCookieSession']);
	}
}else{ //If not check whether the cookies are available and set the session variables
	if(isset($_COOKIE['usercookie']) && isset($_COOKIE['userName'])){
		$mobileNum = $_COOKIE['userName'];
		$cookie = $_COOKIE['usercookie'];
		$dbobj = new database();
		$dbobj->dbconnect();
		$query=mysql_query("SELECT * FROM userlogin WHERE mobilenum=" . $mobileNum . " AND cookie='" . $cookie . "'");

		if(mysql_num_rows($query)==1){
			$_SESSION['userName']=$mobileNum;
			$_SESSION['userCookieSession']=$_COOKIE['usercookie'] . $_COOKIE['userName'];
		}
		$dbobj->dbdisconnect();
	}else{ //if cookies are not available sign out the user;
		unset($_SESSION['userName']);
		unset($_SESSION['userCookieSession']);
	}
}

?>
