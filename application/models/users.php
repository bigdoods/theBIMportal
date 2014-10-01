<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends Ci_Model {
	
	/**
	 * Get the details of 
	 * new registered user details
	 */
	function getNewUsers($id = 0){
		if(!$id)
		$this->db->where('role !=', '1');
		if($id){
			$this->db->where('id', $id);
		}
		$this->db->order_by('id DESC');
		$q = $this->db->get('users');
		if($q->num_rows()){
			return $q->result_array();
		}
		return false;
		
	}
	
	/**
	 * Get the projects name and id 
	 * of the projects in which the user is assigned		
	 */
	 
	public function getAssignedProjects($userid = 0, $ignore= "respect-admin"){
		$where = "a.userid = $userid";
		if( getCurrentUserRole() == 1 && $ignore === "respect-admin"){
			$where = "(1=1)";
		}
		$this->db->where( $where );
		$this->db->where('continue', 1 );
		$this->db->select('b.*');
		$this->db->from('user_assigned_projects a');
		$this->db->join('projects b', 'a.projectid = b.id');
		$q = $this->db->get();		
		$data = array();
		if($q->num_rows()){
			foreach($q->result_array() as $row ){
				$data[$row['id']] = $row;
			}
			return $data;
		}else{
			return false;
		}
	}
	
	/**
	 * Users activation
	 */
	public function setActiveInactive( $user_id = 0, $status = 0 ){
		$data = array(
			'status' => $status,
			'activationdate'=> $status ? time() : '' 
		);
		$this->db->where('id', $user_id);
		$this->db->update('users', $data);
	}
	
	/**
	 * Update the project assignment
	 * Table for a particular uses
	 */
	public function assignProjects( $user_id = 0 , $projects_id_arr = array() ){
		$all_assigned_projects = $this->getAssignedProjects( $user_id, 'ingnore-admin' );
		if($all_assigned_projects){
			$projects_arr = array();
			foreach($all_assigned_projects as $p){
				$projects_arr[]  = $p['id'];				
			}
			$deleted_projects_id = array_diff($projects_arr, $projects_id_arr);
			if( !empty( $deleted_projects_id ) ){
				$this->db->where_in('projectid',  $deleted_projects_id  );
				$this->db->where( 'userid', $user_id );
				$this->db->delete('user_assigned_projects');
				foreach( $deleted_projects_id as $pid){
					$notification = array(
						'case_id' => 2,
						'project_id' => $pid,
						'related_id' => json_encode(array('uid'=>$user_id,'p_id'=>$pid) )
					);
					$this->load->model( 'Notification' );
					$this->Notification->setNotification( $notification );	
				}
				
			}
			
			$new_project_ids = array_diff( $projects_id_arr, $projects_arr );
			
			if( !empty( $new_project_ids ) ){
				$this->assignNewProject( $user_id, $new_project_ids );
			}			
		}else{
			$this->assignNewProject( $user_id, $projects_id_arr );
		}
	}
	
	/**
	 * This function always invokes to assign new projects 
	 * to an user
	 */
	 
	 public function assignNewProject( $user_id = 0, $project_id_arr = array() ){
	 	if( !empty( $project_id_arr ) ){
			$data = array();
			foreach( $project_id_arr  as $newid){
				$data = array(
					'userid' => $user_id,
					'projectid' => $newid,
					'assigndate' => time(),
					'continue' => 1
					);
					$this->db->insert('user_assigned_projects', $data);
					$this->load->model( 'Notification' );
					$notification = array(
						'case_id' => 1,
						'project_id' => $newid,
						'related_id' => json_encode(array('uid'=>$user_id,'p_id'=>$newid) )
					);
					$this->Notification->setNotification( $notification );
			}			
		}
	 }
	 
	 /**
	  * Update the profile picture of the currently
	  * Logged in user
	  */
	 public function  updateProfilePic($pic = ''){
		$arr['profilepic'] = $pic;
		$this->db->where('id', getCurrentuserId());
		$this->db->update('users', $arr);
		}
		
	/**
	 * get the curent logged project
	 */
	 
	 public function getCurrentProjects(){
		$all_projects = $this->getAssignedProjects( getCurrentuserId());		
		$project_ids = $all_projects ? array_keys( $all_projects ) : array(-1);
		if(in_array( getActiveProject(), $project_ids) !== false && getCurrentUserRole() == 2){
			return array( getActiveProject() );
		}elseif( !empty($project_ids)){
			return $project_ids;
		}else{
			return array( -1);
		}
	 }
	 
	 /**
	  * check if the user can access this project or not
	  */
	  
	  public function checkProjectAccess( $project_id, $userid){
			 $this->db->where('projectid', $project_id);
			 $this->db->where('userid', $userid);
			 $q = $this->db->get('user_assigned_projects');
			 if($q->num_rows() > 0 ){
				return true	;
			 }else{
				return false;
			 }
			 
	  }
	  
	  /**
	   * Get user role
	   */
	   
	  public function getuserRole( $user_id = 0 ){
	  	$role = 0;
		$this->db->where( 'id', $user_id );
		$this->db->select('role');
		$q = $this->db->get('users');
		if($q->num_rows()){
			$row = $q->row_array();
			return $row['role'];
		}
		return $role;
			
	  }
	  
	  /**
	   * Get every user
	   */
	   
	  public function getEveryUsers(){
		 $data = array();
		$this->db->order_by('id DESC');
		$q = $this->db->get('users');
		if($q->num_rows()){
			$data = $q->result_array();
		}
		return $data;
		
	}
	
	public function updateProfile($p = array()){
		$error = 0;
		if($p){
			$data = array();
			if(isset($p['name']))
				$data['name'] = $p['name'];
			if(isset($p['phone']))
				$data['phone'] = $p['phone'];
			if(isset($p['password']))
				$data['password'] = md5($p['password']);
		
			$this->db->where('id', getCurrentUserId());
			$this->db->update('users', $data);
			if(!$this->db->_error_number()){
				return 1;
			}else{
				return 0;
			}
		}
		return 0;
	}
}


/* End of file welcome.php */
