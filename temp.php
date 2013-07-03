<?php
require_once 'postcurl.php';

$url = "http://www.indianrail.gov.in/cgi_bin/inet_trnnum_cgi.cgi";
$tablenum = 24;
$postparams = "lccp_trnname=12077";
$obj = new postcurl($url,$tablenum,$postparams);

echo $obj->getInfoFromRow(2,2);

echo "bah";

?>