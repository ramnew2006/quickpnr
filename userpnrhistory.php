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

 
<!-- PNR History
================================================== -->
<section id="pnrhistory">
  <!-- Headings & Paragraph Copy -->
<?php
	echo "<div class=\"page-header\">
    <h3>PNR History</h3>
	</div>
	<div class=\"row\">";
	
	$query = mysql_query("SELECT pn.pnrnum, sl.station_name as source, sl2.station_name as dest, pn.doj 
			FROM pnrdetails AS pn, stationlist AS sl, stationlist AS sl2 
			WHERE 
			pn.src_stn=sl.station_code 
			AND pn.dest_stn=sl2.station_code 
			AND pn.archive='N' 
			AND pn.mobnum=" . $_SESSION['userName']
			);
			
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
	while ($row = mysql_fetch_array($query)) {
		echo "<form method=\"post\" action=\"userprofile.php#pnrstatus\" onsubmit=\"return(pnrtimevalidate());\">";
		echo "<tr>";
			echo "<td>" . $j . "</td>";
			echo "<td>" . $row['pnrnum'] . "</td>";
			echo "<td>" . $row['source'] . "</td>";
			echo "<td>" . $row['dest'] . "</td>";
			$date = date_create($row['doj']);
			echo "<td>" . date_format($date, 'jS F Y') . "<br/>" . "(" . date_format($date, 'l') . ")" . "</td>";
			echo "<td>";
			echo "<input type=\"hidden\" name=\"pnrNum\" value=\"" . $row['pnrnum'] . "\">";
			echo "
			<a style=\"width:75px;margin-left:0.25em;\" href=\"#displaypnrstatus\" rel=\"tooltip\" title=\"Get PNR Status\" name=\"getPNRStatus" . $row['pnrnum'] ."\" class=\"getpnrstatus btn btn-primary\" id=\"getPNRStatus\">PNR Status</a>
			<a style=\"width:75px;margin-left:0.25em;\" id=\"getSMS\" class=\"getsms btn btn-warning\" name=\"getSMS" . $row['pnrnum'] ."\" rel=\"tooltip\" title=\"Get Message to your registered Mobile\">Get SMS</a>
			<a style=\"width:75px;margin-left:0.25em;\" data-toggle=\"modal\" href=\"#mySMSModal\" rel=\"tooltip\" title=\"Send Message to any Mobile\" id=\"sendSMS\" class=\"sendsms btn btn-info\" name=\"sendSMS" . $row['pnrnum'] ."\" value=\"" . $row['pnrnum'] . "\">Send SMS</a>
			";
			echo "</td>";
		echo "</tr>";
		echo "</form>";
		$j++;
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
    <a class="sendsmsanymobile btn btn-info" name="sendSMSanyMobile" id="sendsmsanymobile">Send SMS</a>
	<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>

<br><br><br><br>


<?php
include('footer.php');
$dbobj->dbdisconnect();
?>

</body>
</html>