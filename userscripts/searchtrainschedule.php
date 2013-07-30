<?php
require_once 'database.php';
$dbobj = new database();
$dbobj->dbconnect();

if(isset($_POST['src_stn']) && isset($_POST['dest_stn'])){
	
	$src_stn = $_POST['src_stn'];
	$dest_stn = $_POST['dest_stn'];
	
	$query = mysql_query("SELECT distinct(tr1.train_num) FROM trainschedule AS tr1, trainschedule AS tr2 WHERE tr1.stn_name='" . $src_stn . "' AND tr2.stn_name='" . $dest_stn . "' AND tr1.train_num=tr2.train_num AND tr1.route_num < tr2.route_num");
	
	while($row = mysql_fetch_array($query)){
		$query2 = mysql_query("SELECT * FROM trainlist WHERE train_num=" . $row[0]); 
		$temparray = mysql_fetch_array($query2);
		echo $temparray['train_num'] . "&#09;" . $temparray['train_name'] . "<br/>";
	}
	
}

$dbobj->dbdisconnect();
?>