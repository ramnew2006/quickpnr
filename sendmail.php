<?php

require_once 'class.phpmailer.php';

class sendMail {

	private $mail;
	private $mailfrom;
	private $mailto;
	private $mailsubject;
	private $mailmessage;
	
	function __construct($mailto, $mailfrom, $mailsubject, $mailmessage) {
		$this->mailfrom = $mailfrom;
		$this->mailto = $mailto;
		$this->mailsubject = $mailsubject;
		$this->mailmessage = $mailmessage;
		$this->mail = new PHPMailer(true);
		$this->smtpSetting();
		$this->mailSetting();
		$this->mailSend();
	}
	
	private function smtpSetting(){
		$this->mail->IsSMTP(); // telling the class to use SMTP
		$this->mail->SMTPAuth = true; // enable SMTP authentication
		$this->mail->SMTPSecure = "ssl"; // sets the prefix to the servier
		$this->mail->Host = "smtp.gmail.com"; // sets GMAIL as the SMTP server
		$this->mail->Port = 465; // set the SMTP port for the GMAIL server
		$this->mail->Username = "ramnew2006@gmail.com"; // GMAIL username
		$this->mail->Password = "!@#har0man"; // GMAIL password
	}
	
	private function mailSetting(){
		$this->mail->AddAddress($this->mailto, "Ram");
		$this->mail->SetFrom($this->mailfrom, "Babu");
		$this->mail->Subject = $this->mailsubject;
		$this->mail->Body = $this->mailmessage;
	}

	private function mailSend(){
		try{
			$this->mail->Send();
			return true;
			echo "Success!";
		} catch(Exception $e){
			//Something went bad
			return false;
			echo "Fail :(";
		}
	}
	

}

?>
