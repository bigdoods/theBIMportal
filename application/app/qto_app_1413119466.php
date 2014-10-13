<?php

class QTO_App extends Bim_Appmodule{
	
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
		$this->_me->load->model('Docs');
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
	 		$view = 'qto_view';

	 	call_user_func(array($this, $view));
	 }
	 
	 public function qto_view(){
		/**
		 * Setup for bimsync api interaction
		 */

		// Get current project ID
		$current_project = $this->_me->Users->getCurrentProjects();

		// Get Quantity Takeoff XML files for current project
		$revisions = $this->_me->Docs->getQTODocDetails($current_project[0]);

		// Check that revisions exist
		if(count($revisions)) {

			// If revision date has been set from drop down, set $xml_path
			if(isset($_GET['revision_id'])) {
				foreach($revisions as $revision) {
					if($_GET['revision_id'] == $revision['id']) {
						$xml_path = $revision['path'];
					}
				}
			}
			// else select latest revision by default
			else {
				$xml_path = $revisions[0]['path'];
			}

			// Load QTO XML file using $xml_path
			$xml = simplexml_load_file($xml_path);
			
		 	?>

		 	<div id="qto-wrapper">

		 		<form action="" method="GET">
		 			<select name="revision_id" class="form-input" style="float: left;">
		 				<?php

		 				// populate revisions drop down
		 				foreach($revisions as $revision) {

		 					$revision_date = date('jS F Y - h:ia', $revision['date']);

		 					// select current revision in drop down
		 					if(isset($_GET['revision_id']) && $_GET['revision_id'] == $revision['id']) {
		 						$selected = ' selected';
		 					} else {
		 						$selected = '';
		 					}

		 					echo '<option '.$selected.' value="'.$revision["id"].'">'.$revision_date.'</option>';

		 				}

		 				?>
		 			</select>
		 			<input type="submit" value="Select Revision" class="blue-button action" style="float: left; margin: 6px 0 0 6px">
		 		</form>

		 		<br class="clear">

			 	<script type="text/javascript" src="<?php echo base_url('js/FileTreeView.js'); ?>"></script>
			 	<script type="text/javascript" src="<?php echo base_url('js/TreeListFilter.js'); ?>"></script>
			 	<script type="text/javascript">
				    $(function() {
				    	$('head').append('<link rel="stylesheet" href="<?php echo base_url(); ?>css/FileTreeView.css" />');
				        $('#qto-list').fileTreeView('#expand-list', '#collapse-list', 'folder');
				        $('#filter-list').treeListFilter('#qto-list', 200);
				    });
				</script>

				<input type="text" id="filter-list" placeholder="Filter by keyword..." class="form-input">

		 		<a id="expand-list">Expand All</a> | <a id="collapse-list">Collapse All</a>

		 		<ul id="qto-list">

		 		<?php 

				// var_dump($xml);

				// Parse XML
				foreach($xml->Catalog->ItemGroup as $first_item_group) {
					echo '<li>'.$first_item_group->attributes()->Name.'
					<ul>';

						foreach($first_item_group as $second_item_group) {
							echo '<li>'.$second_item_group->attributes()->Name.'
							<ul>';

								foreach($second_item_group as $third_item_group) {
									echo '<li>'.$third_item_group->attributes()->Name.'</li>';

									if(count($third_item_group->Item)) {
										echo '<ul>';

											foreach($third_item_group->Item as $item) {
												echo '<li>Item Name: '.$item->attributes()->Name.'</li><ul>';
												echo '<li>Description: '.$item->attributes()->Description.'</li>';
												echo '<li>Transparency: '.$item->attributes()->Transparency.'</li>';
												echo '<li>Color: '.$item->attributes()->Color.'</li></ul>';
											}

										echo '</ul>';
									}

								}

							echo '</ul>
							</li>';
						}

					echo '</ul>
					</li>';
				}

		 		?>

		 		</ul>

		 	</div>

		 	<?php
		 }
		 // else display not found message
		 else { ?>

		 <h3>No Quantity Takeoff files found</h3>

		 <?php
		}
	 }

}
?>