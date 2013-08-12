<?php
set_time_limit(0);
require_once '../database.php';
require_once '../userscripts/postcurl.php';

$db = new database();
$db->dbconnect();

$url = "http://www.indianrail.gov.in/cgi_bin/inet_trnnum_cgi.cgi";
$tablenum = 24;
$trn_num = 12077;//$_POST['train_num'];
$postparams = "lccp_trnname=" . $trn_num . "&getIt=Please Wait...";

$postobj = new postcurl($url,$tablenum,$postparams);
$lengthRow = $postobj->tableRows();
$lengthCol = 9;

$fields = "(train_num,stn_code,stn_name,route_num,arr_time,dep_time,halt_time,distance,day,slip)";
$values = "";
$values2 = "";
$k=1; //route number
$normalroute = 1; //normal route is switched on by default
$sliproute = 0; //slip route is switched off initially
$normalroute_array = array(); //used to calculate the junction point for normal route and slip route

for($i=1;$i<$lengthRow;$i++){
	$lengthCol = $postobj->tableColumns($i);
	//checking the starting point of slip route
	if($lengthCol==1){
		$normalroute = 0;
		$sliproute = 1;
		$k = 1; //route number
		for($a=1;$a<$i;$a++){
			//comparison which finds out the junction point
			if(trim($postobj->getInfoFromRow($i+1,1))==trim($normalroute_array[$a][1])){
				$junction = $a;
				$firstroute = $i;
				break;
			}
		}
		//build the slip route at once
		$values2 = "";
		for($h=1;$h<$lengthRow;$h++){
			$lengthCol = $postobj->tableColumns($h);
			//carry out the loop only if the station is in slip route
			if($h<$junction || $h>($firstroute)){
				$values2 .= "('" . $trn_num . "',";
				for($j=1;$j<$lengthCol;$j++){
					//if the cells have empty values or indifferent values assign Null to them
					if($postobj->getInfoFromRow($h,$j)=="Source" || $postobj->getInfoFromRow($h,$j)=="Destination" || $postobj->getInfoFromRow($h,$j)==""){
						$values2 .= "NULL,";
					}else{
						$tempvalue = $postobj->getInfoFromRow($h,$j);
						if($j==3){ //calculating route number
							$values2 .= "'" . $k . "',";
						}elseif($j==6){ //prefixing zero hours to halt time
							$values2 .= "'00:" . (trim($tempvalue)) . "',";
						}else{
							$values2 .= "'" . (trim($tempvalue)) . "',";
						}
					}
				}
				$k++;
				$values2 = trim($values2,",") . ",'Y'),";
			}
		}
		break; //break the parent for loop since the slip route is already built
	//build the normal route
	}elseif($normalroute==1){
		$values .= "('" . $trn_num . "',";
		for($j=1;$j<$lengthCol;$j++){
			//if the cells have empty values or indifferent values assign Null to them
			if($postobj->getInfoFromRow($i,$j)=="Source" || $postobj->getInfoFromRow($i,$j)=="Destination" || $postobj->getInfoFromRow($i,$j)==""){
				$values .= "NULL,";
			}else{
				$tempvalue = $postobj->getInfoFromRow($i,$j);
				if($j==3){ //calculating route number
					$values .= "'" . $k . "',";
					$normalroute_array[$i][$j] = $k;
				}elseif($j==6){ //prefixing zero hours to halt time
					$values .= "'00:" . (trim($tempvalue)) . "',";
					$normalroute_array[$i][$j] = "00:" . trim($tempvalue);
				}else{
					$values .= "'" . (trim($tempvalue)) . "',";
					$normalroute_array[$i][$j] = trim($tempvalue);
				}
			}
		}
		$k++;
		$values = trim($values,",") . ",'N'),";
	}
}

$insert = "INSERT INTO trainschedule " . $fields . " VALUES ". $values;
$insert2 = "INSERT INTO trainschedule " . $fields . " VALUES ". $values2;
$insert = trim($insert,",");
$insert2 = trim($insert2,",");
//mysql_query($insert);
//mysql_query($insert2);
echo $insert . "<br/>";
echo "slip route<br/>"; 
echo $insert2 . "<br/>";
//print_r($test);
$db->dbdisconnect();

?>
