<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Team_members extends Ci_Model {
	
	public function getProjectMembers($project_id){
		if(intval($project_id) ==0)
			return array();

		$members = array();
		
		$this->db->where( 'project_team_members.project_id', $project_id )
			->join('project_team_members', 'project_team_members.team_member_id = team_members.id')
			->order_by('name ASC');
		$query = $this->db->get('team_members');
		if($query->num_rows()){
			foreach($query->result_array() as $member){
				$members[] = $member;
			}
		}
		return $members;
	}

	public function create($data){
		unset($data['id']);

		$this->db->insert('team_members', $data);
	}

	public function update($data){
		if(empty($data['id']))
			return null;

		$this->db->where(array('id' => $data['id']))->limit(1)->update('team_members', $data);
	}

	public function link_project_and_team_member($project_id, $team_member_id){
		if($this->db->limit(1)->where(array('project_id' => $project_id, 'team_member_id' => $team_member_id))->from('project_team_members')->count_all_results() ==0)
			$this->db->insert('project_team_members', array('project_id' => $project_id, 'team_member_id' => $team_member_id));
	}

	public function designations(){
		return array(
			'Engineer'
		);
	}
}


/* End of file team_members.php */
