<?php
set_time_limit(0);
require_once 'postcurl.php';
require_once 'getcurl.php';

$url = "https://www.irctc.co.in/cgi-bin/bv60.dll/irctc/services/login.do";
$username = "ramnew2009";
$password = "man0har";
$postparams = "screen=home&userName=" . $username . "&password=" . $password;

//Initial Login
$postobj = new postcurl($url,1,$postparams);
$url = $postobj->curlheaders()['redirect_url'];
$query = parse_url($url)['query'];
$query = explode('&',$query);

//Extracting Session variables
$bvs = $query[1];
$bve = $query[2];

//Getting PNR History
$url = "https://www.irctc.co.in/cgi-bin/bv60.dll/irctc/services/repassword.do?" . $bvs . "&" . $bve;
$ref = "https://www.irctc.co.in/cgi-bin/bv60.dll/irctc/services/history.do?click=true&LinkValue=2&voucher=4&" . $bvs . "&" . $bve;
$postparams = $bvs . "&" . $bve . "&page=history&password=" . $password . "&Submit=GO";
$postobj = new postcurl($url,15,$postparams,$ref);

//print_r($postobj->curlheaders());
$numRows = $postobj->tableRows();

//Printing PNR Numbers
for($i=1;$i<$numRows;$i++){
	if($i%2==1){
		echo "<br/>" . $postobj->getInfoFromRow($i,3);
	}
}
?>
