<?php
/**
 * This the class which will load throught 
 * The application when ever a controller will load
 * The BIM controller wil be default loaded
 */
class Fileupload_app extends Bim_Appmodule{
	
	/**
	 * The default constructor
	 * any initialization can be placed here
	 * And at first the parent constructor must be called
	 */
	private $_file_details = array();
	private $_file_id = 0;
	private $_allowed_extension =array('pdf', 'csv', 'xml', 'dwg', 'dxf', 'obj', 'dgn', 'acis', 'jpg', 'jpeg',  'fbx', 'ifc', 'bcf', 'png');
	public function __construct(){
		parent::start();
		$this->_me->load->model('Projects');
		
	}
	/**
	 * The mandatory method
	 * The frame work will call this method
	 * This is the entry point of the app
	 * It can produce any browser friendly output
	 */
	 
	 public function init(){
		$r = $this->_me->db->query("SELECT * FROM doctype WHERE is_active=1 AND parent_id=0 ORDER BY `order` ASC");
		echo '<ul class="upload_file">';
		 if($r->num_rows())	{ 
		 	foreach($r->result_array() as $row):
			?>
			<li>
            	<a href="<?php echo $this->_base_uri.'?f=file_type&type='.$row['id']?>">
                    <img src="<?php echo base_url('images/file.png')?>" alt="" />
                    <div class="clear"></div>
                    <h2><?php echo $row['name'];?></h2>
                </a>
            </li>
			<?php
			endforeach;
		 }
	 ?>
    
	 
	 <?php
	 }

	 public function file_type(){
	 	$type_id = $this->_me->input->get('type');
	 	$parent = $this->_me->db->query('SELECT * FROM doctype WHERE is_active=1 AND id='. intval($type_id) .' LIMIT 1');
		$r = $this->_me->db->query('SELECT * FROM doctype WHERE is_active=1 AND parent_id='. intval($type_id) .' ORDER BY `order` ASC');

		echo '<ul class="upload_file"><a href="'. $this->_base_uri .'" class="blue-button action back-link">&lt; Back</a>';
		 if($r->num_rows())	{ 
		 	foreach($r->result_array() as $row):
			?>
			<li>
            	<a href="<?php echo $this->_base_uri.'?f=upload&type='. $row['id'] ?>">
                    <img src="<?php echo base_url('images/file.png')?>" alt="" />
                    <div class="clear"></div>
                    <h2><?php echo $row['name'];?></h2>
                </a>
            </li>
			<?php
			endforeach;
		 }
	 ?>
    
	 
	 <?php
	 }
	 
	 public function upload(){
		 $type = $this->_me->input->get('type');
		 /**
		  * get the diocument type name
		  */
		 $r = $this->_me->db->query("SELECT * FROM doctype where is_active=1 AND id= ".abs($type));
		 if($r->num_rows()){
			$row = $r->row_array();
			$document_type = $row['name'];
		 }
	 ?>
	 <ul id="upload-file" class="request_file">
	 	<li>
		 	<div class="portion">
		 		<h2>Upload File</h2>
			 <form action="#" validate="validate" method="POST">
			     <input type="text" name="details" value="" class="text_area_for_upper_ul form-input" placeholder="Please write some details, before uploading the file...">
			     <input type="text" name="document_date" value="" class="form-input" placeholder="Date on document  dd/mm/yyyy" data-validation-engine="validate[required,custom[ukDateFormat]]" />

			     <ul class="upload_file2 dragdrop">
			    	<li>
			        	<input type="file" style="height:0px;width:0px;" id="file"/>
						<input type="hidden" style="color:#fff;" id="fileval"/>
						<img src="<?php echo base_url('images').'/drag.png'?>" alt="" onclick="$('#file').click();"/>
			            <div class="clear"></div>
			            <h3>Drag and Drop</h3>
			            <p>or click to upload...</p>
			            <div class="clear"></div>
			            <p>(<?php echo $document_type?>)</p>
			        </li>
			    </ul>
	     	<script type="text/javascript">
			var obj = {url:"<?php echo $this->_base_uri;?>?f=upload_file&type=<?php echo $type?>&details="+ $('[name=details]').val()}
	        	$(function(){
					$('[name=details]').focus();
					var dom = $('#file').closest('ul.dragdrop');
					$('#file,.dragdrop').html5Uploader({
							name: 'foo',
							zoo:'sd',
							postUrl : false,
							validate: function(){
								return $('#file').closest('form').validationEngine('validate');
							},
							onClientLoad: function(){
								dom.overlay(1);
								$('.cp-bar-value').css({width: '0%',background:'#ae0000'}).html(' ');
								$('.cp-bar').append('<div class="cp-percent"/>');
								$('.cp-percent').append('<div class="percent-val"/>');
								$('.cp-percent').css({'color':'#fff' ,'position': 'absolute', 'top':'0px', 'width': '100%', 'z-index':'100'});
								$('.percent-val').css({'line-height': '26px', 'text-align':'center'});
							},
							onClientError: function(){
								dom.overlay("Browser failed to read the file");
								dom.overlay(0,-1);
							},
							onServerError:function(){
								console.log('onServerError', $('.submitprocess'));
								dom.overlay("File upload fails, please try again");
								dom.overlay(0,-1);
							},
							onServerAbort:function(){
								console.log('onServerAbort', $('.submitprocess'));
								dom.overlay("File upload fails, please try again");
								dom.overlay(0,-1);
							},
							onServerProgress :function(e){
								var pp=Math.round(e.loaded/e.total*100);
								$('.cp-bar-value').css('width',pp+'%');
								$('.percent-val').html(pp+'%');
							},
							onSuccess:function(e, file, response){
								dom.overlay("Upload complete");
								var res = JSON.parse(response);
								if(res.error.length == 0){
									dom.overlay(res.data);
								}else{
									dom.overlay(res.error[0]);
									$('#file').val('');
								}
								dom.overlay(0, -1);
								$('.cp-percent').remove();
								$('.percent-val').remove();
							},
							dynamicUrl: function(){
								return "<?php echo $this->_base_uri;?>?f=upload_file&type=<?php echo $type?>&details="+$('[name=details]').val() + "&date="+ $('input[name=document_date]').val();
							}
							
						 })
				});
	        </script>

				</form>	
			</div>
		</li>
	</ul>	
	 <?php
     }
	
	
	 /**
	  * This is simepl ajax function this need to call
	  * $this->outputStart(); to before any code
	  * And should use $this->outputEnd(); as the last executable

	  * Instructaion. No return statement should not avoid the last function calling
	  */
	 
	 public function me(){		 
		$this->outputStart();
	 	echo json_encode(array('em'=>'you'));
		$this->outputEnd();
	 }
	 
	 /**
	  * javascript for fileupload
	  */
	 
	 public function upload_file(){	
		$this->outputStart();
		$type= $this->_me->input->get('type');
		
		$doc_details = $this->_me->Projects->getDoctypeDetails( $type );
		
		$extension = pathinfo( $_FILES['foo']['name'] , PATHINFO_EXTENSION );
		$file_name = str_ireplace(' ','-', pathinfo( $_FILES['foo']['name'] , PATHINFO_FILENAME ) );
		$res = array('data'=>array(), 'error'=>array());
		if( $_FILES['foo']['error'] != 0 ){
			$res['error'][] = 'The file is corrupted';
		}elseif( !$doc_details){
			$res['error'][] = 'The previous step is not followed';
		}else{
			/*if( in_array( $extension, $this->_allowed_extension ) === false){
				$res['error'][] = 'The file type is not supported';
			}else{
*/
				$upload_dir = APPPATH .'upload_doc/';
				if(! is_dir($upload_dir)){// create the pload directory if not exists
					mkdir($upload_dir, 0777, true);
				}
				if( !is_dir( $upload_dir. abs( getActiveProject() ) ) ){// create the projetc id folder if not exists
					mkdir( $upload_dir. abs( getActiveProject() ) , 0777, true);
				}
					
				$dir_path = $upload_dir. abs( getActiveProject() ) .'/';
				if( !is_dir($dir_path .abs( $type ))){
					mkdir( $dir_path .abs( $type ) , 0777, true);
				}
				$dir_path .= abs( $type ).'/';
				$_file_new_name = $dir_path . $file_name.'_'.getCurrentuserId(). '_'.time().'.'.$extension;
				//var_dump(exec('whoami'));
				//var_dump(is_writable(dirname($_file_new_name)));
				if( move_uploaded_file( $_FILES['foo']['tmp_name'], $_file_new_name) ){
					/**
					 * Save file into database
					 */
					 $details  =$this->_me->input->get('details');
					 if(!$details){
					 	$details = '';
					 }
					$doc_date = preg_replace('/[^0-9\-\/\\\\]/i', '', $this->_me->input->get('date'));
					$doc_date = strtotime(preg_replace('@(\d{1,2})(?:/|\-)(\d{1,2})(?:/|\-)(\d{4})@i', '$2/$1/$3', $doc_date));
					if($doc_date === false)
						$doc_date = time();

					$sql = sprintf("INSERT INTO uploaddoc (`name`, `path`, `userid`, `projectid`, `doctypeid`, `status`, `uploadtime`, `details`, `document_date`) VALUES ('%s', '%s', %s, %s, %s, %s, %s, '%s', '%s')" , $_FILES['foo']['name'], $_file_new_name, getCurrentuserId(), getActiveProject(), abs($type), 1,  time(), $details, date('Y-m-d', $doc_date));
					$this->_me->db->query( $sql );
					if(  $file_id= $this->_me->db->insert_id() ){
						$this->sendNotificationToadmin( $file_id, $res );
						$this->uploadFileToFTPServer( $file_id, $res );
						$this->createTicket( $file_id, $res );
						$this->createNotification( $file_id, $res );
						if( count($res['error']) === 0)
							$res['data'] = 'You document has been successfully uploaded and an email has been send to the admin ';
					}
				}else{
					$res['error'][] = 'Your file is not uploaded, please try again';
				}
				
			//}
		}		
		echo json_encode( $res );
		$this->outputEnd();	
	 	
	 }
	 
	 private function sendNotificationToadmin( $file_id = 0, &$res ){
		$file_details = $this->getFileDetails( $file_id );
		if( $file_details ){
			$to  = $this->_me->config->item('admin_email');
			$subject = 'An document is uplaoded to project '.$file_details['pname'];			
			$messae = '<p> Webmaster</p><p>A document named <b>'.$file_details['name'].'</b> has been uploaded to the project <b>'.$file_details['pname'].'</b> by the user at '.date('H:i', $file_details['uploadtime']).' on '.date('d-m-y', $file_details['uploadtime']).'. Please take the require action.</p> ';
			if( !sendMail($to, $subject, $messae)){
				$res['error'][] = 'Could not send email';				
			}
		}
	 }
	 
	 public function uploadFileToFTPServer( $file_id = 0, &$res = array()){
		 /*
		  * For debugging
		 */
		 if( !$file_id){
			$file_id  =1;
			}
		$file_details =  $this->getFileDetails( $file_id );
		$this->_me->db->last_query();
		 $this->getFileDetails( $file_id );
		if( $file_details ){
			$ftp_info = $this->_me->config->item('ftp_server');
			if( $ftp_info ){
				$file = $file_details['path'];
				$remote_path = $ftp_info['base_path'].  $file_details['doctypename'];
				
				// set up basic connection
				if(function_exists('ftp_ssl_connect')){
					$conn_id = ftp_ssl_connect( $ftp_info['server'], $ftp_info['port'] );
				}else{
					$conn_id = ftp_connect( $ftp_info['server'], $ftp_info['port'] );
				}
				
				// login with username and password
				@$login_result = ftp_login($conn_id, $ftp_info['user'], $ftp_info['password'] );
				if($login_result){
					if( ftp_chdir ( $conn_id , $remote_path ) ){
						/**
						 * check the directory exist
						 */
						$folder_exists = true;
						if( !is_dir( 'ftp://'.$ftp_info['user'].':'.$ftp_info['password'].'@'.$ftp_info['server'].$remote_path ) ){
							$folder_exists = ftp_mkdir( $conn_id, $file_details['doctypename']);
						}
						if($folder_exists){
							$fh = fopen ($file_details['path'], "r");
    						$ret = ftp_nb_fput ($conn_id, $file_details['name'], $fh, FTP_BINARY);
							while ($ret == FTP_MOREDATA) {								
								$ret = ftp_nb_continue($conn_id);
							}
							if ($ret != FTP_FINISHED) {
								$res['error'][] = "File does not uploaded into ftp server";
							}
							fclose($fh);
						}else{
							//$res['error'][] = 'Ftp sercer directory  '.$ftp_info['server'].$remote_path.' creation failed';
						}
						
						
					}else{
						//$res['error'][] = 'Ftp sercer directory'.$remote_path.' could not be accessed';
					}
					ftp_close($conn_id);
				}else{
					//$res['error'][] = 'Ftp server login failes';
					
				}
				
			}
		}
	 }
	 
	 public function getFileDetails( $file_id = 0){
		if($file_id && $file_id == $this->_file_id && $this->_file_details ) {
			return $this->_file_details;
		}
		if( $file_id ){
			$sql = sprintf( " SELECT a.*,b.name as uname, c.name as pname, d.name as doctypename  FROM uploaddoc a, users b, projects c, doctype d WHERE a.id = %s AND b.id = a.userid AND a.doctypeid = d.id AND a.projectid = c.id", $file_id);			
			$res =  $this->_me->db->query( $sql );
			if( $res->num_rows() ){
				$this->_file_details = $res->row_array();
				$this->_file_id = $file_id;
				return $this->_file_details;
			}else{
				return false;
			}
		}else{
			return false;
		}
	 }
	 
	 /**
	  * This functiohn will crate a ticket on the file uplaod system
	  */
	 private function createTicket( $file_id , &$res){
		 $file_details = $this->getFileDetails( $file_id ) ;
		 if( $file_details ){
			$sql = sprintf("INSERT INTO ticket ( `itemid`, `time`, `created_by`, `user_type`, `ticket_for`, `project_id`)VALUES (%s,  %s, %s, %s, %s, %s)" , $file_details['id'],  time(), getCurrentuserId(), getCurrentUserRole(), '1', getActiveProject() );
			$this->_me->db->query( $sql );
			if( !$ticket_id = $this->_me->db->insert_id()){
				$res['error'][] = " Ticket creation failed";	
			}else{
				/**
				 * Insert into ticket log
				 */
				$comment = 'File:'. $file_details['name'] .' '. strip_tags($this->_me->input->get('details'));
				$sql = sprintf("INSERT INTO ticket_log (`ticket_id`, `modifier_id`, `ticket_status_id`, `modifier_role`, `modify_time`, `log_status`, `comment`) VALUES (%s, %s, %s, %s, %s, %s, '%s')", $ticket_id, getCurrentuserId(), 2, getCurrentUserRole(), time(), 1, $comment);
				$this->_me->db->query( $sql );
			}
		 }else{
			$res['error'][] = " The file details is not valid";
		 }
	 }
	 
	 private function createNotification( $file_id , &$res){
		 $file_details  =  $this->getFileDetails( $file_id ); 
		 if( $file_details ){
			$arr = array( 'file_id' => $file_details['id'] );
		 	$sql = sprintf( " INSERT INTO notifications (`project_id`, `related_id`, `case_id`, `date_time`) VALUES (%s,'%s', %s, %s)", getActiveProject(), mysql_real_escape_string(json_encode($arr) ), 3, time() ) ;
			$this->_me->db->query($sql);
			if( !$this->_me->db->insert_id()){
				$res['error'][] = "The notification is not created";
			}
		 }
		 
		
	 }
}
?>