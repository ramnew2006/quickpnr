<?php
require_once 'userscripts/postcurl.php';

$train_num = "12077";//"";
$day_travel = "09";//"";
$month_travel = "09";//"";
$source_stn = "MAS";
$dest_stn = "NLR";
$class_travel = "2S";
$quota_travel = "GN";
$submit = "Please Wait...";
$url = "http://www.indianrail.gov.in/cgi_bin/inet_frenq_cgi.cgi";
$tablenum = 25;
$postparams = "lccp_trnno=" . $train_num . "&lccp_day=" . $day_travel . "&lccp_month=" . $month_travel . "&lccp_srccode=" . $source_stn . "&lccp_dstncode=" . $dest_stn . "&lccp_classopt=" . $class_travel . "&lccp_age=30&lccp_conc=ZZZZZZ&lccp_enrtcode=NONE&lccp_viacode=NONE&lccp_frclass1=ZZ&lccp_frclass2=ZZ&lccp_frclass3=ZZ&lccp_frclass4=ZZ&lccp_frclass5=ZZ&lccp_frclass6=ZZ&lccp_frclass7=ZZ&lccp_disp_avl_flg=1&getIt=" . $submit; 

$postobj = new postcurl($url,$tablenum,$postparams);
$lengthRow = $postobj->tableRows();
echo $lengthRow;
$i=1;
$lengthCol = $postobj->tableColumns($i);

for($j=0;$j<$lengthCol;$j++){
	echo $postobj->getInfoFromRow($i,$j);
	if($j%4==3){
		echo "<br/>";
	}
}

?>