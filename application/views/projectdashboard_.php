<?php
	ob_start();
	$this->load->view('common/user/header.php');
    $app_hierarchy = array();
	$apps_type = getAppType();

    foreach($app_details as $app){
        if($app['name'] == 'Back to')
            continue;

        if(! isset($app_hierarchy[$app['type']]))
            $app_hierarchy[$app['type']] = array();

        if(! isset($app_hierarchy[$app['type']]['apps']))
            $app_hierarchy[$app['type']]['apps'] = array();

        $app_hierarchy[$app['type']]['name'] = $apps_type[$app['type']];
        $app_hierarchy[$app['type']]['apps'][] = $app;
    }

?>   
    <div class="header-top">

        <div class="main-logo">
            <img src="<?php echo base_url('images/apps_logo.png')?>" alt="VolkerFitzpatrick - Experience Excellence" />
        </div>
        
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
                    <?php foreach($app_hierarchy as $app_type){
                            if(count($app_type['apps']) >0){ ?>
                             	<div class="portion">
                                	<h2><?php echo $app_type['name'] ?></h2>
                                    <div class="clear"></div>
                                    <div class="details">
                                    	 <?php 
            								foreach($app_type['apps'] as $app){  ?>
                                                	<div class="app-button blue-button <?php echo ($app['id'] ==  $app_id ? 'active':'');?>">
                                                    	<a href="<?php echo base_url('portal/project/'. $app['id']);?>">
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
                                            } ?>
                                    </div>
                                </div>
                    <?php   }
                          } ?>
                 </div>
            </div>
            
            <div class="right">
                	<div class="head">                    	
                        <div class="clear"></div>
                        <a class="blue-button back-to-projects" href="<?php echo base_url('portal/dashboard');?>">&lt; Back to Projects</a>
                        <?php echo displayBreadcrumbs($app_id); ?>
                        <h1 class="project-title"><?php echo $project_details[0]['name'] ? $project_details[0]['name'] : 'Project Title'?></h1>
                    </div>
                    
                    <div class="clear"></div>
                    
                    <div class="apps_content_back">
                    	<div id="<?php echo $app_id == 7 ? 'ticket_app' : 'content_2' ;?>" class="content2<?php if($app_id == 1) { echo ' timeline'; }?>">
                        <?php global $app;?>
                        	<div class="<?php echo property_exists ($app , 'class_css') ? $app->class_css: 'content_main' ;?>">
                            	<?php load_app_content();
								?>
                            </div>
                        </div>
                        <?php if($app_id == 1) { ?>
                            <div id="timeline-sidebar">
                                <div id="weather" class="widget">
                                    <!-- <div id="cont_Mjk5MXwxfDN8NHwzfEZGRkZGRnwxfDY2NjY2NnxDfDF8bXBo"><div class="weather-link" id="spa_Mjk5MXwxfDN8NHwzfEZGRkZGRnwxfDY2NjY2NnxDfDF8bXBo"><a id="a_Mjk5MXwxfDN8NHwzfEZGRkZGRnwxfDY2NjY2NnxDfDF8bXBo" rel="nofollow" href="http://www.weather-wherever.co.uk/united-kingdom/bristol_v2991/" target="_blank" style="color:#333;text-decoration:none;">Bristol Weather forecast</a> © weather-wherever.co.uk</div><script type="text/javascript" src="http://widget.weather-wherever.co.uk/js/Mjk5MXwxfDN8NHwzfEZGRkZGRnwxfDY2NjY2NnxDfDF8bXBo"></script></div> -->

                                    <iframe id="NjY1NnwxfDN8MXwzfEZGRkZGRnwxfEZGRkZGRnxDfDF8bXBo" src="http://widget.weather-wherever.co.uk/NjY1NnwxfDN8MXwzfEZGRkZGRnwxfEZGRkZGRnxDfDF8bXBo/" frameborder="0" scrolling="no" name="frame" height="194" width="241" style="-moz-border-radius: 7px;border-radius: 7px;"></iframe>
                                </div>
                                <div id="map" class="widget">
                                    <img src="https://maps.googleapis.com/maps/api/staticmap?center=<?php echo rawurlencode($project_details[0]['embedcode']) ?>&zoom=14&size=241x194">

                                </div>
                                <div id="notes" class="widget">
                                    <textarea id="note"></textarea>
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