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
<section id="pnrhistory">
  <!-- Headings & Paragraph Copy -->
<?php
	echo "<div class=\"page-header\">
    <h3>PNR History</h3>
	</div>
	<div class=\"row\">";
	
	$query = "SELECT pnrnum, archive, src_stn, dest_stn, doj FROM pnrdetails WHERE mobnum=" . $_SESSION['userName'];
	$result = mysql_query($query);
	$numRows = mysql_num_rows($result);
	
	echo "<table class=\"table table-bordered table-striped table-hover\">";
	echo "<thead><tr>
		<th>#</th>
		<th>PNR Number</th>
        <th>Source</th>
		<th>Destination</th>
		<th>Date of Journey</th>
		<th></th>
		</tr></thead>";
	echo "<tbody>";
	$j=1;
	while ($row = mysql_fetch_array($result)) {
		if($row['archive']=="N"){
			echo "<form method=\"post\" action=\"userprofile.php#pnrstatus\" onsubmit=\"return(pnrtimevalidate());\">";
			echo "<tr>";
				echo "<td>";
				echo $j;
				echo "</td>";
				$j++;
				for($i=0;$i<(sizeof($row)/2);$i++){
					if($i!=1){
						echo "<td>";
						if($i==4){
							$date = date_create($row[$i]);
							echo date_format($date, 'jS F Y') . "<br/>";
							echo "(" . date_format($date, 'l') . ")";
						}else{
							echo $row[$i];
						}
						echo "</td>";
					}
				}
				echo "<td>";
				echo "<input type=\"hidden\" name=\"pnrNum\" value=\"" . $row[1] . "\">";
				
				echo "
				<a href=\"#displaypnrstatus\" rel=\"tooltip\" title=\"Get PNR Status\" name=\"getPNRStatus" . $row['pnrnum'] ."\" class=\"getpnrstatus btn btn-primary\" id=\"getPNRStatus\">Get PNR Status</a>
				&nbsp;&nbsp;<a id=\"getSMS\" class=\"getsms btn btn-warning\" name=\"getSMS" . $row['pnrnum'] ."\" rel=\"tooltip\" title=\"Get Message to your registered Mobile\">Get SMS</a>
				&nbsp;&nbsp;<a data-toggle=\"modal\" href=\"#mySMSModal\" rel=\"tooltip\" title=\"Send Message to any Mobile\" id=\"sendSMS\" class=\"sendsms btn btn-info\" name=\"sendSMS" . $row['pnrnum'] ."\" value=\"" . $row['pnrnum'] . "\">Send SMS</a>
				";
				
				echo "</td>";
			echo "</tr>";
			echo "</form>";
		}else{
		}
	}
	echo "</tbody>";
	echo "</table>";
	echo "</div>";
?>

  

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