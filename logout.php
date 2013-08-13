<?php
session_start();

session_destroy();
setcookie('usercookie','',time()-3600,"/",".quickpnr.com");
setcookie('userName','',time()-3600,"/",".quickpnr.com");
header("Location:../index.php");
exit();

?>
