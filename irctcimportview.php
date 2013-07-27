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
  

<!-- Import From IRCTC
================================================== -->
<section id="irctcimport">
  <!-- Headings & Paragraph Copy -->
<div class="page-header">
    <h3>Import Booking History From IRCTC</h3>
  </div>
  <div class="row" style="margin-left:auto;">
	<p>Enter your IRCTC user name and password and start importing your booking history at a go.</p>
	<table>
	<tr>
		<td>IRCTC User Name</td>
		<td style="padding-left:2em;"><input type="text" name="irctcusername" id="irctcusername"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>IRCTC Password</td>
		<td style="padding-left:2em;"><input type="password" name="irctcpassword" id="irctcpassword"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td></td>
		<td style="padding-left:2em;"><a class="btn btn-primary" id="importirctchistory">Start Importing</a></td>
	</tr>
	</table>
	
  </div>
  
</section>

<br><br>

<div id="displayirctcimportstatus"></div>

<br><br>


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