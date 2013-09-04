<?php
include('../checkcookie.php');
$_SESSION['redirect_url']=$_SERVER["REQUEST_URI"];

require_once '../database.php';

$dbobj = new database();
$dbobj->dbconnect();

include('../header.php');
?>

<!-- PNR History
================================================== -->
<section id="smsreminder">
  <!-- Headings & Paragraph Copy 
-->



<?php
if(!isset($_SESSION['userName'])){
?>
	<div class="well" style="line-height:30px;">
	<p style="line-height:30px;text-align:none;">
	Having a busy day? Days before your travel, we know how inconvenient it can be to constantly worry about your ticket status in the back of your mind. <br/><br/>
	Now leave that worrying behind and let us help keep you updated constantly on your status. <br/>Register Now and get the updates starting today! For Free!
	</p>
	</div><br/>
	<p style="text-align:center;padding-top:1em;">
		<a data-toggle="modal" href="#myRegisterModal" class="btn btn-primary">Register Now!</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a data-toggle="modal" href="#myLoginModal" class="btn btn-primary">Login</a>
	</p>
<?php
}else{
	$quickpnrmob = $_SESSION['userName'];
	session_write_close();
?>
	<div class="well" style="line-height:30px;">
	<p style="line-height:30px;text-align:none;">
	Having a busy day? Days before your travel, we know how inconvenient it can be to constantly worry about your ticket status in the back of your mind. <br/><br/>
	Now leave that worrying behind and let us help keep you updated constantly on your status. <br>Choose your frequency preference below and we will send you SMS dynamically updating you on your PNR status.
	</p>
	</div><br/>
<?php
	echo "<div class=\"page-header\">
		<h3>Choose Frequency</h3>
		</div>";
	if(isset($_POST['savePref'])){
		if(isset($_POST['automatedpnrupdatefreq'])){
			$freq = $_POST['automatedpnrupdatefreq'];
			$query = mysql_query("UPDATE userlogin SET msgfrequency='" . $freq . "' WHERE mobilenum=" . $quickpnrmob);
			if($query){
				$output = "Preferences Saved successfully";
			}else{
				$output = "There is some problem while saving the preferences!";
			}
		}
	}

	$query=mysql_query("SELECT msgfrequency FROM userlogin WHERE mobilenum=" . $quickpnrmob);
	$freqval = mysql_result($query,0);
?>  
  <form method="post" action="sms-reminder">
	<div id="automatedpnrupdatefreq">
		<div class="row">
		<div class="span10">
		<table class="table table-bordered table-striped table-hover smsremindertable" style="width:100%;">
		<tr>
		<td><input type="radio" name="automatedpnrupdatefreq" value="3" <?php if($freqval==3){echo "checked";} ?>> A message every other day</td>
		<td><input type="radio" name="automatedpnrupdatefreq" value="1" <?php if($freqval==1){echo "checked";} ?>> One message every day</td>
		<td><input type="radio" name="automatedpnrupdatefreq" value="2" <?php if($freqval==2){echo "checked";} ?>> Two messages every day</td>
		<td><input type="radio" name="automatedpnrupdatefreq" value="0" <?php if($freqval==0){echo "checked";} ?>> Turn Off Reminder Service</td>
		</tr>
		</table>
		</div>
		<div class="span2" style="margin-top:3px;">
		<input type="submit" name="savePref" class="btn btn-primary" value="Save Preferences">
		</div>
		</div>
	</div>
  </form>	
<?php } ?>

</section>

<div>
<?php
if(isset($output)){
	echo $output;
}
?>
</div>

<br><br><br><br>

<?php
include('../footer.php');
$dbobj->dbdisconnect();
?>