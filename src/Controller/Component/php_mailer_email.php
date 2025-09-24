<?php
    require_once("../vendors/PHPMailer/class.phpmailer.php");
	/*
	    Depends on "unhtml"
	*/
	class PhpMailerEmailComponent extends PHPMailer
	{
	    // phpmailer
	    var $Mailer = 'sendmail'; // choose 'sendmail', 'mail', 'smtp'
	    var $unhtml_bin = '/usr/bin/unhtml';
	     
		var $smtpserver    =  "mail.yourdomain.com";
		var $host          =  "smtp.gmail.com";
		var $SMTPDebug     =  1;
		var $mailPort      =  465;
		var $mail_username =  "dilip.swain@navsoft.in";
		var $mail_password =  "password#123"; 
		var $mail_set_from =  "info@izdot.com";
		var $mail_reply_to =  "info@izdot.com";
	    
		function send_invitation($mailTo, $mailToName, $subject, $body, $from, $bounce)
		{
			$successmail=false;
			$mailTitle = "Newsletter";
			$mailBodyContent='';
				 
			//$headers  = "MIME-Version: 1.0\n";
			//$headers .= "Content-type: text/html; charset=iso-8859-1\n";
			//$headers .= "From: info@bowwowmeow.com <info@bowwowmeow.com>\n";
			$bounce_mail = "Reply-to: ".$bounce;
			
			$mail             = new PHPMailer();
			$body             = $body;
			$body             = eregi_replace("[\]",'',$body);
			// 			$body             ;die();
			$mail->IsSMTP(); // telling the class to use SMTP
			$mail->Host       = $this->smtpserver; // SMTP server
			$mail->SMTPDebug  = $this->SMTPDebug;                     // enables SMTP debug information (for testing)
													   // 1 = errors and messages
													   // 2 = messages only
			$mail->SMTPAuth   = true;                  // enable SMTP authentication
			$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
			$mail->Host       = $this->host;      // sets GMAIL as the SMTP server
			$mail->Port       = $this->mailPort;                   // set the SMTP port for the GMAIL server
			$mail->Username   = $this->mail_username;  // GMAIL username
			$mail->Password   = $this->mail_password;            // GMAIL password
			
			$mail->SetFrom($this->mail_set_from, $from);
			
			$mail->AddReplyTo($this->mail_reply_to, $from);
			
			//$mail->AddCustomHeader($bounce_mail);

			
			$mail->Sender = $bounce;
			
			$mail->Subject    = $subject;
			
			$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
			
			$mail->MsgHTML($body);
			$mail->IsHTML(true);
			
			$mail->AddAddress($mailTo, $mailToName);
			
			$mail->CharSet="windows-1251";
			
			$mail->CharSet="utf-8";
			
			$mail->Send();
		}
		
		function sendCampaign( $to_email , $to_name , $from_email , $from_name , $reply_email = '' , $reply_name = '', $subject , $body , $body_text = '' ,  $html = true ){
			
			$mail             = new PHPMailer();
			$mail->IsSMTP();
			$mail->SMTPAuth   = true;                  // enable SMTP authentication
			$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
			
			//// use the credentials set in this componenet 
			$mail->Host       = $this->host          ;  // sets GMAIL as the SMTP server
			$mail->Port       = $this->mailPort      ;  // set the SMTP port
			$mail->Username   = $this->mail_username ;  // GMAIL username
			$mail->Password   = $this->mail_password ;  // GMAIL password
			//// use the credentials set in this componenet 
			
			
			$mail->From       = $from_email ;
			$mail->FromName   = $from_name  ;
			
			if( $reply_email == '' ){
				$reply_email = $from_email ; 
			}
			
			if( $reply_name == '' ){
				$reply_name = $from_name ; 
			}
			
			$mail->AddReplyTo( $reply_email , $reply_name );
			$mail->AddAddress( $to_email    , $to_name );
			////////
			
			$mail->Subject    = $subject ;
			if( $body_text == '' ){
				$body_text = $body ; 
			}
			$mail->AltBody  = $body_text ;  
			$mail->MsgHTML($body);
			$mail->IsHTML($html); // send as HTML

			return $mail->Send(); 
		}
	
		function triggerMail($to , $from_email , $from_name , $reply_email = '' , $reply_name = '', $subject , $body , $body_text = '' ,  $html = true ){
			$mail             = new PHPMailer();
			$mail->IsSMTP();
			$mail->SMTPAuth   = true;                  // enable SMTP authentication
			$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
			
			//// use the credentials set in this componenet 
			$mail->Host       = $this->host          ;  // sets GMAIL as the SMTP server
			$mail->Port       = $this->mailPort      ;  // set the SMTP port
			$mail->Username   = $this->mail_username ;  // GMAIL username
			$mail->Password   = $this->mail_password ;  // GMAIL password
			//// use the credentials set in this componenet 
			
			
			$mail->From       = $from_email ;
			$mail->FromName   = $from_name  ;
			
			if( $reply_email == '' ){
				$reply_email = $from_email ; 
			}
			
			if( $reply_name == '' ){
				$reply_name = $from_name ; 
			}
			
			$mail->AddReplyTo( $reply_email , $reply_name );
			
			foreach( $to as $contact ){
				$mail->AddAddress( $contact['email'] , $contact['name'] );	
			}
			
			////////
			
			$mail->Subject    = $subject ;
			if( $body_text == '' ){
				$body_text = $body ; 
			}
			$mail->AltBody  = $body_text ;  
			$mail->MsgHTML($body);
			$mail->IsHTML($html); // send as HTML

			return $mail->Send(); 
		}
	
 
	}
?>