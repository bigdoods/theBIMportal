<div class="dashboard_back_container">
    	<div class="main">
            <div class="top-header">
                <p class="welcome-message"><?php echo "Welcome, &nbsp;". $_SESSION['userdata']['name']?></p>
                <a href="<?php echo base_url('admin/logout')?>"><button class="blue-button action logout">Logout</button></a>
            </div>
        	<div class="details_back">
            	
                <div class="middle_back">
                    <!-- <div class="profile_pic_back"></div> -->
                    
                    <div class="clear"></div>
                    <div class="dash_back_details">
                    	<?php $this->load->view('admin/sidebar');?>
                        <div class="right_details_tab">
                                
                                <!-- Display App here -->
                                 
                            </div>    
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>