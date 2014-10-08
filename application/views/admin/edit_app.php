<h4>Edit App</h4>
<?php
//v_dump($appdetails);
?>
<form name="edit_app" validate="validate" enctype="multipart/form-data" action="<?php echo base_url('admin/create_app')?>">
  <div class="universal_form_back">
    <div class="clear"></div>
    <input type="hidden" name="app_id" value="<?php echo $appdetails['id']?>"/>
    <input type="text" class="text_box_inner" placeholder="App Name" name="name" id="aname" data-validation-engine="validate[required]" value="<?php echo $appdetails['name']?>"/>
    <input type="text" class="text_box_inner" placeholder="Description" name="description" id="description" data-validation-engine="validate[required]" value="<?php echo $appdetails['description']?>"/>
    <input type="text" class="text_box_inner" placeholder="Order" name="order" id="order" data-validation-engine="validate[required]" value="<?php echo $appdetails['order']?>"/>
    <div class="clear"></div>
    <p class="label">Set type</p>
    <div class="clear"></div>
    <select name="type" data-validation-engine="validate[required]">
    	<?php
        	foreach(getAppType() as $id=>$type){
				if($id == $appdetails['type']){
					$sel = 'selected="selected"';
				}else{
					$sel = '';
				}
				echo '<option '.$sel.' value="'.$id.'">'.$type.'</option>';
			}
		?>
    </select>
    <div class="clear"></div>
    <p class="label">Active / Inactive</p>
    <div class="clear"></div>
    <input type="checkbox" name="is_active" value="1"  <?php echo $appdetails['is_active'] == 1 ? 'checked="checked"': '' ?>/>
    <div class="clear"></div>
    <!--<p class="label">Edit app</p>-->
    <?php
    /*
	 * Get the app code
	 */
	 $app_code = file_get_contents( APPPATH .'app/'.$appdetails['appfilepath']);
	 $app_code = htmlentities($app_code, ENT_NOQUOTES); 
	?>
      <textarea id="app_code" disabled="disabled" rows="40" cols="90" name="project_code"><?php echo $app_code;?></textarea>
  <p class="label">App file</p>
    <div class="clear"></div>
    <input type="file" class="text_box_inner"  name="filename" id="filename" data-validation-engine="validate[required]"/><span style="margin-left:20x">Existing file name : <?php echo $appdetails['appfilepath']?></span>
    <input type="hidden" name="appfilepath" id="appfilepath" value="<?php echo $appdetails['appfilepath'].'~!~'.$appdetails['classname'];?>">
    <div class="clear"></div>
    
    <p class="label">Icon</p>
    <div class="clear"></div>
    <input type="file" class="text_box_inner" name="icon" id="icon" data-validation-engine="validate[required]" /><span style="margin-left:20x">Existing file name : <?php echo $appdetails['appiconfilepath']?></span>
    <input type="hidden" name="appiconfilepath" id="appiconfilepath" value="<?php echo $appdetails['appiconfilepath']?>">
  </div>
  <div class="clear"></div>
  <input type="submit" class="sub_it_back" value="submit" />
  <input type="button" class="sub_it_back show_list" value="Show list" onclick="javascript:forceLoad = true;$('li.active').click();"/>
</form>
<script>
                                             	$('#filename').html5Uploader({
													name: 'foo',
													
													postUrl : '<?php echo base_url('admin/uplodAppfile');?>',
													
													onClientLoad: function(){
														$('#filename').closest('form').overlay(1);
														$('#filename').closest('form').overlay("Please wait while we uploading");
													},
													
													onServerProgress :function(e){},
													onSuccess:function(e, file, response){														
														$('#filename').closest('form').overlay("Upload complete");
														var res = JSON.parse(response);
														if(res.error.length == 0){
															$('#appfilepath').val(res.data);
														}else{
															$('#filename').closest('form').overlay(res.error[0]);
															console.log(file);
															$('#filename').val('');
														}
														$('#filename').closest('form').overlay(0, -1);
													}
													
												 });
												 $('#icon').html5Uploader({
													name: 'foo',
													
													postUrl : '<?php echo base_url('admin/uplodAppIconfile');?>',
													
													onClientLoad: function(){
														$('#icon').closest('form').overlay(1);
														$('#icon').closest('form').overlay("Please wait while we uploading");
													},
													onClientError: function(){
														dom.overlay("Browser fails to read the file");
														dom.overlay(0,-1);
													},
													onServerError:function(){
														$('#icon').closest('form').overlay("File upload fails,please try again");
														$('#icon').closest('form').overlay(0,-1);
													},
													onClientError: function(){
														$('#icon').closest('form').overlay("Browser fails to read the file");
														$('#icon').closest('form').overlay(0,-1);
													},
													onServerError:function(){
														dom.overlay("File upload fails,please try again");
														dom.overlay(0,-1);
													},
													onServerProgress :function(e){},
													onSuccess:function(e, file, response){														
														$('#icon').closest('form').overlay("Upload complete");
														var res = JSON.parse(response);
														if(res.error.length == 0){
															$('#appiconfilepath').val(res.data);
														}else{
															$('#icon').closest('form').overlay(res.error[0]);
															 $('#icon').val('');
														}
														$('#icon').closest('form').overlay(0, -1);
													}
													
												 });
												 
												// $('#app_code').ckeditor();
                                             </script>