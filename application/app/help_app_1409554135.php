<?php

class Help_App extends Bim_Appmodule{
	
	/**
	 * The default constructor
	 * any initialization can be placed here
	 * And at first the parent constructor must be called
	 */
	private $_db = NULL;
	private $_page_id = 1;
	public function __construct(){
		parent::start();
		$this->_db = $this->_me->db;// this is codeigniter db
	}
	
	private function getHelpPageConent(){
		$this->_db->where('page_id', $this->_page_id);
		$q = $this->_db->get('wiki_pages');
		if($q->num_rows()){
			$row = $q->row_array();
			$content = $row['content'];
		}else{
			$content = '';
		}
		return $content;
	}
	
	public function init(){
		$content = $this->getHelpPageConent();
		?>
        	<div>
            	<?php echo $content?>
            </div>
			
		<?php
	}
	
	public function adminInit(){
		$this->outputStart();
		echo '<script type="text/javascript" src="'. base_url('js/ckeditor/ckeditor.js').'"></script>';
		echo '<h1>Please edit the help page content</h1>';
		$content = $this->getHelpPageConent();
		if($content){
			$is_update = 1;
		}else{
			$is_update = 0;
		}
		
		?>
        	<div class="clear"></div>
            <br/>
			<form name="help-app-content">
            <input type="hidden" name="is_update" value="<?php echo $is_update;?>">
            	<textarea id="help_wiki" class="ckeditor" name="help_wiki">
                	<?php echo $content?>
                </textarea>
                <br/>
                <input type="submit" value="save" class="sub_it_back">
            </form>
        <script type="text/javascript">
        	(function($){
				$(function(){
					//CKEDITOR.replace('#help_wiki');
					$('#help_wiki').ckeditor();
				
				/**
				 * call the ajax for form submit
				 */
				 $(document).on('submit', 'form[name=help-app-content]', function(){
					 var frm = $(this);
					$.ajax({
						url : '<?php echo $this->_base_uri?>?f=updateContent',
						type: 'post',
						data: frm.serialize(),
						beforeSend :function(){
							frm.overlay(1);
							frm.overlay('Please wait');
						},
						success: function(){
							frm.overlay("Update successfully");
						},
						complete:function(){
							frm.overlay(0,-1);
						},
						error: function(){
							frm.overlay("Internal server error, please try after sometime");
						}
					});
				 	return false;
				 })
				})
			})(jQuery)
        </script>
		<?php
		$this->outputEnd();
	}
	/**
	 * UIPdate the contetn of the site
	 */
	
	public function updateContent(){
		$this->outputStart();
		if( !empty($_POST)){
			$data = array(
				'page_id' =>$this->_page_id,
				'content' => $_POST['help_wiki'],
				'is_active' =>1
			);
			if(isset($_POST['is_update']) && $_POST['is_update'] == 1){
				$this->_db->where('page_id', $this->_page_id);
				$this->_db->update('wiki_pages', $data);
			}else{
				$this->_db->insert('wiki_pages', $data);			
			}
		}
		$this->outputEnd();
		
	}
}
?>