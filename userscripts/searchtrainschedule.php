<?php
require_once '../database.php';
$dbobj = new database();
$dbobj->dbconnect();

if(isset($_POST['src_stn']) && isset($_POST['dest_stn'])){
	$src_stn = $_POST['src_stn'];
	$dest_stn = $_POST['dest_stn'];
	$date = date_create('06-08-2013');
	$day = strtolower(date_format($date, 'D'));
	$query = mysql_query("SELECT distinct(tr1.train_num), tr1.dep_time, tr4.train_name 
			FROM trainschedule AS tr1, trainschedule AS tr2, trainrunning AS tr3, trainlist AS tr4 
			WHERE 
			tr1.stn_code='" . $src_stn . "' 
			AND tr2.stn_code='" . $dest_stn . "' 
			AND tr1.train_num=tr2.train_num 
			AND tr1.train_num=tr3.train_num 
			AND tr1.train_num=tr4.train_num 
			AND tr1.route_num < tr2.route_num 
			AND tr3." . $day . "='Y' 
			ORDER BY tr1.dep_time");
	
	echo "<table class=\"table table-bordered table-striped table-hover\">";
	echo "<thead>
		<th>Train Number</th>
		<th>Train Name</th>
		<th>Departure Time</th>
		</thead>";
	echo "<tbody>";
	while($row = mysql_fetch_array($query)){
		echo "<tr>";
			echo "<td>" . $row['train_num'] . "</td>";
			echo "<td>" . $row['train_name'] . "</td>";
			echo "<td>" . $row['dep_time'] . "</td>";
		echo "</tr>";
	}
	echo "</tbody>";
	echo "</table>";
}

$dbobj->dbdisconnect();
?>