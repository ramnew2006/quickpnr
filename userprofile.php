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
      <li><a href="#profiledetails">Profile</a></li>
	  <li><a href="#pnrhistory">PNR History</a></li>
	  <?php if(isset($_POST['getStatus'])){ ?>
      <li><a href="#pnrstatus">PNR Status</a></li>
	  <?php }?>
      <li><a href="#forms">Forms</a></li>
      <li><a href="#tables">Tables</a></li>
      <li><a href="#miscellaneous">Miscellaneous</a></li>
    </ul>
  </div>

<section id="profiledetails">
  <div class="page-header">
    <h3>Profile</h3>
  </div>
  <div class="row" style="margin-left:auto;">
	<p>Welcome Ram!!</p>
	<table>
	<tr>
		<td><h4>Mobile Number</h4></td>
		<td style="padding-left:2em;"><h5><div id="profilemob"><?php echo $_SESSION['userName']; ?>&nbsp;&nbsp;<a id="changeprofilemob">[Change]</a></div><div id="formprofilemob" style="display: none;"><form><input type="text" value="<?php echo $_SESSION['userName']; ?>">&nbsp;&nbsp;<input type="submit" class="btn btn-primary" value="Save">&nbsp;&nbsp;<a id="cancelprofilemob" class="btn btn-primary">Cancel</a></form></div></h5></td>
		<!--<td>&nbsp;&nbsp;<a id="changeprofilemob" class="btn btn-warning">Change</a></td>-->
	</tr>
	<tr>
		<td><h4>Password</h4></td>
		<td style="padding-left:2em;"><h5><div id="profilepwd">XXXXXXX&nbsp;&nbsp;<a id="changeprofilepwd">[Change]</a></div><div id="formprofilepwd" style="display: none;"><form><input type="text">&nbsp;&nbsp;<input type="submit" class="btn btn-primary" value="Save">&nbsp;&nbsp;<a id="cancelprofilepwd" class="btn btn-primary">Cancel</a></form></div></h5></td>
	</tr>
	</table>
  </div>
</section>  

<!-- PNR History
================================================== -->
<section id="pnrhistory">
  <!-- Headings & Paragraph Copy -->
<?php
	echo "<div class=\"page-header\">
    <h3>PNR History</h3>
	</div>
	<div class=\"row\">";
	
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
		<th>Status</th>
		</tr></thead>";
	echo "<tbody>";
	while ($row = mysql_fetch_array($result)) {
		echo "<form method=\"post\" action=\"userprofile.php#pnrstatus\" onsubmit=\"return(pnrtimevalidate());\">";
		echo "<tr>";
		for($i=0;$i<(sizeof($row)/2);$i++){
			echo "<td>";
			echo $row[$i];
			echo "</td>";
		}
		echo "<td>";
		echo "<input type=\"hidden\" name=\"pnrNum\" value=\"" . $row[1] . "\">";
		if($row['archive']=="N"){
			echo "
			<a rel=\"tooltip\" title=\"Get PNR Status\"><input class=\"btn btn-warning\" type=\"submit\" name=\"getStatus\" id=\"getStatus\" value=\"Get Status\"></a>
			&nbsp;&nbsp;<a id=\"getSMS\" class=\"getsms btn btn-inverse\" name=\"getSMS" . $row['pnrnum'] ."\" rel=\"tooltip\" title=\"Get Message to your registered Mobile\">Get SMS</a>
			&nbsp;&nbsp;<a data-toggle=\"modal\" href=\"#myModal\" rel=\"tooltip\" title=\"Send Message to any Mobile\" id=\"sendSMS\" class=\"sendsms btn btn-primary\" name=\"sendSMS" . $row['pnrnum'] ."\" value=\"" . $row['pnrnum'] . "\">Send SMS</a>
			";
		}else{
		}
		echo "</td>";
		echo "</tr>";
		echo "</form>";
	}
	echo "</tbody>";
	echo "</table>";
	echo "</div>";
}else{
	header("Location:userlogin.php");
	$_SESSION['redirect_url']=$_SERVER["REQUEST_URI"];
}

?>

  

</section>
<!-- Button to trigger modal 
<a id="idmyModal" href="#myModal" role="button" class="btn" data-toggle="modal" value="1234">Launch demo modal</a>-->
 
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
    <a class="btn btn-primary" name="sendSMSanyMobile" id="sendSMSanyMobile">Send SMS</a>
  </div>
</div>

<!-- PNR Status
================================================== -->
<section id="pnrstatus">
  

  <!-- Headings & Paragraph Copy -->
  

<?php

if(isset($_POST['getStatus'])){

	echo "<div class=\"page-header\">
    <h3>PNR Status</h3>
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
    <h3>PNR Status</h3>
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

<!-- PNR History
================================================== -->
<section id="pnrhistory">
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
  <br><br>
  
  <div id="irctcimportprogress" style="display:none;">
	<div class="progress progress-info progress-striped active">
        <div id="progressbar" class="bar" style="width: 0%;"></div>
     </div>
	</div>

</section>
<div id="displayresult"></div>

<br><br><br><br>


<?php
include('footer.php');
$dbobj->dbdisconnect();
?>

</body>
</html>