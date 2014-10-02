<h4>Edit Project</h4>
      <form name="project_edit" validate="validate" method="post">
          <div class="universal_form_back">
          	<input type="hidden" name="projectid" value="<?php echo $project_details['id'] ?>"> 
            <div class="clear"></div>
            Active / Inactive: <input type="checkbox" name="active" id="active" data-validation-engine="validate[required]" value="1" <?php echo $project_details['active'] >= 1 ? 'checked="checked"' : ''?>/>
            <div class="clear"></div>
            
            <p>Name :</p>
            <div class="clear"></div>
            <input type="text" name="name" class="text_box_inner" value="<?php echo $project_details['name']?>" data-validation-engine="validate[required]">
            <div class="clear"></div>
            
            <p>Embed Code :</p>
            <div class="clear"></div>
            <textarea class="text_box_inner" name="embedcode"><?php echo $project_details['embedcode']?></textarea>           
            <div class="clear"></div>

            <p>Bimsync Project :</p>
            <div class="clear"></div>
            <select name="bimsync_id">
              <option value="" <?php echo (empty($project_details['bimsync_id']) ? 'selected="selected"' : '') ?>>None</option>
              <?php foreach($bimsync_projects as $bimsync_project){debug($bimsync_project->id,$project_details['bimsync_id']); ?>
                <option value="<?php echo $bimsync_project->id ?>" <?php echo ($bimsync_project->id == @$project_details['bimsync_id'] ? 'selected="selected"' : '') ?>><?php echo $bimsync_project->name ?></option>
              <?php } ?>
            </select>
            <div class="clear"></div>
          </div>
          
          
          <div class="clear"></div>
          <input type="submit" class="sub_it_back" value="Update" />
          <input type="button" class="sub_it_back show_list" value="Show list" onclick="javascript:forceLoad = true;$('li.active').click();"/>
     </form>