<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Bim_Appmodule{
	protected $_userid;
	protected $_project_id_arr = array();
	protected $_me = NULL;
	protected $_base_uri = '';
	protected $_root = '';
	protected $_role = '';
	
	public function start(){
		global $_SESSION;
		$this->_userid = getCurrentuserId();
		$this->_me =  &get_instance();
		/**
		 * Lod the project ids 
		 */		
		$this->_project_id_arr = $this->_me->Users->getCurrentProjects() ;
		$base_uri_arr = explode( '?', $_SERVER['REQUEST_URI']);		
		$this->_base_uri = rtrim($base_uri_arr[0], '/').'/';
		$this->_root = base_url();
		$this->_role = getCurrentUserRole();
		 
	}
	
	public function init(){
		echo " I am a parent class init method.Every app class should override me";
	}
	
	protected function outputStart(){
		ob_end_clean();		
		ob_start();
		
	}
	
	protected function outputEnd(){
		ob_flush();
		exit;
		
	}
	
	protected function printScript( $script ){
	?>
		<script type="text/javascript" src="<?php echo  $style  ?>></script>
	<?php
	}
	
	protected function printStyle( $style ){
		?>
        <link href="<?php echo  $style  ?>" rel="stylesheet" type="text/css">
		<?php
	}
	
}
?>
