//User Profile - Sending SMS to Registered Mobile
$(".getsms").one("click",function() {
var current = $(this);
var pnrnum = $(this).attr('name');
pnrnum = pnrnum.match(/[0-9]+/);
current.html("<i class=\"icon-refresh icon-spin\"></i> Sending...");
//alert("You are going to send the SMS for "+ pnrnum);
  
  $.ajax({
		type: "POST",
		url: "messagesend.php",
		data: "pnrNum="+pnrnum+"&regMobile=Y" ,
		success: function(html){
			if(html=="Success"){
				current.html("<i class=\"icon-ok\"></i> "+html);
				current.attr('class','sendsms btn btn-inverse disabled');
			}else if(html=="Failure"){
				current.html("<i class=\"icon-warning-sign\"></i> "+html);
				current.attr('class','sendsms btn btn-inverse disabled');
			}else{
				current.html("Try later");
			}
		}
	});

});

//User Profile - Sending SMS to Any Mobile
$("#sendSMSanyMobile").on("click",function() {
var current = $(this);
var anymobilenum = $('#currentmobileNum').val();
var pnrnum = $('#currentpnr').val();
current.html("<i class=\"icon-refresh icon-spin\"></i> Sending...");
alert("You are going to send the SMS for "+ pnrnum);
  
  $.ajax({
		type: "POST",
		url: "messagesend.php",
		data: "pnrNum="+pnrnum+"&mobileNum="+anymobilenum+"&anyMobile=Y" ,
		success: function(html){
			if(html=="Success"){
				current.html("<i class=\"icon-ok\"></i> "+html);
				current.attr('class','sendsms btn btn-inverse disabled');
			}else if(html=="Failure"){
				current.html("<i class=\"icon-warning-sign\"></i> "+html);
				current.attr('class','sendsms btn btn-inverse disabled');
			}else{
				current.html("Try later");
			}
		}
	});
});

//Loader for Get PNR Status on User Profile
$("#getStatus").on("click",function() {
var current = $(this);
current.val("Retrieving...");
});
function pnrtimevalidate(){
	
}

//User Profile - Change mobile number - Toggle
$("#changeprofilemob").on("click",function() {
	$('#formprofilemob').show();
	$('#profilemob').hide();
});
$("#cancelprofilemob").on("click",function() {
	$('#formprofilemob').hide();
	$('#profilemob').show();
});

//User Profile - Password Toggle
$("#changeprofilepwd").on("click",function() {
	$('#formprofilepwd').show();
	$('#profilepwd').hide();
});
$("#cancelprofilepwd").on("click",function() {
	$('#formprofilepwd').hide();
	$('#profilepwd').show();
});

$(".sendsms").one("click",function() {
	var current = $(this);
	$('#currentpnr').val(current.attr('value'));
});

//Import IRCTC PNR History
$("#importirctchistory").click(function() {
	$('#irctcimportprogress').show();
	var progress = 0;
	var irctcusername = $('#irctcusername').val();
	var irctcpassword = $('#irctcpassword').val();
	var importresult = "Initial";
	
	$.ajax({
		type: "POST",
		url: "irctcpnrhistory.php",
		data: "irctcUsername="+irctcusername+"&irctcPassword="+irctcpassword+"&savePnrHistory=Y" ,
		success: function(html){
			importresult = html;
		}
		
	});
	
	$.ajax({
		dataType: "json",
		type: "GET",
		async: false,
		url: "irctcpnrimportprogress.php",
		success: function(data){
			$('#progressbar').attr('style','width:'+progress+'%;');
			progress = data['result'];
			$('#progressbar').attr('style','width:'+progress+'%;');
		}
	});
	
	$('#irctcimportprogress').html(importresult).delay(4000);
	
});