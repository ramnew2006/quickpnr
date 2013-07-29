<!DOCTYPE html>
<html lang="en">
<?php
session_start();
require_once 'database.php';

$dbobj = new database();
$dbobj->dbconnect();

include('header.php');
?>
<script type="text/javascript">
function fill(Value,boxname){
	var id = "#"+boxname;
	$(id).val(Value);
	$('#'+boxname+'display').hide();
}
</script>	
<!-- PNR Status
================================================== -->
<section id="searchtrain">
	 <!-- Headings & Paragraph Copy -->

	 <div class="page-header">
    <h3>Trains between two stations</h3>
	</div>
	<div class="row" style="margin-left:auto;">
		<table>
		<tr>
			<td><input id="sourcestationsearchtrain" name="sourcestationsearchtrain" type="text" placeholder="From">
			<div id="sourcestationsearchtraindisplay"></div>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><input id="deststationsearchtrain" name="deststationsearchtrain" type="text" placeholder="To">
			<div id="deststationsearchtraindisplay"></div>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><a id="getsearchtrains" class="btn btn-primary">Search Trains</a></td>
		</tr>
		</table>
		<br><br>
		<div id="displaypnrstatus"></div>
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