<?php 
class getcurl {
 
	private $ch;    //cURL Handler
	private $tableRows; //calculate the total rows in the result table
	private $tableNum; //assign the tables number under consideration
	private $responseRows;
	private $getUrl; //post url to be used
	private $userAgent = "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/13.0.782.112 Safari/535.1";
	
	function __construct($getUrl,$tableNum) {
		$this->getUrl = $getUrl;
		$this->tableNum = $tableNum;
		//Fetching Started
		$this->setResponseRows();
	}
	
	private function curlOpt(){
		$this->ch = curl_init();        //Initialize the cURL handler
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER,true);    //To give the file fetched back to the handler
		curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION,false);   //To stop redirects
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($this->ch, CURLOPT_USERAGENT, $this->userAgent);    //To mask the robot as a browser
		curl_setopt($this->ch, CURLOPT_URL, $this->getUrl);   //To set the page to be fetched
		return curl_exec($this->ch);    //Execute and return the response
    }
	
	private function setResponseRows(){
		$responseHTML = $this->curlOpt();
		$responseDOM = new DOMDocument();        //Create new DOM Object
		$responseDOM->strictErrorChecking=false;
		$responseDOM->recover=true;
		@$responseDOM->loadHTML($responseHTML); //Load the HTML into the DOM Object
		$responseTable = $responseDOM->getElementsByTagName("table")->item($this->tableNum); //Parse the DOM Object and get the required Table
		$this->responseRows = @$responseTable->childNodes;
	}
	
	//return the text of the element in the table using row and column number
	public function getInfoFromRow($rowNumber,$colNumber) {
		return @$this->responseRows->item($rowNumber)->getElementsByTagName('td')->item($colNumber)->textContent;
	}
	
	//return the number of rows in the table under consideration
	public function tableRows(){
		$this->tableRows = $this->responseRows->length;
		return $this->tableRows;
	}

}
?>