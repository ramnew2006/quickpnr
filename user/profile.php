<?php
include('../checkcookie.php');
$_SESSION['redirect_url']=$_SERVER["REQUEST_URI"];

if(!isset($_SESSION['userName'])){
	$_SESSION['redirect_url']=$_SERVER["REQUEST_URI"];
	header("Location:../user/login.php");
	exit();
}

require_once '../database.php';

$dbobj = new database();
$dbobj->dbconnect();

include('../header.php');
?>

<!-- Profile Details
================================================== -->
<section id="userprofile">
  
  <div class="row">
  <div class="span6">
	<div class="page-header">
    <h3>Profile</h3>
	</div>
	<h5>Welcome <?php echo $_SESSION['userName']; ?></h5>
	<table>
	<tr>
		<td><h5>Mobile Number</h5></td>
		<td style="padding-left:2em;"><h5><div id="profilemob"><?php echo $_SESSION['userName']; ?>&nbsp;&nbsp;<!--<a id="changeprofilemob">[Change]</a></div><div id="formprofilemob" style="display: none;"><input id="inputprofilemob" type="text" value="<?php //echo $_SESSION['userName']; ?>">&nbsp;&nbsp;<input id="saveprofilemob" type="submit" class="btn btn-primary" value="Save">&nbsp;&nbsp;<a id="cancelprofilemob" class="btn btn-primary">Cancel</a></div>--></h5></td>
		<!--<td>&nbsp;&nbsp;<a id="changeprofilemob" class="btn btn-warning">Change</a></td>-->
	</tr>
	<tr>
		<td><h5>Password</h5></td>
		<td style="padding-left:2em;"><h5>
			<div id="profilepwd">XXXXXXX&nbsp;&nbsp;<a id="changeprofilepwd">[Change]</a>
			<?php 
				if(isset($_POST['saveprofilepwd'])){
					$password = $_POST['inputprofilepwd'];
					$password = $password . $dbobj->returnSalt();
					$password = hash('sha512',$password);
					$query = mysql_query("UPDATE `userlogin` SET `password` = '" . $password . "' WHERE `mobilenum`=" . $_SESSION['userName']);
					if($query){
						echo "  Password Changed Successfully!";
					}else{
						echo "  Please Try Again!";
					}
				}
			?>
			</div>
			<div id="formprofilepwd" style="display: none;"><form method="post" action="userprofile.php"><input type="password" id="inputprofilepwd" name="inputprofilepwd" type="text">&nbsp;&nbsp;<input name="saveprofilepwd" id="saveprofilepwd" type="submit" class="btn btn-primary" value="Save">&nbsp;&nbsp;<a id="cancelprofilepwd" class="btn btn-primary">Cancel</a></form></div>
		</h5></td>
	</tr>
	</table>
	<br>
  </div>
  <div class="span6">
	<div class="page-header">
    <h3>Active Services</h3>
	</div>
	<table>
	<tr>
		<td><a href="smsreminder.php" class="btn btn-warning" style="width:8em">SMS Reminder</a></td>
		<td style="padding-left:2em;"><a href="">[Change]</a></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td><a href="smsreminder.php" class="btn btn-primary" style="width:8em">Sync with IRCTC</a></td>
		<td style="padding-left:2em;"><a href="">[Change]</a></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td><a href="smsreminder.php" class="btn btn-success" style="width:8em">Active Tickets</a></td>
		<td style="padding-left:2em;"><a href="">[Change]</a></td>
	</tr>
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