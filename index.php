<?php
include('checkcookie.php');

if(isset($_SESSION['userName'])){
	header("Location:/user/profile");
}

require_once 'database.php';

$dbobj = new database();
$dbobj->dbconnect();

include('header.php');
?>
  
<!-- PNR Status
================================================== -->
<section id="pnrstatus">
	 <!-- Headings & Paragraph Copy -->
<div class="row">
 <div class="span6">
 	<div class="page-header">
    <h3>PNR Status</h3>
	</div>
	<div class="row" style="margin-left:auto;">
		<table>
			<tr>
				<td><input id="displaypnrstatusinput" name="displaypnrstatusinput" type="text" placeholder="Enter PNR Number" maxlength="10" autocomplete="on"></td>
				<td style="padding-left:2em;">
					<a id="displaypnrstatusinputgetstatus" class="btn btn-primary">Get Status</a>
				</td>
			</tr>
		</table><br><br>
		<div id="displaypnrstatus"></div>
	</div>
 </div>
 <div class="span6">
	<div class="row" style="margin-left:auto;">
		<div class="well" style="line-height:35px;margin-left:auto;margin-right:auto;">
		<h4 style="font-weight:bold;">SMS Reminder</h4>
		<p>Frustrated to check your PNR Status again and again? Don't worry we will keep you updated daily about your PNR Status through <b>SMS!</b> For <b>FREE!</b></p>
		<h4 style="font-weight:bold;">Sync with IRCTC</h4>
		<p>Now sync your IRCTC account and keep getting PNR Status of all your tickets to your registered mobile through <b>SMS!</b> For <b>FREE!</b></p>
		<p style="text-align:center;padding-top:1em;">
		<a data-toggle="modal" href="#myRegisterModal" class="btn btn-primary">Register Now!</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a data-toggle="modal" href="#myLoginModal" class="btn btn-primary">Login</a>
		</div>
	</div>
 </div>
</div>
</section>

<!-- Send SMS Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
include('footer.php');
$dbobj->dbdisconnect();
?>
