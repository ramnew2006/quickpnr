<?php
session_start();

$postParams = "username=8122636821&password=man0har&button=Login";
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
curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookie.txt'); //store the cookie in a local file
curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__).'/cookie.txt');
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_URL,$postUrl);   //To set the page to be fetched
$output = curl_exec($ch);    //Execute and return the response
$info = curl_getinfo($ch); //Get the headers
curl_close($ch);

//print_r($info);

preg_match_all('/(.*)/', $info['request_header'], $matches); //extract cookie part from the header
$cookieinfo = $matches[1][4]; //output to a variable
$cookieinfo = preg_replace('/Cookie: /','',$cookieinfo);
$cookieinfo = preg_replace('/adMask=adMask;/','',$cookieinfo); //remove un necessary params in cookie values
$cookieinfo = trim($cookieinfo);

$url = $matches[1][0]; //get the redirect url
$url = preg_replace('/GET /','',$url);
$url = preg_replace('/ HTTP\/1.1/','',$url);
preg_match_all('/(\?id=)(.*)/',$url, $temp);

$url_id = trim($temp[2][0]); //output the session id to the variable

//second step getting the post parameters to send the message

$getUrl = "http://www.160by2.com/FebSendSMS?id=" . $url_id; //get this url
$referrer = "http://www.160by2.com/Home.action?id=" . $url_id; 

$header = array("Cookie: " . $cookieinfo, 
					"Connection:keep-alive",
					"Host:www.160by2.com",
					"Referer:" . $referrer,
					"User-Agent:Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.112 Safari/535.1");
$ch = curl_init();        //Initialize the cURL handler
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);    //To give the file fetched back to the handler
curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);   //To stop redirects
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookie.txt'); 
curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__).'/cookie.txt');
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_URL,trim($getUrl));   //To set the page to be fetched
$output = curl_exec($ch);    //Execute and return the response
$info = curl_getinfo($ch);
curl_close($ch);

$tempfile = time() . mt_rand();

file_put_contents(dirname(__FILE__).'/tmp/' . $tempfile . '.html',$output); //write the output to a temporary html file
$htmldoc = file_get_contents(dirname(__FILE__).'/tmp/' . $tempfile . '.html'); //construct a html variable from the temporary html file

$responseDOM = new DOMDocument();        //Create new DOM Object
$responseDOM->strictErrorChecking=false;
$responseDOM->recover=true;
@$responseDOM->loadHTML($htmldoc); //Load the HTML into the DOM Object
$responseText = $responseDOM->getElementsByTagName('textarea'); //Parse the DOM Object and get the required tag
$responseInput = $responseDOM->getElementsByTagName('input');

$postparams = "";

foreach ($responseInput as $input) {
    $name = $input->getAttribute('name');
    $val = $input->getAttribute('value');
    if($val=="Enter mobile number or name"){
		$postparams .= $name . "=8939686018&";
	}else{
		if(ctype_upper($name)){
			$postparams .= $name . "=" . $val . "&";
		}
	}	
	$field_vals[$name] = $val;
}

foreach ($responseText as $input) {
    $name = $input->getAttribute('name');
    $val = $input->getAttribute('value');
	if(ctype_upper($name)){
		$postparams .= $name . "=holaaaa" . "&";
	}
	$field_vals[$name] = $val;
}

$postparams .= "hid_exists=no&maxwellapps=" . $url_id . "&UgadHieXampp=ugadicome&aprilfoolc=HoliWed27&janrepu=April3wed&marYellundi=abcdef12345tops&by2Hidden=by2sms&feb2by2action=sa65sdf656fdfd&sel_month=0&sel_day=0&sel_year=0&sel_hour=hh&sel_minute=mm&sel_cat=0&txta_fback=";
echo $postparams;
//get the required post parameters and do the final post request

$postUrl = "http://www.160by2.com/SendSMS2013";
$referrer = "http://www.160by2.com/FebSendSMS?id=" . $url_id;

$header = array("Content-Type:application/x-www-form-urlencoded",
					"Cookie: " . $cookieinfo . ";adCookie=3; shiftpro=axisproapril8th",
					"Connection:keep-alive",
					"Origin:http://www.160by2.com",
					"Host:www.160by2.com",
					"Referer:" . $referrer,
					"User-Agent:Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.112 Safari/535.1");
$ch = curl_init();        //Initialize the cURL handler
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);    //To give the file fetched back to the handler
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);   //To stop redirects
curl_setopt($ch, CURLOPT_POSTFIELDS,$postparams); //Post fields
curl_setopt($ch, CURLOPT_POST,true); //To send the POST parameters
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookie.txt'); //store the cookie in a local file
curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__).'/cookie.txt');
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_URL,$postUrl);   //To set the page to be fetched
$output = curl_exec($ch);    //Execute and return the response
$info = curl_getinfo($ch); //Get the headers
echo curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);


//print_r($output);

unlink(dirname(__FILE__).'/tmp/' . $tempfile . '.html');

// $getUrl = "http://www.160by2.com/SendSMSConfirm.action?id=" . $url_id; //get this url
// $referrer = "http://www.160by2.com/FebSendSMS?id=" . $url_id; 

// $header = array("Cookie: " . $cookieinfo, 
					// "Connection:keep-alive",
					// "Host:www.160by2.com",
					// "Referer:" . $referrer,
					// "User-Agent:Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.112 Safari/535.1");
// $ch = curl_init();        //Initialize the cURL handler
// curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);    //To give the file fetched back to the handler
// curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);   //To stop redirects
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
// curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
// curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookie.txt'); 
// curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__).'/cookie.txt');
// curl_setopt($ch, CURLINFO_HEADER_OUT, true);
// curl_setopt($ch, CURLOPT_URL,trim($getUrl));   //To set the page to be fetched
// $output = curl_exec($ch);    //Execute and return the response
// $info = curl_getinfo($ch);
// curl_close($ch);

// print_r($info);
?>
