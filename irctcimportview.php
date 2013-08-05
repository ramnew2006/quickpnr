<?php
include('checkcookie.php');
$_SESSION['redirect_url']=$_SERVER["REQUEST_URI"];

if(!isset($_SESSION['userName'])){
	$_SESSION['redirect_url']=$_SERVER["REQUEST_URI"];
	header("Location:userlogin.php");
	exit();
}

require_once 'database.php';

$dbobj = new database();
$dbobj->dbconnect();

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
	<p style="text-align:center;">Promise!! We won't store your IRCTC username and password!</p><br/>
	<table>
	<tr>
		<!--<td>IRCTC User Name</td>-->
		<td><input type="text" name="irctcusername" id="irctcusername" placeholder="IRCTC User Name"></td>
	</tr>
	<tr>
		<!--<td>&nbsp;</td>-->
		<td>&nbsp;</td>
	</tr>
	<tr>
		<!--<td>IRCTC Password</td>-->
		<td><input type="password" name="irctcpassword" id="irctcpassword" placeholder="IRCTC Password"></td>
	</tr>
	<tr>
		<!--<td>&nbsp;</td>-->
		<td>&nbsp;</td>
	</tr>
	<tr>
		<!--<td></td>-->
		<td><a class="btn btn-primary" id="importirctchistory">Start Importing</a></td>
	</tr>
	</table>
	
  </div>
  
</section>

<br><br>

<div id="displayirctcimportstatus"></div>

<br><br>


<?php
include('footer.php');
$dbobj->dbdisconnect();
?>

</body>
</html>