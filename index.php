<?php 
	require_once 'pnrapi.php';
	require_once 'database.php';
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
  	<header class="container-fluid-header">
	  <h1 style="padding-top:0.5em;">
	  	<span style="font-size: 3em;line-height:0.75em;">quick PNR</span>
	  	<span style="font-size:1.35em;font-weight:normal;"> - A solution for all our PNR grieves!!</span>
	  </h1>
	</header>
	<div class="container-fluid-content">
		<div class="row-fluid" id="intro-banner">
	  		<div class="span2"></div>
	  		<div class="span8">
	  		<p style="font-family: Imprima;font-size:2em;font-weight:bold;padding:2em;line-height:1.2em;">Do you feel frustrated to check your PNR again and again whether it got confirmed or not? Join the wagon and feel no more frustration!!</p></div>
	     	<div class="span2"></div>
		</div>
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
	if(!empty($_POST['pnrNum'])){
		$pnr = $_POST['pnrNum']; 
		$handle = new PNRAPI($pnr);
		try{
			$pStatus = $handle->getPassengerStatus();
			$cStatus = $handle->getChartStatus();
			$jStatus = $handle->getJourneyDetails();
		}catch (Exception $e){
			echo $e;
		}
		if($pStatus){
			print_r($jStatus);echo "<br/>";
			print_r($pStatus);
		}
		if($cStatus){
			print_r($cStatus);
		}
	}
}
$_POST = array();
?>
		</div>
	</div>
	<footer class="navbar navbar-fixed-bottom">Dedicated to all the people who spent a great deal of their lives on IRCTC!</footer>
	<script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>