<div class="register_back_container">
    	<div class="main">
        	<div class="details_back registration" style="display:none;">
            	<form action="#" method="post" validate="validate" class="regform" >
                <div class="top_back"></div>
                <div class="middle_back">
                	<div class="strip_back"></div>
                    <div class="clear"></div>
                    <div class="profile_pic_back"></div>
                    <div class="clear"></div>
                    <h1>Member REGISTER</h1>
                    <div class="clear"></div>
                    <h1 style="visibility:hidden;" class="reg_success">Registration successfull.Please check your eamil</h1>
                    <div class="form_back">
                    	<div class="details">
                            	<input type="text" class="text_box1" placeholder="USER NAME" data-validation-engine="validate[required,ajax[checkemail]]" name="uname" id="uname"/>
                                <input type="password" class="text_box2" placeholder="PASSWORD" name="password" id="password" data-validation-engine="validate[required]"/>           
                                <input type="password" class="text_box2" placeholder="CONFIRM PASSWORD" id="cpassword" data-validation-engine="validate[required,equals[password]]"/>           
                                <input type="text" class="text_box2" placeholder="NAME" data-validation-engine="validate[required]" name="name" id="name" />
                                <input type="text" class="text_box3" placeholder="COMPANY" data-validation-engine="validate[required]" name="company" id="company" />
                                <select name="discipline" class="selectyze2" data-validation-engine="validate[required]" id="disicipline">
                                   <?php
                                   echo getDicisiplineOption('html', '');
								   ?>
                                   <!--<option>DISCIPLINE</option>
                                   <option>DISCIPLINE 1</option>
                                   <option>DISCIPLINE 2</option>
                                   <option>DISCIPLINE 3</option>
                                   <option>DISCIPLINE 4</option>
                                   <option>DISCIPLINE 5</option>
                                   <option>DISCIPLINE 6</option>
                                   <option>DISCIPLINE 7</option>
                                   <option>DISCIPLINE 8</option>-->
                              	</select>
                                <input type="text" class="text_box4" placeholder="EMAIL ADDRESS" data-validation-engine="validate[required,custom[email]]" name="email" id="email"/>
                                <input type="text" class="text_box5" placeholder="MOBILE NO" data-validation-engine="validate[required,custom[phone]]" name="phone" id="phone"/>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class=" clear"></div>
                    <div class="sub_details">
                    <input type="submit" class="submit" value="Register" />
                    <p><a href="javascript:void(0)" class="login toggle">Login</a></p>
                    </div>
                </div>
                <div class="btm_back"></div>
                <div class="clear"></div>
                <div class="shadow_back"></div>
                </form>
            </div>
            <div class="details_back login">
            	<form action="#" method="post" validate="validate" class="login_form">
                <div class="top_back"></div>
                <div class="middle_back">
                	<div class="strip_back"></div>
                    <div class="clear"></div>
                    <div class="profile_pic_back"></div>
                    <div class="clear"></div>
                    <h1>Member LOGIN</h1>
                    <div class="clear"></div>
                    <h1 style="visibility:hidden;" class="log_error">Registration successfull.Please check your eamil</h1>
                    <div class="form_back">
                    	<div class="details">
                            	<input type="text" class="text_box1" placeholder="USER NAME" name="uname" id="uname" data-validation-engine="validate[required]"/>
                                <input type="password" class="text_box2" placeholder="PASSWORD" name="password" id="password" data-validation-engine="validate[required]"/>                                
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class=" clear"></div>
                    <div class="sub_details">
                    <input type="submit" class="submit" value="login" />
                    <p><a href="javascript:void(0);" class="registration toggle">Register</a></p>
                    </div>
                </div>
                <div class="btm_back"></div>
                <div class="clear"></div>
                <div class="shadow_back"></div>
                </form>
            </div>
        </div>
    </div>