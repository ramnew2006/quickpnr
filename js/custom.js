//User Profile - Sending SMS
$(".getsms").one("click",function() {
var current = $(this);
var pnrnum = $(this).attr('name');
pnrnum = pnrnum.match(/[0-9]+/);
current.html("<i class=\"icon-refresh icon-spin\"></i> Sending...");
//alert("You are going to send the SMS for "+ pnrnum);
  
  $.ajax({
		type: "POST",
		url: "messagesend.php",
		data: "pnrNum="+pnrnum ,
		success: function(html){
			if(html=="Success"){
				current.html("<i class=\"icon-check\"></i> "+html);
				current.attr('class','sendsms btn btn-inverse disabled');
			}else if(html=="Failure"){
				current.html("<i class=\"icon-check\"></i> "+html);
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
