<?php
require_once 'sendsms.php';

if(isset($_POST['mobNumber']) && isset($_POST['pnrStatus'])){
	$toMob = $_POST['mobNumber'];
	$message = $_POST['pnrStatus']; 
	// $message = "P1 - S6 , 25, PQ (CNF)
	// P2 - S6 , 26, PQ (CNF)
	// Charting Status - NOT PREPARED";
	// $message = "PNo. Bkng Status Current Status%0aP1   S6,25,PQ    CNF%0aP2   S6,26,PQ    CNF%0aCHART NOT PREPARED";
	$obj = new sendSMS($toMob,$message);
}
$message="BEGIN:VCALENDAR
VERSION:1.0
BEGIN:VEVENT
DTSTART:20130714T090000
DTEND:20130714T100000
SUMMARY:Test event
LOCATION:chumma
DESCRIPTION:babu
PRIORITY:3
END:VEVENT
END:VCALENDAR";
$obj = new sendSMS("8939686018",$message);
if($obj){
	echo "Message successfully sent";
}else{
	echo "Something is broke. We regret!";
}
?>