<?php
include('../checkcookie.php');
$_SESSION['redirect_url']=$_SERVER["REQUEST_URI"];

if(!isset($_SESSION['userName'])){
	$_SESSION['redirect_url']=$_SERVER["REQUEST_URI"];
	header("Location:../user/login.php");
	exit();
}

$quickpnrmob = $_SESSION['userName'];
session_write_close();

require_once '../database.php';

$dbobj = new database();
$dbobj->dbconnect();

include('../header.php');

$query = mysql_query("SELECT count(*) 
			FROM userpnrdetails AS pn, stationlist AS sl, stationlist AS sl2 
			WHERE 
			pn.src_stn=sl.station_code 
			AND pn.dest_stn=sl2.station_code 
			AND pn.doj>=CURDATE()
			AND pn.mobnum=" . $quickpnrmob
			);
$numTickets = mysql_result($query,0);

$query=mysql_query("SELECT msgfrequency FROM userlogin WHERE mobilenum=" . $quickpnrmob);
$freqval = mysql_result($query,0);
if($freqval==0){
	$smsReminder = "Turned Off";
}else{
	$smsReminder = "Turned On";
}

$query=mysql_query("SELECT count(*) FROM irctcaccounts WHERE mobilenum=" . $quickpnrmob);
if($query){
	$irctcaccounts = mysql_result($query,0);
	$irctcaccounts .= " Accounts Synced";
}
?>

<!-- Profile Details
================================================== -->
<section id="userprofile">
  
  <div class="row">
  <div class="span6">
	<div class="page-header">
    <h3>Profile</h3>
	</div>
	<table>
	<tr>
		<td><h5><b>Mobile Number</b></h5></td>
		<td style="padding-left:2em;"><h5><div id="profilemob"><?php echo $quickpnrmob; ?>&nbsp;&nbsp;<a id="changeprofilemob" style="cursor:pointer;">[Change]</a></div>
			<div id="changeMobileAlert"><?php
				if(isset($_SESSION['changeMobNumDone'])){
					echo "  <br/>Mobile Number Changed Successfully!";
					unset($_SESSION['changeMobNumDone']);
				}
				session_write_close();
			?></div>
			<div id="formprofilemob" style="display: none;"><form method="post" action="../change-mobilenum"><input type="text" id="inputprofilemob" name="inputprofilemob">&nbsp;&nbsp;<input name="saveprofilemob" id="saveprofilemob" type="submit" class="btn btn-primary" value="Save">&nbsp;&nbsp;<a id="cancelprofilemob" class="btn">Cancel</a></form></div>
		</h5></td>
	</tr>
	<tr>
		<td><h5><b>Password</b></h5></td>
		<td style="padding-left:2em;"><h5>
			<div id="profilepwd">XXXXXXX&nbsp;&nbsp;<a id="changeprofilepwd" style="cursor:pointer;">[Change]</a></div>
			<div id="changePasswordAlert"><?php 
				if(isset($_POST['saveprofilepwd'])){
					$password = $_POST['inputprofilepwd'];
					$password = $password . $dbobj->returnSalt();
					$password = hash('sha512',$password);
					$query = mysql_query("UPDATE `userlogin` SET `password` = '" . $password . "' WHERE `mobilenum`=" . $quickpnrmob);
					if($query){
						echo "  <br/>Password Changed Successfully!";
					}else{
						echo "  Please Try Again!";
					}
				}
			?>
			</div>
			<div id="formprofilepwd" style="display: none;"><form method="post" action="../user/profile"><input type="password" id="inputprofilepwd" name="inputprofilepwd" type="text">&nbsp;&nbsp;<input name="saveprofilepwd" id="saveprofilepwd" type="submit" class="btn btn-primary" value="Save">&nbsp;&nbsp;<a id="cancelprofilepwd" class="btn">Cancel</a></form></div>
		</h5></td>
	</tr>
	<tr>
		<td><h5><b>SMS Reminder</b></h5></td>
		<td style="padding-left:2em"><h5><?php echo $smsReminder; ?> <a href="../user/sms-reminder">[Change]</a></h5></td>
	</tr>
	<tr>
		<td><h5><b>Sync with IRCTC</b></h5></td>
		<td style="padding-left:2em"><h5><?php echo $irctcaccounts; ?></h5></td>
		<!-- <td><a  class="btn btn-primary">Change</a></td> -->
	</tr>
	<tr>
		<td><h5><b>Active Tickets</b></h5></td>
		<td style="padding-left:2em"><h5><?php echo $numTickets; ?> Tickets</h5></td>
		<!-- <td><a  class="btn btn-primary">Change</a></td> -->
	</tr>
  </table>
	<br>
	
  </div>
  <div class="span6">
  	<div class="page-header">
    <h3>Link Free SMS Account</h3>
	</div>
	<?php
		$query = mysql_query("SELECT count(*) FROM onesixtybytwo WHERE mobilenum=".$quickpnrmob);
		$result = mysql_result($query, 0);
		if($result==1){
	?>
		<h5>Account Linked!</h5>
	<?php }else{ ?>
	<div class="well" style="line-height:35px;">Link your 160by2 Account and get FREE SMS Reminders.
			<h5 class="displaytrainbetweenstationswell"><i class="icon-check-sign"></i> It's Absolutely Free! Fully Personalized</h5>
			<h5 class="displaytrainbetweenstationswell"><i class="icon-check-sign"></i> No more worries thinking about being spammed!</h5>
			<h5 class="displaytrainbetweenstationswell"><i class="icon-check-sign"></i> Industry Standard Encryption to link your account!</h5>
	</div>
	<table>
		<tr>
			<td>160by2 Username</td>
			<td style="padding-left:2em"><input id="onesixU" type="text" maxlength="10" placeholder="Enter 160by2 Username"></td>
		</tr>
		<tr>
			<td>160by2 Password</td>
			<td style="padding-left:2em"><input id="onesixP" type="password" placeholder="Enter 160by2 Password"></td>
		</tr>
		<tr>
			<td></td>
			<td style="padding-left:2em"><a id="onesixVA" class="btn btn-primary">Verify & Add</a></td>
		</tr>
	</table>
	<br>
	No 160by2 Account? <a data-toggle="modal" href="#myOnesixtybyTwoModal" id="mainLoginModal">Register Here</a> for a Free Account and Link it!
	<?php } ?>
	<br>
	<br>
  </div>
  </div>
</section> 

<!-- 160by2 Registration Modal -->
<div id="myOnesixtybyTwoModal" class="modal hide fade onesixtybytwo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
    <h3 id="myModalLabel">160by2 Registration</h3>
  </div>
  <div class="modal-body" style="height:510px;max-height:510px;">
    <!-- <iframe src="http://www.160by2.com/UserReg#container" style="overflow:hidden;width:790px;height:500px;"></iframe> -->
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>

<br><br><br>


<?php
include('../footer.php');
$dbobj->dbdisconnect();
?>