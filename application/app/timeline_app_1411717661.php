<?php

class Timeline_App extends Bim_Appmodule{
	
	/**
	 * The default constructor
	 * any initialization can be placed here
	 * And at first the parent constructor must be called
	 */
	private $_db = NULL;
	public function __construct(){
		parent::start();
		$this->_db = $this->_me->db;// this is codeigniter db
		$this->_me->load->model('Users');
		$this->_me->load->model('Projects');
		$this->_me->load->model('Docs');
		
	}
	
	/**
	 * The mandatory method
	 * The frame work will call this method
	 * This is the entry point of the app
	 * It can produce any browser friendly output
	 */
	 
	 public function init(){

		/**
		 * Get all notification for my projects
		 */
		
	 	echo '<ul class="profile">'.$this->feedDetails().'</ul>';

	 }
	 
	 /**
	  * get feed details 
	  */
	  public function feedDetails($start = 0, $length = 100){
		 //$this->outputStart();
	  	$this->_db->where('project_id IN ('. implode( ',', $this->_project_id_arr) .')');
		$this->_db->order_by('id DESC');
		
		$this->_db->limit($length,$start);
		$html = '';
		$q = $this->_db->get('notifications');
		if($q->num_rows()){
			foreach($q->result_array() as $row ){
				/**
				 * do the common thinng of every notificaiton				 
				 */
				$tags = array();
				$tags['notif_id'] = $row['id'];
                $tags['datetime'] = date('jS F Y - H:i', $row['date_time']);
				$tags['pid'] = $row['project_id'];
				switch( $row['case_id'] ){
					/**
					 * This is for case 1
					 * as per case table that the case is for
					 * USER ASSIGNED TO PROJECT
					 */ 
					case 1:
						$case_html = $this->caseHtml( $row['case_id']);
						$case_paramters_arr = json_decode($row['related_id'], true);
						
						$user_details = $this->_me->Users->getNewUsers( $case_paramters_arr['uid'] );
						// + if the user is not any more then also delete this notification
						if(!$user_details){
							$this->_db->where('id', $row['id']);
							$this->_db->delete('notifications');
							continue;
						}
						$project_details = $this->_me->Projects->getAllProject( $case_paramters_arr['p_id'] );

						/**
						 * Check user profile picture exists or not
						 */						 
						$user_details[0]['profilepic'] = $this->userprofilePic($user_details[0]['profilepic']);
						$case_html = sprintf( $case_html, $user_details[0]['profilepic'], @$user_details[0]['name'], $project_details[0]['name']);
						
						$html.=$case_html;
						break;
						
					/**
					 * User has been revojked from a
					 */	
					case 2:
						$case_html = $this->caseHtml( $row['case_id']);
						$case_paramters_arr = json_decode($row['related_id'], true);
						
						$user_details = $this->_me->Users->getNewUsers( $case_paramters_arr['uid'] );
						$project_details = $this->_me->Projects->getAllProject( $case_paramters_arr['p_id'] );

						/**
						 * Check user profile picture exists or not
						 */						 
						$user_details[0]['profilepic'] = $this->userprofilePic($user_details[0]['profilepic']);
						$case_html = sprintf( $case_html, $user_details[0]['profilepic'], @$user_details[0]['name'], $project_details[0]['name']);
						
						$html.=$case_html;
						break;
					/**
					 * The file is uploaded by a user
					 */
					case 3:
						$case_html = $this->caseHtml( $row['case_id']);
						$case_paramters_arr = json_decode($row['related_id'], true);						
						$file_details = $this->_me->Docs->getDocDetails($case_paramters_arr['file_id']);
						
						if(count($file_details) ==0)
							continue;

						$user_details = $this->_me->Users->getNewUsers( $file_details[0]['userid'] );						
						$project_details = $this->_me->Projects->getAllProject( $file_details[0]['projectid'] );
						//v_dump($user_details);
						/**
						 * Check user profile picture exists or not
						 */						 
						$user_details[0]['profilepic'] = $this->userprofilePic($user_details[0]['profilepic']);
						$case_html = sprintf( $case_html, $user_details[0]['profilepic'], $user_details[0]['name'], $file_details[0]['name'], $project_details[0]['name'], $file_details[0]['doctypename']);						
						
						/**
						 get the ticket link
						 */
						$tags['ticketlink'] = $this->ticketLink( $row, $case_paramters_arr);
						/**
						 * Get the filemanager link						
						 */
						$tags['filelink'] = $this->getFileManagerLink( $row, $case_paramters_arr); 
						
						/**
						 * File name
						 */
						 $tags['filename'] = $this->getFileName( $row, $case_paramters_arr);
						/***/
						$html.=$case_html;
						break;
					/**
					 * user has requested a file
					 */
					
					case 4:
						$case_html = $this->caseHtml( $row['case_id']);
						$case_paramters_arr = json_decode($row['related_id'], true);
						$request_app = new request_file();
						$request_app->setRequestId( $case_paramters_arr['file_request_id'] );
						$request_details = $request_app->getRequestDetails();
						if($request_details){
							$user_details = $this->_me->Users->getNewUsers( $request_details['userid'] );
							$user_details[0]['profilepic'] = $this->userprofilePic($user_details[0]['profilepic']);
							$case_html = sprintf( $case_html, $user_details[0]['profilepic'], $user_details[0]['name'], $request_details['pname'], $request_details['doctypename'], $request_details['ext_name']);
						
						/**
						 get the ticket link
						 */
						$tags['ticketlink'] = $this->ticketLink( $row, $case_paramters_arr);
						$html.=$case_html;
						}
						break;
					case 5:
						$case_html = $this->caseHtml( $row['case_id']);
						$case_paramters_arr = json_decode($row['related_id'], true);
						$issue_app = new issueviewer_app();
						$issue_details = $issue_app->getAllIssueDetails($case_paramters_arr['issue_id']);
						if($issue_details){
							$issue_details = $issue_details[0];
							$user_details = $this->_me->Users->getNewUsers( $issue_details['userid'] );
							$user_details[0]['profilepic'] = $this->userprofilePic($user_details[0]['profilepic']);
							$case_html = sprintf( $case_html, $user_details[0]['profilepic'], $user_details[0]['name'], date('H:i',$issue_details['time']),date('d-m-Y',$issue_details['time']), base_url($issue_app->issue_image_thumb.'/'.$issue_details['path']) );
						
						/**
						 get the ticket link
						 */
						$tags['ticketlink'] = $this->ticketLink( $row, $case_paramters_arr);
						$html.=$case_html;
						}
						break;
				}
				$html = $this->parse($html, $tags);
			}
		}
	  	
	  	return $html;
	  }
	  
	 /**
	  * Get ticket details of notification
	  */
	 private function ticketLink( $notification_details, $parameters){
		 $id = 0;		
	 	switch($notification_details['case_id']){
			case 3:
				$this->_db->where('ticket_for',1);
				$this->_db->where('itemid', $parameters['file_id']);
				$q = $this->_db->get('ticket');
				if($q->num_rows()){
					$row = $q->row_array();
					$id = $row['id'];
				}
				break;
			case 4:
				$this->_db->where('ticket_for',2);
				$this->_db->where('itemid', $parameters['file_request_id']);
				$q = $this->_db->get('ticket');
				if($q->num_rows()){
					$row = $q->row_array();
					$id = $row['id'];
				}
				break;
			case 5:
				$this->_db->where('ticket_for',3);
				$this->_db->where('itemid', $parameters['issue_id']);
				$q = $this->_db->get('ticket');
				if($q->num_rows()){
					$row = $q->row_array();
					$id = $row['id'];
				}
				break;
		}
		$ticket_link = /*$this->_base_uri.*/ base_url('portal/project/7?f=ticketDetails&id='.$id);
	 	return $ticket_link;
	 }
	 
	 /**
	  * Get profile picture path
	  */
	 private function userprofilePic( $file = ''){
	 	$file = $file  ? str_replace('~.','~_thumb', $file ) : 'default_profile_pic.png';
		$file = base_url('upload/profilepic/'.$file);
		return $file;
	 }
	 
	 /**
	  * remove_placeholder	  
	  */
	 public function parse( $html='', $tags=array()){
		foreach($tags as $var => $val){
			$html = str_ireplace('[+' . $var . '+]', $val, $html);
		}
	 	return $html;
	 }
	 /**
	  * Return the case html
	  */
	 private function caseHtml( $case_id = 0){
		$text = NULL; 
		if( $case_id ){
			$this->_db->where('id', $case_id );
			$q = $this->_db->get('case');
			if($q->num_rows() ){
				$row = $q->row_array();
				$text = $row['text'];
			}
		}
	 	return $text;
	 }
	 
	
	 /**
	  * Get the link of file managert
	  */
	 public function getFileManagerLink($row, $case_paramters_arr){
	 	return base_url('portal/project/16?id='.$case_paramters_arr['file_id']);
	 }
	 public function getFileName($row, $case_paramters_arr){
		 $file_id = $case_paramters_arr['file_id'];
		 $file_app = new Fileupload_app;
		 $file_details = $file_app->getFileDetails($file_id);
		 if($file_details){
		 	return $file_details['name'];
		 }
		 return 'File name'	;
	 }
}
?>