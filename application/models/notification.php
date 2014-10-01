<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notification extends Ci_Model {
	/**
	 * Set notification
	 */
	 public function setNotification( $notification_details ){
		if( !isset( $notification_details['date_time'] )){
		 $notification_details['date_time']  = time();
		}
		$this->db->insert('notifications', $notification_details);
	 }
}

/* End of file welcome.php */
