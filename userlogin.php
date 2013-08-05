<?php
include('checkcookie.php');

if(isset($_SESSION['userName'])){
	header("Location:userprofile.php");
	exit();
}

require_once 'database.php';

$dbobj = new database();
$dbobj->dbconnect();

include('header.php');
?>

<?php if($_SESSION['regSuccess']=="Yes"){ ?>
	<br><br><p style="text-align:center;">Account Successfully Activated</p>
<?php } ?>
<!-- PNR Status
================================================== -->
<section id="userlogin">
  <!-- Headings & Paragraph Copy -->
<div class="page-header">
    <h3>Login To Your Account</h3>
  </div>
  <div class="row" style="margin-left:auto;">
	<p style="text-align:center;"></p><br/>
	<form method="post" action="loginaction.php">
	<table>
	<tr>
		<!--<td>IRCTC User Name</td>-->
		<td><input type="text" name="mobileNum" placeholder="Enter User Name"></td>
	</tr>
	<tr>
		<!--<td>&nbsp;</td>-->
		<td>&nbsp;</td>
	</tr>
	<tr>
		<!--<td>IRCTC Password</td>-->
		<td><input type="password" name="userPassword" placeholder="Enter Password"></td>
	</tr>
	<tr>
		<!--<td>&nbsp;</td>-->
		<td>&nbsp;</td>
	</tr>
	<tr>
		<!--<td></td>-->
		<input type="hidden" name="userLogin" value="Y">
		<td><input type="submit" class="btn btn-primary" value="Login"></td>
	</tr>
	</table>
	</form>
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

</body>
</html>