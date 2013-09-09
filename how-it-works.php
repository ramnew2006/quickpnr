<?php
include('checkcookie.php');
require_once 'database.php';

$dbobj = new database();
$dbobj->dbconnect();

include('header.php');
?>
  
<!-- PNR Status
================================================== -->
<section id="pnrstatus">
	 <!-- Headings & Paragraph Copy -->
	 <div class="page-header">
    <h3>How it Works?</h3>
	</div>
<div class="row">
 
 
</div>
</section>



<br><br>




<?php
include('footer.php');
$dbobj->dbdisconnect();
?>
