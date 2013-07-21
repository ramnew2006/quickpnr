<html>
<head></head>
<body>
<?php
session_start();
require_once 'database.php';
require_once 'sendsms.php';
$dbobj = new database();
$dbobj->dbconnect();

if(isset($_SESSION['user'])){
	header("Location:index.php");
}

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
				echo "<form action=\"activation.php\" method=\"post\" onsubmit=\"return(validateForm());\">
				Enter your activation code: <input type=\"text\" name=\"act_code\" maxsize=\"6\" id=\"actcode\"><br>
				<input type=\"submit\" name=\"mobActivation\" value=\"Validate\">
				</form>";
				$_SESSION['registerNum'] = $mobileNum;
				$message = "Activation Code for qwikTravel: " . $act_code;
				$smsobj = new sendSMS($mobileNum,$message);
				//echo "User Successfully Registered";
			}else{
				echo "something is wrong";
			}
		}
	}
}else{
	header("Location:register.php");
}

function randomDigits($length){
    $numbers = range(0,9);
    shuffle($numbers);
	$digits = "";
    for($i = 0;$i < $length;$i++)
       $digits .= $numbers[$i];
    return $digits;
}

$dbobj->dbdisconnect();

?>
<script type="text/javascript">
function validateForm(){
	var act_code = $('#actcode').val();
	if($.isNumeric(act_code) && act_code.length==6){
		return true;
	}else{
		alert("Please enter six digit activation code!");
		return false;
	}
}
</script>
<script src="http://code.jquery.com/jquery.js"></script>
</body>
</html>