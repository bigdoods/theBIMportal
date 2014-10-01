<?php
class Project_App extends Bim_Appmodule{
	
	private $_db = NULL;
	public function __construct(){
		parent::start();
		$this->_db = $this->_me->db;// this is codeigniter db
		$this->_me->load->model('Users');
		$this->_me->load->model('Projects');
		$this->_me->load->model('Docs');
		
	}
	
	public function init(){
		$all_assigned_projects  =$this->_me->Users->getAssignedProjects(getCurrentuserId());
		if($all_assigned_projects){
			$all_project_ids = array_keys($all_assigned_projects);
			// fetch the last ids of the notification
			$sql = "select max(a.date_time) as date_time,c.embedcode,b.name  as case_name ,MAX(a.id) as notid, c.id as pid from notifications a JOIN `case` b ON a.case_id = b.id JOIN projects as c ON a.project_id = c.id WHERE project_id IN (".implode(',',$all_project_ids).") AND a.case_id = b.id group by project_id having max(a.id) order by a.id desc";
			$res = $this->_db->query($sql);
			if($res->num_rows()){
			?>
				<ul class="project">
			<?php
				foreach($res->result_array() as $row){
								
				if($row['embedcode'] && strpos($row['embedcode'], 'src') !== false ){
					$doc = new DOMDocument();
					$doc->loadHTML($row['embedcode']);
					$iframe = $doc->getElementsByTagName('iframe');
					foreach($iframe as $tag){
					$src= $tag->getAttribute('src');
					if($src){
						 $html = '<iframe src="'.$src.'" height="180" width="400" frameborder="0" style="border:0"></iframe>';
					}
					}
				}else{
					echo 'not available';
				}
			
				?>
				<li class="project_tile" id="project-<?php echo $row['pid']?>">
                            			<div class="left_con_del">
                                        	<h2><?php echo date('d/m/Y', $row['date_time'])?> updates</h2>
                                            <div class="clear"></div>
                                              <p><?php echo date('i:s', $row['date_time'])?> - <?php echo $row['case_name']?> updates <a href="javascript:void(0)">click to view</a></p>
                                        </div>
                                        <div class="image"><?php echo $html?></div>
                                    </li>
				<?php
				}
			?>
			</ul>
			<?php
			}
		}
	}
}
?>