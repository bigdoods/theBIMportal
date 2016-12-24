<div id="tab<?php echo $tabid?>">
                                     <div class="tab_content<?php echo $tabid?>">
                                    <div class="tab_content_detila_back list">
                                            	<ul class="head">
                                                	<li><p>Name</p></li>
                                                    <li class="big"><p>Description</p></li>
                                                    <li><p>Active/Inactive</p></li>
                                                    <li><p>Edit</p></li>
                                                </ul>
                                                <?php if($app_details):
													foreach($app_details as $appId=>$apps):
												?>
													<ul class="details" rel="app-<?php echo $appId?>">
                                                	<li><p><?php echo ucfirst($apps['name'])?></p></li>
                                                    <li class="big"><p><?php echo $apps['description']?></p></li>
                                                    <li><p><input type="checkbox" name="active" disabled="disabled" <?php echo $apps['is_active'] == 1 ? 'checked="checked"' : ''?>></p></li>
                                                    <li><p><a class="edit-app blue-button action" href="javascript:void(0);">Edit</a></p></li>
                                                </ul>



												<?php
													endforeach;
													else:
												?>
													<h2 class="data-blank"> No apps exist in the system</h2>
												<?php
													endif;
												?>
                                              <input type="button" class="sub_it_back create_app" value="Create"/>
                                            </div>
                                    <div class="tab_content_detila_back edit_form" style="display:none"></div>
                                    <div class="tab_content_detila_back create_form" style="display:none">
                                    	<h4>New App</h4>
                                              <form name="create_app" validate="validate" enctype="multipart/form-data" action="<?php echo base_url('admin/create_app')?>">
                                                  <div class="universal_form_back">
                                                    <div class="clear"></div>
                                                    <input type="text" class="text_box_inner" placeholder="App Name" name="name" id="aname" data-validation-engine="validate[required]"/>
                                                    <input type="text" class="text_box_inner" placeholder="Description" name="description" id="description" data-validation-engine="validate[required]"/>
                                                    <input type="text" class="text_box_inner" placeholder="Order" name="order" id="order" data-validation-engine="validate[required]"/>
                                                     <div class="clear"></div>
                                                    <p class="label">App file</p>
                                                    <div class="clear"></div>
                                                    <input type="file" class="text_box_inner"  name="filename" id="filename" data-validation-engine="validate[required]"/>
                                                    <input type="hidden" name="appfilepath" id="appfilepath" value="">
                                                    <div class="clear"></div>
                                                    <p class="label">Icon</p>
                                                    <div class="clear"></div>
                                                    <input type="file" class="text_box_inner" name="icon" id="icon" data-validation-engine="validate[required]"/>
                                                    <input type="hidden" name="appiconfilepath" value="" id="appiconfilepath">

                                                  </div>



                                                  <div class="clear"></div>
                                                  <input type="submit" class="sub_it_back" value="submit" />
                                                  <input type="button" class="sub_it_back show_list" value="Back to Apps List" onclick="javascript:forceLoad = true;$('li.active').click();"/>
                                             </form>
                                             <script>
                                             	$('#filename').html5Uploader({
													name: 'foo',

													postUrl : '<?php echo base_url('admin/uplodAppfile');?>',

													onClientLoad: function(){
														$('#filename').closest('form').overlay(1);
														$('#filename').closest('form').overlay("Knock Knock...");
													},

													onServerProgress :function(e){},
													onSuccess:function(e, file, response){
														$('#filename').closest('form').overlay("Upload complete");
														var res = JSON.parse(response);
														if(res.error.length == 0){
															$('#appfilepath').val(res.data);
														}else{
															$('#filename').closest('form').overlay(res.error[0]);
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
														$('#icon').closest('form').overlay("Knock Knock...");
													},
													onClientError: function(){
														$('#icon').closest('form').overlay("Browser fails to read the file");
														$('#icon').closest('form').overlay(0,-1);
													},
													onServerError:function(){
														$('#icon').closest('form').overlay("File upload fails,please try again");
														$('#icon').closest('form').overlay(0,-1);
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
                                             </script>
                                    </div>
                                     </div>
</div>
