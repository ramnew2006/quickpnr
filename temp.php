<?php
require_once 'getcurl.php';

$url = "http://www.indianrail.gov.in/mail_express_trn_list.html";
$tablenum = 5;

$obj = new getcurl($url,$tablenum);

echo $obj->getInfoFromRow(2,2);

?>