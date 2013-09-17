<?php
set_time_limit(0);
require_once 'database.php';
$dbobj = new database();
$dbobj->dbconnect();

$query=mysql_query("SELECT distinct(stn_code), stn_name FROM trainschedule order by stn_code");
$result ="";
while($row=mysql_fetch_array($query)){
  $result .= "\"" . trim($row['stn_name']) . " (" . trim($row['stn_code']) . ")\",";
}

print_r($result);

// $query = mysql_query("SELECT train_num FROM trainrunning WHERE updated='N'");
// $numrows = mysql_num_rows($query);

// for($i=0;$i<$numrows;$i++){
//   $trnno[$i] = mysql_result($query,$i);
// }

// for($i=0;$i<$numrows;$i++){
//   $url = "http://enquiry.indianrail.gov.in/ntes/NTES?action=getTrainData&trainNo=" . $trnno[$i];
//   $header = array("Proxy-Connection:keep-alive",
//                 "Host:enquiry.indianrail.gov.in",
//                 "Connection:keep-alive",
//                 "Referer:http://enquiry.indianrail.gov.in/ntes/",
//                 "X-Requested-With:XMLHttpRequest",
//                 "Cookie:captchaId=od6mgnbprs1378044788630;",
//                 "User-Agent:Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.112 Safari/535.1");
//   $ch = curl_init();
//   curl_setopt($ch, CURLOPT_URL, $url);
//   curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
//   curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);    //To give the file fetched back to the handler
//   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
//   curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);   //To stop redirects
//   $responseHTML = curl_exec($ch);

//   $responseDOM = new DOMDocument();        //Create new DOM Object
//   $responseDOM->strictErrorChecking=false;
//   $responseDOM->recover=true;
//   @$responseDOM->loadHTML($responseHTML); //Load the HTML into the DOM Object
//   $responseTable = $responseDOM->getElementsByTagName("body")->item(0); //Parse the DOM Object and get the required Table
//   //$this->responseRows = @$responseTable->childNodes;

//   if($responseTable->nodeValue){
//     $result = $responseTable->nodeValue;
//     //print_r($result);
    
//     // $result = preg_replace('/_variable_[0-9]+={/', '', $result);
//     // $result = preg_replace('/}$/', '', $result);
//     // $result = preg_replace('@[\\r|\\n|\\t]+@', '', $result);

//     // $result = json_encode($result);
//     // $result = simplexml_load_string($result);

//     preg_match('/getDaysOfRunString\(\"[0-1]+\"\)/', $result, $matches);
//     $result = $matches[0];
//     $result = preg_replace('/getDaysOfRunString/', '', $result);
//     $result = preg_replace('/\"/', '', $result);
//     $result = preg_replace('/\(/', '', $result);
//     $result = preg_replace('/\)/', '', $result);
//     $result = preg_replace('/1/', 'Y', $result);
//     $result = preg_replace('/0/', 'N', $result);

//     $query = mysql_query("UPDATE trainrunning SET sun='" . $result[0] . "',mon='" . $result[1] . "',tue='" . $result[2] . "',wed='" . $result[3] . "',thu='" . $result[4] . "',fri='" . $result[5] . "',sat='" . $result[6] . "', updated='Y' WHERE train_num='" . $trnno[$i] . "'");

//     if($query){
//       echo "Success<br/>";
//     }else{
//       echo "failure<br/>";
//     }
//     //echo $query;
//     //print_r(str_split($result));
//   }
// }

$dbobj->dbdisconnect();

?>