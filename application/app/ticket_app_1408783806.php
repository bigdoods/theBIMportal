<?php
/**
 * The ticketing app 
 */
 
 class ticket_app extends Bim_Appmodule{
 	
	/**
	 * The constructor
	 */
	public $class_css = 'content_main_tickets';
	public function __construct(){
		parent::start();
		//parent ::__construct();
	}
	/**
	 * This function print the required script
	 */
	function printScript($options_arr = array()){
		foreach( (array)$options_arr as $option){
			switch($option){				
				case 1:
				// + Script start				
				?>  
                <script src="<?php echo base_url();?>/js/jquery.dataTables.min.js"></script>
       			<script src="<?php echo base_url();?>/js/DT_bootstrap.js"></script>              
				<script>
                $(function(){					
					jQuery('#ticket_details').dataTable();
					$('.qtip_comment').qtip({
								 position: {
										  my: 'top middle',  // Position my top left...
										  at: 'bottom middle',
								   }
					});	
				/**
				 * handle the comment submit form
				 */
					$(document).on('submit', '#ticket_modify_form', function(){
						var t =$(this);
						var dom = $(this);
						$.ajax({
							url : base_path+main_container+'/invoke?a=ticket_app&f=updateTicket',
							beforeSend: function(){
								dom.overlay(1);
								dom.overlay("Please wait");
							},
							type: 'post',
							data: t.serialize(),
							success: function(r){
								dom.overlay("Ticket has been successfully modified");
								if(r !=-1){
									$('li.log_details_header').after(r);
									$('[name=comment]').val('');
								}
							},
							error: function(){
								dom.overlay("An error occured, please try again");
							},
							complete: function(){
								dom.overlay(0,-1);
							}
							
						});
						return false;
					
					});
				});
                </script>               
				<?php

			}
		}
		
	}
	
	/**
	 * This function print the required script
	 */
	function printStyle($options_arr = array()){
		foreach( (array)$options_arr as $option){
			switch( $option){
				case 1 : 						
					?>
                    <link href="<?php echo base_url()?>/css/bootstarp/bootstrap.min.css" rel="stylesheet" media="screen">
                    <link href="<?php echo base_url()?>/css/bootstarp/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
                    <link href="<?php echo base_url()?>/css/bootstarp/DT_bootstrap.css" rel="stylesheet" media="screen">
                    <link href="<?php echo base_url()?>/css/bootstarp/styles.css" rel="stylesheet" media="screen">					
					<?php
					break;
			}
		}
			//break;
	}
	
	function init(){
		$data = $this->getAllRelatedTicketDetails();
		$this->displayTicketsDatatable( $data );
	}
	
	function viewAllTicket(){
		$this->outputStart();
		$data = $this->getAllRelatedTicketDetails();
		if( $data ){
			//$this->ticketDetails( $data);
			$this->displayTicketsDatatable( $data );
		}
		$this->outputEnd();
	}	
	
	
	/**
	 * The ticket details display
	 */
	 function displayTicketsDatatable( $data ){
		$this->printStyle(array(1));
		$this->printScript(array(1));
		?>
        	<ul class="tickets">
                <li>
                	<div class="row-fluid">
                        <!-- block -->
                  		<div class="span12">
                        <table cellpadding="0" cellspacing="0" border="0" class="table display table-grey" id="ticket_details">
                        	<thead>
                       			<tr>
                           		<td>Ticket ID</td>
                                <td>Type</td>
                                <td>Created On</td>
                                <td>Created By</td>
                                <td>Project</td>
                                <td>Comment</td>
                                <td>Status</td>
                                <td>Details</td>
                                </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data as $ticket):

                                $short_ticket_comment = strlen($ticket['comment']) > 30 ? substr($ticket['comment'], 0, 30)."..." : $ticket['comment'];

							?><tr>
                           		<td><?php echo $ticket['represent_id']?></td>
                                <td><?php echo $ticket['ticket_for']?></td>
                                <td><?php echo date('H:i', $ticket['time']);echo ' on '.date('d-m-Y', $ticket['time'])?></td>
                                <td><?php echo $ticket['uname']?></td>
                                <td><?php echo $ticket['pname']?></td>
                                <td class="qtip_comment" title="<?php echo $ticket['comment_full'] ? $ticket['comment_full'] : '' ?>"><?php echo $short_ticket_comment; ?></td>
                                <td><?php echo $ticket['ticketmessage']?></td>                                       
                                <td class="small"><a href="<?php echo $this->_base_uri?>?f=ticketDetails&id=<?php echo $ticket['id']?>" class="for_admin_ajax blue-button action">View</a></td></tr>
                            <?php
									endforeach;
							?>                                   
                        </tbody>
                        </table>
                        </div>  
                    </div>
                            
                </li>

			</ul>
              <!-- Showing  qtip-->                       
		<?php
	}
	/**
	 * Get the ticket related information
	 * Like for what it is created etc the current status
	 */
	public function getAllRelatedTicketDetails( $ticket_id = 0){
				$data = array();
				$this->_me->db->select('a.*, b.ticket_for as ticket_for,b.id as ticket_for_id, c.name as uname, d.name as pname,f.name as ticketmessage,f.id as ticket_status_id,comment,modify_time');
				$this->_me->db->from('ticket a');
				$this->_me->db->join('ticket_for b', 'a.ticket_for = b.id');
				$this->_me->db->join('ticket_log e1', 'e1.ticket_id = a.id');
				$this->_me->db->join('(SELECT MAX(id) as id FROM ticket_log as e2 group by e2.ticket_id) as last', 'e1.id = last.id');
				$this->_me->db->join('users c', 'a.created_by = c.id');
				$this->_me->db->join('projects d', 'a.project_id = d.id');
				$this->_me->db->join('ticket_status f', 'f.id = e1.ticket_status_id');

				$this->_me->db->where('a.project_id IN ('.implode( ',' , $this->_project_id_arr ).')');
				if( $ticket_id ){
					$this->_me->db->where('a.id', $ticket_id );
				}
				$this->_me->db->order_by('a.id DESC');
				$q = $this->_me->db->get(  );				
				if( $q->num_rows() ){
					foreach( $q->result_array() as $id=>$row){
						$data[$id] = $row;
						$data[$id]['ticket_for_desc'] = $row['ticket_for'];
						$data[$id]['creator_name'] = $row['uname'];
						$data[$id]['represent_id'] = str_pad($row['id'], 6, '#00000', STR_PAD_LEFT);
						//$data[$id]['comment'] = substr($row['comment'], 0,10).'...';
						$data[$id]['comment'] = $row['comment'];
						$data[$id]['comment_full'] = $row['comment'];
						
					}
				}
			return $data;		
	}
	
	
	
	/**
	 * This function display the tickets
	 */
	 
	 
	/**
	 * Return all the tickets of the current project
	 * Of current user or admin
	 */
	function getAllRelatedTicket(){		
		$sql = 'SELECT a.*,d.name,b.ticket_for FROM ticket a JOIN ticket_for b on a.ticket_for = b.id join ticket_log c1 ON c1.ticket_id = a.id JOIN ticket_status d ON c1.ticket_status_id = d.id JOIN (SELECT MAX(id) as id FROM ticket_log as c2 group by c2.ticket_id ) last on c1.id = last.id WHERE a.project_id IN ('.implode( ',' , $this->_project_id_arr ).')';		
		$res =$this->_me->db->query( $sql );
		$data = array();
		if( $res->num_rows() ){
			foreach( $res->result_array() as $row){				
				$data[$row['id']] = $row;
			}		
		}
		return $data;
	}
	/**
	 * get ticket log
	 */
	
	public function getTicketLogDetails( $ticket_id = 0 ){
		$data = array();
		$id = $this->_me->input->get('id') ? $this->_me->input->get('id') : $ticket_id;
		if( $id ){
			$this->_me->db->select('a.*,c.name as uname,b.name status');
			$this->_me->db->from('ticket_log a');
			$this->_me->db->join('ticket_status b', 'a.ticket_status_id = b.id');
			$this->_me->db->join('users c', 'a.modifier_id = c.id');
			$this->_me->db->where('a.ticket_id', $id);
			$this->_me->db->order_by('a.id DESC');
			$q = $this->_me->db->get();
			if($q->num_rows()){
				foreach( $q->result_array() as $id=>$row ){
					$row['represent_id'] = str_pad($row['id'], 6, '#00000', STR_PAD_LEFT );
					$data[$id] = $row;
				}
			}
		}
		return $data;
	}
	/**
	 * This function represnt the details of a specifi ticket
	 */
	 public function ticketDetails( $ticket_id = 0){
		$id = $this->_me->input->get('id') ? $this->_me->input->get('id') : $ticket_id;
		$details = $this->getAllRelatedTicketDetails( $id );
		if( $details ){
			foreach($details as $index => &$ticket){				
				$ticket['logdetails'] = $this->getTicketLogDetails( $id );			
			}
				
		}
		$this->displayTicketLog( $details );
	 }
	 /**
	  * Display the log details of the ticket
	  */
	 function displayTicketLog( $details ){
		if(isCurrentUserAdmin() == 1) // this function is called from ajax so need to call output staert
			$this->outputStart();
			$this->printScript(array(1));
		?>
		<div class="dash_4_back_container">
                                <a href="<?php echo base_url('portal/project/7');?>" class="blue-button" id="back-to-ticket-list">&lt; Back to Ticket List</a>
      						 	 <div class="clear"></div>
       							<h3 class="sub-heading">Ticket Description</h3>
                                <ul class="details">
                                <li>
                                   		<h3 class="small">Ticket ID</h3>
                                        <h3 class="small">Type</h3>
                                        <h3>Created On</h3>
                                        <h3 class="small">Created By</h3>
                                        <h3>Project</h3>
                                        <h3>Comment</h3>
                                        <h3>status</h3>                                       
                                       </li>
                                    <?php foreach($details as $ticket):

                                        $short_ticket_comment = strlen($ticket['comment']) > 30 ? substr($ticket['comment'], 0, 30)."..." : $ticket['comment'];

									?><li>
                                   		<p class="small"><?php echo $ticket['represent_id']?></p>
                                        <p class="small"><?php echo $ticket['ticket_for']?></p>
                                        <p><?php echo date('H:i', $ticket['time']).' on ';echo date('d-m-Y', $ticket['time'])?></p>
                                        <p class="small"><?php echo $ticket['uname']?></p>
                                        <p><?php echo $ticket['pname']?></p>
                                        <p class="qtip_comment" title="<?php echo $ticket['comment_full'] ? $ticket['comment_full'] : '' ?>"><?php echo $short_ticket_comment; ?></p>
                                        <p><?php echo $ticket['ticketmessage']?></p>                                       
                                        </li>
                                    <?php
											endforeach;
									?>                                   
                                </ul>
					<div class="clear"></div>
       				<h3 class="sub-heading">Ticket log</h3>
                    <ul class="details log_details">
                                <li class="log_details_header">
                                   		<h3>Log ID</h3>
                                        <h3>Last Modified</h3>
                                        <h3 class="small">Modified By</h3>
                                        <h3 class="small">Status</h3>
                                        <h3>Comment</h3>                                        
                                       </li>
                                    <?php 
											foreach($details as $ticket):
												foreach($ticket['logdetails'] as $log):

                                                    $short_log_comment = strlen($log['comment']) > 60 ? substr($log['comment'], 0, 60)."..." : $log['comment'];

									?>             <li>
													<p><?php echo $log['represent_id']?></p>
													<p><?php echo date('H:i', $log['modify_time']).' on ';echo date('d-m-Y', $log['modify_time'])?></p>
													<p class="small"><?php echo $log['uname']?></p>
													<p class="small"><?php echo $log['status']?></p>
													<p class="qtip_comment" title="<?php echo $log['comment'] ? $log['comment'] : '' ?>"><?php echo $short_log_comment;?></p>											
												  </li>
									<?php
												endforeach;
										  endforeach;
									?>                                   
                                </ul>
                    <div class="clear"></div>
                    <?php if($details[0]['created_by'] == getCurrentUserId() || getCurrentUserRole() == 1 || $details[0]['user_type'] == 1){?>
       				<h3 class="sub-heading">Modify ticket</h3>
                   
                    <form action="" id="ticket_modify_form">
                    	<div class="form_reupdate_back" style="width:27%;">
                        					<p>Status :</p>
                                            <div class="clear"></div>
                                        	<select class="form-input" name="status" >
                                            <?php
                                            	$status_all = $this->getAllPossibleTicketStatus($details[0]['ticket_for_id'], $details[0]['ticket_status_id']);
												foreach($status_all as $status_option){
													echo '<option value="'.$status_option['id'].'" '.($status_option['id'] == $details[0]['ticket_status_id'] ? ' selected="selected"': '' ).'>'.$status_option['name'].'</option>';
												}
											?>	
                                            </select>
                                            <div class="clear"></div>
                                            <p>Comment :</p>
                                            <div class="clear"></div>
                                            <textarea name="comment" class="form-input textarea"></textarea>
                                            <div class="clear"></div>
                                            <input type="hidden" name="ticketid" value="<?php echo $details[0]['id'];?>">  
                                            <input type="submit" class="blue-button action" value="submit" />
                        
                                        	
                                        </div>
                    </form>
                    
                    <?php }?>
                    
       </div>
                       
		<?php
	 	if(isCurrentUserAdmin() == 1) // this function is called from ajax so need to call output staert
			$this->outputEnd();
	 }
	 
	 /**
	  get all possible ticket status
	  */
	 function getAllPossibleTicketStatus($type, $status_id){		 
	 	$data = array();
	 	$status = $this->_me->db->get('ticket_status');
		if($status->num_rows()){
			$data = $status->result_array();
		}
		return $data;
	 }
	 
	 /**
	  * updateTicket
	  */
	 function updateTicket(){
		$p = $this->_me->input->post();
		if($p){
			$data = array(
				'ticket_id' => $p['ticketid'],
				'comment' => strip_tags($p['comment']),
				'modifier_id' => getCurrentUserId(),
				'ticket_status_id' => $p['status'],
				'modifier_role' => getCurrentUserRole(),
				'modify_time' => time(),				
			);
			$this->_me->db->insert('ticket_log', $data);
			if($this->_me->db->insert_id()){
				$insert_id = $this->_me->db->insert_id();
				$ticket_log_all = $this->getTicketLogDetails( $p['ticketid'] );
			$ticket_log = $ticket_log_all[0];
			$html ='<li>
													<p>'.str_pad($ticket_log['id'], 6, '#00000', STR_PAD_LEFT).'</p>
													<p>'.date('H:i', $ticket_log['modify_time']). ' on '.date('d-m-Y', $ticket_log['modify_time']).'</p>
													<p class="small">'.$ticket_log['uname'].'</p>
													<p class="small">'.$ticket_log['status'].'</p>
													<p class="qtip_comment_not" title="'.($ticket_log['comment'] ? $ticket_log['comment'] : '').'">'.substr($ticket_log['comment'], 0, 50).'</p>											
												  </li>';
			echo $html;
			exit;
			}else{
				echo -1;
			}
			
		}
	 }
 }
?>