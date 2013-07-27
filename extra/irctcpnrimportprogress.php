<?php
header('Content-Type: application/json');
session_start();
//$_SESSION['irctcimportprogress']=1;
if(isset($_SESSION['irctcimportprogress'])){
	$array['result']=$_SESSION['irctcimportprogress'];
	if($_SESSION['irctcimportprogress']==100){
		unset($_SESSION['irctcimportprogress']);
	}
}else{
	$_SESSION['irctcimportprogress']=0;
	$array['result']=$_SESSION['irctcimportprogress'];
}
print_r(json_encode($array));
?>