<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Library Make Mail
 *
 * This class handles mail related functionality
 *
 * @package     anofie
 * @author      classiebit
**/

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Make_mail {
	
	public function send($recipient = NULL, $subject = NULL, $message = NULL, $mail_type = NULL)
	{	
		$CI 			=& get_instance();

        // DO NOT SEND IN DEMO MODE
        if(DEMO_MODE)
            return true;
		
		if(empty($CI->settings->smtp_server) || empty($CI->settings->smtp_username) || empty($CI->settings->smtp_password) || empty($CI->settings->smtp_port) || empty($CI->settings->sender_email))
		{
			return TRUE;
		}


        // 1: php_mailer, 2: ci_email
        if($CI->settings->email_lib)
        {
            // PHPMailer email
            $this->pm_mail($CI, $recipient, $subject, $message, $mail_type);
        }
        else
        {
            // Codeigniter email
            $this->ci_mail($CI, $recipient, $subject, $message, $mail_type);
        }
	}

    private function pm_mail($CI, $recipient = NULL, $subject = NULL, $message = NULL, $mail_type = NULL)
    {
        // Instantiation and passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = 0;                                       // Enable verbose debug output
            $mail->isSMTP();                                            // Set mailer to use SMTP
            $mail->Host       = $CI->settings->smtp_server;             // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = $CI->settings->smtp_username;           // SMTP username
            $mail->Password   = $CI->settings->smtp_password;           // SMTP password

            // encryption only if available
            if($CI->settings->encryption)
                $mail->SMTPSecure = $CI->settings->encryption;   // Enable TLS encryption, `ssl` also accepted
            
            $mail->Port       = $CI->settings->smtp_port;               // TCP port to connect to

            //Recipients
            $mail->setFrom($CI->settings->sender_email, $CI->settings->sender_name);
            $mail->addAddress($recipient);     // Add a recipient
            
            if($CI->settings->reply_to)
                $mail->addReplyTo($CI->settings->sender_email, $CI->settings->sender_name);

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $message;
            $mail->AltBody = $message;

            @$mail->send();
        } catch (Exception $e) {
            
        }

        return true;
    }

    private function ci_mail($CI, $recipient = NULL, $subject = NULL, $message = NULL, $mail_type = NULL)
    {
        $CI->load->library('email');

		$config 				= array();

		// Send email 
		$config['protocol']		= 'smtp';
		$config['smtp_host'] 	= $CI->settings->smtp_server;
		$config['smtp_port'] 	= $CI->settings->smtp_port;
		$config['smtp_user'] 	= $CI->settings->smtp_username;
		$config['smtp_pass'] 	= $CI->settings->smtp_password;
		$config['useragent'] 	= $CI->settings->site_name;
		
		$config['charset'] 		= "utf-8";
		$config['mailtype'] 	= "html";
		$config['newline'] 		= "\r\n";
		
		$CI->email->initialize($config);
		
		$CI->email->set_header('Content-Type', 'text/html');
		$CI->email->from($CI->settings->sender_email, $CI->settings->sender_name);
		$CI->email->to($recipient);

        if($CI->settings->reply_to)
		    $CI->email->reply_to($CI->settings->sender_email, $CI->settings->sender_name);

		$CI->email->subject($subject);
		$CI->email->message($message);
		    
		@$CI->email->send();

        return true;
    }
}

/*End Make Mail*/