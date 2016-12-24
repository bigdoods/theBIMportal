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
		$this->_me->load->helper('bimsync');
		$this->_me->load->helper('network');
	}


	/**
	 * The mandatory method
	 * The frame work will call this method
	 * This is the entry point of the app
	 * It can produce any browser friendly output
	 */

	 public function init(){
	 	$view = $this->_me->input->get('action');

	 	if(empty($view) || !method_exists($this, $view))
	 		$view = 'render_viewer';

	 	call_user_func(array($this, $view));
	 }

	 public function render_viewer(){
		/**
		 * Setup for bimsync api interaction
		 */

		// fetch model names and ids for select
		$all_models = bimsync_project_models();

		// see if we have a model id parameter
		// else default to the first one from bimsync
		$requested_model = array();

		if(strlen($this->_me->input->get('model')) >0)
			$requested_model['model_id'] = $this->_me->input->get('model');
		else //error: trying to get property of non-object
			$requested_model['model_id'] = array_first($all_models)->id;

		// use the revision query string parameter if present
		if(strlen($this->_me->input->get('revision')) >0)
			$requested_model['revision_id'] = $this->_me->input->get('revision');

		// fetch all revisions for the current model
		// to use in select
		$model_revisions = bimsync_model_revisions($requested_model['model_id']);

		// authorisation is required each time a model viewed or switched
		$project_auth_url = bimsync_project_viewer_url($requested_model);

	 	?>

	 	<div id="viewer-control-wrapper">
	 		<select name="project_model" id="project-model" class="form-input">
	 			<?php foreach($all_models as $model){ ?>
		 			<option value="<?php echo $model->id ?>" <?php echo ($model->id == @$requested_model['model_id'] ? 'selected="selected"' : '') ?>><?php echo $model->name ?></option>
		 		<?php } ?>
	 		</select>

	 		<select name="model_revision" id="model-revision" class="form-input">
	 			<?php foreach($model_revisions as $revision){ ?>
		 			<option value="<?php echo $revision->id ?>" <?php echo ($revision->id == @$requested_model['revision_id'] ? 'selected="selected"' : '') ?>><?php echo date('d-m-Y H:i', substr($revision->timestamp, 0, -3)) ?> <?php echo $revision->comment ?></option>
		 		<?php } ?>
	 		</select>

	 		<a href="#" id="viewer-show">show all</a>
	 		<a href="#" id="viewer-hide">hide selected</a>
	 	</div>

	 	<div id="viewer-info-box"></div>
	 	<div id="component-list"></div>

	 	<link href="<?php echo base_url('css/model_viewer.css').'?v='.rand() ?>" rel="stylesheet" type="text/css">
	 	<script type="text/javascript" src="<?php echo base_url('js/model_viewer.js?v=').filemtime('js/model_viewer.js')?>"></script>

	 	<script src="https://api.bimsync.com/1.0/js/viewer.js"></script>
	 	<div id="model-viewer" data-viewer="webgl" data-url="<?php echo $project_auth_url ?>"></div>

	 	<?php
	 }

	 public function object_info(){
	 	$object_ids = explode(',', $this->_me->input->get('object'));

	 	if(!is_array($object_ids))
	 		$object_ids = array($object_ids);

	 	$info_html = array();
	 	foreach($object_ids as $object_id){
			$object_info_html = bimsync_project_product($object_id, 'html');

			// remove some html
			$info_html[] = preg_replace('/<\!DOCTYPE html><title>.*<\/title>/i', '', $object_info_html);
		}

		ob_clean();
		echo implode('<hr />', $info_html);
		ob_flush();
		exit;
	 }

	public function component_tree_html($tree, $frag){
		$html = '<ul>';
		foreach($frag as $object){
			$title = $object->name;
			$type = preg_replace('/^ifc/i', '', $object->type);
			$type = trim(preg_replace('/([A-Z])/', ' \1', $type));
			if(empty($title) || $title == 'Undefined')
				$title = $type;

			$html .= '<li data-object-id="'. $object->objectId .'" data-object-type="'. $object->type .'"><a href="#" title="'. $object->description .' ('. $type .')">'. $title .'</a>';
			if(isset($tree[$object->objectId]))
				$html .= $this->component_tree_html($tree, $tree[$object->objectId]);
			$html .= '</li>';
		}

		return $html .'</ul>';
	}
	public function component_tree(){
		$objects = bimsync_model_structure($this->_me->input->get('model'));

		$product_details = bimsync_revision_products($this->_me->input->get('model'), $this->_me->input->get('revision'));

		$product_id_map = array();
		foreach($product_details as $product_detail){
			$product_id_map[$product_detail->objectId] = $product_detail;
		}

		$object_hierarchy = array();
		foreach($objects as $object){
			if(! isset($object_hierarchy[$object->parent]))
				$object_hierarchy[$object->parent] = array();

			$object->name = $product_id_map[$object->objectId]->name;
			$object->description = $product_id_map[$object->objectId]->description;
			$object->type = $product_id_map[$object->objectId]->type;

			$object_hierarchy[$object->parent][] = $object;
		}

		$html = $this->component_tree_html($object_hierarchy, $object_hierarchy[0]);

		ob_clean();
		echo $html;
		ob_flush();
		exit;
	}
}
?>
