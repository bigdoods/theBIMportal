<?php

class issueviewer_app extends Bim_Appmodule{
	
	/**
	 * The default constructor
	 * any initialization can be placed here
	 * And at first the parent constructor must be called
	 */
	private $_db = NULL;
	
	public $issue_image_thumb = 'upload/site_issues/thumb/';
	public $issue_image_medium = 'upload/site_issues/medium/';
	public $issue_image_original = 'upload/site_issues/original/';
	
	private $_table_name = 'issues';
	public $_ticket_for_id = '3';
	private $_ticket_default_status_id = '11';
	private $_case_id = '5';
	
	
	public function __construct(){
		parent::start();
		$this->_db = $this->_me->db;// this is codeigniter db
		$this->_me->load->model('Users');
		$this->_me->load->model('Projects');
		$this->_me->load->model('Docs');
		
	}
	
	/**
	 * This function will be called when ever a new app is 
	 * created, the table creation and folder creation, etc
	 * works can be done within this funciton
	 */
	public function install(){
		$error = false;
		$error_details =array();
		$sql = "CREATE TABLE IF NOT EXISTS `".$this->_table_name."` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `name` varchar(255) NOT NULL,
	  `description` text NOT NULL,
	  `time` int(11) NOT NULL,
	  `userid` int(11) NOT NULL,
	  `project` int(11) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

		if( !$this->_db->_error_number()){
			$error = true;
			$error_details[] =  $this->_db->_error_message();
		}
		// + create the folder
		if( !is_dir('upload/site_issues')){
			if(mkdir('upload/site_issues', 0777, true) && mkdir('upload/site_issues/original', 0777, true) && mkdir('upload/site_issues/thumb', 0777, true) && mkdir('upload/site_issues/medium', 0777, true)){
				
			}else{
				$error_details[] = "Folder creation stopped";
			}
		}
		return $error_details;
	}
	/**
	 * Print the scripts
	 */
	public function printScript($options_arr = array()){
		foreach( (array)$options_arr as $option){
			switch($option){				
				case 1:
				// + Script start				
				?>          
				<script>
                $(function(){
					$(document).on('click', '.create_issue', function(){
						$('.tab_content_detila_back').hide();
						$('.create_form').fadeIn();
						return false;
					});
					$(document).on('click', '#list_issue', function(){
						$('.create_form').hide();
						$('.list').fadeIn();						
						return false;
					});					
					bindHtmlUploaderIssue();
					 
					
				})
                </script>               
				<?php
				break;
				case 2:
				?>
				<script type="text/javascript">
                	$(document).on('click', '.delete_file', function(e){})
                </script>
				<?php 
				break;
			}
		}
		
	}
	
	/**
	 * Admin init
	 */
	public function adminInit(){
		$issue_details = $this->getAllIssueDetails();
		$this->displayIsuuesAdmin($issue_details);
	}
	
	public function getAllIssueDetails($issue_id = 0){
		$data = array();
		$this->_db->select('a.*, b.name as pname');
		$this->_db->from($this->_table_name.' as a');
		$this->_db->join('projects as b', 'a.project = b.id');
		if($issue_id){
			$this->_db->where('a.id', $issue_id);
		}
		$this->_db->order_by('a.id DESC');
		$res = $this->_db->get();
		if($res->num_rows()){
			foreach($res->result_array() as $row){
				$this->_db->where('itemid', $row['id']);
				$this->_db->where('ticket_for', $this->_ticket_for_id);
				
				$q = $this->_db->get('ticket');
				if($q->num_rows()){
					$ticket_details = $q->row_array();
					$row['ticket_id'] = $ticket_details['id'];
				}
			$data[] = $row;
			}
			
		}
		
		return $data;
	}
	
	/**
	 * Dislay the issues at admin panel
	 */
	public function displayIsuuesAdmin($issue_details = array()){
		global $tabid;
		$this->printScript(array(1));
		?>
		<div id="tab<?php echo $tabid?>">
                                     <div class="tab_content<?php echo $tabid?>">
                                    <div class="tab_content_detila_back list">
                                            	<ul class="head">
                                                	<li class="big"><p>Project</p></li>
                                                    <li class="big"><p>  Date  </p></li>
                                                    <li class="big" style="width:24%"><p>Image</p></li>
                                                    <li class="big"><p>Discussion</p></li>
                                                    <li class="small" style="width:21%"><p>Details</p></li>
                                                </ul>
                                                <?php if($issue_details):
													foreach($issue_details as $issue):
												?>
													<ul class="details" rel="issue-<?php echo $issue['id']?>">
                                                	<li class="big"><p><?php echo ucfirst($issue['pname'])?></p></li>
                                                    <li class="big"><p><?php echo date('H:i',$issue['time']) .' on '. date('d-m-Y', $issue['time'])?></p></li>
                                                    <li class="big"  style="width:24%"><p><IMG src="<?php echo base_url($this->issue_image_thumb).'/'.$issue['path'];?>" /></p></li>
                                                    <li class="big"><p><mark>
								<a href="<?php echo isset($issue['ticket_id']) ? base_url('admin/viewticket/5?a=issueviewer_app&f=ticketDetails&id='.$issue['ticket_id']) : 'javascript:void(0);'?>" class="for_admin_ajax">view tickets</a>
							</mark></p></li>
													<li class="small"  style="width:21%"><p><a class="issue_edit_link" id="issue_edit-<?php echo $issue['id']?>" href="javascript:void(0)">Edit</a></p><p><?php echo $issue['description']?></p></li>
                                                </ul>
													
												
                                                
												<?php 
													endforeach;
													else:
												?>
													<h2 class="data-blank"> No issues exists in the system</h2>
												<?php
													endif;
												?>                                                
                                                <input type="button" class="sub_it_back create_issue" value="Create"/>
                                            </div>
                                    <div class="create_form" style="display:none">
                                         	<form name="create_issue" id="create_new_issue" validate="validate">
                                            <div class="craate_content universal_form_back">
                                            	<p>Select Project</p>
                                                <div class="clear"></div>
                                                <?php $project_details = $this->getProjectDetails();?>
                                                
                                                <select name="project" data-validation-engine="validate[required]">
                                                	<option value=""> Select a project</option>
                                                	<?php foreach($project_details as $project){
														echo '<option value="'.$project['id'].'">'.$project['name'].'</option>';
													}?>                                                	
                                                </select>
                                                <div class="clear"></div>
                                                
                                                <p>Issue Title</p>
                                                <div class="clear"></div>
                                                <input type="text" name="name" value="" data-validation-engine="validate[required]" class="text_box_inner">
                                                <div class="clear"></div>
                                                
                                                <p>Description</p>
                                                <div class="clear"></div>
                                                <textarea class="text_box_inner" data-validation-engine="validate[required]" name="description"></textarea>
                                                <div class="clear"></div>
                                                
                                                <p>Upload the file</p>
                                                <div class="clear"></div>
                                                <input type="file" name="name" value="" data-validation-engine="validate[required]" class="text_box_inner" id="file_issue">
                                                <input type="hidden" name="path" value="" id="path">
                                                 <input type="hidden" name="original_filename" value="" id="original_filename">
                                                <div class="clear"></div>
                                                
                                                <div class="clear"></div>
                                                <input type="submit" class="sub_it_back submit_issue" value="Create"/>
                                                <input type="button" class="sub_it_back list_issue" value="List" id="list_issue"/>
                                            </div>
                                            </form>
                                         </div>
                                         </div>
                                         
                                         
                                         
</div>
		<?php
	}
	
	public function init($issie_details = array()){
	$issue_details = $this->getAllIssueDetails();	
		?>
		<div class="new_apps_back_container">
        	<ul class="new_details_apps">
				<?php if($issue_details):
					foreach($issue_details as $issue):
				?>
				<li>
                	<div class="portion">
                    	<div class="left_image"><img src="<?php echo base_url($this->issue_image_thumb).'/'.$issue['path'];?>" class="image" alt="" /></div>
                        <div class="right_image">
                        	<h4><?php echo date('H:i', $issue['time']).' on '. date('d-m-Y', $issue['time']);?>
							<mark>
								<a href="<?php echo isset($issue['ticket_id']) ? base_url('portal/project/7?&f=ticketDetails&id='.$issue['ticket_id']) : 'javascript:void(0);'?>">view tickets</a>
							</mark>
							</h4>
                        	<h2><?php echo $issue['name']?></h2>
                            <div class="clear"></div>
                            <h3><?php echo $issue['description']?></h3>
                        </div>
                    </div>
                </li>
				<?php
					endforeach;
						endif;
				?>
            	
                
            </ul>
        </div>
		<?php
	}
	
	public function getProjectDetails(){
		$this->_me->load->model('Projects');
		$project_details = $this->_me->Projects->getAllProject();
		return $project_details;
	}
	
	public function upload(){
		$response = array();
		$old_file = $this->_me->input->get('old_file');
		/**
		 * Unlink the old uplaoded files
		 */
		 @unlink($this->issue_image_original . $old_file);
		 @unlink($this->issue_image_medium . $old_file);
		 @unlink($this->issue_image_thumb . $old_file);
		$response['data'] ='';
		$response['error'] = array();
		if(!empty($_FILES) && $_FILES['foo']['error'] == 0 ){
			$name = pathinfo($_FILES['foo']['name'], PATHINFO_FILENAME);
			$extension = pathinfo($_FILES['foo']['name'], PATHINFO_EXTENSION);
			$new_name = $name . '_' . time(). '.' . $extension;
			if(move_uploaded_file($_FILES['foo']['tmp_name'], $this->issue_image_original . $new_name)){
				# + moving orginal file is done, now resize and move to medium size
					$config['image_library']    = 'gd2';
					$config['source_image']     = $this->issue_image_original . $new_name;
					$config['create_thumb']     = false;
					$config['maintain_ratio']   = true;
					$config['width']            = 500;
					$config['height']           = 500;   
					$config['new_image']        = $this->issue_image_medium . $new_name;
					$this->_me->load->library('image_lib');
					$this->_me->image_lib->initialize($config);
					$this->_me->image_lib->resize();
					
					# + upload in thumb directory
					$config['width']            = 200;
					$config['height']           = 180;   
					$config['new_image']        = $this->issue_image_thumb . $new_name;
					$this->_me->image_lib->initialize($config);
					$this->_me->image_lib->resize();
					$response['data'] = $new_name .'~!~' . $_FILES['foo']['name'];
					
			}else{
				$response['error'] = "Write permission problem";
			}
		}else{
			$response['error'] = "File is corrupted";
		}
	echo json_encode($response);
	}
	
	/**
	 * Create the issue
	 */
	 function createIssue(){
	 	$p = $this->_me->input->post();
		if($p){
			// + Insert into issue table
			$data =array('name' => strip_tags($p['name']),
				'description' => strip_tags($p['description']),
				'path' => $p['path'],
				'original_filename' => $p['original_filename'],
				'time' => time(),
				'userid' => getCurrentUserId(),
				'project' => $p['project']
			 );
			 $this->_db->insert($this->_table_name, $data);
			 $issue_id = $this->_db->insert_id();
			 if($issue_id){
			 	// + create a ticket
				$data_ticket = array(
					'itemid' => $issue_id,
					'time' => time(),
					'created_by' => getCurrentuserId(),
					'user_type' => getCurrentuserRole(),
					'ticket_for' => $this->_ticket_for_id,
					'project_id' => $p['project'],					
				);
				$this->_db->insert('ticket', $data_ticket );
				if($ticket_id = $this->_db->insert_id()){
					// + insert into ticket log
					$data_t_log = array(
						'ticket_id' => $ticket_id,
						'comment' => strip_tags($p['description']),
						'modifier_id' => getCurrentuserId(),
						'ticket_status_id' => $this->_ticket_default_status_id,
						'modifier_role' => getCurrentuserRole(),
						'modify_time' => time()
					);
					$this->_db->insert('ticket_log', $data_t_log);
				}
			 // + create the notificaiton			
				$data_case = array(
					'project_id' => $p['project'],
					'related_id' => json_encode(array('issue_id' => $issue_id)),
					'case_id' => $this->_case_id,
					'date_time' => time()
				);
				$this->_db->insert('notifications', $data_case);
			 }
		}
	 	if($this->_db->_error_number()){
			echo 0;
		}else{
			echo 1;
		}
	 }
	 
	 public function displayEditForm($issue_id = 0){
		if(!$issue_id){
			$issue_id = $this->_me->input->get('id');
		}
		$issue_details = $this->getAllIssueDetails($issue_id);		
		if($issue_details){
			$issue_details = $issue_details[0];
			?>

				<div class="edit_form">
				<form name="edit_issue" id="edit_issue" validate="validate">
				<div class="craate_content universal_form_back">
					<p>Select Project</p>
					<div class="clear"></div>
					<?php $project_details = $this->getProjectDetails();?>
					
					<select name="project" data-validation-engine="validate[required]">
						<option value=""> Select a project</option>
						<?php foreach($project_details as $project){
							echo '<option value="'.$project['id'].'" '.($issue_details['project'] == $project['id'] ? 'selected="selected"': '') .'>'.$project['name'].'</option>';
						}?>                                                	
					</select>
					<div class="clear"></div>
					
					<p>Issue Title</p>
					<div class="clear"></div>
					<input type="text" name="name" data-validation-engine="validate[required]" class="text_box_inner" value="<?php echo $issue_details['name']?>">
					<div class="clear"></div>
					
					<p>Description</p>
					<div class="clear"></div>
					<textarea class="text_box_inner" data-validation-engine="validate[required]" name="description"><?php echo $issue_details['description']?></textarea>
					<div class="clear"></div>
					
					<p>Upload the file</p>
					<div class="clear"></div>
					<input type="file" name="name" value="" data-validation-engine="validate[required]" class="text_box_inner" id="file_issue">
					<input type="hidden" name="path" value="<?php echo $issue_details['path']?>" id="path">
					 <input type="hidden" name="original_filename" value="<?php echo $issue_details['original_filename']?>" id="original_filename">
					<div class="clear"></div>
					
					<div class="clear"></div>
					<input type="hidden" name="id" value="<?php echo $issue_details['id']?>">
					<input type="submit" class="sub_it_back submit_issue" value="Update"/>
					<input type="button" class="sub_it_back list_issue" value="List" id="list_issue" onclick="$('li.active').click();" />
				</div>
				</form>
				<script type="text/javascript">
				bindHtmlUploaderIssue();
				</script>
			 </div>
										
			<?php
		}
	 }
	 
	 public function updateIssue(){
		
	 	$p = $this->_me->input->post();
		if($p){
			// + Insert into issue table
			$data =array('name' => strip_tags($p['name']),
				'description' => strip_tags($p['description']),
				'path' => $p['path'],
				'original_filename' => $p['original_filename'],
				'time' => time(),
				'userid' => getCurrentUserId(),
				'project' => $p['project']
			 );
			 $this->_db->where('id', $p['id']);
			 $this->_db->update($this->_table_name, $data);
			 if(! $this->_db->_error_number()){
				echo 1;exit;
			 }
			}
		echo 0;
	 }
}
?>