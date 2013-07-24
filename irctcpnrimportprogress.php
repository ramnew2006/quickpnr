<?php
header('Content-Type: application/json');
session_start();
//$_SESSION['irctcimportprogress']=1;
$array['result']=$_SESSION['irctcimportprogress'];
print_r($array)
?>