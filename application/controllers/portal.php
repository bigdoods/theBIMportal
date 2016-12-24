<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Portal extends Bim_Controller { //Our Portal class extends the Controller class
	public function __construct(){
		parent::__construct(); // We define the __Construct class as the parent.
		$this->load->model('Users'); // Load our Users model for our entire class
		$this->load->model('Apps'); // Load our Apps model for our entire class
	}

	private $_page_title = '';

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */

	public function index()
	{
		if( isset($_SESSION['userdata']) ){
			redirect($_SESSION['userdata']['dsahboard']);
			exit;
		}
		$data['main'] = 'login'; //Select our view file that will display our login
		$this->load->vars($data); // Make data array variables available in any view file
		$this->load->view('user'); //Display the page with user defined content
	}

	/**
	 * controller function for forgotpassword
	 */

	 public function forgotpass(){
	 	$p = $this->input->post();
		$where = "email = '".$p['uname']."'";
		$this->db->where($where);
		$user = $this->db->get('users');
		$response = array('data'=>array(),'error'=>array());
		if($user->num_rows()){
			$user_details = $user->row_array();

			$random_numer = md5(uniqid(mt_rand().time().$user_details['id'], true));
			$link = base_url().'portal/changepass/'.$random_numer;
			// + send email
			$body  = '<p>Hi! '.$user_details['name'].'<br/> Someone has requested to chage the password of your <strong>BIMSCRIPT</strong> account. If it was you then <a href="'.$link.'">click here</a> to reset your password. If it was not you, then please ignore this email.</p>
			<p>
			If you can\'t view the link you can directly copy and paste it in the browser. Here is your link:
			'.$link.';
			</p>
			';
			$subject = 'BIMSCRIPT: password change request';
			$mailSend = sendMail($user_details['email'],$subject, $body);
			if($mailSend){
				$this->db->where(array('id' => $user_details['id']))->update('users', array(
					'password_reset' => $random_numer
				));
			}else{
				error_log('Error sending password reset email.');
			}
		}

		$response['data'] = "Your password request has been sent. Please check your email";
		echo json_encode($response);
	 }

	/**
	 * function to display the form of change passweord
	 */

	function changepass($random_number){
		// + check the id and random match with any request
		$data = array();
		$request_details = $this->db
			->where('password_reset', $random_number)
			->get('users');
		if($request_details->num_rows()){
			$data['userdetails'] = $request_details->row_array();
		}else{
			redirect('/portal/login');
		}
		$data['main'] = 'changepass';
		$this->load->vars($data);
		$this->load->view('user');
	}

	/**
	 * save the new password
	 */

	function dochange(){
		$response = array('data'=>array(),'error'=>array());
		$p = $this->input->post();
		$request_id = intval($p['requestid']);
		$new_pass = @$p['password'];
		// + get the request details
		$q = $this->db
			->where('id', $request_id)
			->get('users');
		if($q->num_rows()){
			$user_details  = $q->row_array();
			$old_pass = $user_details['password'];
			$this->db
				->where('id', $user_details['id'])
				->update('users', array('password_reset' => ''));
			// +change the user table
			$data = array(
				'password' => md5($new_pass)
			);
			$this->db
				->where('id', $user_details['id'])
				->update('users', $data);
			$response['data'] = base_url();
		}else{
			$response['error'][] = 'Not a valid request';
		}
		echo json_encode($response);
	}

	/**
	 * Make the registration
	 */

	public function do_register(){
		$p = $this->input->post(); //Catch values form URL

		/**
		 * Set extra data
		 */

		 $p['status'] = 1; // just registered
		 $p['role'] = 2; // user
		 $p['password'] = md5($p['password']);
		 $p['joiningdate'] = time();
		 $p['uname'] = '';
		 $this->db->insert('users', $p);
		 $user_id = $this->db->insert_id();

		 /**
		  * Emailpart start
		  */

		 $body  = 'Hi! '.$p['name'].'<br/> Thank you for registering with the BIMscript Portal. An administrator has been notified and will verify your registration before you can log in. Please await a confirmation email with your login details';
		 $subject = 'Thank you registering with BIMScript ';
		 $mailSend = sendMail( $p['email'],$subject, $body);

		 /**
		  * Send email to webmaster
		  */

		  $body  = 'Hello !<br />
		  	a new user has been registered for the portal.Please login into portal webmaster account and verify the user.
			<div>
				Name : '.$p['name'].'<br/>
				Email : '.$p['email'].'<br/>
				Date : '.date('H:i', time()).' on '.date('d-m-Y', time()).'
			</div>
			';
		  $to = $this->config->item('admin_email');
		 $mailSend = sendMail( $to,$subject, $body);


		 if($user_id){
			echo 'Succesfully registered. Please check your email.'.'~!~1';
		 }else{
		 	echo 'Internal server error. Try again afer some time.'.'~!~0';
		 }
	}

	/**
	 * Make the login
	 */

	 // When user submits data on view page. This function store data in array
	 public function do_login(){
	 	$p = $this->input->post();
		$this->db->where(array(
			'binary(email)' => $p['email'],
			'binary(password)' => md5($p['password'])
		));

		$this->db->select('id,status,role,name,email,phone,profilepic');
		$q = $this->db->get('users');
		$data = array();
		$data['response'] = array();
		$data['error'] = array();
		if($q->num_rows()){
			$row = $q->row_array();
			if($row['status'] < 3){
				$data['error'][] = 'The account is still not activated';
			}elseif($row['role'] == 1){
					$row['dsahboard'] = $data['response'] = base_url('admin/dashboard'); // redirect url
			}else{
					$row['dsahboard'] = $data['response'] = base_url('portal/dashboard'); // redirect url
			}
				$_SESSION['userdata'] = $row;
			if( !$this->Users->getAssignedProjects( $row['id'] ) && $row['role'] == 2){
				unset($_SESSION);
				session_destroy();
				$data['error'][] = 'There is no project';
			}
			if( count( $data['error'] ) == 0){
				$this->db->where('id', $row['id']);
				$data_db = array(
					'last_login_time' => time(),
					'activity_status' => 1
				);
				$this->db->update('users', $data_db);

				/* if the user id normal user and have assigned projects
				 * Then pick the last one save that
				 */

			if( $row['role'] == 2){
				$projects = $this->Users->getAssignedProjects( $row['id'] );
				$pid = key($projects);
				//setcookie('actp' , $pid, time()+2592000, '/');
			}
		}

		}else{
			$data['error'][] = 'Username or password does not match';
		}
		echo json_encode($data);
	  }

	/**
	 * Check the email is already in use or not
	 */

	public function checkUnameEmailExists(){
		$arr[] = $this->input->get('fieldId');
		$this->db->where($arr[0], $this->input->get('fieldValue'));
		$this->db->select('id');
		$q = $this->db->get('users');
		if($q->num_rows() > 0){
			$arr[] = false;
		}else{
			$arr[] = true;
		}
		echo json_encode($arr);
	}

	/**
	 * Portal dashboard
	 */

	public function dashboard(){
		$all_assigned_projects  =$this->Users->getAssignedProjects(getCurrentuserId());
		load_app();
		$data['project_details'] = $all_assigned_projects;

		$this->load->vars( $data );
		$this->load->view('userdashboard_');
	}

	/**
	 *uploadprofilepic
	 */

	public function uploadprofilepic(){
		$res = array('data'=>array(), 'error' => array());
		if( !$_FILES['foo']['error']){
			$supported_extension = array('jpeg','png','jpg','giff' );
			$extension = pathinfo($_FILES['foo']['name'], PATHINFO_EXTENSION);
			$name = pathinfo($_FILES['foo']['name'], PATHINFO_FILENAME);
			if(in_array( $extension, $supported_extension) !== false){
				$new_name = getCurrentuserId().'_'.time().'~!~'.$name.'~!~.'.$extension;
				$upload_dir = APPPATH .'../upload/profilepic/';
				if( move_uploaded_file( $_FILES['foo']['tmp_name'], $upload_dir. $new_name) ){
					// + image manipulation
					$config['image_library'] = 'gd2';
					$config['source_image'] = $upload_dir. $new_name;
					$config['create_thumb'] = TRUE;
					$config['maintain_ratio'] = TRUE;
					$config['width'] = 175;
					$config['height'] = 131;
					$this->Users->updateProfilePic(getCurrentuserId().'_'.time().'~!~'.$name.'~!~_thumb.'.$extension);
					$this->load->library('image_lib', $config);

					$this->image_lib->resize();
					$_SESSION['userdata']['profilepic']  = getCurrentuserId().'_'.time().'~!~'.$name.'~!~_thumb.'.$extension;
					$res['data'] = base_url('upload/profilepic/' . getCurrentuserId().'_'.time().'~!~'.$name.'~!~_thumb.'.$extension);
				}else{
					$res['error'][] = 'Profile picture not saved, try again';
				}
			}else{
				$res['error'][] = 'File type not supported';
			}

		}else{
			$res['error'][] = 'The file is corrupted';
		}
		echo json_encode($res);
	}

	/**
	 * Check the project can be assigned to the user
	 * if yes then assign and save into cookie
	 */

	 public function selectProject($pid){
			if(getCurrentUserRole() !=1 && ! $this->Users->checkProjectAccess( $pid, getCurrentuserId() )){
				echo -1;
			}else{
				setcookie('actp' , $pid, time()+2592000, '/');
				$app_id = $this->config->item('default_app_id');
				echo base_url('portal/project/'.$app_id);

			}
	 }

	 /**
	  * Project dashboard
	  */

	  public function project($appid = 0){
		global $app_id;
		$app_id = $appid;
		if(  !$app_id  ){
			$app_id = $this->config->item('default_app_id');
		}
		load_app();

		/**
		 * Get project title
		 */

		$this->load->model('Projects');
		$data['project_details'] = $this->Projects->getAllProject( getActiveProject() );
		if(getActiveProject() == -1){
			redirect($_SESSION['userdata']['dsahboard']);
			exit;
		}
		$data['app_id'] = $app_id;
		$all_apps = $this->Apps->getAllApps(1);
		$data['app_details'] = $all_apps;
		$this->load->vars( $data );
		$this->load->view('projectdashboard_');
	}

	/**
	 * The function will update the database for the
	 * about the user update of their account
	 */

	public function updateProfile(){
		echo $this->Users->updateProfile($this->input->post());
	}

	public function fetch_note(){
		$this->load->model('Note');

		$query = $this->db
			->where(array('user_id' => getCurrentuserId()))
			->limit(1)
			->get('notes');

		$note = array_first($query->result('Note'));
		debug($note);

		ob_clean();
		echo json_encode($note);
	}
	public function save_note(){
		$this->load->model('Note');
		$user_id = getCurrentuserId();
		$note_body = htmlentities($this->input->post('note_body'));

		if($this->db->from('notes')->limit(1)->where(array('user_id' => $user_id))->count_all_results() >0){
			$query = $this->db
				->limit(1)
				->where(array('user_id' => $user_id))
				->update('notes', array('body' => $note_body));
		}else{
			$query = $this->db
				->insert('notes', array('user_id' => $user_id, 'body' => $note_body));
			}
		ob_clean();
		exit;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
