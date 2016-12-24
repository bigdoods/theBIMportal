<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Projects extends Ci_Model {

	/**
	 * Get array of all projects 
	 */
	function getAllProject( $project_id = 0 ){
		$data = array();
		if($project_id){
			$this->db->where( 'id', $project_id );
		}
		$this->db->order_by('name ASC');
		$q = $this->db->get('projects');
		if($q->num_rows()){
			foreach($q->result_array() as $project){
				$data[] = $project;
			}
		}
		return $data;

	}


	/**
	 * Get the projects name and id
	 * of the projects in which the user is assigned
	 */

	public function getAssignedUsers($projectid){
		$this->db->where('a.projectid = '. intval($projectid));
		$this->db->select('b.*');
		$this->db->from('user_assigned_projects a');
		$this->db->join('users b', 'a.userid = b.id');
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



	function create(){

		$p = $this->input->post();
		$p['active'] = 1;
		$this->db->insert('projects', $p);
		if($this->db->insert_id())
			return $this->db->insert_id();
		else{
			return  0;
		}


	}

	function getDoctypeDetails( $type_id = 0){
		if($type_id){
			$this->db->where('id', $type_id );
		}
		$data = array();
		$q = $this->db->get( 'doctype' );
		if($q->num_rows()){
			foreach( $q->result_array() as $row){
				$data[$row['id']] = $row;
			}
		}
		return $data;
	}

	function update( $project_id = 0){
		if($project_id){
			$this->db->where('id', $project_id );
		}
		$p = $this->input->post();
		$data['active']	 = $p['active'];
		$data['name']	 = $p['name'];
		$data['embedcode']	 = $p['embedcode'];
		$data['bimsync_id']	 = @$p['bimsync_id'];

		$this->db->where('id', $project_id);
		$this->db->update('projects', $data);
	}

	/**
	 * Remove all assignments for these project
	 * @param string '1,2,3' the comma seperated projectids
	 */
	 function breakAssingments($pids_str = '-1'){
	 	$this->db->where('projectid IN ('.$pids_str.')');
		$this->db->delete('user_assigned_projects');
	 }
}
