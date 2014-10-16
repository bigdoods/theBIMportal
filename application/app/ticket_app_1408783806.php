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
					jQuery('#ticket_details').dataTable({
                        scrollY: 300
                    });
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
	
	public function createTicket(){

		if(empty($_post['ticket'])){
			?>
				<form action="<?php echo $this->_base_uri ?>?a=ticket_app&f=createTicket" method="post">

					<div>
						<label for="ticket-project-id">Project</label>
						<select name="ticket[project_id]" id="ticket-project-id">
							<option value=""></option>
							<?php foreach($this->_me->Projects->getAllProject() as $project){ ?>
								<option value="<?php echo $project['id'] ?>"><?php echo $project['name'] ?></option>
							<?php } ?>
						</select>
					</div>

					<div>
						<label for="ticket-for-id">Type</label>
						<select name="ticket[for_id]" id="ticket-for-id">
							<option value=""></option>
							<?php foreach($this->getAllTicketTypes() as $type){ ?>
								<option value="<?php echo $type['id'] ?>"><?php echo $type['ticket_for'] ?></option>
							<?php } ?>
						</select>
					</div>

					<div>
						<label for="ticket-comment">Comment</label>
						<textarea name="ticket[comment]" id="ticket-comment"></textarea>
					</div>
				</form>
			<?
		}else{
			
		}
	}

	/**
	 * The ticket details display
	 */
	 function displayTicketsDatatable( $data ){
		$this->printStyle(array(1));
		$this->printScript(array(1));
		?>
        	<div class="tickets">
		        <?php if(isCurrentUserAdmin()){ ?>
		        		<a href="<?php echo $this->_base_uri ?>?a=ticket_app&f=createTicket" class="for_admin_ajax blue-button action create-ticket">Create</a>
		        <?php } ?>


            	<div class="row-fluid">
                    <!-- block -->
              		<div class="span12">
                    <table cellpadding="0" cellspacing="0" border="0" class="table display table-grey" id="ticket_details">
                    	<thead>
                   			<tr>
                       		<td>Ticket ID</td>
                            <td>Type</td>
                            <td>Author</td>
                            <td>Project</td>
                            <td>Editor</td>
                            <td>Comment</td>
                            <td>Status</td>
                            <td>Details</td>
                            </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data as $ticket){

                            $short_ticket_comment = (strlen($ticket['comment']) > 30 ? substr($ticket['comment'], 0, 30)."&hellip;" : $ticket['comment']);
						?><tr>
                       		<td><?php echo $ticket['represent_id']?></td>
                            <td><?php echo $ticket['ticket_for'] ?></td>
                            <td><?php echo $ticket['uname']?><br /><?php echo date('H:i\ \o\n\ d-m-Y', $ticket['time']) ?></td>
                            <td><?php echo $ticket['pname']?></td>
                            <td><?php echo $ticket['modified_name']?><br /><?php echo date('H:i\ \o\n\ d-m-Y', $ticket['modify_time']) ?></td>
                            <td class="qtip_comment" title="<?php echo ($short_ticket_comment != $ticket['comment'] ? $ticket['comment'] : '') ?>"><?php echo $short_ticket_comment; ?></td>
                            <td><?php echo $ticket['ticketmessage']?></td>                                       
                            <td class="small"><a href="<?php echo $this->_base_uri?>?f=ticketDetails&id=<?php echo $ticket['id']?>" class="for_admin_ajax blue-button action">View</a></td></tr>
                        <?php } ?>                                   
                    </tbody>
                    </table>
                    </div>  
                </div>
			</div>
              <!-- Showing  qtip-->                       
		<?php
	}
	/**
	 * Get the ticket related information
	 * Like for what it is created etc the current status
	 */
	public function getAllRelatedTicketDetails( $ticket_id = 0){
				$data = array();
				$this->_me->db->select('a.*, b.ticket_for as ticket_for,b.id as ticket_for_id,b.is_file AS is_file, c.name as uname, d.name as pname,f.name as ticketmessage,f.id as ticket_status_id,comment,modify_time,g.name AS modified_name');
				$this->_me->db->from('ticket a');
				$this->_me->db->join('ticket_for b', 'a.ticket_for = b.id');
				$this->_me->db->join('ticket_log e1', 'e1.ticket_id = a.id');
				$this->_me->db->join('(SELECT MAX(id) AS id, modifier_id FROM ticket_log AS e2 GROUP BY e2.ticket_id) AS last', 'e1.id = last.id');
				$this->_me->db->join('users c', 'a.created_by = c.id');
				$this->_me->db->join('users g', 'last.modifier_id = g.id');
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


	function getAllTicketTypes(){		
		$sql = 'SELECT * FROM ticket_for tf';		
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
	 function displayTicketLog($details){
		if(isCurrentUserAdmin() == 1) // this function is called from ajax so need to call output staert
			$this->outputStart();
			$this->printScript(array(1));

		$this->_me->load->model('Docs');
		?>
		<div class="dash_4_back_container">
                                <a href="<?php echo base_url('portal/project/7');?>" class="blue-button" id="back-to-ticket-list">&lt; Back to Ticket List</a>
      						 	 <div class="clear"></div>
       							<h3 class="sub-heading">Ticket Description</h3>
                                <table class="details" cellspacing="0" cellpadding="0">
	                                <thead>
	                               		<th class="small">Ticket ID</th>
	                                    <th class="small">Type</th>
	                                    <th class="small">Author</th>
	                                    <th>Project</th>
	                                    <th>Comment</th>
	                                    <?php if(@$details[0]['is_file']){ ?>
		                                    	<th>File</th>
		                                <?php } ?>
	                                    <th>status</th>
	                                </thead>
	                                <tbody>
                                    <?php foreach($details as $ticket){
                                        $short_ticket_comment = (strlen($ticket['comment']) > 30 ? substr($ticket['comment'], 0, 30)."&hellip;" : $ticket['comment']);
                                        
                                        if($ticket['is_file'])
	                                        $file = array_first($this->_me->Docs->getDocDetails($ticket['itemid'])); ?>
										<tr>
	                                   		<td class="small"><?php echo $ticket['represent_id']?></td>
	                                        <td class="small"><?php echo $ticket['ticket_for']?></td>
	                                        <td class="small"><?php echo $ticket['uname'] ?><br /><?php echo date('H:i\ \o\n\ d-m-Y', $ticket['time']) ?></td>
	                                        <td><?php echo $ticket['pname']?></td>
	                                        <td class="qtip_comment" title="<?php echo ($short_ticket_comment != $ticket['comment'] ? $ticket['comment'] : '') ?>"><?php echo $short_ticket_comment; ?></td>
	                                        <?php if($ticket['is_file']){ ?>
	                                        	<td>
	                                        		<?php if(!empty($file)){ ?>
	                                        			<a href="<?php echo $file['path'] ?>"><?php echo $file['name'] ?></a>
	                                        		<?php } ?>
	                                        	</td>
	                                        <?php } ?>
	                                        <td><?php echo $ticket['ticketmessage']?></td>                                       
                                        </tr>
                                    <?php } ?>                                   
                                	</tbody>
                            	</table>
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

                                                    $short_log_comment = strlen($log['comment']) > 60 ? substr($log['comment'], 0, 60)."&hellip;" : $log['comment'];

									?>             <li>
													<p><?php echo $log['represent_id']?></p>
													<p><?php echo date('H:i', $log['modify_time']).' on ';echo date('d-m-Y', $log['modify_time'])?></p>
													<p class="small"><?php echo $log['uname']?></p>
													<p class="small"><?php echo $log['status']?></p>
													<p class="qtip_comment" title="<?php echo ($short_log_comment != $log['comment'] ? $log['comment'] : '') ?>"><?php echo $short_log_comment;?></p>											
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
	 	$status = $this->_me->db
	 		->where(array('visible' => 1))
	 		->where_in('only_user_role', array(0, getCurrentUserRole()))
	 		->get('ticket_status');
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