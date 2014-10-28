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

		// Get Quantity Takeoff XML files for current project
		$revisions = $this->_me->Docs->getQTODocDetails(getActiveProject());
		$xml_path = @$revisions[0]['path'];
		$revision_id = $this->_me->input->get('revision_id');

		// Check that revisions exist
		if(count($revisions)) {

			// If revision date has been set from drop down, set $xml_path
			if(!empty($revision_id)) {
				foreach($revisions as $revision) {
					if($revision_id == $revision['id']) {
						$xml_path = $revision['path'];
						break;
					}
				}
			}

			// Load QTO XML file using $xml_path
			$xml = simplexml_load_file($xml_path);
			
		 	?>

		 	<div id="qto-wrapper">

		 		<div class="qto-left">

			 		<form action="" method="GET">
			 			<select name="revision_id" class="form-input" style="float: left;">
			 				<?php

			 				// populate revisions drop down
			 				foreach($revisions as $revision) {

			 					$revision_date = date('jS F Y - h:ia', $revision['date']);

			 					// select current revision in drop down
			 					if($revision_id == $revision['id']) {
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
					    	$('head').append('<link rel="stylesheet" href="<?php echo base_url('css/FileTreeView.css'); ?>" />');
					        $('#qto-list').fileTreeView('#expand-list', '#collapse-list', 'folder');
					        $('#filter-list').treeListFilter('#qto-list', 200);

					        $(document).ready(function(){
					        	$('a#collapse-list').click();
					        });
					    });
					</script>

					<input type="text" id="filter-list" placeholder="Filter by keyword..." class="form-input">

			 		<a id="expand-list">Expand All</a> | <a id="collapse-list">Collapse All</a>

			 		<ul id="qto-list">

			 		<?php 

					// Parse XML
					foreach($xml->Catalog->ItemGroup as $first_item_group) {
						$wbs = $first_item_group->attributes()->WBS;
						echo '<li>'.$first_item_group->attributes()->Name.' ('.$wbs.') <input type="radio" name="qto" value="'.$first_item_group->attributes()->Name.' ('.$wbs.')">
						<ul>';

							foreach($first_item_group as $second_item_group) {
								$wbs_second = $wbs.'.'.$second_item_group->attributes()->WBS;
								if($second_item_group->getName() == 'ItemGroup') {
									echo '<li class="folder">'.$second_item_group->attributes()->Name.' ('.$wbs_second.') <input type="radio" name="qto" value="'.$second_item_group->attributes()->Name.' ('.$wbs_second.')">';

									echo '<ul>';

									foreach($second_item_group as $third_item_group) {
										$wbs_third = $wbs_second.'.'.$third_item_group->attributes()->WBS;
										if($third_item_group->getName() == 'ItemGroup') {
											echo '<li class="folder">'.$third_item_group->attributes()->Name.' ('.$wbs_third.') <input type="radio" name="qto" value="'.$third_item_group->attributes()->Name.' ('.$wbs_third.')">';
										} else {
											echo '<li>'.$third_item_group->attributes()->Name.' ('.$wbs_third.')</li>';
										}

										if(count($third_item_group->Item)) {
											echo '<ul>';

												foreach($third_item_group->Item as $item) {
													$wbs_fourth = $wbs_third.'.'.$item->attributes()->WBS;
													echo '<li>'.$item->attributes()->Name.' ('.$wbs_fourth.')</li>';
												}

											echo '</ul>';
										}

									}

									echo '</ul>';

									} else {
										echo '<li>'.$second_item_group->attributes()->Name.' ('.$wbs_second.')</li>';
									}

								echo '</li>';
							}

						echo '</ul>
						</li>';
					}

			 		?>

			 		</ul>

		 		</div>

		 		<div class="qto-right">

		 			<p><strong>Navigate the list on the left and select a folder to request a Quantity Takeoff file.</strong></p>

		 			<form action="<?php echo base_url('portal/project/8/?f=submitRequest'); ?>" method="POST" id="request-qto">

		 				<p>You are requesting the following folder:</p>

		 				<input type="hidden" name="description" value="">
		 				<input type="hidden" name="extension" value="7">
		 				<input type="hidden" name="type" value="22">
		 				<input type="submit" class="blue-button action" value="Request Quantity Takeoff">

		 			</form>

		 		</div>

		 		<script type="text/javascript" src="<?php echo base_url('js/qto_app.js?v=').filemtime('js/qto_app.js')?>"></script>

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