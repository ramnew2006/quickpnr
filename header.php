<?php 
$titlequery=mysql_query("SELECT title FROM pagetitles WHERE url='" . $_SERVER["REQUEST_URI"] . "'");
$numRows=mysql_num_rows($titlequery);
?>
  <head>
    <meta charset="utf-8">
    <title>
	<?php 
		if($numRows==1){
			echo mysql_result($titlequery,0);
		}else{
			echo "qwikTravel";
		}
	?>
	</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/bootswatch.css" rel="stylesheet">
	<link href="css/custom.css" rel="stylesheet">
	<?php if($_SERVER["REQUEST_URI"]=="/quickpnr/userpnrhistory.php") {?>
	<link href="css/pnrhistory.css" rel="stylesheet">
	<?php } ?>
	<!-- Start WOWSlider.com HEAD section -->
	<link rel="stylesheet" type="text/css" href="sliderengine/style.css" />
	<!-- End WOWSlider.com HEAD section -->
	
	<!--Loading jQuery in the head for good experience-->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<!--[if lt IE 9]>  
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>  
	<![endif]--> 
	<!--[if lt IE 8]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE8.js"></script>
<![endif]-->
  </head>

  <body class="preview" id="top" data-spy="scroll" data-target=".subnav" data-offset="80">
    

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
       <a class="brand" href="/quickpnr/">qwikTravel</a>
       <div class="nav-collapse collapse" id="main-menu">
        <ul class="nav" id="main-menu-left">
          <!--<li><a onclick="pageTracker._link(this.href); return false;" href="http://news.bootswatch.com">News</a></li>
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
          </li>-->
        </ul>
        <ul class="nav pull-right" id="main-menu-right">
		<?php if(isset($_SESSION['user'])){ ?>
          <li><a href="userprofile.php" title="My Profile"><i class="icon-user"></i> My Account</a></li>
          <li><a href="logout.php" title="Logout"><i class="icon-unlock"></i> Logout</a></li>
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
  
  <div class="subnav" id="spyOnThis" data-spy="scroll">
    <ul class="nav nav-pills">
	<!-- Extra Navigation Menu when the user is logged in-->
	<?php if(isset($_SESSION['user'])){ ?>
	  <?php if($_SERVER["REQUEST_URI"]=="/quickpnr/userprofile.php") {?>
	  <li class="active"><a href="#userprofile">My Account</a></li>
      <?php }else{ ?>
	  <li><a href="userprofile.php">My Account</a></li>
	  <?php } ?>
	  
	  <?php if($_SERVER["REQUEST_URI"]=="/quickpnr/userpnrhistory.php") {?>
      <li class="active"><a href="#pnrhistory">PNR History</a></li>
	  <?php }else{ ?>
	  <li><a href="userpnrhistory.php">PNR History</a></li>
	  <?php } ?>
	  <?php if($_SERVER["REQUEST_URI"]=="/quickpnr/irctcimportview.php") {?>
	  <li class="active"><a href="#irctcimport">Import From IRCTC</a></li>
	  <?php }else{ ?>
	  <li><a href="irctcimportview.php">Import From IRCTC</a></li>
	  <?php } ?>
	<?php } ?>
	  <?php if($_SERVER["REQUEST_URI"]=="/quickpnr/userpnrstatus.php") {?>
      <li class="active"><a href="#pnrstatus">PNR Status</a></li>
	  <?php }else{ ?>
	  <li><a href="userpnrstatus.php">PNR Status</a></li>
	  <?php } ?>
	  <?php if($_SERVER["REQUEST_URI"]=="/quickpnr/searchtrain.php") {?>
	  <li class="active"><a href="#searchtrain">Trains</a></li>
	  <?php }else{ ?>
	  <li><a href="searchtrain.php">Trains</a></li>
	  <?php } ?>	  
	</ul>
  </div>
  
  
<?php if(isset($_SESSION['user'])) { ?>
<?php }else{ ?>
  <!-- Login Modal -->
<div id="myLoginModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon-remove"></i></button>
    <h3 id="myModalLabel">Login to your Account</h3>
  </div>
  <div class="modal-body">
  <?php $_SESSION['redirect_url']=$_SERVER["REQUEST_URI"]; ?>
    <form action="loginaction.php" method="post">
		<table>
		<tr>
			<td>Mobile Number</td>
			<td style="padding-left:1em;"><input id="loginmobileNum" name="mobileNum" type="text" size="10" maxlength="10" placeholder="Mobile number"></td>
		</tr>
		<tr>
			<td>Password</td>
			<td style="padding-left:1em;"><input name="userPassword" type="password" placeholder="Password"><br/>
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
  <?php $_SESSION['redirect_url']=$_SERVER["REQUEST_URI"]; ?>
    <form action="doregister.php" method="post" onsubmit="return(validateForm());">
		<table>
		<tr>
			<td>Mobile Number</td>
			<td style="padding-left:1em;"><input id="registermobileNum" name="mobileNum" type="text" size="10" maxlength="10" placeholder="Mobile number"></td>
		</tr>
		<tr>
			<td>Password</td>
			<td style="padding-left:1em;"><input name="userPassword" type="password" placeholder="Password"><br/>
		</tr>
		</table>
	</div>
  <div class="modal-footer">
	<input class="btn btn-primary" name="userRegister" value="Register" type="submit">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    </form>
  </div>
</div>
<?php } ?>