<?php
set_time_limit(0);
require_once 'database.php';

$db = new database();
$db->dbconnect();

function curlOpt(){
	$ch = curl_init();        //Initialize the cURL handler
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);    //To give the file fetched back to the handler
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,false);   //To stop redirects
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.112 Safari/535.1");    //To mask the robot as a browser
	curl_setopt($ch, CURLOPT_URL, "http://www.stationcodes.com/");   //To set the page to be fetched
	return curl_exec($ch);    //Execute and return the response
}

function urlResult(){
	$responseHTML = curlOpt();
	$responseDOM = new DOMDocument();        //Create new DOM Object
	$responseDOM->strictErrorChecking=false;
	$responseDOM->recover=true;
	@$responseDOM->loadHTML($responseHTML); //Load the HTML into the DOM Object
	$responseTable = $responseDOM->getElementsByTagName('table')->item(0);
	$responseRows = @$responseTable->childNodes;
	return $responseRows;
}

$result = urlResult();
$numRowsResult = $result->length;
$badquery = "INSERT INTO stationlist (station_name, station_code) VALUES ('','')";

for($i=1;$i<$numRowsResult;$i++){
	for($j=0;$j<=7;$j++){
		$rem = $j%2;
		if($rem==0){
			$query = "INSERT INTO stationlist (station_name, station_code) VALUES ('" . $result->item($i)->getElementsByTagName('td')->item($j)->textContent . "',";
		}else{
			$query .= "'" . $result->item($i)->getElementsByTagName('td')->item($j)->textContent . "')";
			if($query!=$badquery){
				mysql_query($query);
			}
			//echo $query . "<br/>";
		}
	}
}

//echo $result->item(2)->getElementsByTagName('td')->item(2)->textContent;

$db->dbdisconnect();

?>
