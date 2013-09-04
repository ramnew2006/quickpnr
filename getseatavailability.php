<?php
require_once 'userscripts/postcurl.php';

$train_num = "12077";//"";
$day_travel = "09";//"";
$month_travel = "9";//"";
$source_stn = "mas";
$dest_stn = "nlr";
$class_travel = "2S";
$quota_travel = "GN";
$submit = "Please Wait...";
$url = "http://www.indianrail.gov.in/cgi_bin/inet_accavl_cgi.cgi";
$tablenum = 24;
$postparams = "lccp_trnno=" . $train_num . "&lccp_day=" . $day_travel . "&lccp_month=" . $month_travel . "&lccp_srccode=" . $source_stn . "&lccp_dstncode=" . $dest_stn . "&lccp_class1=" . $class_travel . "&lccp_quota=" . $quota_travel . "&lccp_classopt=ZZ&lccp_class2=ZZ&lccp_class3=ZZ&lccp_class4=ZZ&lccp_class5=ZZ&lccp_class6=ZZ&lccp_class7=ZZ&lccp_class6=ZZ&lccp_class7=ZZ&submit=" . $submit; 

$postobj = new postcurl($url,$tablenum,$postparams);
$i=0;
$lengthCol = $postobj->tableColumns($i);

echo "<table>";
echo "<tr>";
for($j=0;$j<$lengthCol;$j++){
	echo "<td>" . $postobj->getInfoFromRow($i,$j) . "</td>";
	if($j%4==3){
		echo "</tr><tr>";
	}
}
echo "</table>";

?>