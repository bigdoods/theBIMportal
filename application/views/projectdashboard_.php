<?php
	ob_start();
	$this->load->view('common/user/header.php');
	$apps_type = getAppType();
?>   
<div class="trigger_for_480px_onwards"></div>
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
							  ?>
                        	<div class="app_back <?php echo $id ==  $app_id ? 'active':'';?>">
                            	<a href="<?php echo base_url('portal/project/'.$id);?>">
                            	<div class="sub">
                                	<img src="<?php echo base_url('upload/appicon/'.$app['appiconfilepath'])?>" alt="" />
                                    <div class="clear"></div>
                                    <p><?php echo $app['name']?></p>
                                </div>
                                </a>
                            </div>
                            <?php }?>
                            
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
                        	<div class="app_back <?php echo $id ==  $app_id ? 'active':'';?>">
                            	<a href="<?php echo base_url('portal/project/'.$id);?>">
                            	<div class="sub">
                                	<img src="<?php echo base_url('upload/appicon/'.$app['appiconfilepath'])?>" alt="" />
                                    <div class="clear"></div>
                                    <p><?php echo $app['name']?></p>
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
                        	<div class="app_back <?php echo $id ==  $app_id ? 'active':'';?>">
                            	<a href="<?php echo base_url('portal/project/'.$id);?>">
                            	<div class="sub">
                                	<img src="<?php echo base_url('upload/appicon/'.$app['appiconfilepath'])?>" alt="" />
                                    <div class="clear"></div>
                                    <p><?php echo $app['name']?></p>
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
                                <h1><?php echo $project_details[0]['name'] ? $project_details[0]['name'] : 'Project Title'?></h1>
                                <h2><a href="<?php echo base_url('portal/logout')?>">logout</a></h2>
                            </div>
                        </div>
                    </div>
                    
                    <div class="clear"></div>
                    
                    <div class="apps_content_back">
                    	<div id="content_2" class="content2">
                        <?php global $app;?>
                        	<div class="<?php echo property_exists ($app , 'class_css') ? $app->class_css: 'content_main' ;?>">
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