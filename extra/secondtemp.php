<?php
session_start();

$postUrl = "http://www.160by2.com/FebSendSMS?id=1DD15FC87AF92459C2B4FF0577AEAE0A.8705"; //. $_SESSION['urlid'];
$referrer = "http://www.160by2.com/Home.action?id=1DD15FC87AF92459C2B4FF0577AEAE0A.8705"; // . $_SESSION['urlid'];
echo $_SESSION['cookieinfo'];
$header = array("Cookie: " . $_SESSION['cookieinfo'], 
					"Connection:keep-alive",
					//	"Host:www.160by2.com",
					"Referer:" . $referrer,
					"User-Agent:Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.112 Safari/535.1");
$ch = curl_init();        //Initialize the cURL handler
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);    //To give the file fetched back to the handler
curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);   //To stop redirects
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
//curl_setopt($ch, CURLOPT_PROXY, "http://127.0.0.1:8888" );  
//curl_setopt($ch, CURLOPT_REFERER, $referrer);
curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookie.txt'); 
curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__).'/cookie.txt');
//curl_setopt($ch, CURLOPT_COOKIE, $newcookies[0]); 
//curl_setopt($ch, CURLOPT_COOKIE, $newcookies[1]); 
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
//curl_setopt($ch, CURLOPT_VERBOSE, true);
//curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_URL,trim($postUrl));   //To set the page to be fetched
$output2 = curl_exec($ch);    //Execute and return the response
$info = curl_getinfo($ch);
curl_close($ch);

//file_put_contents(dirname(__FILE__).'/temp.html',$output2);
//$htmldoc = file_get_contents(dirname(__FILE__).'/temp.html');
$htmldoc = file_get_contents($output2);


$responseDOM = new DOMDocument();        //Create new DOM Object
$responseDOM->strictErrorChecking=false;
$responseDOM->recover=true;
@$responseDOM->loadHTML($htmldoc); //Load the HTML into the DOM Object
//$thu = $responseDOM->saveXML();
$responseTable = $responseDOM->getElementsByTagName('input'); //Parse the DOM Object and get the required Table
//$responseRows = @$responseTable->childNodes;
//print_r(getElementsByClassName("mob-cont"));
//print_r($xml);

foreach ($responseTable as $input) {
    $name = $input->getAttribute('name');
    $val = $input->getAttribute('value');
    echo $name . ":" . $val . "<br/>";
	$field_vals[$name] = $val;
}




?>