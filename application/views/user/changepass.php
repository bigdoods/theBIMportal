<div class="register_back_container">
    	<div class="main">
            <div class="logo">
                <img src="<?php echo base_url('images/BIMportal1.png')?>" alt="VolkerFitzpatrick - Experience Excellence" />
            </div>
            <?php
				if(!empty($userdetails)){
				?>
				<div class="details_back login">
                <h1>Change Password</h1>
            	<form action="#" method="post" validate="validate" class="changepass_form">
                <div class="middle_back">
                    <div class="clear"></div>
                    <h1 style="display: none;" class="log_error">Password Updated</h1>
                    <div class="form_back">
                    	<div class="details">
                            	<input type="password" placeholder="New Password" name="password" id="password" data-validation-engine="validate[required,equals[cpassword]]"/>
                                <input type="password" placeholder="Confirm Password" name="cpassword" id="cpassword" data-validation-engine="validate[required,equals[password]]"/>
                                <input type="hidden" name="requestid" id="requestid" value="<?php echo $userdetails['id']?>"/>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class=" clear"></div>
                    <div class="sub_details">
                        <input type="submit" class="submit blue-button action" value="Update" />
                    </div>
                </div>
                <div class="clear"></div>
                </form>
            </div>
				<?php
				}else{
				?><div class="details_back login">
                <h1>Change Password</h1>
                <div class="middle_back">
                    <div class="clear"></div>
                    <h1 class="log_error">This is not a valid request</h1>

                    <div class=" clear"></div>
                    <div class="sub_details">
                    <a href="<?php echo base_url();?>"><input type="button" class="submit" value="Return" /></a>
                    </div>
                </div>
                <div class="clear"></div>

            </div>
				<?php
                }
			?>

        </div>
    </div>
