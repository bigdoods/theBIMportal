<?php
class Messaging_app extends Bim_Appmodule{
	
	private $_db;
	
	public $class_css = 'facebook_messaing_2';
	public function __construct(){
		parent::start();
		$this->_me->load->model('Projects');
		$this->_db = $this->_me->db;
		
	}
	/**
	 * The new messages 
	 */
	 
	public function newMessage(){
		$p = $this->_me->input->post();
		if( !$p)
			$p =   $_GET;
		/**
		 * Fetch only tose messages which should receive by me
		 */
		$query = 'select a.content,a.time,a.sender_id, a.receiver_id,a.id, b.profilepic from chat_history a , users b where  a.receiver_id ='.$p['receiver'] .' AND a.sender_id = '.$p['sender'].' AND  a.status IN (1) and a.sender_id = b.id order by a.id desc';
		$r = $this->_db->query($query);
		$count = $r->num_rows();
		$html = '';
		$view_arr = array('-1');
		if($count){
			$result = $r->result_array();
			$result = array_reverse($result) ;
			foreach($result  as $id=>$row){
				if($id == 0)
				$last_id = $row['id'];
				if($row['receiver_id'] == getCurrentUserId()){
					$view_arr[] = $row['id'];
				}
				$html .='<div class="portion_details ch_new_message" id="message_'.$row['id'].'">';
				if($row['sender_id'] == getCurrentUserId()){
					
                 $html .='<div class="own">
                                <h2><span></span>'.html_entity_decode( $row['content'], ENT_NOQUOTES, "UTF-8" ).'</h2>
                            </div>
                      	   </div>';
				}else{
					$profile_pic = $row['profilepic'] ? str_ireplace('~.', '~_thumb.', $row['profilepic']) : 'smile_pic.jpg';
					$profile_pic = base_url('upload/profilepic/'.$profile_pic);
					$html .='<div class="others">
                                <img src="'.$profile_pic.'" alt="" class="image" height="34" width="34"/>
                                <div class="chat_right2">
                                    <p><span></span>'.html_entity_decode( $row['content'], ENT_NOQUOTES, "UTF-8" ).'</p>
                                </div>
                     </div>';
				}
				
				$html .= '</div>';
			}			
		}
		// + change the message status
			if($p['cb_status'] == 0 || $p['cb_status'] == 2 || $p['cb_status'] == 3){// the chat box is invisible or mininmized
				$modified_stat = 2;
			}else if($p['cb_status'] == 1){
				$modified_stat = 3;
			}
			$data = array('status' => $modified_stat);
			$this->_db->where('id IN ('. implode(',' , $view_arr).')');
			$this->_db->update( 'chat_history', $data);
			echo $html .'~!^!~'. $count;
	}
	public function load(){
		$p = $this->_me->input->post();
		
		$query = 'select a.content,a.time,a.sender_id, a.receiver_id,a.id, b.profilepic, if(a.sender_id = '.getCurrentUserId().', a.receiver_id, a.sender_id) as fid from chat_history a , users b where if(a.sender_id ='.getCurrentUserId().',a.receiver_id = '.$p['receiver'].',a.sender_id = '.$p['receiver'].') AND  if(a.sender_id ='.getCurrentUserId().',a.receiver_id = b.id,a.sender_id = b.id)  AND (a.sender_id ='.$p['sender'].' OR a.receiver_id ='.$p['sender'].')';
		if($p['last_id']){
			$query .= ' AND a.id <'.$p['last_id'];
		}
		$query .= ' order by a.id DESC limit 0,7';
		$q = $this->_db->query($query);		
		$html  = '';
		$last_id = $p['last_id'];
		$more_message = 0;
		//echo $this->_db->last_query();
		
		/**
		 * set messages as view which are requested by user
		 */
		 $view_arr = array('-1');
		if($q->num_rows() > 0){
			$result = $q->result_array();
			$result = array_reverse($result) ;
			foreach( $result as $id=>$row){
				if($id == 0)
				$last_id = $row['id'];
				if($row['receiver_id'] == getCurrentUserId()){
					$view_arr[] = $row['id'];
				}
				$html .='<div class="portion_details ch_old_message" id="message_'.$row['id'].'" title="'.date('jS M Y H:i', $row['time']).'">';
				if($row['sender_id'] == getCurrentUserId()){
					
                 $html .='<div class="own">
                                <h2><span></span>'.html_entity_decode( $row['content'], ENT_NOQUOTES, "UTF-8" ).'</h2>
                            </div>';
                      	   
				}else{
					$profile_pic = $row['profilepic'] ? str_ireplace('~.', '~_thumb.', $row['profilepic']) : 'smile_pic.jpg';
					$profile_pic = base_url('upload/profilepic/'.$profile_pic);
					$html .='<div class="others">
                                <img src="'.$profile_pic.'" alt="" class="image" height="34" width="34"/>
                                <div class="chat_right2">
                                    <p><span></span>'.html_entity_decode( $row['content'], ENT_NOQUOTES, "UTF-8" ).'</p>
                                </div>
                     </div>';
				}
				$html .='</div>';
			}
		}else{
			$more_message = 1;
		}
		/**
		 * set the message status as delivered
		 */
		$this->_db->where('id IN (' .implode(',' , $view_arr). ')');
		$data = array(
			'status' => 3
		);
		$this->_db->update('chat_history', $data);
		echo $html.'~!^!~'.$last_id.'~!^!~'.$more_message;
	}
	
	public function createContainer(){
		$this->_db->where('id !=',getCurrentUserId());
		$this->_db->where('status',3, '>=');		
		$q = $this->_db->get('users');	
		$data = array();
		
		// + get the toal count of unread message
		$this->_db->where('receiver_id', getCurrentUserId());
		$this->_db->where('status = 1');
		$res_c = $this->_db->get('chat_history');
		$total_unread_count = $res_c->num_rows();
	?>  
    	<link href="<?php echo base_url('css/chat.css')?>" rel="stylesheet">
        <div class="chat_wrapper" id="ch_main_wrapper">
			<div class="chat_back_container">
        	<div class="trigger">chat <span class="ch_total_inrd_cnt"><?php echo $total_unread_count ? '('.$total_unread_count.')' : ''?></span></div>
            <div class="clear"></div>
            <div class="chat_details_back">
            	<input type="text" class="bottom_details ch_search" placeholder="Search" style="z-index:100"/>
                <div class="chat_details_inner ch_main_c" data-curent_user="<?php echo getCurrentUserId();?>" data-project_context = "<?php echo implode(",", $this->_project_id_arr)?>">
                        <div class="chat_back">
                            <div class="content_chat ch_user_list">
                                                           
                                <?php
                                	if($q->num_rows()){
										foreach( $q->result_array() as $row ){
											if($row['role'] != 1){
												$this->_db->where('a.role != 1');
												$this->_db->where('a.id', $row['id']);
												$this->_db->select('b.*');
												$this->_db->from('users a');
												$this->_db->join('user_assigned_projects b' , 'a.id = b.userid AND b.projectid IN ('.implode(',' , $this->_project_id_arr).')');
												$q2  =$this->_db->get();												
												if($q2->num_rows() === 0)
													continue;
												
											}
											$data[$row['id']] = $row;
											
										$profile_pic = $row['profilepic'] ? str_ireplace('~.', '~_thumb.', $row['profilepic']) : 'smile_pic.jpg';
										//v_dump($row['activity_status'])	
										if( $row['activity_status'] == 1){
											$status_class = 'green';
										}else if ( $row['activity_status'] == 2){
											$status_class = 'yellow';
										}else{
											$status_class = 'red';
										}
										$data[$row['id']]['status'] = $status_class;
										$data[$row['id']]['profilepic'] = $profile_pic;
										/**
										 * Get the unread message and
										 */
										 $this->_db->where('receiver_id', getCurrentUserId());
										 $this->_db->where('sender_id', $row['id']);
										 $this->_db->where('status IN (1,2)');
										 $this->_db->select('id');
										 $n_r = $this->_db->get('chat_history');
										 $unrd_cnt = 0;
										 if($n_r->num_rows()){
											$unrd_cnt = $n_r->num_rows();	
										 }
								?>
										<div class="online ch_user ch_user_id-<?php echo $row['id']?>" data-user_id = "<?php echo $row['id']?>">
                                            <img class="ch_user_profile_pic image"src="<?php echo base_url('upload/profilepic').'/'.$profile_pic ?>" alt="" height="34" width="34"/>
                                            <div class="online_back">
                                                <p class="ch_user_name"><?php echo $row['name'];?><span class="ch_inread_cnt"><?php echo $unrd_cnt ? '('.$unrd_cnt.')': ''?></span></p>
                                                <div class="<?php echo $status_class?> ch_user_status"></div>
                                            </div>
                                        </div>
								<?php			
										}
									}
								?>
                                
                                
                                
                            </div>
                        </div>
                </div>
                
                <div class="clear"></div>
                
                <!--<div class="group_chat_back">
                	<h2>group chat</h2>
                    <div class="clear"></div>
                	<h3>rahul yadav</h3>
                    <h3>abishek malakar</h3>
                    <h3>pritam biswas</h3>
                    <h3>saptarsha saha</h3>
                    <h3>sajib saha</h3>
                </div>-->
                
            </div>
        </div>
        
        
			       
        	<div class="chat_back_container2 ch_chat_box_container">
            <!-- Loop through chat box container-->
            
            <?php 
				$i = 0;				
				foreach($data as $id=>$user){
					$i++;
			?>
				<div class="portion_chat ch_box" data-receiver="<?php echo $user['id']?>" id="ch_box-<?php echo $user['id']?>" style="position:fixed; bottom:0; right:<?php echo $i*309 .'px';?>" data-online_status = <?php echo $user['activity_status'] == 1 ? 1 : 0?> data-username="<?php echo $user['name']?>">
        		<div class="chat_header ch_header"><?php echo $user['name']?><span class="<?php echo $user['status']?> ch_box_stat"></span><span class="cross_fb"></span></div>
                <span class="ch_new_msg_count"></span>
                <div class="clear"></div>
                <div class="chat_details_back2 ch_details">
                	<textarea class="bottom_details2 ch_send_content" placeholder="Type Here"  multiline="true" style="resize:none;z-index:100;"></textarea>
                	<!--<input type="textarea" class="bottom_details2 ch_send_content" placeholder="Type Here"  multiline="true"/>-->
                    <div class="chat_details_inner2 ch_details_inner">
                    <div class="chat_back_input ch_content_whole">                    	
                    </div>
                    </div>
                </div>
            </div>
			<?php	
			}?> 
            <!-- End Loop through chat box container--> 
           
        </div>
        <div  class="add_back ch_add_back">
        <div class="up_panel ch_up_panel" style="z-index:10000">        	
        </div>
        <div class="add_icon"><span class="ch_hidden_count"></span></div>
    </div>       
        </div>
         
        
        
	<?php
	}
	
	public function ajaxInit(){
		$this->outputStart();
		/*echo '<script type="text/javascript" src="'.base_url('js/chat_app.js').'"></script>';*/
        echo base_url('js/chat_app.js');
		$this->outputEnd();
	}		
	public function send($redirect = 0){
		$p = $this->_me->input->post();
		$data = array(
			'sender_id' => $p['sender'],
			'receiver_id' => $p['receiver'],
			'time' => time(),
			'content' => htmlentities( $p['message'], ENT_NOQUOTES, "UTF-8" ),
			'status' => 1
		);
		if($data['content'])
			$this->_db->insert( 'chat_history', $data);
		$this->_me->session->set_userdata('last_active_without_chat', time());
		if( isset($p['redirect_to'])) {

			header('location: '.$p['redirect_to']);
			exit;
		}
		echo date('jS M Y H:i', time());
	}
	
	public function updateUserStatus(){
		$p = $this->_me->input->post();
		//$u_id = $p['me'];
		$data = array();
		$this->_db->where('id !=',getCurrentUserId());
		$this->_db->where('status',3, '>=');		
		$q = $this->_db->select('id, activity_status,last_login_time,role');
		$q = $this->_db->get('users');
		if($q->num_rows()){
			foreach($q->result_array() as $row){
				if($row['role'] != 1){
					$this->_db->where('a.role != 1');
					$this->_db->where('a.id', $row['id']);
					$this->_db->select('b.*');
					$this->_db->from('users a');
					$this->_db->join('user_assigned_projects b' , 'a.id = b.userid AND b.projectid IN ('.implode(',' , $this->_project_id_arr).')');
					$q2  =$this->_db->get();												
					if($q2->num_rows() === 0)
						continue;
					
				}
				$data[$row['id']] =array(
					'activity_status' =>$row['activity_status'],
					'last_login_time' => $row['last_login_time']
				) ;
			}
		}
		
		echo json_encode($data);
	}
	
	
	/**
	 * Get the chat history between tw user
	 */
	public function history(){
		$id = $this->_me->input->get('id');
	 
	 ?>
     <ul class="face">
                                	<li>
                            			<div class="form_back_container">
                                                <div class="facebook_form">
                                                        <div class="facebook_left">
                                                            <div class="search_box">
                                                                <input type="text" class="form-input ch_user_search" />
                                                                <input type="submit" class="search" value="" />
                                                            </div>
                                                            <div class="clear"></div>
                                                            <div id="content_3" class="content3">
                                                            <ul class="left_face">
                                                            	<?php 
																	$my_colleague = $this->getColleague();
																	foreach($my_colleague as $user_id=>$user):
																	?>
                                                                    <a href="?f=history&id=<?php echo $user['id']?>">
                                                                    	<li class="<?php echo $id==$user['id'] ? 'active' : '' ?>">
                                                                    <div class="image_back"><img src="<?php echo base_url('upload/profilepic').'/'.$user['profilepic']?>" alt="" /></div>
                                                                    <div class="details_face_left">
                                                                        <h2><?php echo $user['name']?></h2>
                                                                        <h3><?php echo date( 'H:i A', $user['last_login_time'])?></h3>
                                                                    </div>
                                                                </li>
                                                                </a>
																	<?php
																	endforeach;
																?>

                                                            </ul>
                                                            </div>
                                                        </div>
                                                        <div class="facebook_right">
                                                       	 <div id="content_4" class="content4">
                                                            <ul class="right_face">                                                           
                                                            <?php $last_messages =  $this->getMessages($id);															
																if($last_messages):
																
																	foreach($last_messages as $message):
																		$file = $message['profilepic'];
																		$file = $file  ? str_replace('~.','~_thumb', $file ) : 'smile_pic.jpg';
																		$file = base_url('upload/profilepic/'.$file);
																?>
																<li data-from="<?php echo $message['sender_id']== getCurrentUserId() ? 'me' : 'you'?>"data-messageid="<?php echo $message['id']?>">
                                                                    <div class="image_back"><img src="<?php echo $file?>" alt="" /></div>
                                                                    <div class="details_face_right">
                                                                        <h3><?php echo date('jS M Y H:i', $message['time'])?></h3>
                                                                        <h2><?php echo $message['name']?></h2>
                                                                        <div class="clear"></div>
                                                                        <p><?php echo $message['content'];?></p>
                                                                    </div>
                                                                </li>
																<?php
																	endforeach;
																endif;
															?>  
                                                            </ul>
                                                            </div>
                                                            <div class="clear"></div>
                                                            <div class="text_box_back">
                                                            	<form name="instant_message" action="?f=send" method="post">
                                                                 <?php
                                                            			$file = $_SESSION['userdata']['profilepic']  ? str_replace('~.','~_thumb', $_SESSION['userdata']['profilepic']) : 'smile_pic.jpg';
																		$file = base_url('upload/profilepic/'.$file);
															?>
                                                            <input type="hidden" name="my_prof_pic" value="<?php echo $file?>">
                                                            <input type="hidden" name="my_name" value="<?php echo $_SESSION['userdata']['name']?>">
                                                            	<input type="hidden" name="sender" value="<?php echo getCurrentUserid();?>">
                                                                <input type="hidden" name="receiver" value="<?php echo $id?>">
                                                                <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']?>">				
                                                                <textarea class="text_area_box" name="message" placeholder="Type your message here..."></textarea>
                                                                <input type="submit" class="submit blue-button action" value="post" />
                                                                </form>
                                                                <div class="clear"></div>
                                                            </div>
                                                        </div>
                                                    <div class="clear"></div>
                                                </div>
                                            </div>
                                    </li>
                                    
                                    
                                    
                                </ul>
                                <script>
     $(window).load(function(){
				$("#content_4").mCustomScrollbar({
					autoDraggerLength : true,
					scrollButtons:{
						enable:true
					},
					callbacks:{
							onTotalScroll : loadConetnDynamically
						},
				});
			})
			 $(window).load(function(){
				$("#content_3").mCustomScrollbar({
					autoDraggerLength : true,
					scrollButtons:{
						enable:true
					}
				});
			});	
			
					loadConetnDynamically = function(){
							$.ajax({
									url : "<?php echo base_url('portal/invoke?a=messaging_app&f=getoldHistory')?>",
									data:{last_id:$('ul li[data-messageid]:last').data('messageid'), uid:$('[name=receiver]').val()},
									type : 'post',
									beforeSend: function(){},
									success: function(r){
										if(r){
											$("#content_4 ul.right_face").append(r);
											$("#content_4").mCustomScrollbar('update');
										}
									}
								})
					}
									
	</script>        
		<?php	
	 
	 }
	/**
	 * The main funciton
	 */
	 public function init(){
	 
	 ?>
     <ul class="face">
                                	<li>
                            			<div class="form_back_container">
                                                <div class="facebook_form">
                                                        <div class="facebook_left">
                                                            <div class="search_box">
                                                                <input type="text" class="text_box_face ch_user_search" />
                                                                <input type="submit" class="search" value="" />
                                                            </div>
                                                            <div class="clear"></div>
                                                            <div id="content_3" class="content3">
                                                            <ul class="left_face">
                                                            	<?php 
																	$my_colleague = $this->getColleague();
																	//v_dump($my_colleague);
																	foreach($my_colleague as $user_id=>$user):
																	?>
                                                                    	<a href="?f=history&id=<?php echo $user['id']?>">
                                                                        <li class="">
                                                                    <div class="image_back"><img src="<?php echo base_url('upload/profilepic').'/'.$user['profilepic']?>" alt="" /></div>
                                                                    <div class="details_face_left">
                                                                        <h2><?php echo $user['name']?></h2>
                                                                        <h3><?php echo date( 'H:i', $user['last_login_time'])?></h3>
                                                                    </div>
                                                                </li>
                                                                </a>
																	<?php
																	endforeach;
																?>
                                                            </ul>
                                                            </div>
                                                        </div>
                                                        <div class="facebook_right">
                                                       	 <div id="content_4" class="content4">
                                                            <ul class="right_face">
                                                            <?php $last_messages =  $this->getLastmessages();
																if($last_messages):
																	foreach($last_messages as $message):
																	$file = $message['profilepic'];
																	$file = $file  ? str_replace('~.','~_thumb', $file ) : 'smile_pic.jpg';
																	$file = base_url('upload/profilepic/'.$file);
																?>
																<li>
                                                                    <div class="image_back"><img src="<?php echo $file?>" alt="" /></div>
                                                                    <div class="details_face_right">
                                                                        <h3><?php echo date('jS M Y H:i', $message['time'])?></h3>
                                                                        <h2><?php echo $message['name']?></h2>
                                                                        <div class="clear"></div>
                                                                        <p><?php echo $message['content'];?></p>
                                                                    </div>
                                                                </li>
																<?php
																	endforeach;
																endif;
															?>  
                                                            </ul>
                                                            </div>
                                                            <div class="clear"></div>                                                         
                                                        </div>
                                                    <div class="clear"></div>
                                                </div>
                                            </div>
                                    </li>
                                    
                                    
                                    
                                </ul>
                                <script>
     $(window).load(function(){
				$("#content_4").mCustomScrollbar({
					scrollButtons:{
						enable:true
					},
					callBacks:{
						onTotalScroll: function(){
						
						}
					}
				});
			});
			
			 $(window).load(function(){
				$("#content_3").mCustomScrollbar({
					scrollButtons:{
						enable:true
					}
				});
			});
	</script>        
		<?php	
	 
	 }
	 
	 public function getColleague(){
	 	$this->_db->where('id !=',getCurrentUserId());
		$this->_db->where('status',3, '>=');		
		$q = $this->_db->get('users');	
		$data = array();
		foreach( $q->result_array() as $row ){
			if($row['role'] != 1){
				$this->_db->where('a.role != 1');
				$this->_db->where('a.id', $row['id']);
				$this->_db->select('b.*');
				$this->_db->from('users a');
				$this->_db->join('user_assigned_projects b' , 'a.id = b.userid AND b.projectid IN ('.implode(',' , $this->_project_id_arr).')');
				$q2  =$this->_db->get();												
				if($q2->num_rows() === 0)
					continue;
				
			}
		$data[$row['id']] = $row;
											
		$profile_pic = $row['profilepic'] ? str_ireplace('~.', '~_thumb.', $row['profilepic']) : 'smile_pic.jpg';
		//v_dump($row['activity_status'])	
		if( $row['activity_status'] == 1){
			$status_class = 'green';
		}else if ( $row['activity_status'] == 2){
			$status_class = 'yellow';
		}else{
			$status_class = 'red';
		}
		$data[$row['id']]['status'] = $status_class;
		$data[$row['id']]['profilepic'] = $profile_pic;
		}
	 	return $data;
	 }
	 
	 /**
	  * get last messages from all users
	  */
	  
	  public function getLastmessages(){
	  	$data = array();
		$sql = "SELECT a.*, c.name, c.profilepic from chat_history a JOIN users c ON a.sender_id = c.id where receiver_id = ".getCurrentUserId()." AND a.id = (SELECT MAX(b.id) FROM chat_history b WHERE b.receiver_id = ". getCurrentUserId() ." AND b.sender_id = a.sender_id) order by a.id DESC";
		$res=  $this->_db->query($sql);
		if($res->num_rows()){
			foreach($res->result_array() as $row ){
				$data[] =$row;
			}
		}
		return $data;
	  }
	  
	  public function getMessages($id, $last_message_id = 0, $other_user_id = 0){
		$data = array();
		if( !$other_user_id){
			$query = 'select a.content,a.time,a.sender_id, a.receiver_id,a.id, b.profilepic,b.name, if(a.sender_id = '.getCurrentUserId().', a.receiver_id, a.sender_id) as fid from chat_history a JOIN  users b on a.sender_id = b.id where if(a.sender_id ='.getCurrentUserId().',a.receiver_id = '.$id.',a.sender_id = '.$id.') AND (a.sender_id ='.getCurrentUserId().' OR a.receiver_id ='.getCurrentUserId().')';
		}else{
			$query = 'select a.content,a.time,a.sender_id, a.receiver_id,a.id, b.profilepic,b.name, if(a.sender_id = '.$other_user_id.', a.receiver_id, a.sender_id) as fid from chat_history a JOIN  users b on a.sender_id = b.id where if(a.sender_id ='.$other_user_id.',a.receiver_id = '.$id.',a.sender_id = '.$id.') AND (a.sender_id ='.$other_user_id.' OR a.receiver_id ='.$other_user_id.')';
		}
		
		
		if($last_message_id){
			$query .=  ' AND a.id < '.$last_message_id;
		}
		if(isset($p) && $p['last_id']){
			$query .= ' AND a.id <'.$p['last_id'];
		}
		$query .= ' order by a.id DESC limit 0,10';
		$q = $this->_db->query($query);	
		if($q->num_rows()){
			foreach($q->result_array() as $row){
				$data[]  = $row;
			}
		}
		//$data = array_reverse($data);
		return $data;
	}
	
	/**
	 * Public function eventsource
	 * 
	 */
	 public function eventsource(){
		$this->outputStart(); 
		header('Content-type: text/event-stream');
		
		/**
		 * Fetch the messages		 
		 */
		$query = 'select a.content,a.time,a.sender_id, a.receiver_id,a.id, b.profilepic from chat_history a , users b where  a.receiver_id ='.getCurrentUserId() .' AND  a.status IN (1) and a.sender_id = b.id order by a.id desc';
		$output_arr = array();
		$r = $this->_db->query($query);
		if($r->num_rows()>0){
			$count = $r->num_rows();
		$html = '';		
		$view_arr = array('-1');		
		if($count){			
			$result = $r->result_array();
			$result = array_reverse($result) ;
			$output_arr = array(); 
			
			foreach($result  as $id=>$row){				
							
				$html = '';
				if($id == 0)
				$last_id = $row['id'];
				if($row['receiver_id'] == getCurrentUserId()){
					$view_arr[] = $row['id'];
				}
				$html .='<div class="portion_details ch_new_message" id="message_'.$row['id'].'" title="'.date('jS M Y H:i', $row['time']).'">';
				if($row['sender_id'] == getCurrentUserId()){
					
                 $html .='<div class="own">
                                <h2><span></span>'.html_entity_decode( $row['content'], ENT_NOQUOTES, "UTF-8" ).'</h2>
                            </div>
                      	   </div>';
				}else{
					$profile_pic = $row['profilepic'] ? str_ireplace('~.', '~_thumb.', $row['profilepic']) : 'smile_pic.jpg';
					$profile_pic = base_url('upload/profilepic/'.$profile_pic);
					$html .='<div class="others">
                                <img src="'.$profile_pic.'" alt="" class="image" height="34" width="34"/>
                                <div class="chat_right2">
                                    <p><span></span>'.html_entity_decode( $row['content'], ENT_NOQUOTES, "UTF-8" ).'</p>
                                </div>
                     </div>';
				}
				
				$html .= '</div>';
				$output_arr[$row['sender_id']][] = $html;
			}			
		}
		// + change the message status
			
			$data = array('status' => 3);
			$this->_db->where('id IN ('. implode(',' , $view_arr).')');
			$this->_db->update( 'chat_history', $data);
			if(!empty($output_arr)){
				echo 'data: '.json_encode(array('response'=>array($output_arr)))."\n\n";
			}else{
				echo "data: 12\n\n";
			}
		}else{
			echo 'data: '. json_encode(array()) ."\n\n";
		}
	 	/*$arr = array('response'=>array(1,2,3));
		echo 'data: {msg:as}'."\n\n";*/
		$this->outputEnd();
	 }
	 
	 /**
	  * Get old messages for history
	  */
	  public function getoldHistory(){
	  	$uid = $this->_me->input->post('uid');
		$other_user_id = $this->_me->input->post('ouid');
		/* + If we want to fetch the chat histry of
		 * any two arbitrary user then that user must be admin
		 * check if the user is admin
		 */
		if($other_user_id && getCurrentUserrole() != 1){
			echo '<p>Sorry you are not authorized to view this</p>';exit;
		}
		$last_message_id = $this->_me->input->post('last_id');
		$message_loop = $this->getMessages($uid, $last_message_id, $other_user_id);
		if($message_loop){
			$li_html = $this->messageListHtml($message_loop);
			echo $li_html;
		}
	  }
	  
	  /**
	   * generate html from message array
	   */
	  public function messageListHtml($message_loop = array()){
		$html = '';
	  	if($message_loop):
			foreach($message_loop as $message):
				$file = $message['profilepic'];
				$file = $file  ? str_replace('~.','~_thumb', $file ) : 'smile_pic.jpg';
				$file = base_url('upload/profilepic/'.$file);
		$html .='<li data-from="'.($message['sender_id']== getCurrentUserId() ? 'me' : 'you') .'"data-messageid="'.$message['id'].'">
			<div class="image_back"><img src="'.$file.'" alt="" /></div>
			<div class="details_face_right">
				<h3>'.date('jS M Y H:i', $message['time']).'</h3>
				<h2>'.$message['name'].'</h2>
				<p>'.$message['content'].'</p>
			</div>
		</li>';
			endforeach;
		endif;
		return $html;													  
	  }
}
?>