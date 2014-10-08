<?php
class filemanager_app extends Bim_Appmodule{
	private $_db;
	public $class_css = 'content_main_tickets';

	protected $previewable_file_extensions = array();

	//public $class_css = 'facebook_messaing_2';
	public function __construct(){
		parent::start();
		$this->_me->load->model('Projects');
		$this->_me->load->model('Docs');
		$this->_db = $this->_me->db;

		$this->previewable_file_extensions = array(
			'pdf', 'svg', 'emf', 'wmf', 'dwg', 'dxf', 'dwf', 'hpgl', 'plt', 'cgm',
			'tep', 'stp', 'iges', 'igs', 'brep', 'stl', 'sat', 'png', 'bmp', 'jpg',
			'gif', 'tiff', 'tga', 'cal', '7z', 'rar', 'cab', 'zip', 'bzip', 'tar'
		);
		$this->_me->load->helper('network');
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
										  my: 'top left',  // Position my top left...
										  at: 'bottom right',
								   }
					});	
				})
                </script>               
				<?php
				break;
				case 2:
				?>
				<script type="text/javascript">
                	$(document).on('click', '.delete_file', function(e){
						var file_id = $(this).data('file_id');
						var dom = $('.span12');
						var t= $(this);
						if(confirm("Are you sure you wnat to delete?")){
							$.ajax({								
								url : base_path + 'admin/invoke?a=filemanager_app&f=delete&id='+file_id,
								beforeSend:function(){
									
									dom.overlay("Please wait");
								},
								success:function(r){
									if(r !== '-1'){
										if($('table.table tbody tr').length >2){
											t.closest('tr').slideUp();
										}else{
											window.location.href = base_path+'admin/dashboard/4';
										}
									}else{
										dom.overlay("Some error ocured, please try after some time");
									}
									console.log(r);
								},
								error:function(){},
								complete:function(){
									dom.overlay(0, -1);
								}				
							})
						}
						return false;
					})
                </script>
				<?php 
				break;
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

	public function file_list(){
		global $app_id;

		$this->printStyle(array(1));
		$this->printScript(array(1,2));
		$id = $this->_me->input->get('id');
		$doc_details = $this->_me->Docs->getDocDetails($id);
		?>

        <ul class="tickets">
                <li>
                	<div class="row-fluid">
                        <!-- block -->
                        <div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left">File details</div>
                            </div>
                            <div class="block-content collapse in">
                          		<div class="span12">
                                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered display" id="ticket_details">
                                	<thead>
                               			<tr>
                                   		<td>File Name</td>
                                        <td>File Extension</td>
                                        <td>Group</td>
                                        <td>Type</td>
                                        <td>Preview</td>
                                        <td>Size</td>
                                        <td>Document Date</td>
                                        <td>Date Modified</td>
                                        <td>Details</td>
                                        <td>Download</td>
                                        <td>View Ticket</td>
                                        <?php
                                        	if(getCurrentUserRole() == 1):
										?>
											<td>Delete</td>
										<?php
											endif;
											
										?>
                                       
                                        </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($doc_details as $file):
                                    if(file_exists($file['path'])){
										//++ The file size
										$file_size = filesize($file['path']);
										if($file_size){
											$file_size = (float)$file_size/1024;
											$file_size = number_format($file_size, 2).'Kb';
										}else{
											$file_size = '-';
										}
										// + file moidifed time
										$file_modifed_time = filemtime($file['path']);
										if(!$file_modifed_time){
											$file_modifed_time = '';
										}else{
											$file_modifed_time = date('H:i \o\n d-m-Y', $file_modifed_time);
										}

										$file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
									}else{
										$file_size = '-';
										$file_modifed_time = '';
										$file_extension = '';
									}

									$document_time = strtotime($file['document_date']);
									if(!$document_time){
										$document_time = '';
									}else{
										$document_time = date('d-m-Y', $document_time);
									}


									?><tr>
                                   		<td><?php echo pathinfo($file['name'], PATHINFO_FILENAME)?></td>
                                        <td><?php echo $file_extension ?></td>
                                        <td><?php echo $file['parent_doctypename']?></td>
                                        <td><?php echo $file['doctypename']?></td>
                                        <td><p><img src="<?php echo base_url($file['path'])?>" alt="No preview" width="100" height="100" onError="javascript:$(this).closest('p').html('No preview')"></p></td>
                                        <td><?php echo $file_size;?></td>
                                        <td><?php echo $document_time;?></td>
                                        <td><?php echo $file_modifed_time;?></td>
                                        <td><?php echo $file['details']?></td>
                                        <td class="small">
                                        	<a href="<?php echo base_url('admin/download/'.$file['id'])?>" target="_blank">Download</a>
                                        	<?php if(in_array($file_extension, $this->previewable_file_extensions)){ ?>
                                        		<a href="<?php echo base_url('portal/project/'. $app_id .'?action=file_preview&id='.$file['id'])?>" target="_blank">Preview</a>
                                        	<?php } ?>
                                        </td>
                                        <td class="small"><a class="for_admin_ajax" href="<?php echo getCurrentUserRole() == 1 ? base_url('admin/invoke/5/').'?a=ticket_app&f=ticketDetails&id='.$file['ticket_id'] : base_url('portal/project/7?f=ticketDetails&id='.$file['ticket_id'])?>"><?php echo $file['represent_id'];?></a></td>
                                        <?php
                                        	if(getCurrentUserRole() == 1):
										?>
											<td><a href="javascript:void(0)" class="delete_file" data-file_id="<?php echo $file['id']?>">Delete</a></td>
										<?php
											endif;
											
										?>
                                        </tr>
                                    <?php
											endforeach;
									?>                                   
                                </tbody>
                                </table>
                                </div>  
                            </div>
                        </div>
                    </div>
                            
                </li>

			</ul>
        <?php
	}

	private function preview_proxy(){
		$url = $this->_me->input->get('url');

		//$response = get_from('http://sharecad.org/cadframe/load?url='. $url);
		$response = get_from('https://sharecad.org/cadframe/load?url=http://www.cadsofttools.com/dwgviewer/floorplan.dwg');
		
		$response = preg_replace('/(src|href)\=\"\//', '\1="https://sharecad.org/', $response);
		//$response = str_replace('<div class="powered"><a id="shareCad" href="#" target="_blank" style="color: gray;">ShareCAD.org</a></div>', '<div class="powered" style="display:none"><a id="shareCad" href="#" target="_blank" style="color: gray;">ShareCAD.org</a></div><div class="powered"><a id="bimscript" href="http://bimscript.org" target="_blank" style="color: gray;">BIMscript.com</a></div>', $response);
		$response = str_replace('<div style="position: absolute; z-index: 1000; left: 8px; top: 46px"><span class="lt"><a id="cadEditorX" href="http://cadsofttools.com/products/abviewer/" target="_blank" style="color: black;">ABViewer</a></span></div>', '', $response);
		
		ob_clean();
		echo $response;
		ob_flush();
		exit;
	}

	private function file_preview(){
		global $app_id;

		$id = $this->_me->input->get('id');
		$doc_details = $this->_me->Docs->getDocDetails($id);
		debug($doc_details);
		?>
		<iframe id="file-preview" src="/portal/project/<?php echo $app_id ?>?action=preview_proxy&url=<?php echo base_url($doc_details[0]['path']) ?>" scrolling="no" width="1000px" height="1000px"></iframe>
		<?php
	}

	/**
	 * The funciton which will be triggered 
	 */
	public function init(){
	 	$view = $this->_me->input->get('action');

	 	if(empty($view) || !method_exists($this, $view))
	 		$view = 'file_list';

	 	call_user_func(array($this, $view));
	}
	
	/**
	 * The admin will call this function
	 * Admin will select project and type and upload files to the
	 */
	 
	 public function adminInit(){?>
    	<div class="content_main">
        	<ul class="request_file">
            	<li>
        			<div class="portion">
                    	<h2>File Upload</h2>
                        <div class="clear"></div>
                        <p>please enter details below :</p>
                        <div class="clear"></div>
                       	<form action="#" method="post"validate="validate">
                            <input type="text" class="text_file" placeholder="Comments" data-validation-engine="validate[required]" style="resize:none;" name="comment"/>
                            <div class="clear"></div>
                            <select class="drop" data-validation-engine="validate[required]" name="project">
                            	<option value=""> Select your project</option>
                                <?php 
								// Get all projects
								$projects  =$this->_me->Projects->getAllProject();
								if($projects){
									foreach($projects as $project){

										echo '<option value="'.$project['id'].'">'.$project['name'].'</option>';
									}
								}
								?>
                            </select>
                            <div class="clear"></div>
                            <?php
                                	$doc_details = $this->_me->Projects->getDoctypeDetails(  );
									
								?>
                            <select class="drop" data-validation-engine="validate[required]"  name="documetntype">
                            	<option value=""> Select your file type</option>
                                <?php

                                	// structure all doctypes by their parent_id
                                	$document_type_heirarchy = array();
                                	foreach($doc_details as $doc_detail){
                                		if(! isset($document_type_heirarchy[$doc_detail['parent_id']]))
                                			$document_type_heirarchy[$doc_detail['parent_id']] = array();

                                		$document_type_heirarchy[$doc_detail['parent_id']][] = $doc_detail;
                                	}

                                	// loop through only two levels of structure
                                	// this can be made recursive in future to handle many levels
									foreach($document_type_heirarchy[0] as $doc){
										if(isset($document_type_heirarchy[$doc['id']])){
											echo '<optgroup label="'. $doc['name'] .'">';
											foreach($document_type_heirarchy[$doc['id']] as $child_type){
												echo '<option value="'.$child_type['id'].'">'.$child_type['name'].'</option>';
											}

											echo '</optgroup>';
										}else{
											echo '<option value="'.$doc['id'].'">'.$doc['name'].'</option>';
										}
									}
								?>
                            </select>
                            <div class="clear"></div>
                            <input type="file" style="color:#fff; margin:10px 0 0 0;" data-validation-engine="validate[required]" id="file"/>
                            <div class="clear"></div>
                            <!--<input type="submit" class="submit" value="submit" />-->
                        </form>
                    </div>
                </li>
            </ul>
        </div>

     	<script type="text/javascript">
			var binded = false;
        	$(function(){
				var dom = $('#file').closest('ul.request_file');
				// + prevent uploading files with out selecting project or type
				$(document).on('click','#file', function(e){
					var status = true;
					var message = "";					
					if( !$('[name=project]').val()){
						 status = false;
						 message += 'Please select a project first. ' ;
					}
					if( !$('[name=documetntype]').val()){
						 status = false;
						 message += 'Please select a type first. ' ;
					}
					
					if(status === false){
						dom.overlay(1);
						dom.overlay(message);
						dom.overlay(0,-1);						
					}else{
						if(binded === false){
							binded = true;
							
						}
						
					}
					return status;
				});
				
				
				$('#file').html5Uploader({
						name: 'foo',
						zoo:'sd',
						postUrl : "<?php echo $this->_base_uri;?>?a=filemanager_app&f=upload_file&type="+$('[name=documetntype]').val()+"&pid="+$('[name=project]').val()+"&details="+$('[name=comment]').val(),
						
						onClientLoad: function(e){
							dom.overlay(1);
						},
						
						onClientError: function(){
							dom.overlay("Browser fails to read the file");
							dom.overlay(0,-1);
						},
						onServerError:function(){
							dom.overlay("File upload fails,please try again");
							dom.overlay(0,-1);
						},
						onServerProgress :function(e){},
						onSuccess:function(e, file, response){
							dom.overlay("Upload complete");
							var res = JSON.parse(response);
							if(res.error.length == 0){
								dom.overlay(res.data);setTimeout(function(){
									window.location.href='<?php echo base_url('admin/dashboard').'/4'?>';
								},200);
							}else{
								dom.overlay(res.error[0]);
								$('#file').val('');
							}
							dom.overlay(0, -1);
							//alert(3);
							
						},
						dynamicUrl: function(){
							return "<?php echo $this->_base_uri;?>?a=filemanager_app&f=upload_file&type="+$('[name=documetntype]').val()+"&pid="+$('[name=project]').val()+"&details="+$('[name=comment]').val();
						}
						
					 });	
			
			});
        </script>
     <?php
     }
	
	/**
	 * UPload the file with the help
	 * Of fileupload app
	 */
	 
	 public function upload_file(){
	 	$type = $this->_me->input->get('type');
		$pid = $this->_me->input->get('pid');
		$comment = $this->_me->input->get('comment');
		Admin::setActiveProject($pid);
		$obj = new Fileupload_app();
		$obj->upload_file();
	 }
	
	/**
	 * Delete file friom file manager
	 */
	 
	 public function delete(){
	 	$id  = $this->_me->input->get('id');
		if($id && is_numeric($id)){
			// + have to delete from 
			/**
			 * Notification
			 * uploadedoc
			 * Ticket
			 */
			 
			 // + delete notification
			 $this->_db->trans_start();
			 $this->_db->where('case_id', 3);
			 
			 $arr = array( 'file_id' => $id);
			 $this->_db->where('related_id', json_encode($arr));
			 
			 $this->_db->delete('notifications');
			 
			 // + delete from ticket table
			 $this->_db->where('ticket_for', 1); //1 =file upload refference table ticket_for
			 $this->_db->where('itemid', $id);
			 $this->_db->delete('ticket');
			 
			 // + Delete upload doc
			 $this->_db->where('id',  $id);
			 $this->_db->delete('uploaddoc');
			 $this->_db->trans_complete();
			 echo $this->_db->_error_number() == 0 ? 1 : -1;
		}
	 }
}
?>