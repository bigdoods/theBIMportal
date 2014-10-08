<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Bim_Controller {
	
	private static $_activeProject = NULL;
	
	/**
	 * Set an active project this 
	 * Function need to call every time 
	 * when ever an project specific task is eing performed by admin
	 */
	static public function setActiveProject( $actp = 0){
		self::$_activeProject = $actp;
	}
	
	/**
	 * Get active projet 
	 * it retrives the active project id
	 */
	
	static public function getActiveProject(){
		return self::$_activeProject;
	}
	function __construct(){
		parent::__construct();
		$this->load->model('Users');
		$this->load->model('Projects');
		$this->load->model('Team_members');
		$this->load->model('Apps');

		$this->load->config('bimsync');
		$this->load->helper('bimsync');
	}
	
	public function dashboard( $tabid = 1 ){
		$data['main'] = 'dashboard';
		$data['tabid'] = $tabid;

		$this->load->vars($data);
		$this->load->view('admin');
		
	}
	/**
	 * display the tab content of
	 * The new user tab
	 */
	public function getNewUserDetails($tabid){		
			$data['userdetaiils'] = $this->Users->getNewUsers();
			$data['tabid'] = $tabid;
			$this->load->vars($data);
			$this->load->view('admin/newusers');
	}
	
	/**
	 * display the tab content of
	 * The new user tab
	 */
	public function getProjectDetails($tabid){			
			$data['projectdetails'] = $this->Projects->getAllProject();
			$data['tabid'] = $tabid;

			$this->load->vars($data);
			$this->load->view('admin/projectlist');
	}


	public function getTeamDetails($tabid){			
			$data['projectdetails'] = $this->Projects->getAllProject();
			$data['teamdetails'] = null;
			$data['project_id'] = intval($this->input->get('project_id'));
			if($data['project_id'] == 0)
				$data['project_id'] = $data['projectdetails'][0]['id'];

			$data['teamdetails'] = $this->Team_members->getProjectMembers($data['project_id']);
			$data['designations'] = $this->Team_members->designations();

			$data['tabid'] = $tabid;

			$this->load->vars($data);
			$this->load->view('admin/teamlist');
	}

	public function saveTeam(){
		$team_data = $this->input->post('team');

		foreach($team_data as $team_member_data){
			if(!empty($team_member_data['id'])){
				$this->Team_members->update($team_member_data);
			}else{
				$team_member_data['id'] = $this->Team_members->create($team_member_data);
			}

			$this->Team_members->link_project_and_team_member($this->input->post('project_id'), $team_member_data['id']);
		}
	}
	
	/**
	 * Insert projects into table
	 */
	public function createProject(){
		echo $this->Projects->create();
	}
	
	/**
	 * Update projects 
	 */
	public function updateProject(){
		echo $this->Projects->update( $this->input->post('projectid'));
	}
	
	/**
	 * Get the details of a particular user
	 */
	public function getUserDetail($userid){
		
		$user_details = $this->Users->getNewUsers($userid);
		$data['userdetails']  = $user_details[0];
		$data['assignedprojects'] = $this->Users->getAssignedProjects($userid,'ingonre-admin');
		$data['allprojects'] = $this->Projects->getAllProject();
		$data['userid'] = $userid;
		$this->load->vars($data);
		$this->load->view('admin/useredit');		
	}
	
	/**
	 * Update the user status and
	 * Project allocation chart
	 */
	public function editUser( ){
		$p = $this->input->post();
		$user_id = $p['userid'];
		$user_details = $this->Users->getNewUsers( $user_id ); 
		$this->Users->setActiveInactive( $user_id, isset( $p['status'] ) ? 3 : 2 );
		$project_list = isset($p['project_list']) ? $p['project_list'] : array();
		$this->Users->assignProjects( $user_id,  $project_list);
		
		/**
		 * Send email to user if admin chnage any thing
		 */
		 
		
		 if( $user_details ){
			$user_details = $user_details[0];
		 }
		 switch( $user_details['status'] ){
		 	case '0':
				// it means the user is first time edited by admin
				if($project_list && isset($p['status'])){
					$to = $user_details['email'];
					$suject = " You account has been edited by BIM webmaster";
					$body = "<div>
								<span>Dear {$user_details['name']},</span>
								<br/>
								<div>Your account has been edited by BIM webmaster, please login to your bim account.</div>
								</div>";
					sendMail( $to,$suject,  $body);
				}
		 
				break;
		 }
		 if($user_details['status'] == 0){}
	}
	
	/**
	 * Show the all aps
	 */
	public function applist($tabid = 0){
		$data['app_details'] = $this->Apps->getAllApps();
		$data['tabid'] = $tabid;
		$this->load->vars($data);
		$this->load->view('admin/applist.php');
	}
	
	/**
	 * Upload php file for apps
	 */
	public function uplodAppfile(){
		$allowed_extension = array('php');
		$extension = strtolower (pathinfo($_FILES['foo']['name'], PATHINFO_EXTENSION ) );
		$name = pathinfo($_FILES['foo']['name'], PATHINFO_FILENAME );
		$response['error'] = array();
		$response['data'] = '';
		if( in_array( $extension, $allowed_extension )=== false){
			$response['error'][] = 'Not a valid php file';			
		}else{
			$dir = getcwd();
			$upload_dir = APPPATH .'/app/';
			$newname = $name.'_'.time(). '.'.$extension;
			foreach(glob(APPPATH.'app/'.$name.'*.php') as $file){
				$fname= basename($file);
				$oname = pathinfo($fname, PATHINFO_FILENAME) ;
				$name_arr =  explode('_',$oname);
				$new_arr = array_slice($name_arr,0,-1);
				$old_name = implode('_', $new_arr);
				if($old_name == $name)
					@unlink($file);
			}	
			if(move_uploaded_file( $_FILES['foo']['tmp_name'], $upload_dir. $newname)){
				$response['data'] = $newname.'~!~'.$name;
			}else{
				$response['error'][] = 'Upload path does not exists';
			}			
		}
		echo json_encode($response);exit;
		
	}
	
	/**
	 * Upload php file for apps
	 */
	public function uplodAppIconfile(){
		$allowed_extension = array('jpg','png','gif','jpeg');
		$extension = strtolower( pathinfo($_FILES['foo']['name'], PATHINFO_EXTENSION ) );
		$name = pathinfo($_FILES['foo']['name'], PATHINFO_FILENAME );
		$response['error'] = array();
		$response['data'] = '';
		if( in_array( $extension, $allowed_extension) === false){
			$response['error'][] = 'Not a valid icon file';			
		}else{			
			$dir = getcwd();
			$upload_dir = 'upload/appicon/';
			$newname = $name.'_'.time(). '.'.$extension;
			if(move_uploaded_file( $_FILES['foo']['tmp_name'], $upload_dir. $newname)){
				// + image manipulation
				$config['image_library'] = 'gd2';
				$config['source_image'] = $upload_dir. $newname;
				$config['maintain_ratio'] = TRUE;
				$config['width'] = 35;
				$config['height'] = 40;
				
				$this->load->library('image_lib', $config);
				
				$this->image_lib->resize();
				$response['data'] = $newname;
			}else{
				$response['error'][] = 'Upload path does not exists';
			}			
		}
		echo json_encode($response);exit;
		
	}
	/**
	 * Show the all aps
	 */
	public function createApp(){
		$app_id =  $this->Apps->create();
		if($app_id){
			$app_details = $this->Apps->getAllApps(1, $app_id);
			if($app_details){
				$class_name = $app_details[$app_id]['classname'];
				if(class_exists($class_name)){
					$obj = new $class_name();
					// + install feature exists
					if(is_callable(array($obj, 'install'))){
						$error = $obj->install();
						if(!empty($error)){
							// + delete the app
							$this->db->where('id', $app_id);
							$this->db->delete('apps');
							echo 0;
							exit;
						}
					}
				}
			}
		}
		echo $app_id;
		exit;
	}
	
	/**
	 * get the edit project form
	 */
	 public function editProject($project_id = 0){
		$project_details = $this->Projects->getAllProject( $project_id );
		$data['project_details']  = $project_details[0];
		$data['bimsync_projects'] = bimsync_projects();
		debug($data['bimsync_projects']);

		$this->load->vars( $data );
		$this->load->view('admin/project_edit');
	 }
	/**
	 * Display the app edit form
	 */
	public function editAppForm($appid = 0){
		$appdetails = $this->Apps->getAllApps(0, $appid);
		if($appdetails){
		$data['appdetails'] = array_shift( $appdetails );
		$this->load->vars($data);
		$this->load->view('admin/edit_app');
		
		}
	}
	/**
	 * Update the app detrails
	 */
	public function editApp(){
		/**
		 * Update the file code
		 */
/*		 $p = $this->input->post();
		 $project_code = $this->input->post( 'project_code' );
		 $app_code = html_entity_decode( $project_code, ENT_NOQUOTES, "UTF-8" );
*/
		 /**
		 * app file name
		 */
		 /**
		  Old ap details = 
		  */
/*		  $old_appFile = $this->Apps->getAllApps(1, $this->input->post('app_id'));
		  if( ! empty( $old_appFile ) ){
			$app_details = array_shift( $old_appFile);
			$arr['appfilepath'] = $app_details['appfilepath'];
			$app_file = APPPATH .'app/'. $arr['appfilepath'];
		 	file_put_contents( $app_file, $app_code);	 
		  }*/
		 $this->Apps->update();
	}
	
	/**
	 * Public function uploaddoc to view the documents uploaded by the user of the project
	 */
	public function uploadedoc( $tabid = 0){
		/**
		 * Load module doc
		 */
		
		$this->load->model('Docs');
		$data['files'] = $this->Docs->getDocDetails();
		$data['tabid'] = $tabid;
		$this->load->vars($data);
		$this->load->view('admin/viewdoc');
	}
	
	/**
	 * downlaod the uplaoded file 
	 */
	public function download( $file_id ){
		$this->load->model('Docs');
		$file = array_first($this->Docs->getDocDetails($file_id));
		$data['doc_details'] = $file;

		$this->db->insert('ticket', array(
			'itemid' => $file['id'],
			'time' => time(),
			'created_by' => getCurrentuserId(),
			'user_type' => getCurrentUserRole(),
			'ticket_for' => 4,
			'project_id' => getActiveProject()
		));

		$ticket_id = $this->db->insert_id();
		$this->db->insert('ticket_log', array(
			'ticket_id' => $ticket_id,
			'modifier_id' => getCurrentuserId(),
			'ticket_status_id' => 12,
			'modifier_role' => getCurrentUserRole(),
			'modify_time' => time(),
			'log_status' => 1,
			'comment' => 'Downloaded file '. $file['name']
		));

		$this->load->vars( $data );
		if($data['doc_details']){
			$this->load->view('admin/downloadodc');
		}
	}
	
	/**
	 * View the tickets in the system 
	 */
	public function viewTicket( $tabid = 0 ){
		/**
		 * Load module doc
		 */
	    $function = $this->input->get('f');
		$ticket_app = new ticket_app();
		if($function && is_callable( array( $ticket_app, $function))){
			$ticket_app->$function();	
		}else{//echo 123;exit;
			$ticket_app->viewAllTicket();
		}
		
		$data['tabid'] = $tabid;		
		
	}
	/**
	 * See the requested file
	 */
	 
	 public function viewRequest($tabid = 0 ){		 
		$data['tabid'] = $tabid;
		$request_file_app = new request_file();
		$this->load->model('Request_Doc');
		$data['request_details'] = $request_file_app->getAllRequestDetails();//$this->Request_Doc->getRequestDetails();
		$this->load->vars($data);		
		$this->load->view('admin/viewrequest');
	 }
	 
	
	  
	/**
	 * Display the help wiki page to 
	 */
	 public function helpWiki( $tabid=0){
	 	$data['tabid'] = $tabid;
		$help_app = new help_app;
		if(isset( $_POST) && !empty($_POST)){
			$help_app->updateContent();
		}else{
			$help_app->adminInit();
		}
	 }
	 
	 /**
	  * Display the message log
	  */
	  
	  public function messages( $tabid=0){
	  	$data['tabid'] = $tabid;
		$data['all_users']  = $this->Users->getEveryUsers();
		$this->load->vars($data);
		$this->load->view('admin/messages');
	  }
	  /**
	   * delete 
	   */
	  public function delete( $tabid=0){
	  	$data['tabid'] = $tabid;
		$data['all_users']  = $this->Users->getEveryUsers();
		$this->load->vars($data);
		$this->load->view('admin/delete');
	  }
	  /**
	   * Fetch the delete form
	   */
	  public function prepareDeleteFrom(){
	  	$type = $this->input->post('type');
		$data = array();
		if($type){
			switch($type){
				case 'user': 
					$data = $this->Users->getNewUsers();
					break;
				case 'project':
					$data = $this->Projects->getAllProject();
					break;
				case 'app':
					$data = $this->Apps->getAllApps();
					break;
			}
		}
		$data['data'] = $data;
		$data['type'] = $type;
		$this->load->vars($data);
		$this->load->view('admin/deleteentity');
	  }
	  
	  /**
	   * A function to delete the
	   * entites like uses projects and apps
	   * The delete form is submited at this 
	   * Function
	   */
	   
	   function performDelete(){
		   $post  = $this->input->post();
		   $success = 1;
		   if(isset($post['user'])){
			  /** when a project is beign removed from system the 
			   * The following tables data will b deleted 
			   1.user_assigned_projects
			   2.uploaddoc
			   3.site_renders
			   4.site_photographs
			   5.request_doc
			   6.notifications
			   7.ticket
			   8.Ticketlog
			   9.users
			   */
			  
			  $user_id_str = implode(',', array_keys($post['user'])); 
			  $this->db->trans_start();
			  
			  //+ delete assignments
			  $this->db->where('userid in('.$user_id_str.')');
			  $this->db->delete('user_assigned_projects');
			  
			  //+ delete feedback
			  $this->db->where('user_id in('.$user_id_str.')');
			  $this->db->delete('users_feedback');
			  
			  //+ delete ticket & log
			  $this->db->where('created_by in('.$user_id_str.')');
			  $this->db->delete('ticket');
			  
			  //+ delete uploaddoc
			  $this->db->where('userid in('.$user_id_str.')');
			  $this->db->delete('uploaddoc');
			  
			  //+ delete site_renders
			  $this->db->where('user_id in('.$user_id_str.')');
			  $this->db->delete('site_renders');
			  
			  //+ delete site_photographs
			  $this->db->where('user_id in('.$user_id_str.')');
			  $this->db->delete('site_photographs');
			  
			  //+ delete request_doc
			  $this->db->where('userid in('.$user_id_str.')');
			  $this->db->delete('request_doc');
			  
			  //+ delete request_doc
			  $this->db->where('id in('.$user_id_str.')');
			  $this->db->delete('users');
			  
			  $this->db->trans_complete();
			  // + delete from user_assigned_projects
			  
			  
		   }else if(isset($post['project'])){			  
			   
			  /** when a project is beign removed from system the 
			   * The following tables data will b deleted 
			   1.user_assigned_projects
			   2.uploaddoc
			   3.site_renders
			   4.site_photographs
			   5.request_doc
			   6.notifications
			   7.ticket
			   8.Ticketlog
			   9.project
			   */
			   
			  $project_string = implode(',', array_keys($post['project']));
			  $this->db->trans_start();
			  // + break assignment
			  $this->_breakAssingment($project_string);
			  
			  // remove uploaded doc
			  $this->_deleteUploadDoc($project_string);
			  
			  // remove site Renders
			  $this->db->where('project_id IN ('.$project_string.')');
			  $this->db->delete('site_renders');
			  
			  // remove site_photographs
			  $this->db->where('project_id IN ('.$project_string.')');
			  $this->db->delete('site_photographs');
			  
			  // remove request_doc
			  $this->db->where('project_id IN ('.$project_string.')');
			  $this->db->delete('request_doc');
			  
			  // remove notifications
			  $this->db->where('project_id IN ('.$project_string.')');
			  $this->db->delete('notifications');
			  
			  // remove ticket
			  $this->db->where('project_id IN ('.$project_string.')');
			  $this->db->delete('ticket'); // and this will delete the ticket log also

			  // remove project
			  $this->db->where('id IN ('.$project_string.')');
			  $this->db->delete('projects'); // and this will delete the ticket log also
			  $this->db->trans_complete();
		   }elseif(isset($post['app'])){
			  /* delete apps from databse only..As because 
			   * The codes are invaluable
			   */
			   $app_id_str = implode(',', array_keys($post['app'])); 
			   /**
			    * delete from 
				* App list
				*/
			    $this->db->where('id IN ('.$app_id_str.')');
				$this->db->delete('apps');
			  
		   }
	   	if($this->db->trans_status() === FALSE){
			$success = 0;
		}
		echo $success;
	   }
	   
	   /**
	    * break assignments of uses to proects
		*/
	   private function _breakAssingment($pids_str = '-1'){
	   		$this->Projects->breakAssingments($pids_str);
	   }
	   
	   /**
	    * break assignments of uses to proects
		*/
	   private function _deleteUploadDoc($pids_str = '-1'){
	   		$this->db->where('projectid IN ('.$pids_str.')');
			$this->db->delete('uploaddoc');
	   }
	   
	   /**
	    * A function for test new pages
		* at admin panel
		*/
		public function test( $tabid = 0 ){
			$data['tabid'] = $tabid;
			$this->load->vars($data);
			$this->load->view('admin/test');
		}	
	
}