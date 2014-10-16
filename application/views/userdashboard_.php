<?php
	ob_start();
	$this->load->view('common/user/header.php');
?>   
	<div class="header-top">

        <div class="main-logo"></div>

        <a class="blue-button action logout" href="<?php echo base_url('portal/logout')?>">Logout</a>
        <?php if(isCurrentUserAdmin()){ ?>
            <a class="blue-button action admin" href="<?php echo base_url('admin/dashboard')?>">Admin</a>
        <?php } ?>

        </div>

    </div>
    <div class="header_top_bdr"></div>
    <div class="back_container">
    	<div class="main">
            <div class="menu-trigger"></div>
        	<div class="left">
	             <div id="content_1" class="content">                 
                 	<div class="portion">
                    	<h2>Projects</h2>
                        <div class="clear"></div>
                        <div class="details">
                        	 <?php 
								foreach($project_details as $project){									
							  ?>
                        	<div class="project_tile project-button blue-button" id="project-<?php echo $project['id']?>">
                            	<a href="javascript:void(0);">
                            	<div class="sub">
                                    <img src="<?php echo base_url('/images/project_tile_icon')?>.png" alt="" />                                	
                                    <div class="project-name">
                                        <div><?php echo $project['name']?></div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <?php }?>
                            
                        </div>
                    </div>
                
                 </div>
            </div>
            
            <div class="right">
                	<div class="head">                     
                        <div class="clear"></div>
                        <h1 class="project-title">Overview</h1>
                    </div>
                    
                    <div class="clear"></div>
                    
                    <div class="apps_content_back">
                    	<div id="content_2" class="content2">
                        	<div class="content_main">
                            	<?php load_app_content();
								?>       
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    <div class="footer_back">
                        <a class="need-help-link" href="<?php echo base_url('portal/project/9')?>">Need Help?</a>                          
                    </div>
                    
                </div>
        </div>
    </div>
<?php
	$this->load->view("common/user/footer.php");
	ob_flush();
?>