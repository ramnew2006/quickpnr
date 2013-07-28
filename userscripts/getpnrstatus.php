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
		echo "PNR Number: " . $_POST['pnrNum'] . "<br/><br/>";
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
	}else{
		echo "There is a problem with Indian Railway Servers!!";
	}
}
?>