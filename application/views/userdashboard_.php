<?php
	ob_start();
	$this->load->view('common/user/header.php');
?>   
<div class="trigger_for_480px_onwards"></div>
	<div class="header_top_bdr"></div>
    <div class="back_container">
    	<div class="main">
        	<div class="left">
	             <div id="content_1" class="content">                 
                 	<div class="portion">
                    	<h2>Projects</h2>
                        <div class="clear"></div>
                        <div class="details">
                        	 <?php 
								foreach($project_details as $project){									
							  ?>
                        	<div class="app_back project_tile" id="project-<?php echo $project['id']?>">
                            	<a href="javascript:void(0);">
                            	<div class="sub">                                	
                                    <div class="clear"></div>
                                    <p><?php echo $project['name']?></p>
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
                        <div class="btm">
                        	<div class="main_back">
                            	<img src="<?php echo base_url('images/apps_logo.jpg')?>" class="logo" alt="" />
                                <h1><?php
									if(isset($project_details[0]))
									 echo $project_details[0]['name'] ? $project_details[0]['name'] : 'Project Title'?></h1>
                                <h2><a href="<?php echo base_url('portal/logout')?>">logout</a></h2>
                            </div>
                        </div>
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
                    	<div class="main_footer">
                    		<h2 style="float:left;"><a href="<?php echo base_url('portal/project/9')?>">need help?</a></h2>
                            <p style="margin:0 0 0 80px; padding:8px 0 0 0">Powered by <a href="">BIMscript Ltd © 2014,</a> all rights reserved</p>
                        </div>
                    </div>
                    
                </div>
        </div>
    </div>
<?php
	$this->load->view("common/user/footer.php");
	ob_flush();
?>