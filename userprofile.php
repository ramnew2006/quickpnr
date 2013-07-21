<!DOCTYPE html>
<html lang="en">
<?php
session_start();
require_once 'database.php';
require_once 'postcurl.php';
require_once 'sendsms.php';

$dbobj = new database();
$dbobj->dbconnect();
  if(isset($_SESSION['user'])){
	include('header.php');
?>

    <div class="container">


<!-- Masthead
================================================== -->
  
  <div class="subnav">
    <ul class="nav nav-pills">
      <li><a href="#pnrhistory">PNR History</a></li>
      <li><a href="#pnrstatus">PNR Status</a></li>
      <li><a href="#buttons">Buttons</a></li>
      <li><a href="#forms">Forms</a></li>
      <li><a href="#tables">Tables</a></li>
      <li><a href="#miscellaneous">Miscellaneous</a></li>
    </ul>
  </div>

  

<!-- PNR History
================================================== -->
<section id="pnrhistory">
  <div class="page-header">
    <h1>PNR History</h1>
  </div>

  <!-- Headings & Paragraph Copy -->
  <div class="row">

<?php
	$query = "SELECT * FROM pnrdetails WHERE mobnum=" . $_SESSION['userName'];
	$result = mysql_query($query);
	$numRows = mysql_num_rows($result);
	
	echo "<table class=\"table table-bordered table-striped table-hover\">";
	echo "<thead><tr>
		<th>#</th>
		<th>PNR Number</th>
        <th>Mobile Number</th>
        <th>Archived</th>
		<th>Source</th>
		<th>Destination</th>
		<th>Date of Journey</th>
		<th></th>
		</tr></thead>";
	echo "<tbody>";
	while ($row = mysql_fetch_array($result)) {
		//echo "<form method=\"post\" action=\"userprofile.php#pnrstatus\">";
		echo "<tr>";
		for($i=0;$i<(sizeof($row)/2);$i++){
			echo "<td>";
			echo $row[$i];
			echo "</td>";
		}
		echo "<td>";
		echo "<input type=\"hidden\" name=\"pnrNum\" value=\"" . $row[1] . "\">";
		if($row['archive']=="N"){
			echo "<input class=\"btn btn-primary\" type=\"submit\" name=\"getStatus\" id=\"getStatus\" value=\"Get Status\">&nbsp;&nbsp;<input id=\"sendSMS\" class=\"sendsms btn btn-primary\" type=\"submit\" name=\"sendSMS" . $row['pnrnum'] ."\" value=\"Send SMS\">";
		}
		echo "</td>";
		echo "</tr>";
		//echo "</form>";
	}
	echo "</tbody>";
	echo "</table>";
}else{
	header("Location:userlogin.php");
	$_SESSION['redirect_url']=$_SERVER["REQUEST_URI"];
}

?>

  </div>

</section>


<!-- PNR Status
================================================== -->
<section id="pnrstatus">
  

  <!-- Headings & Paragraph Copy -->
  

<?php

if(isset($_POST['getStatus'])){

	echo "<div class=\"page-header\">
    <h1>PNR Status</h1>
	</div>
	<div class=\"row\">";
	
	if(isset($_POST['pnrNum'])){
		$url = "http://www.indianrail.gov.in/cgi_bin/inet_pnrstat_cgi.cgi";
		$tablenum = 25;
		$postparams = "lccp_pnrno1=" . $_POST['pnrNum'] . "&submit=Wait+For+PNR+Enquiry%21";
		
		$postobj = new postcurl($url,$tablenum,$postparams);
		$lengthRow = $postobj->tableRows();
		
		if($lengthRow==1){
			$tablenum = 26;
			$postobj = new postcurl($url,$tablenum,$postparams);
			$lengthRow = $postobj->tableRows();
		}
		
		echo "<table class=\"table table-bordered table-striped table-hover\">";
		echo "<tbody>";
		for($i=0;$i<$lengthRow-1;$i++){
			$lengthCol = 3;//$postobj->tableColumns($i);
			echo "<tr>";
			for($j=0;$j<$lengthCol;$j++){
				if($i==0){
					echo "<th>" . $postobj->getInfoFromRow($i,$j) . "</th>";
				}else{
					echo "<td>" . $postobj->getInfoFromRow($i,$j) . "</td>";
				}
				// if($j%4==3){
					// echo "<br/>";
				// }
			}
			echo "</tr>";
		}
		echo "</tbody>";
		echo "</table><br/>";
		echo "</div>";
	}
}

if(isset($_POST['sendSMS'])){

	echo "<div class=\"page-header\">
    <h1>PNR Status</h1>
	</div>
	<div class=\"row\">";
	
	if(isset($_POST['pnrNum'])){
		$url = "http://www.indianrail.gov.in/cgi_bin/inet_pnrstat_cgi.cgi";
		$tablenum = 25;
		$postparams = "lccp_pnrno1=" . $_POST['pnrNum'] . "&submit=Wait+For+PNR+Enquiry%21";
		
		$postobj = new postcurl($url,$tablenum,$postparams);
		$lengthRow = $postobj->tableRows();
		
		if($lengthRow==1){
			$tablenum = 26;
			$postobj = new postcurl($url,$tablenum,$postparams);
			$lengthRow = $postobj->tableRows();
		}
		
		$message = "PNR: " . $_POST['pnrNum'] . "%0aSNo.  B.Status  C.Status%0a";

		for($i=1;$i<$lengthRow-1;$i++){
			$lengthCol = $postobj->tableColumns($i);
			for($j=0;$j<$lengthCol;$j++){
				$temp = $postobj->getInfoFromRow($i,$j);
				$temp = preg_replace('/Passenger /','P',$temp);
				if($j==1 && ($i!=$lengthRow-2)){
					$temp = preg_replace('/ /','',$temp);
				}
				if(($i==$lengthRow-2)&&($j==0)){
					
				}else{
					$message .= trim($temp) . "  ";
				}
			}
			//$message = trim($message);
			if($i!=$lengthRow-2){
				$message .= "%0a";
			}
		}
		
		//echo $message;
		$smsobj = new sendSMS($_SESSION['userName'],$message);
		if($smsobj){
			echo "Message successfully sent";
		}else{
			echo "Something is broke. We regret!";
		}
	}
	echo "</div>";
}

?>



</section>
<div id="displayresult"></div>

<br><br><br><br>


<?php
include('footer.php');
$dbobj->dbdisconnect();
?>
  <script type="text/javascript">
  $(".sendsms").on("click",function() {
	var pnrnum = $(this).attr('name');
	pnrnum = pnrnum.match(/[0-9]+/);
	  alert("You are going to send the SMS for "+ pnrnum);
	  $.ajax({
			type: "POST",
			url: "messagesend.php",
			data: "pnrNum="+pnrnum ,
			success: function(html){
			$("#displayresult").html(html).show();
			}
		});
	});
  </script>
  </body>
</html>