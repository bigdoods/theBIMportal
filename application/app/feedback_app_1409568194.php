<?php
/**
 * The ticketing app 
 */
 
 class feedback_app extends Bim_Appmodule{
	
 	
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
                                        	<h2>Feedback</h2>
                                            <div class="clear"></div>
                                            <p>Please send us your comments, suggestions, complaints or queries in the form below</p>
                                            <div class="clear"></div>
                                           	<form name="feedback" action="?f=sendFeedback" validate="validate" method="post">
                                            	<textarea class="form-input textarea" data-validation-engine="validate[required]" name="comment"></textarea>
                                                <div class="clear"></div>
                                                <input type="submit" class="blue-button action" value="submit" />
                                            </form>
                                        </div>
                                    </li>
                                    
                                    
                                    
                                </ul>
		<?php
		
	}
	
	/**
	 * Process the user submitted
	 * Dara
	 */
	
	public function sendFeedback(){
		$p = $this->_me->input->post();
		if($p){
			$id = $this->saveData($p);
			if($id){
				$this->sendEmailToRespective( $id );
			}
			?>
				<div class="thankyou">
                    <p>Thank you for your feedback. We will endeavour to respond as soon as possible.</p>
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
					<span> Dear webmaster,</span>
					<br/>
					<p>
						An user <strong>{$fb_details[0]['uname']}</strong> from the project <strong>{$fb_details[0]['pname']}</strong>
						has gave some feedback. The detail as follows:--					
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
			'date' => time()
		);
		
		$this->_db->insert('users_feedback', $data);
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