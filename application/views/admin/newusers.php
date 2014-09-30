<div id="tab<?php echo $tabid?>">
                                     <div class="tab_content<?php echo $tabid?>">
                                    <div class="tab_content_detila_back list">
                                            	<ul class="head">
                                                	<li><p>Name</p></li>
                                                    <li class="big"><p>Email</p></li>
                                                    <li><p>Company</p></li>
                                                    <li><p>Discipline</p></li>
                                                    <li><p>Phone</p></li>
                                                    <li><p>Join-date</p></li>
                                                    <li><p>Activation-date</p></li>
                                                    <li class="small"><p>on</p></li>
                                                    <li class="small"><p>View</p></li>
                                                </ul>
                                                <?php if($userdetaiils):
													foreach($userdetaiils as $user):
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
                                                    <li class="small userdetails"><p><a href="javascript:void(0);">Details</a></p></li>
                                                </ul>
													
												
                                                
												<?php 
													endforeach;
													else:
												?>
													<h2 class="data-blank"> No user exists in the system</h2>
												<?php
													endif;
												?>                                                
                                                
                                            </div>
                                        <div class="tab_content_detila_back edit_form" style="display:none"></div>
                                         <div class="tab_content_detila_back create_form" style="display:none">
                                         	
                                         </div>
                                         </div>
                                         
                                         
                                         
</div>