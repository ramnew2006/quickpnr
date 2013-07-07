<?php 
	require_once 'pnrapi.php';
	require_once 'database.php';
	require_once 'postcurl.php';
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Quick PNR - Find PNR Status</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet" media="screen">
    <link href="css/bootstrap-icon-large.css" rel="stylesheet" media="screen">
    <link href="css/custom.css" rel="stylesheet" media="screen">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <script type="text/javascript">
    function validateForm(){
		var pnrNum = $(':input#pnrNum').val();
    	if($.isNumeric(pnrNum)){
    		return true;
    	}else{
    		alert("Please enter a number!");
        	return false;
        }
    }
    function resetForm(){
    	$('#pnrResult').hide();
    }
    </script>
  </head>
  <body>
  	<div id="containercustom">
		<header class="container-fluid-header">
		  <h1 style="padding-top:0.5em;">
			<span style="font-size: 1.05em;line-height:0.75em;font-style:italic;">quickPNR</span>
			<div style="font-size:0.8em;font-weight:normal;float:right;padding-top:5px;">
				<a href="" class="btn btn-primary">PNR Status</a>
				<a href="" class="btn btn-primary">Trains</a>
				<a href="" class="btn btn-primary">My Account</a>
				<a href="" class="btn btn-primary">Sign Up</a>
			</div>
		  </h1>
		</header>
		<div class="container-fluid-content">
			<div class="row-fluid" id="pnrForm">
				<div class="span4"></div>
				<div class="span4">
					<form name="myForm" method="post" action="index.php" onsubmit="return(validateForm());">
					<input id="pnrNum" name="pnrNum" type="text" size="10" maxlength="10" placeholder="Enter your PNR number">
					<input type="submit" name="checkStatus" value="Get Status" class="btn">
					<input type="submit" name="savePnr" value="Save PNR" class="btn">
					</form>
					<form>
					</form>
					<button id="reset" value="Reset" class="btn" onclick="resetForm()">Reset</button>
				</div>
				<div class="span4"></div>
			</div>
			<div class="container-fluid" id="pnrResult">
				<?php
				if(isset($_POST['savePnr'])){
					if(!empty($_POST['pnrNum'])){
						$pnrNum = $_POST['pnrNum'];
						$dbobj = new database();
						$dbobj->dbconnect();
						$query = "INSERT INTO pnrdetails (pnrnum) VALUES ($pnrNum)";
						if(mysql_query($query)){
							echo "PNR number saved successfully!";
						}else{
							echo "There was an error while saving your PNR number";
						}
						$dbobj->dbdisconnect();	
					}
				}
				if(isset($_POST['checkStatus'])){
					// if(!empty($_POST['pnrNum'])){
						// $pnr = $_POST['pnrNum']; 
						// $handle = new PNRAPI($pnr);
						// try{
							// $pStatus = $handle->getPassengerStatus();
							// $cStatus = $handle->getChartStatus();
							// $jStatus = $handle->getJourneyDetails();
						// }catch (Exception $e){
							// echo $e;
						// }
						// if($pStatus){
							// print_r($jStatus);echo "<br/>";
							// print_r($pStatus);
						// }
						// if($cStatus){
							// print_r($cStatus);
						// }
					// }
					$url = "http://www.indianrail.gov.in/cgi_bin/inet_pnrstat_cgi.cgi";
					$tablenum = 25;
					$postparams = "lccp_pnrno1=" . $_POST['pnrNum'] . "&submit=Wait+For+PNR+Enquiry%21";
					
					$postobj = new postcurl($url,$tablenum,$postparams);
					$lengthRow = $postobj->tableRows();

					echo "<table>";
					for($i=0;$i<$lengthRow;$i++){
						$lengthCol = $postobj->tableColumns($i);
						echo "<tr>";
						for($j=0;$j<$lengthCol;$j++){
							echo "<td>" . $postobj->getInfoFromRow($i,$j) . "</td>";
							// if($j%4==3){
								// echo "<br/>";
							// }
						}
						echo "</tr>";
					}
					echo "</table>";
					
				}
				?>
			</div>
		</div>
		<footer class="container-fluid-footer"></footer>
	</div>
	<script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>