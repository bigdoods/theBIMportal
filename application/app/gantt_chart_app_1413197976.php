<?php

class Gantt_chart_app extends Bim_Appmodule{
	
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
			$view = 'chart_render';

		call_user_func(array($this, $view));
	}

	private function StampToFdate($timestamp)
	{
		$norm_date = date('d/m/Y', $timestamp);
		$result = $norm_date;
		//returns date in mm-dd-yyyy format
		return $result;
	}

	public function chart_render(){

		// Get Quantity Takeoff XML files for current project
		$revisions = $this->_me->Docs->getGanttDocDetails(getActiveProject());
		$xml_path = @$revisions[0]['path'];
		$revision_id = $this->_me->input->get('revision_id');
		
		// Check that revisions exist
		if(count($revisions) >0 && !empty($revision_id)) {
			// If revision date has been set from drop down, set $xml_path
			foreach($revisions as $revision) {
				if($revision_id == $revision['id']) {
					$xml_path = $revision['path'];
					break;
				}
			}
		}

		$gantt_file = dirname(BASEPATH) . '/' . $xml_path;

		//do all pre-processing here
		$error_msg = 'Unknown';
		$data_load_error = false;
		
		//set defaults
		$a_gantt_data = array(); //stores the gantt chart data from the csv file
		$a_gantt_links = array(); //stores dependencies and links of the chart data
		$a_task_ids = array(); //stores an array of task id for easy use on dependencies
		$skip_header = true; //skips the label header of the csv file
		$a_index = 0; //task array index number
		$a_link_index = 0; //link array index number
		$task_id = 1; //creates a task id number
		$current_parent_id = 0; //stores the parent task id in the data stream for dependencies and links
		$start_display_stamp = 0;
		$end_display_stamp = 0;
		
		$fp = fopen($gantt_file, 'r');
		while (($a_txt = @fgetcsv($fp, 9999, ",")) !== FALSE)
		{
			$data_size = sizeof($a_txt);
			if($data_size != 33)
			{
				$data_load_error = true;
				exit;
			}
			if($skip_header)
			{
				$skip_header = false;
				continue;
			}
				
			//get the field data
			$id = $task_id++;
			$a_task_ids[$a_index] = $id;
			$task_name = ucwords(strtolower(strip_tags($a_txt[1])));
			if($task_name == '')
			{
				$task_name = 'Task ' .$id;
			}
			$progress = (str_replace('%', '', $a_txt[17]) / 100);
			if($progress == 1)
			{
				$stat = '&bull;';
				$color = '#008000';
			}
			if($progress > 0 && $progress < 1)
			{
				$stat = '&bull;';
				$color = '#FFA500';
			}
			if($progress == 0)
			{
				$stat = '&bull;';
				$color = '#FF0000';
			}
				
			//deal with the start and end dates
			$orig_pstart_date = $a_txt[7];
			$orig_pend_date = $a_txt[8];
			$orig_astart_date = $a_txt[4];
			$orig_aend_date = $a_txt[5];
			
			if($orig_pstart_date == '' && $orig_astart_date != '')
			{
				$orig_pstart_date = $orig_astart_date;
			}
			if($orig_astart_date == '' && $orig_pstart_date != '')
			{
				$orig_astart_date = $orig_pstart_date;
			}
			if($orig_pend_date == '' && $orig_aend_date != '')
			{
				$orig_pend_date = $orig_aend_date;
			}
			if($orig_aend_date == '' && $orig_pend_date != '')
			{
				$orig_aend_date = $orig_pend_date;
			}
			if($orig_pstart_date == '' || $orig_pend_date == '' || $orig_astart_date == '' || $orig_aend_date == '')
			{
				continue;
			}
				
			$current_start_display_stamp = strtotime(str_replace('/', '-', $orig_pstart_date));
			$current_end_display_stamp = strtotime(str_replace('/', '-', $orig_pend_date));
			
			if($start_display_stamp == 0)
			{
				$start_display_stamp = $current_start_display_stamp;
			}
			else
			{
				if($current_start_display_stamp < $start_display_stamp)
				{
					$start_display_stamp = $current_start_display_stamp;
				}
			}
			if($end_display_stamp == 0)
			{
				$end_display_stamp = $current_end_display_stamp;
			}
			else
			{
				if($current_end_display_stamp > $end_display_stamp)
				{
					$end_display_stamp = $current_end_display_stamp;
				}
			}
			
			$start_display_date = $this->StampToFdate($start_display_stamp - 86400);
			$end_display_date = $this->StampToFdate($end_display_stamp + 86400);
			
			$raw_astart_date = str_replace('/', '-', $orig_astart_date);
			$raw_aend_date = str_replace('/', '-', $orig_aend_date);
			$temp = explode(' ', $raw_astart_date);
			$actual_start = $temp[0];
			$temp = explode(' ', $raw_aend_date);
			$actual_end = $temp[0];
			
			$raw_pstart_date = str_replace('/', '-', $orig_pstart_date);
			$raw_pend_date = str_replace('/', '-', $orig_pend_date);
			$temp = explode(' ', $raw_pstart_date);
			$planned_start = $temp[0];
			$temp = explode(' ', $raw_pend_date);
			$planned_end = $temp[0];
				
			$task_type = ucwords(strtolower(strip_tags($a_txt[9])));
			$active = $a_txt[0];
			$comments = $a_txt[18];
			$material_cost = $a_txt[19];
			$labor_cost = $a_txt[20];
			$equipment_cost = $a_txt[21];
			$subcontractor_cost = $a_txt[22];
			
			//this section handles task dependencies
			$nested = $a_txt[2];
			if($nested == 0)
			{
				//reset when you find a new parent
				$current_parent_id = 0;
			}
			else
			{
				//this is a nested task
				if($current_parent_id == 0)
				{
					//only record this once for the parent
					//this is the first link
					$current_parent_id = $a_task_ids[($a_index - 1)];
					$source_id = $current_parent_id;
					$task_link_type = 1;
				}
				else
				{
					//this is another link after the first
					$source_id = $a_task_ids[($a_index - 1)];
					$task_link_type = 0;
				}
				//create the link entry
				'{id:4, source:3, target:4, type:"1"}';
				$link_data = '{id:' .$id. ', source:' .$source_id. ', target:' .$id. ', type:"' .$task_link_type. '"}';
				$a_gantt_links[$a_link_index] = $link_data;
				$a_link_index++;
			}//end else
			
			$open = true;
			$parent = $current_parent_id; //need to change this to current_parent_id when done
			
			$data = '{id:' .$id. ', stat:"' .$stat. '", text:"' .$task_name. '", start_date:"' .$raw_pstart_date. '", end_date:"' .$raw_pend_date. '", progress:' .$progress. ', p_color:"' .$color. '", open:' .$open. ', parent:' .$parent. ', task_type:"' .$task_type. '", active:"' .$active. '", nested:"' .$nested. '", actual_start:"' .$actual_start. '", actual_end:"' .$actual_end. '", planned_start:"' .$planned_start. '", planned_end:"' .$planned_end. '", comments:"' .$comments. '", material_cost:"' .$material_cost. '", labor_cost:"' .$labor_cost. '", equipment_cost:"' .$equipment_cost. '", subcontractor_cost:"' .$subcontractor_cost. '"}';

			$a_gantt_data[$a_index] = $data;
			$a_index++;

		}//end while
		
		$gantt_data = implode(',', $a_gantt_data);
		$gantt_links = implode(',', $a_gantt_links);

		?>

			<script src="<?php echo base_url('js/dhtmlxgantt/dhtmlxgantt.js') ?>"></script>
			<script src="<?php echo base_url('js/dhtmlxgantt/dhtmlxgantt_marker.js') ?>"></script>
			<script src="<?php echo base_url('js/dhtmlxgantt/dhtmlxgantt_tooltip.js') ?>"></script>
			<link href="<?php echo base_url('js/dhtmlxgantt/skins/dhtmlxgantt_skyblue.css') ?>" rel="stylesheet">
			<link href="<?php echo base_url('js/dhtmlxgantt/rb.css') ?>" rel="stylesheet">

			<script type="text/javascript">
				var tasks = {
		    		data:[
						<?php echo "$gantt_data" ?>
					],
		    		links:[
						<?php echo "$gantt_links" ?>
					]
				},
				start_display_date = '<?php echo $start_display_date ?>',
				end_display_date = '<?php echo $end_display_date ?>';
			</script>
			<script type="text/javascript" src="<?php echo base_url('js/gantt_chart.js?v=').filemtime('js/model_viewer.js')?>"></script>

			<div class='sample_header' style="height: 73px;border-bottom:5px solid #828282; overflow:hidden;">
		 		<form action="" method="GET">
		 			<select name="revision_id" class="form-input" style="float: left;">
		 				<?php

		 				// populate revisions drop down
		 				foreach($revisions as $revision){

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
		 			<input type="submit" value="Select Revision" class="blue-button action" style="float: left; margin: 6px 0 0 6px" />
		 		</form>

				<div class='controls_bar'>
					<strong> Zooming: &nbsp; </strong>
					<label>
						<input name='scales' onclick='zoom_tasks(this)' type='radio' value='trplweek'  checked='true'>
						<span>Days</span>
					</label>
					<label>
						<input name='scales' onclick='zoom_tasks(this)' type='radio' value='year'>
						<span>Months</span>
					</label>
					<div id="filter_hours">
						<span>&nbsp;&nbsp;|&nbsp;&nbsp;</span>
						<strong> Display: &nbsp; </strong>
						<label>
							<input name='scales_filter' onclick='set_scale_units(this)' type='radio' value='full_day'>
							<span>Full day</span>
						</label>
						<label>
							<input name='scales_filter' onclick='set_scale_units(this)' type='radio' value='work_hours'>
							<span>Office hours</span>
						</label>
						<label>
							<input name='scales_filter' onclick='set_scale_units(this)' type='radio' value='full_week'>
							<span>Full week</span>
						</label>
						<span>&nbsp;&nbsp;|&nbsp;&nbsp;</span>
					</div><!--filter hours-->
					<strong> Start Date: &nbsp; </strong>
					<span>
			    		<img id="calendar_icon" src="<?php echo base_url('js/dhtmlxgantt/calendar.gif') ?>" border="0">
					</span>
					<label>
					<input name='st_date' onchange='set_chart_range()' type='text' id="sdate" size='10' value=''>
					</label>
					<strong> End Date: &nbsp; </strong>
					<span>
			    		<img id="calendar_icon" src="<?php echo base_url('js/dhtmlxgantt/calendar.gif') ?>" border="0">
					</span>
					<label>
					<input name='ed_date' onchange='set_chart_range()' type='text' id="edate" size='10' value=''>
					</label>
				</div><!--control bar-->
			</div><!--sample header-->

			<div id="gantt-chart" style='width:100%; height:100%;'></div><!--end gantt-->
			

		<?php
	}
}
?>