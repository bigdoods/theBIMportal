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
		$this->_me->load->model('Team_members');
	}
	
	/**
	 * The mandatory method
	 * The frame work will call this method
	 * This is the entry point of the app
	 * It can produce any browser friendly output
	 */
	 
	public function init(){
		?>
		<?php
	}


	public function adminInit(){
		$this->team_list();
	}

	public function team_list(){
		$project_id = $this->_me->input->get('project_id');
		$project_team_members = $this->_me->Team_members->getProjectMembers($project_id);
		
		foreach($project_team_members as $project_team_member){
		?>
			<div>
				<?php echo $project_team_member ?>
			</div>
		<?php
		}

		?>

			<div>
				<?php echo $project_team_member ?>
			</div>

		<?php
	}
}
?>