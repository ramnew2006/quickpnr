<?php
require_once 'database.php';
require_once 'postcurl.php';

$dbobj = new database();
$dbobj->dbconnect();



$dbobj->dbdisconnect();

?>