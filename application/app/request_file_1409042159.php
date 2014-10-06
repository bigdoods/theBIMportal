<?php
/**
 * The app for request a file
 */
 
 class request_file extends Bim_Appmodule{
 	
	
	private $_allowed_extension = array('pdf', 'csv', 'xml', 'dxf', 'obj', 'dgn', 'acis');
	private static $_request_details = NULL;
	
	private static $_request_id = NULL;
	function __construct(){
		parent:: start();
	}
	
	/**
	 * The entry method
	 */
	function init(){
		$r = $this->_me->db->query("SELECT * FROM doctype where is_active=1 AND parent_id=0 ORDER BY `order` ASC");
		echo '<ul class="upload_file">';
		 if($r->num_rows())	{ 
		 	foreach($r->result_array() as $row):
			?>
			 <li>
                                    	<a href="<?php echo $this->_base_uri.'?f=pickType&type='.$row['id']?>">
                                            <img src="<?php echo base_url('images/file.png')?>" alt="" />
                                            <div class="clear"></div>
                                            <h2><?php echo $row['name'];?></h2>
                                        </a>
                                    </li>
			<?php
			endforeach;
		 }
	 }

	 function pickType(){
	 	$group_id = intval($this->_me->input->get('type'));
		$r = $this->_me->db->query("SELECT * FROM doctype where is_active=1 AND parent_id=". $group_id ." ORDER BY `order` ASC");
		echo '<a href="'. $this->_base_uri .'">Back</a><ul class="upload_file">';
		 if($r->num_rows())	{ 
		 	foreach($r->result_array() as $row):
			?>
			 <li>
                                    	<a href="<?php echo $this->_base_uri.'?f=requestFile&type='.$row['id']?>">
                                            <img src="<?php echo base_url('images/file.png')?>" alt="" />
                                            <div class="clear"></div>
                                            <h2><?php echo $row['name'];?></h2>
                                        </a>
                                    </li>
			<?php
			endforeach;
		 }

	 }



	 /**
	  Success function to didplay a message
	  */
	 public function success(){
	 	$rid= $this->_me->input->get('rid');
		$this->setRequestId( $rid );
		$request_details = $this->getRequestDetails();
		if($request_details){
			?>
            <P>Your request has been successfully submitted.And webmaster is also notified.We will contact you soon.</P>
            <?php
		}
	 }
	 
	 /**
	  * request a file
	  */
	 public function requestFile(){
		$type = $this->_me->input->get('type');
		?>
        
        <ul class="request_file"><li>
                            			<div class="portion">
                                        	<h2>File Requested</h2>
                                            <div class="clear"></div>
                                            <p>please enter detsils below :</p>
                                            <div class="clear"></div>
                                           	<form action="?f=submitRequest" method="post" validate="validate">
                                            <input type="hidden" name="type" value="<?php echo $type?>">
                                            	<textarea class="text_area" name="description"></textarea>
                                                <div class="clear"></div>
                                                <select class="drop" name="extension" data-validation-engine="validate[required]">
                                                	<option value=""> Select your file type</option>
                            						<?php foreach( $this->_allowed_extension as $id =>$ext){
														echo '<option value="'.$id.'">'.$ext.'</option>';
													}?>
                                                </select>
                                                <div class="clear"></div>
                                                <input type="submit" class="submit" value="submit" />
                                            </form>
                                        </div>
                                    </li>
                                </ul>
		<!--<div class="dash_3_back_container">
        	<form action="?f=submitRequest" method="post" validate="validate">
                         <h5>Please Enter Details of File Requested:</h5>
                            <div class="clear"></div>
                            <input type="hidden" name="type" value="<?php echo $type?>">
                            <textarea class="text_box_dash2" name="description" data-validation-engine="validate[required]"></textarea>
                            <div class="clear"></div>
                            <div class="clear"></div>
                            <select class="text_box_dash3" name="extension" data-validation-engine="validate[required]">
                            <option value=""> Select your file type</option>
                            <?php foreach( $this->_allowed_extension as $id =>$ext){
								echo '<option value="'.$id.'">'.$ext.'</option>';
							}?>
                            </select>
                            <div class="clear"></div>
                            <div class="clear"></div>
                            <input type="submit" class="submit_dash3" value="submit" />
			</form>                        
                        </div>-->
		<?php	
	 }
	
	
	/**
	 * Perform the various action to save request
	 */
	 
	 public function submitRequest(){		 
	 	$this->saveRequest();//setRequestId();		
		if(self::$_request_id){
			/**
			 * Send admin an notification
			 */
			$this->sendEmailAboutRequest( );
			
			/**
			 * Create ticket
			 */
			$ticket_log_id  = $this->createTciket();
			 
			 /**
			  * Create notification
			  */
			 
			 $notification_id = $this->createNotification();
			 
			header('Location:?f=success&rid='.self::$_request_id);
			exit;
			 
		}
		
	 }
	 
	 
	 /**
	  * Create notification for a file is requested
	  */
	 private function  createNotification(){
	 	$request_details = $this->getRequestDetails( );
		if($request_details){
			$data =array();
			$data['project_id'] = $_COOKIE['actp'];
			$data['related_id'] = json_encode( array( 'file_request_id' => self::$_request_id));
			$data['case_id'] = 4 ;// This is a hard code value from table case
			$data['date_time'] = time();
			$this->_me->db->insert( 'notifications', $data );
			return $this->_me->db->insert_id();
		}
	 }
	 
	 
	 /**
	  * Create tixket for file reuested
	  */
	 private function createTciket(){
	 	
		$request_details = $this->getRequestDetails( );
		if($request_details){
			/**
			 * Create aticket as the user has been created a ticket for
			 * a file request for a project
			 */
			 
			 $data['itemid'] = self::$_request_id;
			 $data['time'] = time();
			 $data['created_by'] = $_SESSION['userdata']['id'];
			 $data['user_type'] = $_SESSION['userdata']['role'];
			 $data['ticket_for'] = 2; // as the ticket is created for file request
			 $data['project_id'] = $_COOKIE['actp'];
			 
			 $this->_me->db->insert('ticket', $data);
			 $ticket_id =  $this->_me->db->insert_id();
			 if( $ticket_id ){
			 	$data = array();
				$data['ticket_id'] = $ticket_id;
				$data['comment'] = 'System: This ticket hs been created for a file request for project '.self::$_request_details['pname'];
				$data['modifier_id'] = $_SESSION['userdata']['id'];
				$data['ticket_status_id'] = 1 ;// as  it is just requested and it is a hardcode value from table ticket_status
				$data['modifier_role'] = $_SESSION['userdata']['role'];
				$data['modify_time'] = time();				
				$this->_me->db->insert( 'ticket_log', $data );
				return $this->_me->db->insert_id();				
			 }
		}
	 }
	 
	 /**
	  * Send an email to the admin about the request details
	  */
	  
	  public function sendEmailAboutRequest( ){
	  		$request_details = $this->getRequestDetails( );
			$to = $this->_me->config->item('admin_email');
			$subject = "A file is requested";
			$body = "<div>
					<div class='greeting'>
						<span>Dear Webmaster</span><br/>
					</di>
					<div class='para1'>
						An user <b>{$request_details['uname']}</b> has requested a file for the project <b>{$request_details['pname']}</b> of type <b>{$request_details['doctypename']}</b>
						<p>Request Extension</p>
						<span>{$this->_allowed_extension[$request_details['extension']]}</span>
						<p>Request Details</p><br>
						<p>{$request_details['description']}</p>
					<div>
					</div>";
				sendMail($to, $subject, $body);
	  }
	 /**
	  * save the request 
	  */
	  
	  private function saveRequest(){
	  	$p = $this->_me->input->post();
		
		$data = array();
		$data['description'] = htmlentities($p['description'], ENT_NOQUOTES);
		$data['type'] = $p['type'];
		$data['extension'] = $p['extension'];
		$data['time'] = time();
		$data['userid'] = $_SESSION['userdata']['id'] ;
		$data['project_id'] = $_COOKIE['actp'];
		
		$this->_me->db->insert('request_doc', $data);
		$this->setRequestId( $this->_me->db->insert_id() ) ;
	  }
	  
	  /**
	   * Get the request details of a specific
	   * Request
	   */
	  public function getRequestDetails( ){
		  if( self::$_request_details)
		  	return self::$_request_details;
	  	$data = array();
		$this->_me->db->where('a.id', self::$_request_id );
		$this->_me->db->select('a.*, b.name as pname, c.name as uname, d.name as doctypename');
		$this->_me->db->from('request_doc a');
		$this->_me->db->join( 'projects b', 'a.project_id = b.id');
		$this->_me->db->join( 'users c', 'a.userid = c.id');
		$this->_me->db->join( 'doctype d', 'a.type = d.id');
		$q = $this->_me->db->get();
		if($q->num_rows()){
			foreach( $q->result_array() as $row){
				//v_dump($row);
				$row['ext_name'] = $this->_allowed_extension[$row['extension']];
				$data = $row;// as it will retrive only one row
			}
		}
	  	return $data;
	  }
	  
	  /**
	   * If we are working with a specif 
	   * Request then make that request
	   * a static variable
	   * and access through the object life time
	   */
	  public function setRequestId( $request_id ){
	  	self::$_request_id  = $request_id;
	  }
	  
	  /**
	   * Get the request details of all
	   * Request
	   */
	  public function getAllRequestDetails( ){		  
	  	$data = array();
		//$this->_me->db->where('a.id', self::$_request_id );
		$this->_me->db->select('a.*, b.name as pname, c.name as uname, d.name as doctypename,e.id as ticket_id');
		$this->_me->db->from('request_doc a');
		$this->_me->db->join( 'projects b', 'a.project_id = b.id');
		$this->_me->db->join( 'users c', 'a.userid = c.id');
		$this->_me->db->join( 'doctype d', 'a.type = d.id');
		$this->_me->db->join( 'ticket e', 'a.id = e.itemid');
		$this->_me->db->join( 'ticket_for f', 'f.id = e.ticket_for');
		$this->_me->db->where('f.id = 2');
		$this->_me->db->order_by( 'a.id desc');
		$q = $this->_me->db->get();
		//echo $this->_me->db->last_query();
		if($q->num_rows()){
			foreach( $q->result_array() as $row){
				$row['represent_id'] = str_pad( $row['id'] , 6, '#000000', STR_PAD_LEFT);
				$row['extension_name'] = $this->_allowed_extension[$row['extension']];
				$data[] = $row;// as it will retrive only one row
			}
		}
	  	return $data;
	  }
	  
	
 }
?>