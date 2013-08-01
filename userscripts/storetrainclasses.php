<?php
set_time_limit(0);
require_once 'database.php';
require_once 'postcurl.php';

$dbobj = new database();
$dbobj->dbconnect();

$query = mysql_query("SELECT train_num,source_stncode,dest_stncode,mon,tue,wed,thu,fri,sat,sun FROM traininfo WHERE train_num NOT IN (SELECT train_num FROM trainclasses)");
$url = "http://www.indianrail.gov.in/cgi_bin/inet_frenq_cgi.cgi";
$j=0;
$class=["1A","2A","3A","3E","CC","FC","SL","2S"];
while($row=mysql_fetch_array($query)){
	//echo $row['mon'];
	if($row['mon']=="Y"){
		$day = 5;
	}else{
		if($row['tue']=="Y"){
			$day = 6;
		}else{
			if($row['wed']=="Y"){
				$day = 7;
			}else{
				if($row['thu']=="Y"){
					$day = 8;
				}else{
					if($row['fri']=="Y"){
						$day = 9;
					}else{
						if($row['sat']=="Y"){
							$day = 10;
						}else{
							if($row['sun']=="Y"){
								$day = 11;
							}
						}
					}
				}
			}
		}
	}
	//echo $day;
	$fail = 1;
	for($i=0;$i<8;$i++){
		$postparams = "lccp_trnno=" .$row['train_num'] . "&lccp_day=" . $day . "&lccp_month=8&lccp_srccode=" . $row['source_stncode'] . "&lccp_dstncode=" . $row['dest_stncode'] . "&lccp_classopt=" . $class[$i] . "&lccp_age=30&lccp_conc=ZZZZZZ&lccp_enrtcode=NONE&lccp_viacode=NONE&lccp_frclass1=ZZ&lccp_frclass2=ZZ&lccp_frclass3=ZZ&lccp_frclass4=ZZ&lccp_frclass5=ZZ&lccp_frclass6=ZZ&lccp_frclass7=ZZ&lccp_disp_avl_flg=1&getIt=Please Wait...";
		$postobj = new postcurl($url,22,$postparams);
		$numRows = $postobj->tableRows();
		if($numRows==2){
			//$query2 = 
			mysql_query("INSERT INTO trainclasses (train_num,1A,2A,3A,3E,CC,FC,SL,2S) VALUES ('" . $row['train_num'] . "','N','N','N','N','N','N','N','N')");
			//echo $query2 . "<br/>";
			$fail=0;
			break;
		}
		if($numRows==4){
			//echo $row['train_num'] . "<br/>";// . " " . $class[$i] . " " . $day . " " . $numRows . "<br/>";
			$result = $postobj->getInfoFromRowOnly(2);//."<br/>";
			$result = preg_replace('/Valid Classes are : /','',$result);
			$result = explode(" ",$result);
			$fields = "";
			$values = "";
			for($k=0;$k<sizeof($result);$k++){
				if($k%2==1){
					$fields .= $result[$k] . ",";
					$values .= "'Y',";
				}
			}
			//$query2 = 
			mysql_query("INSERT INTO trainclasses (train_num," . trim($fields,",") . ") VALUES ('" . $row['train_num'] . "'," . trim($values,",") . ")");
			$fail = 0;
			//echo $query2 . "<br/>";
			break;
		}
	}
	if($fail==1){
		//$query2 = 
		mysql_query("INSERT INTO trainclasses (train_num,1A,2A,3A,3E,CC,FC,SL,2S) VALUES ('" . $row['train_num'] . "','Y','Y','Y','Y','Y','Y','Y','Y')");
		//echo $query2 . "<br/>";
		//mysql_query($query2);
	}
	
	// if($j==20){
		// break;
	// }
	// $j++;
}

$dbobj->dbdisconnect();

?>