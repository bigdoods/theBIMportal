<?php
class Account_app extends Bim_Appmodule{
	
	private $_db = NULL;
	
	function __construct(){
		parent::start();
		$this->_db = $this->_me->db;// this is codeigniter db
	}
	
	/**
	 * Public fucntion printScript
	 */
	function printScript(){
		?>
		<script>
		try{
		
			$(function(){
				
				$(document).on('submit', '#account_update', function(){
					var t =$(this);
					var dom = t;
					$.ajax({
						url : base_path+'portal/updateProfile',
						data: t.serialize(),
						type: 'post',
						beforeSend: function(){
							dom.overlay(1);
							dom.overlay("Please wait");
						},
						success: function(r){
							if(r==1){
								dom.overlay("Your profile has been updated");
								window.location.reload();
							}else{
								dom.overlay("Your profile has no new value");
							}
							
						},
						error: function(){
							dom.overlay("Internal server error, please try again later");
						},
						complete: function(){
							dom.overlay(0,-1);
						}
					});
					return false;
				})
			})
		}catch(e){
		
		}
        	
        </script>
		<?php
	}
	
	/**
	 * Public function 
	 */	 
	 function init(){
		 $this->printScript();
		$this->_me->load->model('Users');		
		$user_details = $this->_me->Users->getNewUsers($this->_userid);
		$u_detail = $user_details[0];
		if($u_detail['profilepic'] == '')	{
			$u_detail['profilepic'] = 'default_profile_pic.png';
		}
		//v_dump($u_detail);
		?>
		<div class="portion">
                                            
                                    <div class="form_reupdate_back">
                                    <form name="account_update" validate="validate" id="account_update">
                                    	<div class="reupdate_left profile_back">
                                        	<img src="<?php echo base_url('upload/profilepic/'.$u_detail['profilepic'])?>" onclick="$('#prof_pic_upload').click()" title="Drag & drop" style="cursor:pointer;" id="prof_pic_img">
                                            <input type="file" name="" id="prof_pic_upload" style="height:0px;width:0px;">
                                        </div>
                                        <div class="reupdate_right">
                                        	<p>Name :</p>
                                            <div class="clear"></div>
                                        	<input type="text" class="text_box" name="name" value="<?php echo $u_detail['name']?>" data-validation-engine="validate[required]"/>
                                            <div class="clear"></div>
                                            
                                            <p>Phone :</p>
                                            <div class="clear"></div>
                                        	<input type="text" class="text_box" name="phone" data-validation-engine="validate[required,custom[phone]]" value="<?php echo $u_detail['phone']?>"/>
                                            <div class="clear"></div>
                                            
                                            <p>New password :</p>
                                            <div class="clear"></div>
                                        	<input type="password" class="text_box" name="password" value="" id="password" data-validation-engine="validate[equals[cpass],minSize[6],maxSize[20]]"/>
                                            <div class="clear"></div>
                                            
                                            <p>Confirm password :</p>
                                            <div class="clear"></div>
                                        	<input type="password" class="text_box" data-validation-engine="validate[equals[password]]" id="cpass" name="cpass"/>
                                            <div class="clear"></div>
                                            
                                            
                                            <p>Email :</p>
                                            <div class="clear"></div>
                                        	<input type="text" class="text_box" readonly="readonly" disabled="disabled" value="<?php echo $u_detail['email']?>"/>
                                            <div class="clear"></div>
                                            
                                            <p>Joining date :</p>
                                            <div class="clear"></div>
                                        	<input type="text" class="text_box" readonly="readonly" disabled="disabled" value="<?php echo date('d-m-Y', $u_detail['joiningdate']);?>"/>
                                            <div class="clear"></div>
                                            
                                             <p>Activation date :</p>
                                            <div class="clear"></div>
                                        	<input type="text" class="text_box" readonly="readonly" disabled="disabled" value="<?php echo date('d-m-Y', $u_detail['activationdate']);?>"/>
                                            <div class="clear"></div>
                                            
                                            <p>Company :</p>
                                            <div class="clear"></div>
                                        	<input type="text" class="text_box" readonly="readonly" disabled="disabled"  value="<?php echo $u_detail['company']?>"/>
                                            <div class="clear"></div>
                                            
                                           
                                            <p>Discipline :</p>
                                            <div class="clear"></div>
                                        	<select class="text_box2" name="discipline" disabled="disabled">
                                            	<?php
												   echo getDicisiplineOption('html', '');
												?>
                                            </select>
                                            <div class="clear"></div>
                                            <input type="submit" class="sub_rep" value="submit" />
                                        </div>
                                    </form>
                                    </div>        
         </div>
		<?php
	 }

}
?>