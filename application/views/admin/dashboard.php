<div class="dashboard_back_container">
    	<div class="main">
        	<div class="details_back">
            	<a href="<?php echo base_url('admin/logout')?>" style="text-decoration:none;"><button class="log_out_button">log out</button></a>
                <div class="top_back"></div>
                <div class="middle_back">
                	<div class="strip_back"></div>
                    <div class="clear"></div>
                    <div class="profile_pic_back"></div>
                    <div class="clear"></div>
                    <h1><?php echo "Welcome, &nbsp;". $_SESSION['userdata']['name']?></h1>
                    <div class="clear"></div>
                    <div class="logo_back_details">
                    <!--<div class="profile_pic_back"></div>-->
                    <div class="logo"><h2>logo</h2></div>
                    	<div class="right_details"><h2>bim portal</h2></div>
                    </div>
                    <div class="clear"></div>
                    <div class="dash_back_details">
                    	<?php $this->load->view('admin/sidebar');?>
                        <div class="right_details_tab">
                        
                        		<!--<div id="tab1" class="tab_content">
                                     <div class="tab_content1">
                                     <p>Lorem ipsum dolor sit amet, ei amet percipit facilisi vis, ex vel tractatos accommodare. Mel ferri semper eruditi te, nec id mutat augue. Vis id utamur debitis. Aliquip officiis periculis ad sea, nam oratio maluisset eu, vim ei conceptam dissentiet. Vel ad dicunt nonumes interpretaris, eu duo mucius nonumes. Noster putant iudicabit cum ex, nam oportere pericula te. Mei novum eligendi ex, ei sit omnes vidisse denique, minim clita id duo.</p>
                                     </div>
                            	</div>-->     
                                
                                 
                            </div>    
                        </div>
                    </div>
                </div>
                <div class="btm_back"></div>
                <div class="clear"></div>
                <div class="shadow_back"></div>
            </div>
        </div>