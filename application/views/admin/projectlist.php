<div id="tab<?php echo $tabid?>">
  <div class="tab_content1 project-List">    
      <div class="tab_content_detila_back">
        <ul class="head">
          <li>
            <p>Name</p>
          </li>
          <li class="big"  style="width:50%">
            <p>Location</p>
          </li>
          <li class="big">
            <p>Action</p>
          </li>
        </ul>
        <?php if($projectdetails):
			foreach($projectdetails as $project):	
		?>
        <ul class="details">
          <li>
            <p><?php echo ucfirst($project['name'])?></p>
          </li>
          <li class="big"  style="width:50%">
            <p><?php			
				if($project['embedcode'] && strpos($project['embedcode'], 'src') !== false ){
					$doc = new DOMDocument();
					$doc->loadHTML($project['embedcode']);
					$iframe = $doc->getElementsByTagName('iframe');
					foreach($iframe as $tag){
					$src= $tag->getAttribute('src');
					if($src){
						echo $html = '<iframe src="'.$src.'" height="180" width="400" frameborder="0" style="border:0"></iframe>';
					}
					}
				}else{
					echo 'not available';
				}
			?></p>
          </li>
          <li class="small">
          	<p><a href="javascript:void(0)" class="p_edit" rel="<?php echo $project['id']?>">Edit</a></p>
          </li>
        </ul>
        <?php 
			endforeach;
			else:
		?>
        <h2> No Projects Exists</h2>
        <?php
			endif;
		?>
        <input type="button" class="sub_it_back create_project" value="Create"/>
      </div>
   </div>
 <div class="tab_content1 project_create" style="display:none;">
  <h4>New Project</h4>
  <form name="create_project" validate="validate">
      <div class="universal_form_back">
        <div class="clear"></div>
        <input type="text" class="text_box_inner" placeholder="Project Name" name="name" id="pname" data-validation-engine="validate[required]"/>
        <input type="text" class="text_box_inner" placeholder="Embed code" name="embedcode" id="embedcode" data-validation-engine="validate[required]"/>
      </div>
      
      
      <div class="clear"></div>
      <input type="submit" class="sub_it_back" value="submit" />
      <input type="button" class="sub_it_back show_list" value="Show list" onclick="javascript:forceLoad = true;$('li.active').click();"/>
 </form>
</div>
 <div class="tab_content1 project_edit" style="display:none;">
 
 </div>
  
</div>
