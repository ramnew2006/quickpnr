<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <?php 
		if($numRows==1){
			$result=mysql_fetch_array($titlequery);
			echo "<title>" . $result['title'] . " - quickPNR</title>";
			echo "<meta name=\"description\" content=\"" . $result['description'] . "\">";
			echo "<meta name=\"keywords\" content=\"" . $result['keywords'] . "\">";
		}else{
			echo "<title>quickPNR</title>";
			echo "<meta name=\"description\" content=\"\">";
			echo "<meta name=\"keywords\" content=\"\">";
		}
	?>
	
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="../css/font-awesome.min.css" rel="stylesheet">
	<!--[if IE 7]>
	<link href="../css/font-awesome-ie7.min.css" rel="stylesheet">
	<![endif]-->
	<link href="../css/bootswatch.css" rel="stylesheet">
	<?php if($_SERVER["REQUEST_URI"]!="/search-train") {?>
	<link href="../css/pnrhistory.css" rel="stylesheet">
	<?php } ?>
	
	<!--Loading jQuery in the head for good experience-->
	<link href="../css/jquery-ui.css" rel="stylesheet">
	<link href="../css/custom.css" rel="stylesheet">
	<script src="../js/jquery.js"></script>
  </head>

  <body class="preview" id="top" data-spy="scroll" data-target=".subnav" data-offset="80">
<?php include_once("analyticstracking.php") ?> 
  <!-- Navbar
    ================================================== -->
 <div class="navbar navbar-fixed-top">
   <div class="navbar-inner">
     <div class="container">
       <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
       </a>
       <a class="brand" href="/">
       	<h1 class="sitelogo"><img src="../img/logo.png" title="quickPNR"></h1>
       </a>
       <div class="nav-collapse collapse" id="main-menu">
        <ul class="nav pull-right" id="main-menu-right">
		<?php if(isset($_SESSION['userName'])){ ?>
          <li><a href="../user/profile"><i class="icon-user"></i> My Account</a></li>
          <li><a href="../logout"><i class="icon-unlock"></i> Logout</a></li>
		<?php }else{ ?>
		  <li><a data-toggle="modal" href="#myRegisterModal" id="mainRegisterModal">Sign Up</a></li>
          <li><a data-toggle="modal" href="#myLoginModal" id="mainLoginModal">Login</a></li>
		<?php } ?>
        </ul>
       </div>
     </div>
   </div>
 </div>

 <div class="container">


<!-- Masthead
================================================== -->
  
  <div class="subnav">
    <ul class="nav nav-pills">
	<!-- Extra Navigation Menu when the user is logged in-->
	<?php if(isset($_SESSION['userName'])){ ?>
	  <?php if($_SERVER["REQUEST_URI"]=="/user/profile") {?>
	  <li class="active"><a href="#userprofile">My Account</a></li>
      <?php }else{ ?>
	  <li><a href="../user/profile">My Account</a></li>
	  <?php } ?>
	<?php } ?>
	  <?php if($_SERVER["REQUEST_URI"]=="/user/pnr-status") {?>
      <li class="active"><a href="#pnrstatus">PNR Status</a></li>
	  <?php }else{ ?>
	  <li><a href="../user/pnr-status">PNR Status</a></li>
	  <?php } ?>
	  <?php if($_SERVER["REQUEST_URI"]=="/user/sms-reminder") {?>
	  <li class="active"><a href="#smsreminder">SMS Reminder</a></li>
      <?php }else{ ?>
	  <li><a href="../user/sms-reminder">SMS Reminder</a></li>
	  <?php } ?>
	<?php if(isset($_SESSION['userName'])){ ?>
	  <?php if($_SERVER["REQUEST_URI"]=="/user/pnr-history") {?>
      <li class="active"><a href="#pnrhistory">PNR History</a></li>
	  <?php }else{ ?>
	  <li><a href="../user/pnr-history">PNR History</a></li>
	  <?php } ?>
	  <?php if($_SERVER["REQUEST_URI"]=="/user/sync-with-irctc") {?>
	  <li class="active"><a href="#irctcimport">Sync with IRCTC</a></li>
	  <?php }else{ ?>
	  <li><a href="../user/sync-with-irctc">Sync with IRCTC</a></li>
	  <?php } ?>
	<?php } ?>
	  <?php if($_SERVER["REQUEST_URI"]=="/search-train") {?>
	  <li class="active"><a href="#searchtrain">Trains</a></li>
	  <?php }else{ ?>
	  <li><a href="../search-train">Trains</a></li>
	  <?php } ?>
	  <?php if($_SERVER["REQUEST_URI"]=="/how-it-works") {?>
	  <li class="active"><a href="../how-it-works">How it Works?</a></li>
	  <?php }else{ ?>
	  <li><a href="../how-it-works">How it Works?</a></li>
	  <?php } ?>
	  <?php if($_SERVER["REQUEST_URI"]=="/feedback") {?>
	  <li class="active"><a href="../feedback">Feedback!</a></li>
	  <?php }else{ ?>
	  <li><a href="../feedback">Feedback!</a></li>
	  <?php } ?>
	</ul>
  </div>
  
  
<?php if(isset($_SESSION['userName'])) { ?>
<?php }else{ ?>
  <!-- Login Modal -->
<div id="myLoginModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
    <h3 id="myModalLabel">Login to your Account</h3>
  </div>
  <div class="modal-body">
    	<table>
		<tr>
			<td>Mobile Number</td>
			<td style="padding-left:1em;"><input id="loginmobileNum" name="mobileNum" type="text" maxlength="10" placeholder="Mobile number"></td>
		</tr>
		<tr>
			<td>Password</td>
			<td style="padding-left:1em;"><input id="loginPassword" name="userPassword" type="password" placeholder="Password"><br/></td>
		</tr>
		</table>
	</div>
  <div class="modal-footer">
	<a data-dismiss="modal" data-toggle="modal" href="#myForgotPassModal" style="float:left;">Forgot Password?</a>
	<input class="btn btn-primary" value="Login" name="userLogin" id="userLogin" type="submit">
	<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>

<!-- Register Modal -->
<div id="myRegisterModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
    <h3 id="myModalLabel">Register Now!</h3>
  </div>
  <div class="modal-body">
    <form action="../doregister" method="post" onsubmit="return(validateRegisterForm());">
		<table>
		<tr>
			<td>Mobile Number</td>
			<td style="padding-left:1em;"><input id="registermobileNum" name="mobileNum" type="text" size="10" maxlength="10" placeholder="Mobile number"></td>
		</tr>
		<tr>
			<td>Password</td>
			<td style="padding-left:1em;"><input id="registeruserPassword" name="userPassword" type="password" placeholder="Password"><br/>
		</tr>
		<tr>
			<td>Confirm Password</td>
			<td style="padding-left:1em;"><input id="registeruserConfirmPassword" name="userConfirmPassword" type="password" placeholder="Password"><br/>
		</tr>
		</table>
	</div>
  <div class="modal-footer">
    <div id="registrationValidation"></div>
	<span style="float:left;"><input type="checkbox"> I Agree to the <a href="#">Terms & Conditions</a></span>
    <input class="btn btn-primary" name="userRegister" value="Register" type="submit">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    </form>
  </div>
</div>

<!-- Forgot Password Modal -->
<div id="myForgotPassModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
    <h3 id="myModalLabel">Forgot Password?</h3>
  </div>
  <div class="modal-body">
    <form action="../forgot-password" method="post" onsubmit="return(validateForgotPassForm());">
		<table>
		<tr>
			<td>Mobile Number</td>
			<td style="padding-left:1em;"><input id="forgotPassmobileNum" name="mobileNum" type="text" size="10" maxlength="10" placeholder="Mobile number"></td>
		</tr>
		</table>
	</div>
  <div class="modal-footer">
    <div id="registrationValidation"></div>
    <input class="btn btn-primary" name="forgotPassword" value="Submit" type="submit">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    </form>
  </div>
</div>
<script type="text/javascript">
//Validating Registration Form
function validateRegisterForm(){
	var mobilenum = $('#registermobileNum').val();
	var password = $('#registeruserPassword').val();
	var confpassword = $('#registeruserConfirmPassword').val();
	if($.isNumeric(mobilenum) && mobilenum.length==10){
	}else{
		alert("Enter valid Mobile Number!");
		return false;
	}
	if(password==confpassword){
		return true;
	}else{
		alert("Passwords do not match!");
		return false;
	}
}

//Validating Forgot Password Form
function validateForgotPassForm(){
	var mobilenum = $('#forgotPassmobileNum').val();
	if($.isNumeric(mobilenum) && mobilenum.length==10){
	}else{
		alert("Enter valid Mobile Number!");
		return false;
	}
}
</script>
<?php } ?>

<!-- PNR Status
================================================== -->
<section id="searchtrain">
	 <!-- Headings & Paragraph Copy -->

	 <div class="page-header">
    <h3>Trains between two stations</h3>
	</div>
	<div class="row" style="margin-left:auto;">
	<div class="span4">
		<table>
		<tr>
			<td>Source</td>
			<td><input id="sourcestationsearchtrain" name="sourcestationsearchtrain" class="sourcestation" type="text" placeholder="From">
			<!--<div id="sourcestationsearchtraindisplay"></div>-->
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>Destination</td>
			<td><input id="deststationsearchtrain" name="deststationsearchtrain" type="text" placeholder="To">
			<!--<div id="deststationsearchtraindisplay"></div>-->
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>Date of Journey</td>
			<td>
			<input type="text" name="searchtraindateday" id="searchtraindateday" size="4" maxlength="2" style="width:30px" placeholder="DD" /> /
			<input type="text" name="searchtraindatemon" id="searchtraindatemon" size="4" maxlength="2" style="width:30px" placeholder="MM" /> /
			<input type="text" name="searchtraindateyear" id="searchtraindateyear" size="5" maxlength="4" style="width:54px" placeholder="YYYY" />
			<!--<input type="text" id="searchtrainsdatepicker" placeholder="Select Date">--></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><a id="getsearchtrains" class="btn btn-primary">Search Trains</a></td>
			<td style="padding-left:1em;"><a id="getsearchtrains" class="btn">Reset</a></td>
		</tr>
		</table>
		<br><br>
		
	</div>
	<div class="span7">
		<div id="displaytrainfareavailability" style="display: none;"></div>
		<div id="displaytrainbetweenstations">
			<div class="well">
			<h5 class="displaytrainbetweenstationswell"><i class="icon-check-sign"></i> Find Trains Between Two Stations</h5>
			<h5 class="displaytrainbetweenstationswell"><i class="icon-check-sign"></i> Check Seat Availability</h5>
			<h5 class="displaytrainbetweenstationswell"><i class="icon-check-sign"></i> Check Ticket Fare</h5>
			</div>
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
?>