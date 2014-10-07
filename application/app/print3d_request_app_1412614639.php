<?php
/**
 * The 3D Print app 
 */
 
 class print3d_request_app extends Bim_Appmodule{
	
 	
	/**
	 *@var who will receive the message
	 */
	private $_email = '';
	
	/**
	 * The entry point of the app
	 */
	public function __construct(){
		parent::start();
		$this->_db = $this->_me->db;// this is codeigniter db
		$this->_email = $this->_me->config->item('feedbackemail');
	}
	
	public function init(){
		?><ul class="request_file">
                                	<li>
                            			<div class="portion">
                                        	<h2>3D Print Request</h2>
                                            <div class="clear"></div>
                                            <p>Please enter details about the 3D print you would like to request.</p>
                                            <div class="clear"></div>
                                           	<form name="request" action="?f=sendRequest" validate="validate" method="post">
                                            	<textarea class="text_area" data-validation-engine="validate[required]" name="comment"></textarea>
                                                <div class="clear"></div>
                                                <input type="submit" class="submit" value="submit" />
                                            </form>
                                        </div>
                                    </li>
                                    
                                    
                                    
                                </ul>
		<?php
		
	}
	
	/**
	 * Process the user submitted
	 * Data
	 */
	
	public function sendRequest(){
		$p = $this->_me->input->post();
		if($p){
			$id = $this->saveData($p);
			if($id){
				$this->sendEmailToRespective( $id );
			}
			?>
				<div>
                	<span>SENT!</span>
                    <br/>
                    <p>Thank you for your request. We will endeavour to respond as soon as possible.</p>
                </div>
			<?php
		}else{
			echo "<p>There is some problem. Please try again after sometime</p>";
		}
	}
	
	/**
	 * Send the email to respective authority
	 */
	 
	 private function sendEmailToRespective( $id ){
	 	$fb_details = $this->getFeedbackDetails( $id );
		if($fb_details){
			$to = $this->_email;
			$subject = 'Feedback From '.$fb_details[0]['uname'];
			$body = "<div>
					<span> Dear BIMscript,</span>
					<br/>
					<p>
						An user <strong>{$fb_details[0]['uname']}</strong> from the project <strong>{$fb_details[0]['pname']}</strong>
						has requested a 3D Print. The detail as follows:--					
					</p>
					<hr />
					<p>
					{$fb_details[0]['comment']}
					</p>
			</div>";
			sendMail($to, $subject, $body);
		}
	 }
	
	/**
	 * Save the data in the database
	 */
	 
    private function saveData($p){
		$data = array(
			'user_id' => $this->_userid,
			'project_id' => $this->_project_id_arr[0],
			'comment' => strip_tags($p['comment']),
			'date' => time(),
			'reuest_type' => '3D Print'
		);
		
		$this->_db->insert('users_requests', $data);
		return $this->_db->insert_id();
	}
	
	/**
	 * Fetch the feedback details like which user project name etc
	 */
	
	function getFeedbackDetails( $id = 0){
		$data  = array();
		if($id){
			$this->_db->where('a.id', $id);
		}
		$this->_db->select('a.*, b.name as uname, c.name as pname');
		$this->_db->from('users_feedback a');
		$this->_db->join('users b', 'a.user_id = b.id');
		$this->_db->join('projects c','a.project_id = c.id');
		
		$q = $this->_db->get();
		if($q->num_rows()){
			foreach($q->result_array() as $id=>$row){
				$data[$id] = $row;
			}
		}
		
		return $data;
	}
 }
?>

