<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apps extends Ci_Model {

	public function __construct(){
		parent:: __construct();
	}

	/**
	 * return all the apps in the database.
	 * If the active flag is on then
	 * Only return the active apps
	 */
	function getAllApps( $active = 0, $app_id = 0 ){
		if( $active ){
			$this->db->where('is_active', 1);
		}

		if( $app_id ){
			$this->db->where('id', $app_id);
		}
		$this->db->order_by('type ASC, order ASC, id ASC');
		$q = $this->db->get('apps');
		$data = array();
		if($q->num_rows()){
			foreach( $q->result_array() as $row){
				$data[$row['id']] = $row;
			}
		}
		return $data;
	}

	/**
	 * save the app into database
	 */

	 public function create(){
		$p = $this->input->post();
		$arr = array(
			'name' => $p['name'],
			'description' => $p['description'],
			'order' => $p['order'],
			'is_active' => 1
		);

		/**
		 * app file name
		 */
		 $file_details = explode('~!~', $p['appfilepath']);
		 $arr['appfilepath'] = $file_details[0];
		 $arr['classname'] = $file_details[1];
		 /**
		  * icon file path
		  */
		$arr['appiconfilepath'] = $p['appiconfilepath'];
		$this->db->insert('apps', $arr);
		return $this->db->insert_id();
	 }

	 /**
	  * Update apps table data
	  */

	 public function update(){
		$p = $this->input->post();
		$arr = array(
			'name' => $p['name'],
			'description' => $p['description'],
			'order' => $p['order'],
			'is_active' => isset($p['is_active']) ? 1 : 0,
			'type' => $p['type']
		);

		/**
		 * app file name
		 */
		 $file_details = explode('~!~', $p['appfilepath']);
		 $arr['appfilepath'] = $file_details[0];
		 $arr['classname'] = $file_details[1];
		 /**
		  * icon file path
		  */
		$arr['appiconfilepath'] = $p['appiconfilepath'];
		// + get the old icon file
		$this->db->where('id', $p['app_id']);
		$app_details = $this->db->get('apps');
		if($app_details->num_rows()){
			$row = $app_details->row_array();
			if($row['appiconfilepath'] !== $arr['appiconfilepath']){
				@unlink('upload/appicon/'.$row['appiconfilepath']);
			}
			if($row['appfilepath'] !== $arr['appfilepath']){
				@unlink('application/app/'.$row['appfilepath']);
			}
		}
		$this->db->where('id', $p['app_id']);
		$this->db->update('apps', $arr);

	 }

	 /**
	  * Get app by class name
	  */

	  function getAppByClassname( $class_name = ''){
		$data = array();
	  	if($class_name){
			$this->db->where('classname', $class_name);
			$this->db->limit(1, 0);
			$q = $this->db->get('apps');
			if($q->num_rows()){
				$data = $q->row_array();
			}

		}
	  	return $data;
	  }

	  function getAppNameById($app_id){
		$data = array();
	  	if($app_id){
			$this->db->where('id', $app_id);
			$this->db->limit(1, 0);
			$q = $this->db->get('apps');
			if($q->num_rows()){
				$data = $q->row_array();
			}

		} else {
			$app_name = '';
		}

		$app_name = $data['name'];

	  	return $app_name;
	  }
}

/* End of file welcome.php */
