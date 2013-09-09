<?php
session_start();
require_once '../database.php';

$dbobj = new database();
$dbobj->dbconnect();

if(isset($_SESSION['userName'])){
	$quickpnrmob = $_SESSION['userName'];
	session_write_close();
	if(isset($_POST['onesixU']) && isset($_POST['onesixP'])){
		$username = $_POST['onesixU']; //"8122636821";
		$password = $_POST['onesixP']; //"man0har";

		$result = doLogin($username,$password);
		
		if (strpos($result,'Login') !== false) {
		    echo "Failure";
		}
		if (strpos($result,'Home') !== false) {
			$query = mysql_query("INSERT INTO onesixtybytwo (mobilenum, username, password) VALUES ('" . $quickpnrmob . "','" . $username . "', AES_ENCRYPT('" . $password . "','" . $dbobj->returnSalt() . "'))");
		    echo "Success";
		}
	
	}else{
		header("Location:/user/profile");
	}
}else{
	header("Location:/");
}

function doLogin($username,$password){
	$tempfile=time() . mt_rand();
	$postParams = "username=" . $username . "&password=" . $password . "&button=Login";
	$postUrl = "http://www.160by2.com/re-login";

	$header = array("Content-Type:application/x-www-form-urlencoded","Host:www.160by2.com",
						"Origin:http://www.160by2.com",
						"Proxy-Connection:keep-alive",
						"Connection:keep-alive",
						"Referer:http://www.160by2.com/",
						"User-Agent:Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.112 Safari/535.1");
	$ch = curl_init();        //Initialize the cURL handler
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);    //To give the file fetched back to the handler
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);   //To stop redirects
	curl_setopt($ch, CURLOPT_POSTFIELDS,$postParams); //Post fields
	curl_setopt($ch, CURLOPT_POST,true); //To send the POST parameters
	curl_setopt($ch, CURLINFO_HEADER_OUT, true);
	curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/tmp/cookies/' . $tempfile . '_cookie.txt'); //store the cookie in a local file
	curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__).'/tmp/cookies/' . $tempfile . '_cookie.txt');
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_URL,$postUrl);   //To set the page to be fetched
	$result = curl_exec($ch);    //Execute and return the response
	$info = curl_getinfo($ch); //Get the headers
	curl_close($ch);
	
	unlink(dirname(__FILE__).'/tmp/cookies/' . $tempfile . '_cookie.txt');
	return $info['url'];
}

$dbobj->dbdisconnect();
?>