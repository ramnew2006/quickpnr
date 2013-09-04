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
?>

<!-- Import From IRCTC
================================================== -->
<section id="irctcimport">
  <!-- Headings & Paragraph Copy -->
<div class="page-header">
  <h3>Sync Your IRCTC Account</h3>
  </div>
  
  <div class="row" style="margin-left:0;">
	<div class="span6" style="margin-left:0;margin-bottom:2em;">
	  <h4>Synced Accounts</h4><br/>
	  <?php 
		$query = mysql_query("SELECT * FROM irctcaccounts WHERE mobilenum=" . $quickpnrmob);
		if(mysql_num_rows($query)>=1){
			echo "<table class=\"irctcsyncedaccounts\">
				<tbody>";
			$i=1;
			while($row = mysql_fetch_array($query)){
				echo "<tr>";
					echo "<td>" . $row['username'] . "</td>";
					echo "<td style=\"padding-left:1em;\"><a class=\"irctcsyncnowbtn btn btn-primary\" name=\"" . $row['username'] . "\">Sync Now</a>
					&nbsp;<a class=\"irctcremovebtn btn btn-danger\" name=\"" . $row['username'] . "\">Remove</a></td>";
					echo "<td><i>&nbsp;Last synced on " . $row['updatetime'] . "</i></td>";
				echo "</tr>";
				$i++;
			}
			echo "</tbody>
				</table>";
		}else{
			echo "No Accounts are Synced! Please go ahead Sync your IRCTC accounts!<br><br>";
		}
	  ?>
	  <br><br>
	  <div id="displayirctcimportbtnstatus"></div>
	</div>
	<div class="span5">
	  <h4>Sync New Account</h4>
		<!--<p style="text-align:center;">Promise!! We won't store your IRCTC username and password!</p>--><br/>
		<table>
		<tr>
			<td>IRCTC User Name</td>
			<td style="padding-left:2em;"><input type="text" name="irctcusername" id="irctcusername" placeholder="IRCTC User Name"></td>
		</tr>
		<tr>
			<td>IRCTC Password</td>
			<td style="padding-left:2em;"><input type="password" name="irctcpassword" id="irctcpassword" placeholder="IRCTC Password"></td>
		</tr>
		<tr>
			<td></td>
			<td style="padding-left:2em;"><a class="btn btn-primary" id="importirctchistory">Add and Sync</a></td>
		</tr>
		</table>
		<br><br>
		<div id="displayirctcimportstatus"></div>
	</div>
	
  </div>
  
</section>



<br><br>


<?php
include('../footer.php');
$dbobj->dbdisconnect();
?>