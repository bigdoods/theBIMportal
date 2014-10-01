<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Request_Doc extends Ci_Model {
	public function getRequestDetails( $request_id = 0){
		/**
		 * call the app to get the request details
		 */
		$app_request = new Request_File();
		$app_request->setRequestId( $request_id );
		$request_details = $app_request->getAllRequestDetails();
		return $request_details;
	}
}


/* End of file welcome.php */
