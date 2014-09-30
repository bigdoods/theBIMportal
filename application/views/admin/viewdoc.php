<div id="tab<?php echo $tabid?>">
                                     <div class="tab_content<?php echo $tabid?>">
                                    <div class="tab_content_detila_back list">
                                            	<ul class="head">
                                                	<li><p>Filename</p></li>
                                                    <li class="big"><p>Project name</p></li>
                                                    <li><p>Upload date</p></li>
                                                    <li><p>User</p></li>                                                   
                                                    <li><p>Type</p></li>
                                                    <li><p>Download</p></li>
                                                    <li><p>Preview</p></li>                                                   
                                                    <li><p>Status</p></li>
                                                    <li><p>Ticket</p></li>
                                                </ul>
                                                <?php if($files):
													foreach($files as $file):
												?>
													<ul class="details" rel="user-<?php echo $file['id']?>">
                                                	<li><p><?php echo ucfirst($file['name'])?></p></li>
                                                    <li class="big"><p><?php echo $file['pname']?></p></li>
                                                    <li><p><?php echo date('h:i', $file['uploadtime']).' on ';echo date('m-d-Y', $file['uploadtime'])?></p></li>
                                                    <li><p><?php echo $file['uname']?></p></li>
                                                    <li><p><?php echo $file['doctypename']?></p></li>                                                   
                                                    <li><p><a href="<?php echo base_url('admin/download/'.$file['id'])?>" target="_blank">Download</a></p></li>
                                                     <li><p><img src="<?php echo base_url($file['path'])?>" alt="No preview" width="100" height="100" onError="javascript:$(this).closest('p').html('No preview')"></p></li>
                                                     <li><p><?php echo $file['ticketmessage'];?></p></li>
                                                     <li><p><a class="for_admin_ajax" href="<?php echo base_url('admin/invoke/5/').'?a=ticket_app&f=ticketDetails&id='.$file['ticket_id']?>"><?php echo $file['represent_id'];?></a></p></li>
                                                    
                                                    
                                                </ul>
													
												
                                                
												<?php 
													endforeach;
													else:
												?>
													<h2 class="data-blank"> No document exists in the system</h2>
												<?php
													endif;
												?>                                                
                                                
                                            </div>
                                        <div class="tab_content_detila_back edit_form" style="display:none"></div>
                                         <div class="tab_content_detila_back create_form" style="display:none">
                                         	
                                         </div>
                                         </div>
                                         
                                         
                                         
</div>