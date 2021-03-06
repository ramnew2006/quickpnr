<?php
require_once '../userscripts/postcurl.php';
require_once '../database.php';

$dbobj = new database();
$dbobj->dbconnect();

if(isset($_POST['getSeatAvailability'])){
	if(isset($_POST['travelDetails'])){
		$travel = explode('_',$_POST['travelDetails']);
		$train_num = $travel[0];
		$day_travel = $travel[1];
		$month_travel = $travel[2];
		$source_stn = $travel[3];
		$dest_stn = $travel[4];
		$class_travel = $travel[5];
		$quota_travel = "GN";
		$submit = "Please Wait...";
		
		$query=mysql_query("SELECT train_name FROM traininfo WHERE train_num=".$train_num);
		echo "<div class=\"row\">";
		echo "<div class=\"page-header\" style=\"margin-bottom:0;padding-bottom:0;margin-left:auto;margin-top:0;\">
		    <h4>#" . $train_num . " - " . mysql_result($query,0) . " - " . $class_travel . "</h4>
			</div>";
		
		$url = "http://www.indianrail.gov.in/cgi_bin/inet_frenq_cgi.cgi";
		$tablenum = 25;
		$postparams = "lccp_trnno=" . $train_num . "&lccp_day=" . $day_travel . "&lccp_month=" . $month_travel . "&lccp_srccode=" . $source_stn . "&lccp_dstncode=" . $dest_stn . "&lccp_classopt=" . $class_travel . "&lccp_age=30&lccp_conc=ZZZZZZ&lccp_enrtcode=NONE&lccp_viacode=NONE&lccp_frclass1=ZZ&lccp_frclass2=ZZ&lccp_frclass3=ZZ&lccp_frclass4=ZZ&lccp_frclass5=ZZ&lccp_frclass6=ZZ&lccp_frclass7=ZZ&lccp_disp_avl_flg=1&getIt=" . $submit; 

		echo "<div class=\"span3\">";
		echo "<h4><b>Ticket Fare</b></h4>";
		$postobj = new postcurl($url,$tablenum,$postparams);
		$lengthRow = $postobj->tableRows();
		
		if($lengthRow){
			if($lengthRow==2){
				echo "<br/><div class=\"well\">Apologies!! This train might not be running on this day! Please check other trains!</div>";
				echo "</div>";

				echo "<div class=\"span4\">";
				echo "<h4><b>Availability</b></h4>";
				echo "<br/><div class=\"well\">Apologies!! This train might not be running on this day! Please check other trains!</div>";
				echo "</div>";
			}else{
				echo "<table class=\"table table-bordered table-striped table-hover\" style=\"width:auto;margin-left:0;\">";
				for($i=1;$i<7;$i++){
					$lengthCol = $postobj->tableColumns($i);
					echo "<tr>";
					for($j=0;$j<$lengthCol;$j++){
						echo "<td>" . $postobj->getInfoFromRow($i,$j) . "</td>";
						//if($j%4==3){
						//}
					}
					echo "</tr>";
				}
				echo "</table>";
				echo "</div>";

				echo "<div class=\"span4\">";
				echo "<h4><b>Availability</b></h4>";
				$url = "http://www.indianrail.gov.in/cgi_bin/inet_accavl_cgi.cgi";
				$tablenum = 24;
				$postparams = "lccp_trnno=" . $train_num . "&lccp_day=" . $day_travel . "&lccp_month=" . $month_travel . "&lccp_srccode=" . $source_stn . "&lccp_dstncode=" . $dest_stn . "&lccp_class1=" . $class_travel . "&lccp_quota=" . $quota_travel . "&lccp_classopt=ZZ&lccp_class2=ZZ&lccp_class3=ZZ&lccp_class4=ZZ&lccp_class5=ZZ&lccp_class6=ZZ&lccp_class7=ZZ&lccp_class6=ZZ&lccp_class7=ZZ&submit=" . $submit; 

				$postobj = new postcurl($url,$tablenum,$postparams);
				$i=0;
				$lengthCol = $postobj->tableColumns($i);
				
				if($lengthCol==1){
					echo "<br/><div class=\"well\">There is a problem with Indian Railways Servers. Please Try again!</div>";
				}else{
					echo "<table class=\"table table-bordered table-striped table-hover\" style=\"width:auto;margin-left:0;\">";
					echo "<tr>";
					for($j=0;$j<$lengthCol;$j++){
						if($j%4==0){

						}else{
							if($j%4==1){
								echo "<td>" . preg_replace('/\s+/','',$postobj->getInfoFromRow($i,$j)) . "</td>";
							}else{
								echo "<td>" . $postobj->getInfoFromRow($i,$j) . "</td>";
							}
							if($j%4==3){
								echo "</tr><tr>";
							}
						}
					}
					echo "</table>";
				}
				echo "</div>";
			}
		}
		
		echo "</div>";
		echo "<br/>";
	}
}

$dbobj->dbdisconnect();
?>