<?php
require_once '../database.php';
$dbobj = new database();
$dbobj->dbconnect();

if(isset($_POST['src_stn']) && isset($_POST['dest_stn']) && isset($_POST['sel_date'])){
	$screenWidth = $_POST['screenWidth'];
	if($screenWidth<760){
		$desktop="none";
		$mobile="normal";
	}else{
		$desktop="normal";
		$mobile="none";
	}
	$src_stn = $_POST['src_stn'];
	$dest_stn = $_POST['dest_stn'];
	
	$DDMM = explode('-',$_POST['sel_date']);
	$MM = $DDMM[1];
	$DD = $DDMM[2];
	$date = date_create($_POST['sel_date']);
	$day = strtolower(date_format($date, 'D'));
	$query = mysql_query("SELECT distinct(tr1.train_num), tr1.dep_time, tr4.train_name 
			FROM trainschedule AS tr1, trainschedule AS tr2, trainrunning AS tr3, trainlist AS tr4 
			WHERE 
			tr1.stn_code = '" . $src_stn . "' 
			AND tr2.stn_code = '" . $dest_stn . "'
			AND tr1.train_num=tr2.train_num 
			AND tr1.train_num=tr3.train_num 
			AND tr1.train_num=tr4.train_num 
			AND tr1.route_num < tr2.route_num 
			AND tr3." . $day . "='Y' 
			ORDER BY tr1.dep_time");
	
	echo "<h4><b>List of Trains</b></h4>";
	echo "<table class=\"table table-bordered table-striped table-hover desktoptrains2stations\" style=\"display:" . $desktop . ";width:auto;\">";
	echo "<thead>
		<th>Train No</th>
		<th>Train Name</th>
		<th>Dept Time</th>
		<th>FC</th>
		<th>1A</th>
		<th>2A</th>
		<th>3A</th>
		<th>3E</th>
		<th>CC</th>
		<th>SL</th>
		<th>2S</th>
		</thead>";
	echo "<tbody>";
	while($row = mysql_fetch_array($query)){
		$query1 = mysql_query("SELECT * FROM trainclasses WHERE train_num='" . $row['train_num'] . "'");
		echo "<tr>";
			echo "<td>" . $row['train_num'] . "</td>";
			echo "<td>" . $row['train_name'] . "</td>";
			echo "<td>" . $row['dep_time'] . "</td>";
			while($row1 = mysql_fetch_array($query1,MYSQL_NUM)){
				for($i=2;$i<=9;$i++){
					if($row1[$i]=="N"){
						echo "<td>-</td>";
					}
					if($row1[$i]=="Y"){
						echo "<td><input type=\"radio\" name=\"trains2stations\" class=\"trains2stations\" value=\"" . $row['train_num'] . "_" . $DD . "_" . $MM . "_" . $src_stn . "_" . $dest_stn . "_" . trainClass($i-2). "\"></td>";
					}
				}
			}
		echo "</tr>";
	}
	echo "</tbody>";
	echo "</table>";

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

	echo "<table class=\"table table-bordered table-striped table-hover mobiletrains2stations\" style=\"display:" . $mobile . ";width:auto;\">";
	echo "<tbody>";
	while($row = mysql_fetch_array($query)){
		$query1 = mysql_query("SELECT * FROM trainclasses WHERE train_num='" . $row['train_num'] . "'");
		echo "<tr>";
			echo "<td><p>" . $row['train_num'] . "</p><p>";
			echo $row['train_name'] . "</p><p>";
			echo $row['dep_time'] . "</p></td>";
			while($row1 = mysql_fetch_array($query1,MYSQL_NUM)){
				echo "<td>";
				for($i=2;$i<=9;$i++){
					if($row1[$i]=="Y"){
						echo trainClass($i-2) . " <input type=\"radio\" name=\"trains2stations\" class=\"trains2stations\" value=\"" . $row['train_num'] . "_" . $DD . "_" . $MM . "_" . $src_stn . "_" . $dest_stn . "_" . trainClass($i-2). "\"> ";
					}
				}
				echo "</td>";
			}
		echo "</tr>";
	}
	echo "</tbody>";
	echo "</table>";
}

function trainClass($i){
	$trainclass = array("FC","1A","2A","3A","3E","CC","SL","2S");
	return $trainclass[$i];
}

$dbobj->dbdisconnect();
?>