<?php 
$titlequery=mysql_query("SELECT title FROM pagetitles WHERE url='" . $_SERVER["REQUEST_URI"] . "'");
$numRows=mysql_num_rows($titlequery);
if(isset($_SESSION['userName']) && ($_SERVER["REQUEST_URI"]=="index" || $_SERVER["REQUEST_URI"]=="")){
	header("Location:../user/profile");
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>
	<?php 
		if($numRows==1){
			echo mysql_result($titlequery,0) . " - quickPNR";
		}else{
			echo "quickPNR";
		}
	?>
	</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="../css/font-awesome.min.css" rel="stylesheet">
	<link href="../css/bootswatch.css" rel="stylesheet">
	<?php //if($_SERVER["REQUEST_URI"]=="/userpnrhistory" || $_SERVER["REQUEST_URI"]=="/smsreminder") {?>
	<link href="../css/pnrhistory.css" rel="stylesheet">
	<?php //} ?>
	
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
       <a class="brand" href="/">quickPNR</a>
       <div class="nav-collapse collapse" id="main-menu">
        <!--<ul class="nav" id="main-menu-left">
          <li><a onclick="pageTracker._link(this.href); return false;" href="http://news.bootswatch.com">News</a></li>
          <li><a id="swatch-link" href="../#gallery">Gallery</a></li>
          <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Preview <b class="caret"></b></a>
            <ul class="dropdown-menu" id="swatch-menu">
              <li><a href="../default/">Default</a></li>
              <li class="divider"></li>
              <li><a href="../amelia/">Amelia</a></li>
              <li><a href="../cerulean/">Cerulean</a></li>
              <li><a href="../cosmo/">Cosmo</a></li>
              <li><a href="../cyborg/">Cyborg</a></li>
              <li><a href="../flatly/">Flatly</a></li>
              <li><a href="../journal/">Journal</a></li>
              <li><a href="../readable/">Readable</a></li>
              <li><a href="../simplex/">Simplex</a></li>
              <li><a href="../slate/">Slate</a></li>
              <li><a href="../spacelab/">Spacelab</a></li>
              <li><a href="../spruce/">Spruce</a></li>
              <li><a href="../superhero/">Superhero</a></li>
              <li><a href="../united/">United</a></li>
            </ul>
          </li>
          <li class="dropdown" id="preview-menu">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">Download <b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li><a target="_blank" href="bootstrap.min.css">bootstrap.min.css</a></li>
              <li><a target="_blank" href="bootstrap.css">bootstrap.css</a></li>
              <li class="divider"></li>
              <li><a target="_blank" href="variables.less">variables.less</a></li>
              <li><a target="_blank" href="bootswatch.less">bootswatch.less</a></li>
            </ul>
          </li>
        </ul>-->	
        <ul class="nav pull-right" id="main-menu-right">
		<?php if(isset($_SESSION['userName'])){ ?>
          <li><a href="../user/profile" title="My Profile"><i class="icon-user"></i> My Account</a></li>
          <li><a href="../logout" title="Logout"><i class="icon-unlock"></i> Logout</a></li>
		<?php }else{ ?>
		  <li><a data-toggle="modal" href="#myRegisterModal" title="Register Now!" id="mainRegisterModal">Sign Up</a></li>
          <li><a data-toggle="modal" href="#myLoginModal" title="Login" id="mainLoginModal">Login</a></li>
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
    <form action="../user/loginaction" method="post" onsubmit="return(validateLoginForm());">
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
	<input class="btn btn-primary" value="Login" name="userLogin" type="submit">
	<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    </form>
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
    <input class="btn btn-primary" name="userRegister" value="Register" type="submit">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    </form>
  </div>
</div>
<script type="text/javascript">
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
function validateLoginForm(){
	var mobilenum = $('#loginmobileNum').val();
	var password = $('#loginPassword').val();
	if($.isNumeric(mobilenum) && mobilenum.length==10){
	}else{
		alert("Enter valid Mobile Number!");
		return false;
	}
	if(password){
	}else{
		alert("Enter Password!");
		return false;
	}
}
</script>
<?php } ?>

