<div id="tab<?php echo $tabid?>">
                                     <div class="tab_content<?php echo $tabid?>">
                                    <div class="tab_content_detila_back list">
                                            	<ul class="head">
                                                	<li><p>Request ID</p></li>
                                                    <li class="big"><p>Project Name</p></li>
                                                    <li><p>Date</p></li>
                                                    <li><p>User</p></li>                                                   
                                                    <li><p>Type</p></li>
                                                    <li><p>Extension</p></li>
                                                    <li><p>Details</p></li>
                                                    <li><p>Ticket</p></li>
                                                </ul>
                                                <?php if($request_details):
													foreach($request_details as $request):
												?>
													<ul class="details" rel="user-<?php echo $request['id']?>">
                                                	<li><p><?php echo $request['represent_id']?></p></li>
                                                    <li class="big"><p><?php echo $request['pname']?></p></li>
                                                    <li><p><?php echo date('H:i', $request['time']).' on ';echo date('m-d-Y', $request['time'])?></p></li>
                                                    <li><p><?php echo $request['uname']?></p></li>
                                                    <li><p><?php echo $request['doctypename']?></p></li> 
                                                    <li><p><?php echo $request['extension_name']?></p></li>                                                   
													<li><p><?php echo html_entity_decode($request['description'], ENT_NOQUOTES)?></p></li>
                                                     <li><p><a class="for_admin_ajax blue-button action" href="<?php echo base_url('admin/invoke/5/').'?a=ticket_app&f=ticketDetails&id='.$request['ticket_id']?>">View</a></p></li>    
                                                </ul>
													
												
                                                
												<?php 
													endforeach;
													else:
												?>
													<h2 class="data-blank"> No request exists in the system</h2>
												<?php
													endif;
												?>                                                
                                                
                                            </div>
                                        <div class="tab_content_detila_back edit_form" style="display:none"></div>
                                         <div class="tab_content_detila_back create_form" style="display:none">
                                         	
                                         </div>
                                         </div>
                                         
                                         
                                         
</div>