<!DOCTYPE html>
<html lang="en">
<?php
session_start();
require_once 'database.php';

$dbobj = new database();
$dbobj->dbconnect();
  if(isset($_SESSION['user'])){
	include('header.php');
?>

<!-- Profile Details
================================================== -->
<section id="userprofile">
  <div class="page-header">
    <h3>Profile</h3>
  </div>
  <div class="row" style="margin-left:auto;">
	<p>Welcome Ram!!</p>
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
	<h5>Do you want to turn on Automated PNR Status update to your Registered Mobile?</h5>
	<form>
	<input type="radio" name="automatedpnrupdatestatus" value="Y">&nbsp;Yes&nbsp;&nbsp;
	<input type="radio" name="automatedpnrupdatestatus" value="N">&nbsp;No
	</form>
	<br/>
	
	<div id="automatedpnrupdatefreq" style="display:none;">
		<h5>Frequency of Messages</h5>
		<form>
		<table class="table table-bordered table-striped table-hover" style="margin-left:0px;width:auto;">
		<thead>
			<th>Daily - Messages/Day</th>
			<th>Weekly - Messages/Week</th>
		</thead>
		<tbody>
			<tr>
				<td>
				<input type="radio" name="automatedpnrupdatefreq" value="1"> 1&nbsp;
				<input type="radio" name="automatedpnrupdatefreq" value="2"> 2&nbsp;
				<input type="radio" name="automatedpnrupdatefreq" value="3"> 3&nbsp;
				<input type="radio" name="automatedpnrupdatefreq" value="4"> 4
				</td>
				<td>
				<input type="radio" name="automatedpnrupdatefreq" value="1"> 1&nbsp;
				<input type="radio" name="automatedpnrupdatefreq" value="2"> 2&nbsp;
				<input type="radio" name="automatedpnrupdatefreq" value="3"> 3&nbsp;
				<input type="radio" name="automatedpnrupdatefreq" value="4"> 4
				</td>
			</tr>
		</tbody>
		</table>
		</form>
	</div>
  </div>
</section>  

<br><br><br><br>


<?php
}else{
	header("Location:userlogin.php");
	$_SESSION['redirect_url']=$_SERVER["REQUEST_URI"];
}
include('footer.php');
$dbobj->dbdisconnect();
?>

</body>
</html>