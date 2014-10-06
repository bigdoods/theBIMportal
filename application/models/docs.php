<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Docs extends Ci_Model {
	public function getDocDetails( $file_id = 0){
		if($file_id ){
			$this->db->where('a.id', $file_id );			
		}
		
		$data = array();
		$this->db->where('b.id = a.userid');	
		$this->db->where('a.doctypeid = d.id');
		$this->db->where('d.parent_id =0 OR d.parent_id = e.id');
		$this->db->where('a.projectid = c.id');
		$this->db->group_by('a.projectid, a.id'); 				
		$this->db->order_by('a.id DESC');
		$this->db->from('uploaddoc a, users b, projects c , doctype d, doctype e');
		$this->db->select('a.*,b.name as uname, c.name as pname, d.name as doctypename, e.name as parent_doctypename');
		if(getCurrentProjectContext())
			$this->db->where('c.id IN ('. implode(',' , getCurrentProjectContext()) .')');$result = $this->db->get();
		if($result->num_rows()){			
				foreach($result->result_array() as $row){
					$q2 = $this->db->query("SELECT d.name,a.id as ticket_id FROM ticket a JOIN ticket_for b on a.ticket_for = b.id join ticket_log c1 ON c1.ticket_id = a.id JOIN ticket_status d ON c1.ticket_status_id = d.id JOIN (SELECT MAX(id) as id FROM ticket_log as c2 group by c2.ticket_id ) last on c1.id = last.id WHERE a.itemid = ". $row['id'] );
					if($q2->num_rows()){
						$message = $q2->row_array();
						$row['ticket_id'] = $message['ticket_id'];
						$row['ticketmessage'] = $message['name'];
						$row['represent_id'] = str_pad( $message['ticket_id'], 6, '#000000', STR_PAD_LEFT);
					}
					$data[] = $row;
				}
			
			return $data;
		}else{
			return $data;
		}
	}
}


/* End of file welcome.php */
