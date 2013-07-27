<!DOCTYPE html>
<html lang="en">
<?php
session_start();
require_once 'database.php';

$dbobj = new database();
$dbobj->dbconnect();

include('header.php');
?>
  
<!-- PNR Status
================================================== -->
<section id="pnrstatus">
	 <!-- Headings & Paragraph Copy -->
<div class="row">
 <div class="span7">
	<div class="well">
        <h1>h1. Heading 1</h1>
        <h2>h2. Heading 2</h2>
        <h3>h3. Heading 3</h3>
		<h1>h1. Heading 1</h1>
        <h2>h2. Heading 2</h2>
		<h2>h2. Heading 2</h2>
        <h3>h3. Heading 3</h3>
        <h4>h4. Heading 4</h4>
        <h5>h5. Heading 5</h5>
        <h6>h6. Heading 6</h6>
    </div>
 </div>
 <div class="span5">
	<div class="page-header">
    <h3>PNR Status</h3>
	</div>
	<div class="row" style="margin-left:auto;">
		<div class="span2">
		<input id="displaypnrstatusinput" name="displaypnrstatusinput" type="text" placeholder="Enter PNR Number">
		</div>
		<div class="span2">
		<a id="displaypnrstatusinputgetstatus" class="btn btn-primary">Get Status</a>
		</div>
		<br><br>
		<div id="displaypnrstatus"></div>
	</div>
 </div>
</div>
</section>

<!-- Send SMS Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
    <h3 id="myModalLabel">Send PNR Status to Any Mobile</h3>
  </div>
  <div class="modal-body">
    <input type="hidden" name="currentpnr" id="currentpnr" value="">
	Mobile Number:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;+91&nbsp;&nbsp;<input type="text" name="currentmobileNum" id="currentmobileNum">
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <a class="sendsmsanymobile btn btn-info" name="sendSMSanyMobile" id="sendsmsanymobile">Send SMS</a>
  </div>
</div>

<br><br><br><br>


<?php
include('footer.php');
$dbobj->dbdisconnect();
?>

</body>
</html>