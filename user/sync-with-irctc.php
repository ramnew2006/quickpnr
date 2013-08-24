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

<!-- Import From IRCTC
================================================== -->
<section id="irctcimport">
  <!-- Headings & Paragraph Copy -->
<div class="page-header">
  <h3>Sync Your IRCTC Account</h3>
  </div>
  
  <div class="row" style="margin-left:auto;">
	<div class="span5">
	  <h4>Synced Accounts</h4><br/>
	  <?php 
		$query = mysql_query("SELECT * FROM irctcaccounts WHERE mobilenum=" . $_SESSION['userName']);
		if(mysql_num_rows($query)>=1){
			echo "<table>
				<tbody>";
			$i=1;
			while($row = mysql_fetch_array($query)){
				echo "<tr>";
					echo "<td>" . $row['username'] . "</td>";
					echo "<td style=\"padding-left:2em;\"><a class=\"btn btn-primary\">Sync</a></td>";
					echo "<td style=\"padding-left:0.5em;\"><a class=\"btn btn-danger\">Remove</a></td>";
				echo "</tr>";
				$i++;
			}
			echo "</tbody>
				</table>";
		}
	  ?>
	</div>
	<div class="span5">
	  <h4>Sync New Account</h4>
		<!--<p style="text-align:center;">Promise!! We won't store your IRCTC username and password!</p>--><br/>
		<table>
		<tr>
			<td>IRCTC User Name</td>
			<td><input type="text" name="irctcusername" id="irctcusername" placeholder="IRCTC User Name"></td>
		</tr>
		<tr>
			<!--<td>&nbsp;</td>-->
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>IRCTC Password</td>
			<td><input type="password" name="irctcpassword" id="irctcpassword" placeholder="IRCTC Password"></td>
		</tr>
		<tr>
			<!--<td>&nbsp;</td>-->
			<td>&nbsp;</td>
		</tr>
		<tr>
			<!--<td></td>-->
			<td><a class="btn btn-primary" id="importirctchistory">Add and Sync</a></td>
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