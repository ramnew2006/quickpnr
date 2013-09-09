<?php
include('checkcookie.php');
require_once 'database.php';
require_once 'userscripts/sendsms_class.php';

$dbobj = new database();
$dbobj->dbconnect();


if(isset($_POST['userRegister'])){
	$mobileNum = $_POST['mobileNum'];
	$password = $_POST['userPassword'];
	if(is_numeric($mobileNum) && strlen($mobileNum)==10){
		$password = $password . $dbobj->returnSalt();
		$password = hash('sha512',$password);
		$query = mysql_query("SELECT * FROM `userlogin` WHERE `mobilenum` = " . $mobileNum . " AND `password` = '" . $password . "'");
		if(mysql_num_rows($query)==0){
			$act_code = randomDigits(6); //hash('sha512',mt_rand().time());
			$query = "INSERT INTO userlogin (mobilenum, password, act_code) VALUES ('" . $mobileNum . "','" . $password . "','" . $act_code . "')";
			if(mysql_query($query)){
				include('header.php');
				echo "<section id=\"registerconfirm\">
					<div class=\"page-header\">
					<h3>Enter Activation Code</h3>
					</div>";
				echo "<form action=\"activation.php\" method=\"post\" onsubmit=\"return(validateForm());\">
				<input type=\"text\" name=\"act_code\" maxlength=\"6\" id=\"actcode\">  <input type=\"submit\" name=\"mobActivation\" class=\"btn btn-primary\" value=\"Validate\">
				</form>";
				$_SESSION['registerNum'] = $mobileNum;
				$message = "Activation Code for quickPNR: " . $act_code;
				$smsobj = new sendSMS("8122636821","man0har",$mobileNum,$message);
				//echo "User Successfully Registered";
			}else{
				echo "something is wrong";
			}
		}else{
			include('header.php');
			echo "<section id=\"alreadyregistered\">
					<div class=\"page-header\">
					<h3>You are already Registered! Please <a data-toggle=\"modal\" href=\"#myLoginModal\">Login</a> to proceed!</h3>
					</div>";
		}
	}
}else{
	if(isset($_SESSION['registerNum'])){
		include('header.php');
		echo "<section id=\"registerconfirm\">
			<div class=\"page-header\">
			<h3>Enter Activation Code</h3>
			</div>";
		echo "<form action=\"activation.php\" method=\"post\" onsubmit=\"return(validateForm());\">
		<input type=\"text\" name=\"act_code\" maxlength=\"6\" id=\"actcode\">  <input class=\"btn btn-primary\" type=\"submit\" name=\"mobActivation\" value=\"Validate\">
		</form>";
	}else{
		header("Location:/");
	}
}

function randomDigits($length){
    $numbers = range(0,9);
    shuffle($numbers);
	$digits = "";
    for($i = 0;$i < $length;$i++)
       $digits .= $numbers[$i];
    return $digits;
}
?>

<script type="text/javascript">
function validateForm(){
	var act_code = $("#actcode").val();
	if($.isNumeric(act_code) && act_code.length==6){
		return true;
	}else{
		alert("Please enter six digit activation code!");
		return false;
	}
}
</script>

<?php
$dbobj->dbdisconnect();
include('footer.php');
?>

