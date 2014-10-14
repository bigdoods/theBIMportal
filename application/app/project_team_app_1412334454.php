<?php
class Project_Team_App extends Bim_Appmodule{
	
	/**
	 * The default constructor
	 * any initialization can be placed here
	 * And at first the parent constructor must be called
	 */
	
	public function __construct(){
		parent::start();

		$this->_me->load->model('Projects');
	}
	
	/**
	 * The mandatory method
	 * The frame work will call this method
	 * This is the entry point of the app
	 * It can produce any browser friendly output
	 */
	 
	public function init(){
		$this->team_list();
	}


	public function adminInit(){
	}

	public function team_list(){
		$project_team_members = $this->_me->Projects->getAssignedUsers(getActiveProject());
		debug($project_team_members);
		?>

		<div class="project_team_back_container">
        	<div class="main_project">
            	<div class="search_back">
                   <input type="text" class="form-input" placeholder="Filter..." />
                </div>
                <div class="clear"></div>
            	<ul class="details_new_project">
            		<?php foreach($project_team_members as $project_team_member){?>
	                	<li>
	                		<div class="left_pro"><img src="<?php echo base_url() ?>/upload/profilepic/<?php echo (!empty($project_team_member['profilepic']) ? $project_team_member['profilepic'] : 'default_profile_pic.png') ?>" class="image" alt="" /></div>
	                        <div class="right_pro">
	                            <p class="member-name"><?php echo ucwords($project_team_member['name']) ?></p>

	                            <h2>Phone</h2>
	                            <p><?php echo $project_team_member['phone'] ?></p>

	                            <h2>Email</h2>
	                            <p><?php echo $project_team_member['email'] ?></p>

	                            <h2>Company</h2>
	                            <p><?php echo ucwords($project_team_member['company']) ?></p>

	                            <h2>Discipline</h2>
	                            <p><?php echo ucwords($project_team_member['discipline']) ?></p>
	                        </div>
	                    </li>
					<?php } ?>
                </ul>
            </div>
        </div>
<?php
	}
}
?>