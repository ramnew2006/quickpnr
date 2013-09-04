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
		<td style="padding-left:2em;"><h5><div id="profilemob"><?php echo $quickpnrmob; ?>&nbsp;&nbsp;<a id="changeprofilemob">[Change]</a></div>
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
			<div id="profilepwd">XXXXXXX&nbsp;&nbsp;<a id="changeprofilepwd">[Change]</a></div>
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
	</table>
	<br>
  </div>
  <div class="span6">
	<div class="page-header">
    <h3>Active Services</h3>
	</div>
	<table class="table table-bordered table-striped table-hover">
	<thead></thead>
	<tbody>
		<tr>
			<td><!--<a class="btn" style="width:8em">SMS Reminder</a>--><h5><b>SMS Reminder</b></h5></td>
			<td><h5><?php echo $smsReminder; ?></h5></td>
			<td><a class="btn btn-primary" href="../user/sms-reminder">Change</a></td>
		</tr>
		<tr>
			<td><h5><b>Sync with IRCTC<!--<a class="btn btn-primary" style="width:8em">Sync with IRCTC</a>--></b></h5></td>
			<td><h5><?php echo $irctcaccounts; ?></h5></td>
			<td><a href="../user/sync-with-irctc" class="btn btn-primary">Change</a></td>
		</tr>
		<tr>
			<td><h5><b>Active Tickets<!--<a class="btn btn-success" style="width:8em">Active Tickets</a>--></b></h5></td>
			<td><h5><?php echo $numTickets; ?> Tickets</h5></td>
			<td><a href="../user/pnr-history" class="btn btn-primary">Change</a></td>
		</tr>
	</tbody>
	</table>
	<br>
	<br>
  </div>
  </div>
</section> 

<br><br><br><br>


<?php
include('../footer.php');
$dbobj->dbdisconnect();
?>