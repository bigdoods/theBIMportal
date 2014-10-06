<?php

class test_app extends Bim_Appmodule{
	
	/**
	 * The default constructor
	 * any initialization can be placed here
	 * And at first the parent constructor must be called
	 */
	private $_db = NULL;
	public $class_css = 'content_main_tickets';
	public function __construct(){
		parent::start();
		$this->_db = $this->_me->db;// this is codeigniter db
		$this->_me->load->model('Users');
		$this->_me->load->model('Projects');
		$this->_me->load->model('Docs');
		
	}
	
	/**
	 * This function will be called when ever a new app is 
	 * created, the table creation and folder creation, etc
	 * works can be done within this funciton
	 */
	public function install(){
		$error = false;
		$error_details =array();
		$sql = "CREATE TABLE IF NOT EXISTS `issues` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `name` varchar(255) NOT NULL,
	  `description` text NOT NULL,
	  `time` int(11) NOT NULL,
	  `userid` int(11) NOT NULL,
	  `project` int(11) NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

		if( !$this->_db->_error_number()){
			$error = true;
			$error_details[] =  $this->_db->_error_message();
		}
		// + create the folder
		if( !is_dir('upload/site_issues')){
			if(mkdir('upload/site_issues', 0777, true) && mkdir('upload/site_issues/original', 0777, true) && mkdir('upload/site_issues/thumb', 0777, true) && mkdir('upload/site_issues/medium', 0777, true)){
				
			}else{
				$error_details[] = "Folder creation stopped";
			}
		}
		return $error_details;
	}
	
	/**
	 * Admin init
	 */
	public function adminInit(){
		$issue_details = $this->getAllIssueDetails();
		$this->displayIsuuesAdmin($issue_details);
	}
	
	public function getAllIssueDetails(){
		return array();
	}
	
	/**
	 * Dislay the issues at admin panel
	 */
	public function displayIsuuedAdmin($issie_details = array()){
		global $tabid;
		?>
		<div id="tab<?php echo $tabid?>">
                                     <div class="tab_content<?php echo $tabid?>">
                                    <div class="tab_content_detila_back list">
                                            	<ul class="head">
                                                	<li><p>Project</p></li>
                                                    <li class="small"><p>Date</p></li>
                                                    <li class="big"><p>Image</p></li>
                                                    <li class="small"><p>Discussion</p></li>
                                                    <li class="small"><p>Details</p></li>
                                                </ul>
                                                <?php if($issie_details):
													foreach($issie_details as $issues):
												?>
													<ul class="details" rel="user-<?php echo $user['id']?>">
                                                	<li><p><?php echo ucfirst($user['name'])?></p></li>
                                                    <li class="big"><p><?php echo $user['email']?></p></li>
                                                    <li><p><?php echo $user['company']?></p></li>
                                                    <li><p><?php echo $user['discipline']?></p></li>
                                                    <li><p><?php echo $user['phone']?></p></li>
                                                    <li><p><?php echo date('H:i', $user['joiningdate']).' on ';echo date('m-d-Y', $user['joiningdate']);?></p></li>
                                                    <li><p><?php echo $user['activationdate'] ? date('H:i', $user['activationdate']).' on '.date('m-d-Y', $user['activationdate']) : '-' ?></p></li>
                                                    <li class="small"><input type="checkbox" class="check_box" <?php echo ($user['status'] >= 3) ? 'checked="checked"' : '';?> disabled="disabled"/></li>
                                                    <li class="small userdetails"><p><a href="javascript:void(0);">Details</a></p></li>
                                                </ul>
													
												
                                                
												<?php 
													endforeach;
													else:
												?>
													<h2 class="data-blank"> No issues exists in the system</h2>
												<?php
													endif;
												?>                                                
                                                <input type="button" class="sub_it_back create_project" value="Create"/>
                                            </div>
                                        <div class="tab_content_detila_back edit_form" style="display:none"></div>
                                         <div class="tab_content_detila_back create_form" style="display:none">
                                         	
                                         </div>
                                         </div>
                                         
                                         
                                         
</div>
		<?php
	}
	
	public function init_issue($issie_details = array()){
		?>
		<div class="new_apps_back_container">
        	<ul class="new_details_apps">
            	<li>
                	<div class="portion">
                    	<div class="left_image"><img src="../../upload/site_photograph/original/home-post-3-330x248_2_1_1411992323.jpg" class="image" alt="" /></div>
                        <div class="right_image">
                        	<h4>12.35 on 24/09/2014 <mark><a href="">view tickets</a></mark></h4>
                        	<h2>clash detected!</h2>
                            <div class="clear"></div>
                            <h3>the details enter by webmaster apper here.</h3>
                            <div class="clear"></div>
                            <h3>the details enter by webmaster apper here.</h3>
                            <div class="clear"></div>
                            <h3>the details enter by webmaster apper here.</h3>
                            <div class="clear"></div>
                            <h3>the details enter by webmaster apper here.</h3>
                            <div class="clear"></div>
                        </div>
                    </div>
                </li>
                <li>
                	<div class="portion">
                    	<div class="left_image"><img src="../../upload/site_photograph/original/home-post-3-330x248_2_1_1411992323.jpg" class="image" alt="" /></div>
                        <div class="right_image">
                        	<h4>12.35 on 24/09/2014 <mark><a href="">view tickets</a></mark></h4>
                        	<h2>clash detected!</h2>
                            <div class="clear"></div>
                            <h3>the details enter by webmaster apper here.</h3>
                            <div class="clear"></div>
                            <h3>the details enter by webmaster apper here.</h3>
                            <div class="clear"></div>
                            <h3>the details enter by webmaster apper here.</h3>
                            <div class="clear"></div>
                            <h3>the details enter by webmaster apper here.</h3>
                            <div class="clear"></div>
                        </div>
                    </div>
                </li>
                <li>
                	<div class="portion">
                    	<div class="left_image"><img src="../../upload/site_photograph/original/home-post-3-330x248_2_1_1411992323.jpg" class="image" alt="" /></div>
                        <div class="right_image">
                        	<h4>12.35 on 24/09/2014 <mark><a href="">view tickets</a></mark></h4>
                        	<h2>clash detected!</h2>
                            <div class="clear"></div>
                            <h3>the details enter by webmaster apper here.</h3>
                            <div class="clear"></div>
                            <h3>the details enter by webmaster apper here.</h3>
                            <div class="clear"></div>
                            <h3>the details enter by webmaster apper here.</h3>
                            <div class="clear"></div>
                            <h3>the details enter by webmaster apper here.</h3>
                            <div class="clear"></div>
                        </div>
                    </div>
                </li>
                <li>
                	<div class="portion">
                    	<div class="left_image"><img src="../../upload/site_photograph/original/home-post-3-330x248_2_1_1411992323.jpg" class="image" alt="" /></div>
                        <div class="right_image">
                        	<h4>12.35 on 24/09/2014 <mark><a href="">view tickets</a></mark></h4>
                        	<h2>clash detected!</h2>
                            <div class="clear"></div>
                            <h3>the details enter by webmaster apper here.</h3>
                            <div class="clear"></div>
                            <h3>the details enter by webmaster apper here.</h3>
                            <div class="clear"></div>
                            <h3>the details enter by webmaster apper here.</h3>
                            <div class="clear"></div>
                            <h3>the details enter by webmaster apper here.</h3>
                            <div class="clear"></div>
                        </div>
                    </div>
                </li>
                <li>
                	<div class="portion">
                    	<div class="left_image"><img src="../../upload/site_photograph/original/home-post-3-330x248_2_1_1411992323.jpg" class="image" alt="" /></div>
                        <div class="right_image">
                        	<h4>12.35 on 24/09/2014 <mark><a href="">view tickets</a></mark></h4>
                        	<h2>clash detected!</h2>
                            <div class="clear"></div>
                            <h3>the details enter by webmaster apper here.</h3>
                            <div class="clear"></div>
                            <h3>the details enter by webmaster apper here.</h3>
                            <div class="clear"></div>
                            <h3>the details enter by webmaster apper here.</h3>
                            <div class="clear"></div>
                            <h3>the details enter by webmaster apper here.</h3>
                            <div class="clear"></div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
		<?php
	}
	
	public function init($issie_details = array()){
	?>
		<div class="project_team_back_container">
        	<h5>project team</h5>
        	<div class="clear"></div>
        	<div class="main_project">
            	<div class="search_back">
                	<h6>search :</h6>
                   <input type="text" class="text_box5" placeholder="Type name" />
                </div>
                <div class="clear"></div>
            	<ul class="details_new_project">
                	<li>
                		<div class="left_pro"><img src="../../upload/site_photograph/original/home-post-3-330x248_2_1_1411992323.jpg" class="image" alt="" /></div>
                        <div class="right_pro">
                        	<h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <select class="text2">
                            	<option>10</option>
                                <option>10</option>
                                <option>10</option>
                            </select>
                        </div>
                    </li>
                    <li>
                		<div class="left_pro"><img src="../../upload/site_photograph/original/home-post-3-330x248_2_1_1411992323.jpg" class="image" alt="" /></div>
                        <div class="right_pro">
                        	<h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <select class="text2">
                            	<option>10</option>
                                <option>10</option>
                                <option>10</option>
                            </select>
                        </div>
                    </li>
                    <li>
                		<div class="left_pro"><img src="../../upload/site_photograph/original/home-post-3-330x248_2_1_1411992323.jpg" class="image" alt="" /></div>
                        <div class="right_pro">
                        	<h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <select class="text2">
                            	<option>10</option>
                                <option>10</option>
                                <option>10</option>
                            </select>
                        </div>
                    </li>
                    <li>
                		<div class="left_pro"><img src="../../upload/site_photograph/original/home-post-3-330x248_2_1_1411992323.jpg" class="image" alt="" /></div>
                        <div class="right_pro">
                        	<h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <select class="text2">
                            	<option>10</option>
                                <option>10</option>
                                <option>10</option>
                            </select>
                        </div>
                    </li>
                    <li>
                		<div class="left_pro"><img src="../../upload/site_photograph/original/home-post-3-330x248_2_1_1411992323.jpg" class="image" alt="" /></div>
                        <div class="right_pro">
                        	<h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <select class="text2">
                            	<option>10</option>
                                <option>10</option>
                                <option>10</option>
                            </select>
                        </div>
                    </li>
                    <li>
                		<div class="left_pro"><img src="../../upload/site_photograph/original/home-post-3-330x248_2_1_1411992323.jpg" class="image" alt="" /></div>
                        <div class="right_pro">
                        	<h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <input type="text" class="text" placeholder="Type name" />
                            <div class="clear"></div>
                            <h2>name :</h2>
                            <div class="clear"></div>
                            <select class="text2">
                            	<option>10</option>
                                <option>10</option>
                                <option>10</option>
                            </select>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
	<?php
	}
}
?>