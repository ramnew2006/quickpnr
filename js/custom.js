$(document).ready(function() {
	
	//jQuery event for Enter key press - plugin
	$.fn.pressEnter = function(fn) {  
		return this.each(function() {  
			$(this).bind('enterPress', fn);
			$(this).keyup(function(e){
				if(e.keyCode == 13)
				{
				  $(this).trigger("enterPress");
				}
			})
		});  
	 }; 
	
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
				var newhtml = html+'<br><a id="getSMS" class="getsms btn btn-warning" name="getSMS" rel="tooltip" title="Get Message to your registered Mobile">Get SMS</a>&nbsp;&nbsp;<a data-toggle="modal" href="#mySMSModal" rel="tooltip" title="Send Message to any Mobile" id="sendSMS" class="sendsms btn btn-info" name="sendSMS" value="">Send SMS</a>';
				$('#displaypnrstatus').html(newhtml);
			}
		});
	});
	
	
	//Get PNR Status by pressing Enter key - on PNR Status page
	$('input[name="displaypnrstatusinput"]').pressEnter(function(){
		var current = $(this);
		var pnrnum = $('#displaypnrstatusinput').val();
		if($.isNumeric(pnrnum)){
		}else{
			alert("Enter a Numberic Value");
			return false;
		}
		if(pnrnum==pnrnum.match(/[0-9]{10}/)){
		}else{
			alert("Enter 10 Digit PNR Number");
			return false;
		}
		$('#displaypnrstatus').html("<h3><i class=\"icon-refresh icon-spin\"></i> Retrieving PNR Status...</h3>");
		
		$.ajax({
			type: "POST",
			url: "userscripts/getpnrstatus.php",
			data: "pnrNum="+pnrnum,
			success: function(html){
				var newhtml = html+'<br><a id="getSMS" class="getsms btn btn-warning" name="getSMS" rel="tooltip" title="Get Message to your registered Mobile">Get SMS</a>&nbsp;&nbsp;&nbsp;<a data-toggle="modal" href="#mySMSModal" rel="tooltip" title="Send Message to any Mobile" id="sendSMS" class="sendsms btn btn-info" name="sendSMS" value="">Send SMS</a>';
				$('#displaypnrstatus').html(newhtml);
			}
		});
	});
	
	//User Profile - PNR History- GET PNR Status
	$("#displaypnrstatusinputgetstatus").click(function() {
		var current = $(this);
		var pnrnum = $('#displaypnrstatusinput').val();
		if($.isNumeric(pnrnum)){
		}else{
			alert("Enter a Numberic Value");
			return false;
		}
		if(pnrnum==pnrnum.match(/[0-9]{10}/)){
		}else{
			alert("Enter 10 Digit PNR Number");
			return false;
		}
		$('#displaypnrstatus').html("<h3><i class=\"icon-refresh icon-spin\"></i> Retrieving PNR Status...</h3>");
		
		$.ajax({
			type: "POST",
			url: "userscripts/getpnrstatus.php",
			data: "pnrNum="+pnrnum,
			success: function(html){
				var newhtml = html+'<br><a id="getSMS" class="getsms btn btn-warning" name="getSMS" rel="tooltip" title="Get Message to your registered Mobile">Get SMS</a>&nbsp;&nbsp;&nbsp;<a data-toggle="modal" href="#mySMSModal" rel="tooltip" title="Send Message to any Mobile" id="sendSMS" class="sendsms btn btn-info" name="sendSMS" value="">Send SMS</a>';
				$('#displaypnrstatus').html(newhtml);
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
	
	//Focus on inputs in Login and Register Modals
	$('#myLoginModal').on('shown', function () {
		$('#loginmobileNum').focus();
	});
	$('#myRegisterModal').on('shown', function () {
		$('#registermobileNum').focus();
	});
	
	//Automated PNR Status Update toggle
	$('input[name="automatedpnrupdatestatus"]').click(function(){
		var current = $(this);
		if(current.attr('value')=="Y"){
			$('#automatedpnrupdatefreq').show();
		}
		if(current.attr('value')=="N"){
			$('#automatedpnrupdatefreq').hide();
		}
	});
	
	//Trains between two stations - selecting source station
	$('#sourcestationsearchtrain').keyup(function(){
		var stationname = $('#sourcestationsearchtrain').val();
		if(stationname==""){
			$('#sourcestationsearchtraindisplay').html("");
		}else{
			$.ajax({
				type: "POST",
				url: "userscripts/searchstation.php",
				data: "stationname="+stationname+"&boxname=sourcestationsearchtrain" ,
				success: function(html){
					$('#sourcestationsearchtraindisplay').html(html).show();			
				}
			});
		}
	});
	
	//Trains between two stations - selecting destination station
	$('#deststationsearchtrain').keyup(function(){
		var stationname = $('#deststationsearchtrain').val();
		if(stationname==""){
			$('#deststationsearchtraindisplay').html("");
		}else{
			$.ajax({
				type: "POST",
				url: "userscripts/searchstation.php",
				data: "stationname="+stationname+"&boxname=deststationsearchtrain" ,
				success: function(html){
					$('#deststationsearchtraindisplay').html(html).show();			
				}
			});
		}
	});
	
	//Trains between two stations - Finding results
	$('#getsearchtrains').click(function(){
		var src_stn = $('#sourcestationsearchtrain').val();
		src_stn = src_stn.match(/\((.+)\)/)[1];
		var dest_stn = $('#deststationsearchtrain').val();
		dest_stn = dest_stn.match(/\((.+)\)/)[1];
		$('#displaytrainbetweenstations').html("<h5><i class=\"icon-refresh icon-spin\"></i> Retrieving the trains between two stations...</h5>").show();
		$.ajax({
			type: "POST",
			url: "userscripts/searchtrainschedule.php",
			data: "src_stn="+src_stn+"&dest_stn="+dest_stn ,
			success: function(html){
				$('#displaytrainbetweenstations').html(html);			
			}
		});
	});
	
	//Focus on text box on page landing
	$('input[name="irctcusername"]').focus(); //IRCTC Import Page
	$('#sourcestationsearchtrain').focus(); //Trains Page
	
	
});