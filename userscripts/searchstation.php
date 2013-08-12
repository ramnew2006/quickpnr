<?php
require_once '../database.php';
$dbobj = new database();
$dbobj->dbconnect();
if(isset($_POST['stationname'])){
	$name=trim($_POST['stationname']);
	if(strlen($name)<=3){
		$query2=mysql_query("SELECT * FROM stationlist WHERE station_name LIKE '%$name%' or station_code LIKE '%$name%'");
	}else{
		$query2=mysql_query("SELECT * FROM stationlist WHERE station_name LIKE '%$name%'");
	}
	
	// while($row=mysql_fetch_array($query2)){
		// $options['myData'][] = array(
        // 'name' => $row['station_name']) . "(" .$row['station_code'] . ")"
		// ); 
	// }
	
	// echo json_encode($options);
	
	echo "<ul class=\"ajaxdropdownsearch\">";
	while($row=mysql_fetch_array($query2))
	{
	?>

	<li onclick='fill("<?php echo strtoupper($row['station_name']) . "(" .$row['station_code'] . ")"; ?>","<?php echo $_POST['boxname'] ?>")' class="ajaxdropdownsearch"><?php echo $row['station_name']; ?></li>
	<?php
	}
}
$dbobj->dbdisconnect();
?>
</ul>