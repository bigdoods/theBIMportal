<div id="tab<?php echo $tabid?>">
    <div class="tab_content<?php echo $tabid?>">

        <label for="selected-project-id">Project</label>
        <select name="project_id" id="selected-project-id">
            <option value="">All</option>
            <?php foreach($projectdetails as $project){ ?>
                <option value="<?php echo $project['id'] ?>" <?php echo ($project_id == $project['id'] ? 'selected="selected"' : '') ?>><?php echo $project['name'] ?></option>
            <?php } ?>
        </select>

        <div class="tab_content_detila_back list">
        	<ul class="head">
            	<li><p>Name</p></li>
                <li class="big"><p>Email</p></li>
                <li><p>Company</p></li>
                <li><p>Discipline</p></li>
                <li><p>Phone</p></li>
                <li><p>Join Date</p></li>
                <li><p>Activation Date</p></li>
                <li class="small"><p>Active?</p></li>
                <li class="small"><p>View</p></li>
            </ul>
            <?php if($userdetaiils){
    			     foreach($userdetaiils as $user){
    		?>
            		<ul class="details" rel="user-<?php echo $user['id']?>">
                    	<li><p><?php echo ucfirst($user['name'])?></p></li>
                        <li class="big"><p><?php echo $user['email']?></p></li>
                        <li><p><?php echo $user['company']?></p></li>
                        <li><p><?php echo $user['discipline']?></p></li>
                        <li><p><?php echo $user['phone']?></p></li>
                        <li><p><?php echo date('H:i', $user['joiningdate']).' on ';echo date('m-d-Y', $user['joiningdate']);?></p></li>
                        <li><p><?php echo $user['activationdate'] ? date('H:i', $user['activationdate']).' on '.date('m-d-Y', $user['activationdate']) : '-' ?></p></li>
                        <li class="small"><input type="checkbox" class="check_box" <?php echo ($user['status'] >= 3) ? 'checked="checked"' : '';?> disabled="disabled"/></li>
                        <li class="small userdetails"><p><a href="javascript:void(0);" class="blue-button action">Details</a></p></li>
                    </ul>
    		<?php 
    			     }
    			}else{
    		?>
    			<h2 class="data-blank"> No user exists in the system</h2>
    		<?php
    			}
    		?>                                                
        </div>
    <div class="tab_content_detila_back edit_form" style="display:none"></div>
        <div class="tab_content_detila_back create_form" style="display:none">
     	
        </div>
    </div>
</div>