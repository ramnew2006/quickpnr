<?php
require_once 'database.php';
require_once 'postcurl.php';

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

	// Always set content-type when sending HTML email
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
	$headers .= 'From: noreply@qwiktravel.com' . "\r\n" .
    'Reply-To: noreply@qwiktravel.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
	
	$to = "ram_new2006@yahoo.co.in";
	$subj = "hi";
	
	if(mail($to, $subj, $html, $headers)){
		echo "mail successfully sent";
	}else{
		echo "something is wrong";
	}
}

echo $html;

$dbobj->dbdisconnect();
?>