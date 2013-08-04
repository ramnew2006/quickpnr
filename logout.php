<?php
session_start();

session_destroy();
setcookie('usercookie','',time()-3600);
setcookie('userName','',time()-3600);
header("Location:index.php");
exit();

?>