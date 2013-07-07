<?php
session_start();
require_once 'database.php';

if(isset($_SESSION['user'])){
	header("Location:index.php");
}else{
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
		var mobileNum = $(':input#mobileNum').val();
    	if($.isNumeric(mobileNum)){
			if(mobileNum.length==10){
				return true;
			}else{
				alert("Enter the complete phone number");
				return false;
			}
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
					<form action="loginaction.php" method="post" onsubmit="return(validateForm());">
					<input id="mobileNum" name="mobileNum" type="text" size="10" maxlength="10" placeholder="Your 10 digit Mobile number"><br/>
					<input name="userPassword" type="password" placeholder="Your Password"><br/>
					<input type="submit" name="userLogin" value="Login" class="btn">
					<input type="submit" name="userRegister" value="Register" class="btn">
					</form>
				</div>
				<div class="span4"></div>
			</div>
			<div class="container-fluid" id="pnrResult">

			</div>
		</div>
		<footer class="container-fluid-footer">
			<div class="footer-content">
				This is the Footer
			</div>
		</footer>
	</div>
	<script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>

<?php
}
?>