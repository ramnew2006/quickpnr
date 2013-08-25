<?php
include('checkcookie.php');
require_once 'database.php';
require_once 'userscripts/sendsms_class.php';

$dbobj = new database();
$dbobj->dbconnect();

if(isset($_POST['forgotPassword'])){
	$mobileNum = $_POST['mobileNum'];
	if(is_numeric($mobileNum) && strlen($mobileNum)==10){
		$query = mysql_query("SELECT * FROM `userlogin` WHERE `mobilenum` = " . $mobileNum);
		if(mysql_num_rows($query)==1){
			$act_code = randomDigits(6); //hash('sha512',mt_rand().time());
			$query = "UPDATE userlogin SET fgt_passcode='" . $act_code . "' WHERE mobilenum=" . $mobileNum;
			if(mysql_query($query)){
				include('header.php');
				?>
				<section id="forgotpassword">
					<div class="page-header">
					<h3>Enter One Time Password</h3>
					</div>
				<form action="../change-password.php" method="post" onsubmit="return(validateForm());">
				<table>
				<input type="hidden" name="mobNum" value="<?php echo $mobileNum; ?>">
				<tr>
					<td>One Time Password</td> 
					<td style="padding-left:2em;"><input type="text" name="act_code" maxlength="6" id="actcode"></td>
				</tr>
				<tr>
					<td>Enter New Password</td>
					<td style="padding-left:2em;"><input type="password" name="forgotuserPassword"></td>
				</tr>
				<tr>
					<td>Confirm Password</td>
					<td style="padding-left:2em;"><input type="password" name="forgotconfirmpassword"></td>
				</tr>
				</table><br/>
				<input class="btn btn-primary" type="submit" name="changePassword" value="Submit">
				</form>
				
				<?php
				$message = "One Time Password for quickPNR: " . $act_code;
				$smsobj = new sendSMS($mobileNum,$message);
			}else{
				echo "Something went wrong. Please try again!";
			}
		}else{
			echo "We cannot find your mobile Number. Please Register!";
		}
	}
}else{
	header("Location:index.php");
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
	var password = $('input[name="forgotuserPassword"]').val();
	var confpassword = $('input[name="forgotconfirmpassword"]').val();
	if($.isNumeric(act_code) && act_code.length==6){
	}else{
		alert("Please enter six digit activation code!");
		return false;
	}
	if(password==confpassword){
	}else{
		alert("Passwords do not match");
		return false;
	}
}
</script>
<?php
$dbobj->dbdisconnect();
include('footer.php');
?>

