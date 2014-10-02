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

	/* net functions
		post_to and get_from are basic curl wrappers to set up for
		two http request methods, mainly used for bimsync api interaction
	*/
	function post_to($url, $options=array(), &$ch=null){
		if(is_null($ch))
			$ch = curl_init();
		$defaults = array(
			CURLOPT_URL => $url,
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,

/*			CURLOPT_STDERR         => fopen('/var/log/dev_console.log', 'a+'),
			CURLOPT_VERBOSE        => true
*/		);

		// merge passed in curl params with defaults
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

/*			CURLOPT_STDERR         => fopen('/var/log/dev_console.log', 'a+'),
			CURLOPT_VERBOSE        => true
*/		);

		// merge passed in curl params with defaults
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

		// responses are always in json so request and decode
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

		// responses are always in json so request and decode
		$response = json_decode($this->get_from($auth_url, array(
			CURLOPT_HTTPHEADER => array('Authorization: Bearer '. $this->_me->config->item('bimsync_api_token'))
		)));

		return (array) $response;
	}

	private function bimsync_model_revisions($model_id=null){
		// if we don't have a model yet, return an empty array
		if(is_null($model_id))
			return array();

		$auth_url = sprintf(
			'%s/revisions?model_id=%s',
			$this->_me->config->item('bimsync_api_url_prefix'),
			$model_id
		);

		// responses are always in json so request and decode
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

		// fetch model names and ids for select
		$all_models = $this->bimsync_project_models();

		// see if we have a model id parameter
		// else default to the first one from bimsync
		$requested_model = array();
		if(strlen($this->_me->input->get('model')) >0)
			$requested_model['model_id'] = $this->_me->input->get('model');
		else
			$requested_model['model_id'] = array_first($all_models)->id;

		// use the revision query string parameter if present
		if(strlen($this->_me->input->get('revision')) >0)
			$requested_model['revision_id'] = $this->_me->input->get('revision');

		// fetch all revisions for the current model
		// to use in select
		$model_revisions = $this->bimsync_model_revisions($requested_model['model_id']);

		// authorisation is required each time a model viewed or switched
		$project_auth_url = $this->project_viewer_url($requested_model);
		
	 	?>

	 	<div id="viewer-control-wrapper">
	 		<select name="project_model" id="project-model">
	 			<?php foreach($all_models as $model){ ?>
		 			<option value="<?php echo $model->id ?>" <?php echo ($model->id == @$requested_model['model_id'] ? 'selected="selected"' : '') ?>><?php echo $model->name ?></option>
		 		<?php } ?>
	 		</select>

	 		<select name="model_revision" id="model-revision">
	 			<?php foreach($model_revisions as $revision){ ?>
		 			<option value="<?php echo $revision->id ?>" <?php echo ($revision->id == @$requested_model['revision_id'] ? 'selected="selected"' : '') ?>><?php echo date('d-m-Y H:i', substr($revision->timestamp, 0, -3)) ?> <?php echo $revision->comment ?></option>
		 		<?php } ?>
	 		</select>
	 		<br />

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