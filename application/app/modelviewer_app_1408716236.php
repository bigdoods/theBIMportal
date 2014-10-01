<?php

class Modelviewer_App extends Bim_Appmodule{
	
	/**
	 * The default constructor
	 * any initialization can be placed here
	 * And at first the parent constructor must be called
	 */
	private $_db = NULL;

	public function __construct(){
		parent::start();
		$this->_db = $this->_me->db;// this is codeigniter db
		$this->_me->load->model('Users');
		$this->_me->load->model('Projects');
		$this->_me->load->model('Docs');

		$this->_me->load->config('bimsync');
	}

	/* net functions */
	function post_to($url, $options=array(), &$ch=null){
		if(is_null($ch))
			$ch = curl_init();
		$defaults = array(
			CURLOPT_URL => $url,
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,

			CURLOPT_STDERR         => fopen('/var/log/dev_console.log', 'a+'),
			CURLOPT_VERBOSE        => true
		);

		curl_setopt_array($ch, real_array_merge_recursive(
			$defaults,
			$options
		));

		$response = curl_exec($ch);

		return $response;
	}

	function get_from($url, $options=array(), &$ch=null){
		if(is_null($ch))
			$ch = curl_init();

		$defaults = array(
			CURLOPT_URL => $url,
			CURLOPT_POST => false,
			CURLOPT_RETURNTRANSFER => true,

			CURLOPT_STDERR         => fopen('/var/log/dev_console.log', 'a+'),
			CURLOPT_VERBOSE        => true
		);

		curl_setopt_array($ch, real_array_merge_recursive(
			$defaults,
			$options
		));

		$response = curl_exec($ch);

		return $response;
	}

	private function project_viewer_url($model=null){
		$project = $this->_me->Projects->getAllProject(getActiveProject());

		$post_body = array();
		if(! empty($model))
			$post_body[] = $model;

		$auth_url = sprintf(
			'%s/viewer/access?project_id=%s',
			$this->_me->config->item('bimsync_api_url_prefix'),
			$project[0]['bimsync_id']
		);

		$response = json_decode($this->post_to($auth_url, array(
			CURLOPT_HTTPHEADER => array('Authorization: Bearer '. $this->_me->config->item('bimsync_api_token')),
			CURLOPT_POSTFIELDS => (count($post_body) ==0 ? '' : json_encode($post_body))
		)));

		return $response->url;
	}

	private function bimsync_project_models(){
		$project = $this->_me->Projects->getAllProject(getActiveProject());

		$auth_url = sprintf(
			'%s/models?project_id=%s',
			$this->_me->config->item('bimsync_api_url_prefix'),
			$project[0]['bimsync_id']
		);
		$response = json_decode($this->get_from($auth_url, array(
			CURLOPT_HTTPHEADER => array('Authorization: Bearer '. $this->_me->config->item('bimsync_api_token'))
		)));

		return (array) $response;
	}

	/**
	 * The mandatory method
	 * The frame work will call this method
	 * This is the entry point of the app
	 * It can produce any browser friendly output
	 */
	 
	 public function init(){
		/**
		 * Setup for bimsync api interaction
		 */

		$requested_model = array();
		if(strlen($this->_me->input->get('model')) >0)
			$requested_model['model_id'] = $this->_me->input->get('model');

		if(strlen($this->_me->input->get('revision')) >0)
			$requested_model['revision_id'] = $this->_me->input->get('revision');

		$project_auth_url = $this->project_viewer_url($requested_model);
		
	 	?>

	 	<div id="viewer-control-wrapper">
	 		<select name="project_model" id="project-model">
	 			<?php foreach($this->bimsync_project_models() as $model){ ?>
		 			<option value="<?php echo $model->id ?>" <?php echo ($model->id == $requested_model['model_id'] ? 'selected="selected"' : '') ?>><?php echo $model->name ?></option>
		 		<?php } ?>
	 		</select>

	 		<button id="viewer-show">show all</button>
	 		<button id="viewer-hide">hide selected</button>
	 	</div>

	 	<link href="<?php echo base_url('css/model_viewer.css').'?v='.rand() ?>" rel="stylesheet" type="text/css">
	 	<script type="text/javascript" src="<?php echo base_url('js/model_viewer.js?v=').filemtime('js/model_viewer.js')?>"></script>

	 	<script src="https://api.bimsync.com/1.0/js/viewer.js"></script>
	 	<div id="model-viewer" data-viewer="webgl" data-url="<?php echo $project_auth_url ?>"></div>
	 	<?php
	 }
	 

}
?>