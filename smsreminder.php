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

 
<!-- PNR History
================================================== -->
<section id="smsreminder">
  <!-- Headings & Paragraph Copy 
-->
<div class="alert alert-block" style="line-height:30px;">
 <!-- <h4 style="line-height:30px;text-align:none;"> -->
Having a busy day? Days before your travel, we know how inconvenient it can be to constantly worry about your ticket status in the back of your mind. <br/><br/>
Now leave that worrying behind and let us help keep you updated constantly on your status. <br/>Choose your frequency preference below and we will send you SMS dynamically updating you on your PNR status.
<!--</h4>-->
</div><br/>
<div class="page-header">
    <h3>Choose Frequency</h3>
  </div>
	<!--<form>
	<input type="radio" name="automatedpnrupdatestatus" value="Y">&nbsp;Yes&nbsp;&nbsp;
	<input type="radio" name="automatedpnrupdatestatus" value="N">&nbsp;No
	</form>
	<br/>-->
	
	<div id="automatedpnrupdatefreq">
		<div class="row">
		<div class="span10">
		<table class="table table-bordered table-striped table-hover" style="width:100%;">
		<tr>
		<td><input type="radio" name="automatedpnrupdatefreq" value="1"> A message every other day</td>
		<td><input type="radio" name="automatedpnrupdatefreq" value="2"> One message every day</td>
		<td><input type="radio" name="automatedpnrupdatefreq" value="3"> Two messages every day</td>
		<td><input type="radio" name="automatedpnrupdatefreq" value="4"> Turn Off Reminder Service</td>
		</tr>
		</table>
		</div>
		<div class="span2" style="margin-top:3px;">
		<a class="btn btn-primary">Save Preferences</a>
		</div>
		</div>
		<!--<form>
		<table class="table table-bordered table-striped table-hover" style="margin-left:0px;width:auto;">
		<thead>
			<th>Daily - Messages/Day</th>
		</thead>
		<tbody>
			<tr>
				<td>
				<input type="radio" name="automatedpnrupdatefreq" value="1"> 1&nbsp;
				<input type="radio" name="automatedpnrupdatefreq" value="2"> 2&nbsp;
				<input type="radio" name="automatedpnrupdatefreq" value="3"> 3&nbsp;
				<input type="radio" name="automatedpnrupdatefreq" value="4"> 4
				</td>
			</tr>
		</tbody>
		</table>
		</form>-->
	</div> 
  

</section>

<div id="displaypnrstatus"></div>

<!-- Send SMS Modal -->
<div id="mySMSModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
    <h3 id="myModalLabel">Send PNR Status to Any Mobile</h3>
  </div>
  <div class="modal-body">
    <input type="hidden" name="currentpnr" id="currentpnr" value="">
	Mobile Number:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;+91&nbsp;&nbsp;<input type="text" name="currentmobileNum" id="currentmobileNum">
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <a class="sendsmsanymobile btn btn-info" name="sendSMSanyMobile" id="sendsmsanymobile">Send SMS</a>
  </div>
</div>

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