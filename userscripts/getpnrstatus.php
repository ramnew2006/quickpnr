<?php
//session_start();
set_time_limit(0);
require_once 'postcurl.php';

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
	
	if($lengthRow){
	echo "<form>";
		echo "<table style=\"width:100%;\">";
		echo "<tr>";
		echo "<td>PNR Number: " . $_POST['pnrNum'] . "</td>";
		echo "<td style=\"float:right;\">";
		echo "<input type=\"hidden\" name=\"pnrNum\" value=\"" . $_POST['pnrNum'] . "\">";
		echo "
			&nbsp;&nbsp;<a id=\"getSMS\" class=\"getsms btn btn-warning\" name=\"getSMS" . $_POST['pnrNum'] ."\" rel=\"tooltip\" title=\"Get Message to your registered Mobile\">Get SMS</a>
			&nbsp;&nbsp;<a data-toggle=\"modal\" href=\"#mySMSModal\" rel=\"tooltip\" title=\"Send Message to any Mobile\" id=\"sendSMS\" class=\"sendsms btn btn-info\" name=\"sendSMS" . $_POST['pnrNum'] ."\" value=\"" . $_POST['pnrNum'] . "\">Send SMS</a>
			";
		echo "</td>";
		echo "</tr>";
		echo "</table><br/><br/>";
		echo "<table class=\"table table-bordered table-striped table-hover\">";
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
	echo "</form>";
	}else{
		echo "There is a problem with Indian Railway Servers!!";
	}
}
?>