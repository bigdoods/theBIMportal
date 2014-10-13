<div class="register_back_container">
    	<div class="main">        	
            <?php 
				if(!empty($userdetails)){
				?>
					<div class="details_back login">
            	<form action="#" method="post" validate="validate" class="changepass_form">
                <div class="top_back"></div>
                <div class="middle_back">
                	<div class="strip_back"></div>
                    <div class="clear"></div>
                    <div class="profile_pic_back"></div>
                    <div class="clear"></div>
                    <h1>Change password</h1>
                    <div class="clear"></div>
                    <h1 style="visibility:hidden;" class="log_error">Registration successfull.Please check your eamil</h1>
                    <div class="form_back">
                    	<div class="details">
                            	<input type="password" class="text_box1" placeholder="New password" name="password" id="password" data-validation-engine="validate[required,equals[cpassword]]"/>
                                <input type="password" class="text_box2" placeholder="Confirm password" name="cpassword" id="cpassword" data-validation-engine="validate[required,equals[password]]"/>
                                <input type="hidden" name="requestid" id="requestid" value="<?php echo $userdetails['id']?>"/>                                
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class=" clear"></div>
                    <div class="sub_details">
                    <input type="submit" class="submit" value="Proceed" />                    
                    </div>
                </div>
                <div class="btm_back"></div>
                <div class="clear"></div>
                <div class="shadow_back"></div>
                </form>
            </div>                 	
				<?php
				}else{
				?><div class="details_back login">            	
                <div class="top_back"></div>
                <div class="middle_back">
                	<div class="strip_back"></div>
                    <div class="clear"></div>
                    <div class="profile_pic_back"></div>
                    <div class="clear"></div>
                    <h1>Change password</h1>
                    <div class="clear"></div>
                    <h1 class="log_error">This is not a valid request</h1>
                    
                    <div class=" clear"></div>
                    <div class="sub_details">
                    <a href="<?php echo base_url();?>"><input type="button" class="submit" value="Take me out" /></a>
                    </div>
                </div>
                <div class="btm_back"></div>
                <div class="clear"></div>
                <div class="shadow_back"></div>
                
            </div>
				<?php
                }
			?>
            
        </div>
    </div>