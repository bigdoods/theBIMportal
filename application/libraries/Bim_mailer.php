<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Bim_mailer{
	
	private $mail = NULL;
	private $CI =NULL;
	private $config = array(
		'smtp' =>true,
		'Host' => ''
	);
	public function __construct(){
		require_once('PHPMailer/class.phpmailer.php');
		$this->mail = new PHPMailer;		
		$this->CI = &get_instance();
		$this->configure();
	}
	
	/**
	 * This function will be used to 
	 * Send email
	 *@param $to array key can contain email id and name as value of just eamil id
	 *@param $subject string
	 *@param $body string
	 */
	public function sendMail($to, $subject, $body){
		if(is_array($to)){
			$this->mail->addAddress( key($to), reset($to) ) ;
		}else{
			$this->mail->addAddress( $to, 'Dear user');
		}
		$this->mail->Subject = $subject;
		
		$this->mail->Body = $body;
		if($this->mail->send())
			return true;
		return $this->mail->ErrorInfo;
		
	}
	
	private function configure(){
		$config = $this->CI->config->item('smtp_config');
		if($config['protocol'] === 'smtp'){			
			$this->mail->isSMTP();
			$this->mail->Host = $config['smtp_host'];
			$this->mail->Port  = $config['smtp_port'];
			$this->mail->SMTPSecure = $config['smtp_secure'];
			$this->mail->SMTPAuth  = $config['auth'];
			$this->mail->Username = $config['smtp_user'];
			$this->mail->Password  = $config['smtp_pass'];
			$this->mail->setFrom($config['from_email'], $config['from_name']);
			$this->mail->addReplyTo($config['reply_to'], $config['from_name']);
			$this->mail->SMTPDebug =1;
		}
	}
	
	
	

}
?>
