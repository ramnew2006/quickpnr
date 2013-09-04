<?php
//session_start();
set_time_limit(0);
require_once '../userscripts/postcurl.php';
require_once '../database.php';

$dbobj = new database();
$dbobj->dbconnect();

if(isset($_POST['pnrNum'])){
	$url = "http://www.indianrail.gov.in/cgi_bin/inet_pnrstat_cgi.cgi";
	$tablenum = 25;
	$postparams = "lccp_pnrno1=" . $_POST['pnrNum'] . "&submit=Wait+For+PNR+Enquiry%21";
	
	$postobj = new postcurl($url,$tablenum,$postparams);
	$lengthRow = $postobj->tableRows();
	
	$traintable = 23;
	$trainobj = new postcurl($url,$traintable,$postparams);
	$trainlengthRow = $trainobj->tableRows();
	
	if($lengthRow==1){
		$tablenum = 26;
		$postobj = new postcurl($url,$tablenum,$postparams);
		$lengthRow = $postobj->tableRows();
	}
	
	
	if($lengthRow){
		if($trainlengthRow){
			echo "<h4>PNR Number: " . $_POST['pnrNum'] . "</h4>";	
			if($_SERVER['REQUEST_URI']=="/user/pnr-status"){
				echo "<div class=\"well\">Get SMS updates of this PNR Number daily to your Mobile number OR Send the PNR Status to your friend's Mobile Number<br/><br/>";
				echo "Mobile Number: <input type=\"text\" maxsize=\"10\">&nbsp;&nbsp;&nbsp;<input title=\"Get SMS updates of this PNR to your Mobile\" type=\"submit\" value=\"Get SMS Updates\" class=\"btn btn-warning\">
					&nbsp;&nbsp;<a data-toggle=\"modal\" href=\"#mySMSModal\" rel=\"tooltip\" title=\"Send Message to any Mobile\" id=\"sendSMS\" class=\"sendsms btn btn-info\" name=\"sendSMS" . $_POST['pnrNum'] ."\" value=\"" . $_POST['pnrNum'] . "\">Send SMS</a>
					</div>";
			}
			
			echo "<h5>Train Details</h5>";
			echo "<table class=\"table table-bordered table-striped table-hover\" style=\"margin-left:0;\">";
			for($i=0;$i<$trainlengthRow;$i++){
				if($i==1){
					echo "<thead>";
				}elseif($i==2){
					echo "</thead>";
					echo "<tbody>";
				}
				$trainlengthCol = 8;//$postobj->tableColumns($i);
				echo "<tr>";
				for($j=0;$j<$trainlengthCol;$j++){
					if($j==5 || $j==6){
					}else{
						if($i==1){
							echo "<th>" . $trainobj->getInfoFromRow($i,$j) . "</th>";
						}
						if($i==2){
							if($j==3 || $j==4){
								$station_code = trim($trainobj->getInfoFromRow($i,$j));
								$query = mysql_query("SELECT station_name FROM stationlist WHERE station_code='" . $station_code . "'");
								echo "<td>" . mysql_result($query,0) . "</td>";
							}else{
								echo "<td>" . $trainobj->getInfoFromRow($i,$j) . "</td>";
							}
						}
					}
				}
				echo "</tr>";
			}
			echo "</tbody>";
			echo "</table>";
		}
		echo "<h5>PNR Status</h5>";
		echo "<table class=\"table table-bordered table-striped table-hover\" style=\"margin-left:0;\">";
		for($i=0;$i<$lengthRow-1;$i++){
			if($i==0){
				echo "<thead>";
			}else{
				echo "</thead>";
				echo "<tbody>";
			}
			$lengthCol = 3;//$postobj->tableColumns($i);
			echo "<tr>";
			for($j=0;$j<$lengthCol;$j++){
				if($i==0){
					echo "<th>" . $postobj->getInfoFromRow($i,$j) . "</th>";
				}else{
					echo "<td>" . $postobj->getInfoFromRow($i,$j) . "</td>";
				}
			}
			echo "</tr>";
		}
		echo "</tbody>";
		echo "</table>";
	}else{
		echo "There is a problem with Indian Railway Servers!!";
	}
}

$dbobj->dbdisconnect();
?>