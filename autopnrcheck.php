<?php
require_once 'database.php';
require_once 'postcurl.php';
require_once 'sendmail.php';

$dbobj = new database();
$dbobj->dbconnect();

$query = mysql_query("SELECT pnrnum FROM pnrdetails");
$numRows = mysql_num_rows($query);

for($k=0;$k<$numRows;$k++){
	$pnrnum = mysql_result($query,$k);
	$url = "http://www.indianrail.gov.in/cgi_bin/inet_pnrstat_cgi.cgi";
	$tablenum = 25;
	$postparams = "lccp_pnrno1=" . $pnrnum . "&submit=Wait+For+PNR+Enquiry%21";
	
	$postobj = new postcurl($url,$tablenum,$postparams);
	$lengthRow = $postobj->tableRows();

	$html = "<table>";
	for($i=0;$i<$lengthRow;$i++){
		$lengthCol = $postobj->tableColumns($i);
		$html .= "<tr>";
		for($j=0;$j<$lengthCol;$j++){
			$html .= "<td>" . $postobj->getInfoFromRow($i,$j) . "</td>";
			// if($j%4==3){
				// echo "<br/>";
			// }
		}
		$html .= "</tr>";
	}
	$html .= "</table>";
	$headers = 'From: ramnew2006@gmail.com' . "\r\n" .
    'Reply-To: ramnew2006@gmail.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
	$from = "ramnew2006@gmail.com";
	$to = "ramnew2006@gmail.com";
	$subj = "hi";
	$msg = "hello there";
	$mailobj = new sendMail($to, $from, $subj, $msg);
	if($mailobj){
		echo "success";
	}else{
		echo "bad";
	}
	// if(mail("ramnew2006@gmail.com", "Hi", "Hello", $headers)){
		// echo "mail successfully sent";
	// }else{
		// echo "something is wrong";
	// }
}

echo $html;

$dbobj->dbdisconnect();
?>