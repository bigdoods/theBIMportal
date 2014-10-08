<?php
/**
 * The sitephotograph app
 */
 
 class sitephotograph_app extends Bim_Appmodule{
	
 	/**
	 * The entry point of the app
	 */
	private $_suported_type = array('jpg', 'jpeg', 'png', 'tif', 'gif', 'img', 'bmp');
	public function __construct(){
		parent::start();
		$this->_db = $this->_me->db;// this is codeigniter db
	}
	
	public function init(){
		$site_photographs = $this->sitePhotographs();		
		?>
        <script type="text/javascript">
			
        	(function($){
				$.fn.ext=function(a){var ar=a.split('.');return ar[ar.length-1].toLowerCase()};
				var support_type = ['jpg','jpeg','png', 'gif'];
				var min_size = 1024*500;
				var max_size = 1024*5000;
				$(function(){
					$('.dragdrop,#file').html5Uploader({
						name: 'foo',
						
						postUrl : '<?php echo base_url('portal/invoke/?a=sitephotograph_app&f=uploadPicture');?>',
						
						onClientLoad: function(e, file){/*
							var status = 1;
							try{
								$('.dragdrop').overlay(1);
								console.log($.fn.ext(file.name));
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
						*/},
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
					var html = '<li><a class="vlightbox1 vlightbox1_new" href="<?php echo base_url() ?>/upload/site_photograph/original/'+response.data+'" title="site photography"><img src="<?php echo base_url() ?>/upload/site_photograph/thumb/'+response.data+'" alt="Site images"/><div class="clear"></div><h2>Just now</h2></a></li>';
						var dom = $(html).fadeOut();						
//						$('.dragdrop').insertBefore(dom);
						dom.insertBefore('.dragdrop');
						dom.fadeIn('slow');
							}
							
							
						addLightBoxOnDynamicElement();
						}
					})
				})
			})(jQuery);
        </script>
        <script src="<?php echo base_url('js/vlbdata1.js')?>" type="text/javascript"></script>
		<ul class="site_photo_graph">
           <?php
		   		foreach($site_photographs as $photo){
					?>
                    <li>
                            			<a class="vlightbox1" href="../../upload/site_photograph/original/<?php echo $photo['path']?>" title="<?php echo $photo['original_name']?>"><img src="../../upload/site_photograph/thumb/<?php echo $photo['path']?>" alt="<?php echo $photo['original_name']?>" />
                                            <div class="clear"></div>
                                            <h2>At <?php echo date('H:i' , $photo['upload_date']).' on '; echo date('d-m-Y', $photo['upload_date'])?></h2>
                                        </a>
                                        
                   </li>
					
					<?php
				}
		   ?>     <li class="dragdrop">
                            			<img src="<?php echo base_url()?>/images/upload.jpg" alt="" onclick="$('#file').click();"/>
                                        <input type="file" style="height:0px;width:0px;" id="file"/>
                                        <div class="clear"></div>
                                        <div class="loader" style="display:none;"><img src="../../upload/site_photograph/ajax-loader.gif" alt="rahul yadav" /></div>
                                        <h2>Upload here</h2>
                                    </li>       
               </ul>
		<?php
	}
	
	/**
	 * Function uplpoad pic
	 */
	 
	public function uploadPicture(){
		$response = array('data'=>'', 'error' => array());
		if($_FILES['foo']['error'] === 0){
			$extension = strtolower(pathinfo( $_FILES['foo']['name'], PATHINFO_EXTENSION));
			$name = pathinfo( $_FILES['foo']['name'], PATHINFO_FILENAME);
			if( in_array( $extension, $this->_suported_type) !== false){
				#+ upload file to the upload folder
				$new_name = $name . '_' . $this->_userid . '_' . $this->_project_id_arr[0] . '_' .time() . '.' . $extension;
				$folder = 'original/';
				$upload_dir = 'upload/site_photograph/';				
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
							$this->_db->insert('site_photographs' , $data);
						}
						
				}else{
					$response['error'][] = 'File write failed,Please check back soon';
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
	public function sitePhotographs(){
		$data = array();
		$this->_db->where('project_id IN ('. implode(',' , $this->_project_id_arr).')' );
		$q = $this->_db->get('site_photographs');
		if($q->num_rows()){
			foreach($q->result_array() as $id=>$row){
				$data[$id] = $row;
			}
		}
		return $data;
	} 
	 
 }
?>