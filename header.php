<?php 
$titlequery=mysql_query("SELECT * FROM metadata WHERE url='" . $_SERVER["REQUEST_URI"] . "'");
$numRows=mysql_num_rows($titlequery);
if(isset($_SESSION['userName']) && ($_SERVER["REQUEST_URI"]=="index" || $_SERVER["REQUEST_URI"]=="")){
	header("Location:../user/profile");
}
if(isset($_SESSION['userName'])){
	$query=mysql_query("SELECT count(*) FROM onesixtybytwo WHERE mobilenum=".$_SESSION['userName']);
	$result = mysql_result($query, 0);
	if($result==1){
		$linkedsmsaccount = true;
	}else{
		$linkedsmsaccount = false;
	}
}
?>
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
	<span style="float:left;"><input id="terms" type="checkbox"> I Agree to the <a href="#">Terms & Conditions</a></span>
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
	var termsChecked = $('#terms:checked').length;
	if($.isNumeric(mobilenum) && mobilenum.length==10){
	}else{
		alert("Enter valid Mobile Number!");
		return false;
	}
	if(password==confpassword){
	}else{
		alert("Passwords do not match!");
		return false;
	}
	if(termsChecked==1){
		return true;
	}else{
		alert("You have to agree to the terms and conditions to complete the sign up!");
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
<?php } 
if($linkedsmsaccount){
	echo "<input type=\"hidden\" name=\"linkedsmsaccount\" id=\"linkedsmsaccount\" value=\"" . 100 . "\">";
}else{
	echo "<input type=\"hidden\" name=\"linkedsmsaccount\" id=\"linkedsmsaccount\" value=\"" . 500 . "\">";
}
?>

