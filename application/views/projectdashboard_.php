<?php
	ob_start();
	$this->load->view('common/user/header.php');
	$apps_type = getAppType();
?>   
<div class="trigger_for_480px_onwards"></div>
    <div class="header-top">

        <div class="main-logo">
            <img src="<?php echo base_url('images/apps_logo.png')?>" alt="VolkerFitzpatrick - Experience Excellence" />
        </div>
        
        <a class="blue-button action logout" href="<?php echo base_url('portal/logout')?>">Logout</a>

        </div>

    </div>
	<div class="header_top_bdr"></div>
    <div class="back_container">
    	<div class="main">
        	<div class="left">
	             <div id="content_1" class="content">                 
                 	<div class="portion">
                    	<h2><?php echo $apps_type[0]?></h2>
                        <div class="clear"></div>
                        <div class="details">
                        	 <?php 
								foreach($app_details as $id => $app){
									if( $app['type'] !== '0'){
										continue;
									}
									/*if($app['appiconfilepath']){
										$extension = pathinfo($app['appiconfilepath'], PATHINFO_EXTENSION);
										$app['appiconfilepath'] = str_ireplace('.'.$extension, '_thumb.'.$extension, $app['appiconfilepath']);
									}*/
                                    if($id != '3') {
							  ?>
                        	<div class="app-button blue-button <?php echo $id ==  $app_id ? 'active':'';?>">
                            	<a href="<?php echo base_url('portal/project/'.$id);?>">
                            	<div class="sub">
                                	<img src="<?php echo base_url('upload/appicon/'.$app['classname'])?>.png" alt="" />
                                    <div class="clear"></div>
                                    <div class="app-name">
                                        <div><?php echo $app['name']?></div>
                                    </div>
                                </div>
                                </a>
                            </div>
                            <?php 
                                }
                            }
                            ?>
                            
                        </div>
                    </div>
                    <div class="portion">
                    	<h2><?php echo $apps_type[1]?></h2>
                        <div class="clear"></div>
                        <div class="details">
                        	 <?php 
								foreach($app_details as $id => $app){
									if( $app['type'] != 1){
										continue;
									}
									/*if($app['appiconfilepath']){
										$extension = pathinfo($app['appiconfilepath'], PATHINFO_EXTENSION);
										$app['appiconfilepath'] = str_ireplace('.'.$extension, '_thumb.'.$extension, $app['appiconfilepath']);
									}*/
							  ?>
                        	<div class="app-button blue-button <?php echo $id ==  $app_id ? 'active':'';?>">
                            	<a href="<?php echo base_url('portal/project/'.$id);?>">
                            	<div class="sub">
                                	<img src="<?php echo base_url('upload/appicon/'.$app['classname'])?>.png" alt="" />
                                    <div class="clear"></div>
                                    <div class="app-name">
                                        <div><?php echo $app['name']?></div>
                                    </div>
                                </div>
                                </a>
                            </div>
                             <?php }?>
                            
                        </div>
                    </div>
                    <div class="portion">
                    	<h2><?php echo $apps_type[2]?></h2>
                        <div class="clear"></div>
                        <div class="details">
                        	 <?php 
								foreach($app_details as $id => $app){
									if( $app['type'] != 2){
										continue;
									}
									/*if($app['appiconfilepath']){
										$extension = pathinfo($app['appiconfilepath'], PATHINFO_EXTENSION);
										$app['appiconfilepath'] = str_ireplace('.'.$extension, '_thumb.'.$extension, $app['appiconfilepath']);
									}*/
							  ?>
                        	<div class="app-button blue-button <?php echo $id ==  $app_id ? 'active':'';?>">
                            	<a href="<?php echo base_url('portal/project/'.$id);?>">
                            	<div class="sub">
                                	<img src="<?php echo base_url('upload/appicon/'.$app['classname'])?>.png" alt="" />
                                    <div class="clear"></div>
                                    <div class="app-name">
                                        <div><?php echo $app['name']?></div>
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
                        <a class="blue-button back-to-projects" href="<?php echo base_url('portal/dashboard');?>">&lt; Back to Projects</a>
                        <h1 class="project-title"><?php echo $project_details[0]['name'] ? $project_details[0]['name'] : 'Project Title'?></h1>
                    </div>
                    
                    <div class="clear"></div>
                    
                    <div class="apps_content_back">
                    	<div id="content_2" class="content2<?php if($app_id == 1) { echo ' timeline'; }?>">
                        <?php global $app;?>
                        	<div class="<?php echo property_exists ($app , 'class_css') ? $app->class_css: 'content_main' ;?>">
                            	<?php load_app_content();
								?>       
                            </div>
                        </div>
                        <?php if($app_id == 1) { ?>
                            <div id="timeline-sidebar">
                                <div id="weather" class="widget">
                                    <img src="<?php echo base_url('images/weather-widget-placeholder.jpg'); ?>">
                                </div>
                                <div id="map" class="widget">
                                    <img src="<?php echo base_url('images/map-widget-placeholder.jpg'); ?>">
                                </div>
                                <div id="notes" class="widget">
                                    <img src="<?php echo base_url('images/notes-widget-placeholder.jpg'); ?>">
                                </div>
                            </div>
                        <?php } ?>
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