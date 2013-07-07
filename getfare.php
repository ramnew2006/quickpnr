<?php
require_once 'postcurl.php';

$train_num = "12077";//"";
$day_travel = "08";//"";
$month_travel = "7";//"";
$source_stn = "mas";
$dest_stn = "nlr";
$class_travel = "2S";
$quota_travel = "GN";
$submit = "Please Wait...";
$url = "http://www.indianrail.gov.in/cgi_bin/inet_accavl_cgi.cgi";
$tablenum = 24;
$postparams = "lccp_trnno=" . $train_num . "&lccp_day=" . $day_travel . "&lccp_month=" . $month_travel . "&lccp_srccode=" . $source_stn . "&lccp_dstncode=" . $dest_stn . "&lccp_classopt=" . $class_travel . "&lccp_quota=" . $quota_travel . "&lccp_classopt=ZZ&lccp_class2=ZZ&lccp_class3=ZZ&lccp_class4=ZZ&lccp_class5=ZZ&lccp_class6=ZZ&lccp_class7=ZZ&lccp_class6=ZZ&lccp_class7=ZZ&submit=" . $submit; 

$postobj = new postcurl($url,$tablenum,$postparams);
$i=0;
$lengthCol = $postobj->tableColumns($i);

for($j=0;$j<$lengthCol;$j++){
	echo $postobj->getInfoFromRow($i,$j);
	if($j%4==3){
		echo "<br/>";
	}
}

?>