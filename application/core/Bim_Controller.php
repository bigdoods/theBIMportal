<?php
/**
 * This is the class which will load throughout
 * the application when ever a controller will load
 * the BIM controller will be loaded by default
 */
class	Bim_Controller extends CI_Controller{
	/**
	 *Place any code which is needed to run on every page load
	 */
	private $admin_private_pages = array('dashboard');
	private $user_private_pages = array('dashboard', 'project');
	public function __construct(){
		parent::__construct();

		if(!session_id()){
			session_start();
			/**
		 * If isset session and check he is active
		 */
		 if(isset($_SESSION['userdata'])){
		 	$app = $this->input->get('a');

			if($app && strtolower($app) === 'messaging_app'){
				// user is active
				$last_active = $this->session->userdata('last_active_without_chat', time());

				if($last_active < (time() - 60*10) ){
					// set the user status inactive
					$data  = array('activity_status' => 2);
					$this->db->where('id', getCurrentUserId());
					$this->db->update('users', $data);
				}
			}else{
				$data  = array('activity_status' => 1);
				$this->db->where('id', getCurrentUserId());
				$this->db->update('users', $data);
				$this->session->set_userdata('last_active_without_chat', time());
			}
		 }
			$this->checkAuth();
		}
	}

	private function checkAuth(){
		$page = $this->uri->segment(2);
		$controller = $this->uri->segment(1);
		if((in_array($page,$this->admin_private_pages ) && @getCurrentUserRole() != 1 && $controller == 'admin')
			|| (in_array($page,$this->user_private_pages ) && !in_array(@getCurrentUserRole(), array(1,2)) && $controller == 'portal')){
			//$this->load->view('adminUnauthorized');
			redirect(base_url());
		}
	}

	/**
	 * This method will be used
	 * to invoke any app from ajax or direct
	 * From front end
	 */

	  public function invoke(){
	  	$this->load->model('Apps');
	 	$app = $this->input->get('a');

	 	global $app_id;
	 	$app_data = $this->Apps->getAppByClassname($app);
	 	$app_id = @$app_data['id'];

		$app_method = $this->input->get('f');
		$tab_id = $this->input->get('t');
		if(class_exists($app)){
			$app_obj = new $app();
			if( is_callable( array( $app_obj, $app_method)) ){
				$app_obj->$app_method();
			}else{
				echo '<p class="error"> Some thing is not going well.</p>';
			}
		}else{
			echo '<p class="error"> Some thing is not going well really.</p>';
		}

	 }

	 /**
	 * Logout for user
	 */

	public function logout(){

		if(session_id()){
			/** Set the activity_status logged out
			 */
			$this->db->where('id', $_SESSION['userdata']['id']);
			$data = array(
				'last_logout_time'=> time(),
				'activity_status' => 0
			);
			$this->db->update('users', $data);
			unset($_SESSION);

			session_destroy();
		}
		//setcookie('act_p', 0, time() - 100, '/');
		redirect(base_url());
	}

}
?>
