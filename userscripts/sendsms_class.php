<?php

class sendSMS {
	
	private $username;
	private $password;
	private $htmldoc;
	private $toMob;
	private $message;
	private $cookieinfo;
	private $url_id;
	private $tempfile;
	private $finalsmspostparams;
	
	function __construct($toMob, $message) {
		$this->username = "8122636821";
		$this->password = "man0har";
		$this->toMob = $toMob;
		$this->message = $message;
		$this->tempfile = time() . mt_rand();
		$this->finalsmspostparams = "&hid_exists=no&&UgadHieXampp=ugadicome&aprilfoolc=HoliWed27&janrepu=April3wed&marYellundi=abcdef12345tops&by2Hidden=by2sms&feb2by2action=sa65sdf656fdfd&sel_month=0&sel_day=0&sel_year=0&sel_hour=hh&sel_minute=mm&sel_cat=0&txta_fback=";
		$this->doLogin();
		$this->smsPage();
		if($this->finalPage()=="http://www.160by2.com/SendSMSConfirm.action?id=".$this->url_id){
			return true;
		}else{
			return false;
		}
	}
	
	private function doLogin(){
		$postParams = "username=" . $this->username . "&password=" . $this->password . "&button=Login";
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
		curl_setopt($ch, CURLOPT_COOKIEJAR, '/var/www/userscripts/tmp/cookies/' . $this->tempfile . '_cookie.txt'); //store the cookie in a local file
		curl_setopt($ch, CURLOPT_COOKIEFILE, '/var/www/userscripts/tmp/cookies/' . $this->tempfile . '_cookie.txt');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_URL,$postUrl);   //To set the page to be fetched
		$result = curl_exec($ch);    //Execute and return the response
		$info = curl_getinfo($ch); //Get the headers
		curl_close($ch);
		
		preg_match_all('/(.*)/', $info['request_header'], $matches); //extract cookie part from the header
		
		$cookieinfo = $matches[1][4]; //output to a variable
		$cookieinfo = preg_replace('/Cookie: /','',$cookieinfo);
		$cookieinfo = preg_replace('/adMask=adMask;/','',$cookieinfo); //remove un necessary params in cookie values
		
		$this->cookieinfo = trim($cookieinfo);

		$url = $matches[1][0]; //get the redirect url
		$url = preg_replace('/GET /','',$url);
		$url = preg_replace('/ HTTP\/1.1/','',$url);
		preg_match_all('/(\?id=)(.*)/',$url, $temp);

		$this->url_id = trim($temp[2][0]); //output the session id to the variable
	}
	
	private function smsPage(){
		$getUrl = "http://www.160by2.com/FebSendSMS?id=" . $this->url_id; //get this url
		$referrer = "http://www.160by2.com/Home.action?id=" . $this->url_id; 

		$header = array("Cookie: " . $this->cookieinfo, 
							"Connection:keep-alive",
							"Host:www.160by2.com",
							"Referer:" . $referrer,
							"User-Agent:Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.112 Safari/535.1");
		$ch = curl_init();        //Initialize the cURL handler
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);    //To give the file fetched back to the handler
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);   //To stop redirects
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_COOKIEJAR, '/var/www/userscripts/tmp/cookies/' . $this->tempfile . '_cookie.txt'); 
		curl_setopt($ch, CURLOPT_COOKIEFILE, '/var/www/userscripts/tmp/cookies/' . $this->tempfile . '_cookie.txt');
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_URL,trim($getUrl));   //To set the page to be fetched
		$output = curl_exec($ch);    //Execute and return the response
		curl_close($ch);

		file_put_contents('/var/www/userscripts/tmp/' . $this->tempfile . '.html',$output); //write the output to a temporary html file
	}
	
	private function finalPage(){
		
		$htmldoc = file_get_contents('/var/www/userscripts/tmp/' . $this->tempfile . '.html'); //construct a html variable from the temporary html file

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
				$postparams .= $name . "=" . $this->toMob . "&";
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
				$postparams .= $name . "=" . $this->message . "&";
			}
			$field_vals[$name] = $val;
		}

		$postparams .= "maxwellapps=" . $this->url_id . $this->finalsmspostparams;
		
		$postUrl = "http://www.160by2.com/SendSMS2013";
		$referrer = "http://www.160by2.com/FebSendSMS?id=" . $this->url_id;

		$header = array("Content-Type:application/x-www-form-urlencoded",
							"Cookie: " . $this->cookieinfo . ";adCookie=3; shiftpro=axisproapril8th",
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
		curl_setopt($ch, CURLOPT_COOKIEJAR, '/var/www/userscripts/tmp/cookies/' . $this->tempfile . '_cookie.txt'); //store the cookie in a local file
		curl_setopt($ch, CURLOPT_COOKIEFILE, '/var/www/userscripts/tmp/cookies/' . $this->tempfile . '_cookie.txt');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_URL,$postUrl);   //To set the page to be fetched
		$output = curl_exec($ch);    //Execute and return the response
		$info = curl_getinfo($ch); //Get the headers
		curl_close($ch);
		
		//print_r($info);
		
		unlink('/var/www/userscripts/tmp/' . $this->tempfile . '.html');
		unlink('/var/www/userscripts/tmp/cookies/' . $this->tempfile . '_cookie.txt');
		return $info['url'];
	}
}
?>