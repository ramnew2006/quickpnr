<?php
include('../checkcookie.php');
$_SESSION['redirect_url']=$_SERVER["REQUEST_URI"];

if(!isset($_SESSION['userName'])){
	$_SESSION['redirect_url']=$_SERVER["REQUEST_URI"];
	header("Location:../user/login.php");
	exit();
}

//quickpnr - mobilenum
$quickmobnum = $_SESSION['userName'];
session_write_close();

require_once '../database.php';

$dbobj = new database();
$dbobj->dbconnect();

include('../header.php');
?>

 
<!-- PNR History
================================================== -->
<section id="pnrhistory">
  <!-- Headings & Paragraph Copy -->

	<div class="page-header">
		<h3>PNR History</h3>
		
	</div>
	
	<div class="row" style="margin-left:auto;">
		<!-- <div>
			<ul class="nav nav-pills">
		    <li class="active" id="activeticketslink"><a href="#pnrhistory">Active Tickets</a></li>
		    <li id="archiveticketslink"><a href="#pnrhistory">Archived Tickets</a></li>
		    </ul>
		</div> -->

		<ul class="nav nav-pills">
		    <li class="active"><a href="#activetickets" data-toggle="tab">Active Tickets</a></li>
        	<li class=""><a href="#archivetickets" data-toggle="tab">Archive Tickets</a></li>
        	<li class=""><a href="#linkedtickets" data-toggle="tab">Linked Tickets</a></li>
	    </ul><br>

	  <!-- <ul class="nav nav-tabs">
        <li class=""><a href="#activetickets" data-toggle="tab">Active Tickets</a></li>
        <li class=""><a href="#archivetickets" data-toggle="tab">Archive Tickets</a></li>
        <li class="active"><a href="#linkedtickets" data-toggle="tab">Linked Tickets</a></li>
      </ul> -->
      <div class="tabbable">
        <div class="tab-content">
<?php
	
	$query = mysql_query("SELECT pn.pnrnum, sl.station_name as source, sl2.station_name as dest, pn.doj 
			FROM userpnrdetails AS pn, stationlist AS sl, stationlist AS sl2 
			WHERE 
			pn.src_stn=sl.station_code 
			AND pn.dest_stn=sl2.station_code 
			AND pn.doj>=CURDATE()
			AND pn.mobnum=" . $quickmobnum
			);
	
?>
          <div class="tab-pane active" id="activetickets">
            <table class="table table-bordered table-striped table-hover">
				<thead><tr>
					<th>#</th>
					<th>PNR Number</th>
			        <th>Source</th>
					<th>Destination</th>
					<th>Date of Journey</th>
					<th></th>
				</tr></thead>
				<tbody>
			<?php
				$j=1;
				while ($row = mysql_fetch_array($query)) {
					echo "<form method=\"post\" action=\"userprofile.php#pnrstatus\" onsubmit=\"return(pnrtimevalidate());\">";
					echo "<tr>";
						echo "<td>" . $j . "</td>";
						echo "<td>" . $row['pnrnum'] . "</td>";
						echo "<td>" . $row['source'] . "</td>";
						echo "<td>" . $row['dest'] . "</td>";
						$date = date_create($row['doj']);
						echo "<td>" . date_format($date, 'jS F Y') . "<br/>" . "(" . date_format($date, 'l') . ")" . "</td>";
						echo "<td>";
						echo "<input type=\"hidden\" name=\"pnrNum\" value=\"" . $row['pnrnum'] . "\">";
						echo "
						<a style=\"margin-left:0.25em;\" href=\"#displaypnrstatus\" rel=\"tooltip\" title=\"Get PNR Status\" name=\"getPNRStatus" . $row['pnrnum'] ."\" class=\"getpnrstatus btn btn-primary\" id=\"getPNRStatus\">PNR Status</a>
						<a style=\"margin-left:0.25em;\" id=\"getSMS\" class=\"getsms btn btn-warning\" name=\"getSMS" . $row['pnrnum'] ."\" rel=\"tooltip\" title=\"Get Message to your registered Mobile\">Get SMS</a>
						<a style=\"margin-left:0.25em;\" data-toggle=\"modal\" href=\"#mySMSModal\" rel=\"tooltip\" title=\"Send Message to any Mobile\" id=\"sendSMS\" class=\"sendsms btn btn-info\" name=\"sendSMS" . $row['pnrnum'] ."\" value=\"" . $row['pnrnum'] . "\">Send SMS</a>
						";
						echo "</td>";
					echo "</tr>";
					echo "</form>";
					$j++;
				}
			?>
				</tbody>
			</table>
          </div>
<?php	
	$query = mysql_query("SELECT pn.pnrnum, sl.station_name as source, sl2.station_name as dest, pn.doj 
			FROM userpnrdetails AS pn, stationlist AS sl, stationlist AS sl2 
			WHERE 
			pn.src_stn=sl.station_code 
			AND pn.dest_stn=sl2.station_code 
			AND pn.doj<CURDATE()
			AND pn.mobnum=" . $quickmobnum
			);
			
?>
          <div class="tab-pane" id="archivetickets">
            <table class="table table-bordered table-striped table-hover">
				<thead><tr>
					<th>#</th>
					<th>PNR Number</th>
			        <th>Source</th>
					<th>Destination</th>
					<th>Date of Journey</th>
					</tr>
				</thead>
				<tbody>

			<?php
				$j=1;
				while ($row = mysql_fetch_array($query)) {
					echo "<tr>";
						echo "<td>" . $j . "</td>";
						echo "<td>" . $row['pnrnum'] . "</td>";
						echo "<td>" . $row['source'] . "</td>";
						echo "<td>" . $row['dest'] . "</td>";
						$date = date_create($row['doj']);
						echo "<td>" . date_format($date, 'jS F Y') . "<br/>" . "(" . date_format($date, 'l') . ")" . "</td>";
					echo "</tr>";
					$j++;
				}
			?>
				</tbody>
			</table>
          </div>
          <div class="tab-pane" id="linkedtickets">
            <p>What up girl, this is Section C.</p>
          </div>
        </div>
      </div> <!-- /tabbable -->
      
    <!-- </div> -->

</div>
  

</section>

<div id="displaypnrstatus"></div>

<!-- Send SMS Modal -->
<div id="mySMSModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
    <h3 id="myModalLabel">Send PNR Status to Any Mobile</h3>
  </div>
  <div class="modal-body">
    <input type="hidden" name="currentpnr" id="currentpnr" value="">
	Mobile Number:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;+91&nbsp;&nbsp;<input type="text" name="currentmobileNum" id="currentmobileNum">
  </div>
  <div class="modal-footer">
    <a class="sendsmsanymobile btn btn-info" name="sendSMSanyMobile" id="sendsmsanymobile">Send SMS</a>
	<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>

<br><br><br><br>


<?php
include('../footer.php');
$dbobj->dbdisconnect();
?>