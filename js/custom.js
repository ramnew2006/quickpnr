$(document).ready(function() {
	
	//User Profile - PNR History- GET PNR Status
	$(".getpnrstatus").click(function() {
		var current = $(this);
		var pnrnum = $(this).attr('name');
		pnrnum = pnrnum.match(/[0-9]+/);
		$('#displaypnrstatus').html("<h3><i class=\"icon-refresh icon-spin\"></i> Retrieving PNR Status...</h3>");
		
		$.ajax({
			type: "POST",
			url: "userscripts/getpnrstatus.php",
			data: "pnrNum="+pnrnum,
			success: function(html){
				$('#displaypnrstatus').html(html);
			}
		});
	});
	
	//User Profile - PNR History- GET PNR Status
	$("#displaypnrstatusinputgetstatus").click(function() {
		var current = $(this);
		var pnrnum = $('#displaypnrstatusinput').val();
		$('#displaypnrstatus').html("<h3><i class=\"icon-refresh icon-spin\"></i> Retrieving PNR Status...</h3>");
		
		$.ajax({
			type: "POST",
			url: "userscripts/getpnrstatus.php",
			data: "pnrNum="+pnrnum,
			success: function(html){
				$('#displaypnrstatus').html(html);
			}
		});
	});

	//User Profile - Sending SMS to Registered Mobile
	$(".getsms").one("click",function() {
		var current = $(this);
		var pnrnum = $(this).attr('name');
		pnrnum = pnrnum.match(/[0-9]+/);
		current.html("<i class=\"icon-refresh icon-spin\"></i> Sending...");
		//alert("You are going to send the SMS for "+ pnrnum);
		  
		$.ajax({
			type: "POST",
			url: "userscripts/sendtextmessage.php",
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
	$('.sendsms').click(function(){ //Toggle the send SMS button on modal
		$('.sendsmsanymobile').html("Send SMS");
		$('.sendsmsanymobile').attr('class','sendsmsanymobile btn btn-info');
	});
	
	$(".sendsmsanymobile").click(function() {
		var current = $(this);
		var anymobilenum = $('#currentmobileNum').val();
		if(anymobilenum){ //if mobile number is empty stop the action
		}else{
			return false;
		}	
		if(current.attr('class')=="sendsmsanymobile btn btn-info"){ //If the Sens SMS button is active then continue the action
			var pnrnum = $('#currentpnr').val();
			current.html("<i class=\"icon-refresh icon-spin\"></i> Sending...");
			alert("You are going to send the SMS for "+ pnrnum);
			  
				$.ajax({
					type: "POST",
					url: "userscripts/sendtextmessage.php",
					data: "pnrNum="+pnrnum+"&mobileNum="+anymobilenum+"&anyMobile=Y" ,
					success: function(html){
						if(html=="Success"){
							current.html("<i class=\"icon-ok\"></i> "+html);
							current.attr('class','sendsmsanymobile btn btn-info disabled');
							//current.attr('disabled','disabled');
						}else if(html=="Failure"){
							current.html("<i class=\"icon-warning-sign\"></i> "+html);
							current.attr('class','sendsmsanymobile btn btn-info disabled');
						}else{
							current.html("Try Again!");
						}
					}
				});
		}else{
			return false;
		}
	});

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
		var current = $(this);
		var irctcusername = $('#irctcusername').val();
		var irctcpassword = $('#irctcpassword').val();
		
		$('#displayirctcimportstatus').html("<h5><i class=\"icon-refresh icon-spin\"></i> Importing PNR History from your IRCTC account...</h5>");
		//current.html("<i class=\"icon-refresh icon-spin\"></i> Importing...");
		$.ajax({
			type: "POST",
			url: "userscripts/irctcpnrhistoryimport.php",
			data: "irctcUsername="+irctcusername+"&irctcPassword="+irctcpassword+"&savePnrHistory=Y" ,
			success: function(html){
				var result = html;
				if(result=="Success"){
					$('#displayirctcimportstatus').html("<h5>Successfully Imported your booking history!</h5>");
					//current.html("<i class=\"icon-thumbs-up\"></i> Success");
				} else if(result=="Failure"){
					$('#displayirctcimportstatus').html("<h5>Your Booking history has already been imported and upto date!!</h5>");
					//current.html("<i class=\"icon-warning-sign\"></i> Enough");
				} else {
					$('#displayirctcimportstatus').html("<h5>There is a problem connecting to IRCTC Servers. Please try again!</h5>");
					//current.html("<i class=\"icon-warning-sign\"></i> Try Again");
				} 			
			}
			
		});
	});
	
	
	
});