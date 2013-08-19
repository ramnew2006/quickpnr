<?php
class database{
	
	private $dbhost;
	private $dbuser;
	private $dbpass;
	private $dbname;
	private $dblink;
	private $dbsalt;
		
	public function database(){
		$this->dbhost="localhost";
		$this->dbuser="root";
		$this->dbpass="";
		$this->dbname="quickpnr";
	}
	
	public function dbconnect(){
		$this->dblink=mysql_connect($this->dbhost,$this->dbuser,$this->dbpass);
		if($this->dblink){
			mysql_select_db($this->dbname);
		}
	}
	
	public function dbdisconnect(){
		mysql_close($this->dblink);
	}
	
	public function returnSalt(){
		$this->dbsalt = "qWikTrAVel";
		return $this->dbsalt;
	}
}
?>
