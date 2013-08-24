<?php
session_start();
require_once '../database.php';

$dbobj = new database();
$dbobj->dbconnect();

if(isset($_SESSION['userName'])){
	header("Location:../user/profile");
}

if(isset($_POST['userLogin'])){
	$mobileNum = $_POST['mobileNum'];
	$password = $_POST['userPassword'];
	if(is_numeric($mobileNum) && strlen($mobileNum)==10){
		$password = $password . $dbobj->returnSalt();
		$password = hash('sha512',$password);
		$query = mysql_query("SELECT * FROM `userlogin` WHERE `mobilenum` = " . $mobileNum . " AND `password` = '" . $password . "'");
		if(mysql_num_rows($query)==1){
			$result = mysql_fetch_array($query);
			$act_status = $result['act_status'];
			if($act_status=="Y"){
				$_SESSION['userName']=$mobileNum;
				//$_SESSION['userEmail']=$email;
				//$_SESSION['userFrequency']=$frequency;
				$rand_cookie = hash('sha512', $mobileNum . $dbobj->returnSalt() . time() . rand());
				setcookie('usercookie',$rand_cookie,time()+(86400*7),"/");
				setcookie('userName',$_SESSION['userName'],time()+(86400*7),"/");
				$_SESSION['userCookie']=$rand_cookie . $mobileNum;
				$query = mysql_query("UPDATE userlogin SET cookie='" . $rand_cookie . "' WHERE mobilenum=" . $mobileNum);
				if(isset($_SESSION['redirect_url'])){
					if($_SESSION['redirect_url']=="../index.php" || $_SESSION['redirect_url']=="../"){
						header("Location:../user/profile");
					}else{
						header("Location: {$_SESSION['redirect_url']}");
					}
				}else{
					header("Location:../user/profile");
				}
			}else{
				$_SESSION['registerNum']=$mobileNum;
				header("Location:../doregister");
			}
		}else{
			echo "wrong mobile num or password";
		}
	}
}else{
	header("Location:../user/login");
}

$dbobj->dbdisconnect();
?>
