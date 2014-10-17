<br>
<h4>Edit user - <?php echo $userdetails['name'];//v_dump($assignedprojects)?></h4>
      <form name="project_assign" validate="validate" method="post">
          <div class="universal_form_back">
          	<input type="hidden" name="userid" value="<?php echo $userid ?>"> 
            <div class="clear"></div>
            Active / Inactive: <input type="checkbox" name="status" id="status" data-validation-engine="validate[required]" value="1" <?php echo $userdetails['status'] >= 3 ? 'checked="checked"' : ''?>/>
            <div class="clear"></div>
            <br>
            <span>Assign Projects:</span>
            <select name="project_list[]" multiple="multiple" style="width:300px;">
            	<?php
                	if($allprojects){
						foreach($allprojects as $project){
							if(array_key_exists( $project['id'] , $assignedprojects )){
								$selected = 'selected="selected"';
							}else{
								$selected = '';
							}	
							echo '<option value="'.$project['id'].'" '.$selected.'>'.$project['name'].'</option>';
						}
					}
				?>
                
            </select>
            <script>
             $("select").multipleSelect({
				placeholder: "Select the projects"
			});
			 /**
	   * save user details
	   */
	  $('form[name=project_assign]').ajaxForm({
			url : 'editUser/',
			'type': 'post',
			beforeSubmit:function(arr, frm){
				frm.overlay(1);
				frm.overlay('Please wait');
			},
			success:function(r, status, xhr, frm){
				var response_arr = r.split('~!~');
				$('.reg_success').html(response_arr[0]).css({'visibility': 'visible'});
				if(response_arr[1] == 1){
					frm.resetForm();
				}
				frm.overlay(0);
			},
			error:function(){
				frm.overlay('An error occured, please try again');
				frm.overlay(0);
			}
		 })
		 .on('focus',function(){
				$('.reg_success').css('visibility', 'hidden');
		 } );
		</script>
            <div class="clear"></div>
          </div>
          
          
          <div class="clear"></div>
          <input type="submit" class="blue-button action" value="Save" />
          <input type="button" class="grey-button show_list" value="Show list" onclick="javascript:forceLoad = true;$('li.active').click();"/>
     </form>