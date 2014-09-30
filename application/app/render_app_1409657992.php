<?php
/**
 * The sitephotograph app
 */
 
 class render_app extends Bim_Appmodule{
	
 	/**
	 * The entry point of the app
	 */
	private $_suported_type = array('jpg', 'jpeg', 'png', 'tif', 'gif');
	public function __construct(){
		parent::start();
		$this->_db = $this->_me->db;// this is codeigniter db
	}
	
	public function init(){
		$site_photographs = $this->sitePhotographs();
		$base_url = base_url('upload/site_renders');		
		?>
        <script type="text/javascript">
			
        	(function($){
				$.fn.ext=function(a){var ar=a.split('.');return ar[ar.length-1].toLowerCase()};
				var support_type = ['jpg','jpeg','png', 'gif'];
				var min_size = 1024*500;
				var max_size = 1024*5000;
				var base_url = '<?php echo base_url()?>';
				$(function(){
					$('.dragdrop,#file').html5Uploader({
						name: 'foo',
						<?php
						if(getCurrentUserRole()== 1){
						?>
						postUrl : '<?php echo base_url('admin/invoke/?a=render_app&f=uploadPicture');?>',
						<?php
						}else{
						?>
						postUrl : '<?php echo base_url('portal/invoke/?a=render_app&f=uploadPicture');?>',
						<?php
						}
						?>
						
						onClientLoad: function(e, file){
							var status = 1;
							try{
								$('.dragdrop').overlay(1);
								if( $.inArray($.fn.ext(file.name),support_type)  == -1 ){									
									$('.dragdrop').overlay("Sorry, the file is not a valid image");
									status = 0;
								}else if(file.size < min_size){									
									$('.dragdrop').overlay("Sorry, minimum allowed filesize is 500kb");			
									status = 0;
								}else if(file.size > max_size){
									$('.dragdrop').overlay("Sorry, allowed allowed filesize is 5mb");
									status = 0;
								}								
								$('.dragdrop').overlay(0,-1);
								if(status !=0){
									$('.loader').show();
								}
							}catch(e){
								
							}
						},
						onClientError: function(){
							$('.dragdrop').overlay("Browser fails to read the file");
							$('.dragdrop').overlay(0,-1);
						},
						onServerError:function(){
							$('.dragdrop').overlay("File upload fails,please try again");
							$('.dragdrop').overlay(0,-1);
						},
						onServerProgress :function(e){},
						onSuccess:function(e, file, response){
							var response = JSON.parse(response);
							$('.loader').fadeOut('slow');
							if($.isEmptyObject(response.error) !== true){
								$('.dragdrop').overlay(1);
								$('.dragdrop').overlay(response.error[0]);
							}else{
								var html = '<li><a class="vlightbox1 vlightbox1_new" href="'+base_url+'upload/site_renders/original/'+response.data+'" title="site photography"><img src="'+base_url+'upload/site_renders/thumb/'+response.data+'" alt="Site images"/><div class="clear"></div><h2 rel="download" data-renderid="<?php echo base_url('admin/invoke?a=render_app&f=renderDownlod&id=')?>'+response.id+'">Just now</h2></a></li>';
						var dom = $(html).fadeOut();						
//						$('.dragdrop').insertBefore(dom);
						dom.insertBefore('.dragdrop');
						dom.fadeIn('slow');
						addLightBoxOnDynamicElement();
							}
							
							
						}
					})
				})
				/**
				 * Download render
				 */
				 <?php 
				 if(getCurrentUserRole() == 1){
				?>
				 $('h2[rel=download]').on('click',function(e){
					 e.stopImmediatePropagation();
				 	window.open($(this).data('renderid'), '_new');
					e.preventDefault();
					return false;
				 });
				<?php
				 }?>
			})(jQuery);
        </script>
		<ul class="site_photo_graph">
           <?php
		   		foreach($site_photographs as $photo){
					?>
                    <li>
                            			<a class="vlightbox1" href="<?php echo base_url();?>upload/site_renders/original/<?php echo $photo['path']?>" title="<?php echo $photo['original_name']?>"><img src="<?php echo base_url();?>upload/site_renders/thumb/<?php echo $photo['path']?>" alt="<?php echo $photo['original_name']?>" />
                                            <div class="clear"></div>
                                            <h2  rel="download" data-renderid="<?php echo base_url('admin/invoke?a=render_app&f=renderDownlod&id='.$photo['id'])?>">At <?php echo date('H:i' , $photo['upload_date']).' on ';echo date('d-m-Y', $photo['upload_date'])?></h2>
                                        </a>
                                        
                   </li>
					
					<?php
				}
				if(getCurrentUserRole() == 1){
		   ?>     <li class="dragdrop">
                            			<img src="<?php echo base_url()?>/images/upload.jpg" alt="" onclick="$('#file').click();"/>
                                        <div class="clear"></div>
                                        <input type="file" style="height:0px;width:0px;" id="file"/>
                                        <div class="loader" style="display:none;"><img src="<?php echo base_url();?>upload/site_renders/ajax-loader.gif" alt="rahul yadav" /></div>
                                        <h2>Upload here</h2>
                                    </li>       
              
		<?php
				}
				echo ' </ul>';
		?>
        <script src="<?php echo base_url('js/vlbdata1.js')?>" type="text/javascript"></script>
        <?php
	}
	
	/**
	 * Function uplpoad pic
	 */
	 
	public function uploadPicture(){
		$response = array('data'=>'', 'error' => array());
		if($_FILES['foo']['error'] === 0){
			$extension = pathinfo( $_FILES['foo']['name'], PATHINFO_EXTENSION);
			$name = pathinfo( $_FILES['foo']['name'], PATHINFO_FILENAME);
			if( in_array( $extension, $this->_suported_type) !== false){
				#+ upload file to the upload folder
				$new_name = $name . '_' . $this->_userid . '_' . $this->_project_id_arr[0] . '_' .time() . '.' . $extension;
				$folder = 'original/';
				$upload_dir = 'upload/site_renders/';
				if( move_uploaded_file($_FILES['foo']['tmp_name'], $upload_dir . $folder . $new_name) ){
					# + moving orginal file is done, now resize and move to medium size
					$config['image_library']    = 'gd2';
					$config['source_image']     = $upload_dir . 'original/' . $new_name;
					$config['create_thumb']     = false;
					$config['maintain_ratio']   = true;
					$config['width']            = 500;
					$config['height']           = 500;   
					$config['new_image']        = $upload_dir .'medium/' . $new_name;
					$this->_me->load->library('image_lib');
					$this->_me->image_lib->initialize($config);
					$this->_me->image_lib->resize();
					
					# + upload in thumb directory
					$config['width']            = 200;
					$config['height']           = 180;   
					$config['new_image']        = $upload_dir .'thumb/' . $new_name;
					$this->_me->image_lib->initialize($config);
					$this->_me->image_lib->resize();
					$response['data'] = $new_name;
					$data = array(
						'project_id' => $this->_project_id_arr[0],
						'user_id' => $this->_userid,
						'upload_date' => time(),
						'path' => $new_name,
						'original_name' =>$_FILES['foo']['name']
						);
						foreach($this->_project_id_arr as $pid){
							$data['project_id'] = $pid;
							$this->_db->insert('site_renders' , $data);
						}
				$response['id'] = $this->_db->insert_id();
				
						
				}
			}else{
				$response['error'][] = 'File type is not supported';
			}
		}else{
			$response['error'][] = 'File is corrupted';
		}
		echo json_encode($response);
	}
	/**
	 * Get all site photographic of specifi context
	 */
	public function sitePhotographs(  $id = 0){
		$data = array();
		$this->_db->where('project_id IN ('. implode(',' , $this->_project_id_arr).')' );
		if($id){
			$this->_db->where('id', $id);
		}
		$this->_db->group_by('path');
		$q = $this->_db->get('site_renders');
		if($q->num_rows()){
			foreach($q->result_array() as $id=>$row){
				$data[$id] = $row;
			}
		}
		return $data;
	} 
	 
	/**
	 * Render download
	 */
	public function renderDownlod(){
		$id = $this->_me->input->get('id');
		if($id){
			$render_details  = $this->sitePhotographs($id);
			if($render_details){
				$file = $render_details[0];
				$data  = file_get_contents('upload/site_renders/original/'.$file['path']);
				$this->_me->load->helper('download');
				force_download($file['original_name'], $data);
			}
		}
	}
 }
?>