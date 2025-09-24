<?php
//App::import('Vendor', 'PHPMailer', array('file' => 'phpmailer' . DS . 'class.phpmailer.php'));
require_once("../Vendor/PhpMailer/class.phpmailer.php");
/*
    Depends on "unhtml"
*/
class PhpMailerEmailComponent extends Component
{
        // phpmailer
	//var $Mailer = 'sendmail'; // choose 'sendmail', 'mail', 'smtp'
	//var $unhtml_bin = '/usr/bin/unhtml';
	 
	//var $smtpserver    =  "mail.yourdomain.com";
	//var $host          =  "smtp.gmail.com";
	//var $SMTPDebug     =  1;
	//var $mailPort      =  465;
	//var $mail_username =  "debiprasad.sahoo@navsoft.in";
	//var $mail_password =  'zxcvbnm!@'; 
	//var $mail_set_from =  FROMEMAIL;
	//var $mail_reply_to =  FROMEMAIL;
       
	
	function send_mail($mailTo,$subject, $body, $from,$mailToName,$filename,$bounce)
	{
		
		$successmail=false;
		$mailTitle = '';
		$mailBodyContent='';
			 
		$headers  = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\n";
		$headers .= "From: Huracan\n";
		//$headers .= "Return-Path: ".$bounce."<".$bounce.">"."\n";
		
		$mail             = new PHPMailer();

		$body             = $body;
		$body             = eregi_replace("[\]",'',$body);
		// 			$body             ;die();
		//$mail->IsSMTP(); // telling the class to use SMTP
		//$mail->Host       = $this->smtpserver; // SMTP server
		//$mail->SMTPDebug  = $this->SMTPDebug; 
		// enables SMTP debug information (for testing)
	   // 1 = errors and messages
       // 2 = messages only
		//$mail->SMTPAuth   = true;
		// enable SMTP authentication
		//$mail->SMTPSecure = "ssl";  
		// sets the prefix to the servier
		///$mail->Host       = $this->host;
		// sets GMAIL as the SMTP server
		///$mail->Port       = $this->mailPort;  
		// set the SMTP port for the GMAIL server
		///$mail->Username   = $this->mail_username; 
		// GMAIL username
		//$mail->Password   = $this->mail_password; 
		// GMAIL password
		
		//$mail->SetFrom("ac@gmail.com",'Huracan');
		
		$mail->SetFrom($from,'Huracan<hird_noreply@huracan.com.au>');
		$mail->AddReplyTo($from,'Huracan Team');
		
		$mail->Subject    = $subject;
  if(isset($filename[1])){
		for($i=0; $i < count($filename[1]); $i++){
		$mail->AddAttachment($filename[0].$filename[1][$i],$filename[1][$i]);
		 }
	}
		$mail->AltBody    = ""; // optional, comment out and test
		
		$mail->MsgHTML($body);
		$mail->IsHTML(true);
		
		$mail->AddAddress($mailTo, '');
		//$mail->AddCC(FROMEMAIL, "poorvi"); // check the mailing functionality.
		
		$mail->CharSet="windows-1251";
		
		$mail->CharSet="utf-8";
		
		$mail->Send();
		echo '<pre>';
		print_r($mail);
	
	}
}
?>