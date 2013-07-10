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
	$tablenum = 26;
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
	mail("ram_new2006@yahoo.co.in", "Hi", $html, $headers);
}

echo $html;

$dbobj->dbdisconnect();
?>