<div id="tab<?php echo $tabid?>">
<?php //v_dump($all_users)?>
                                     <div class="tab_content<?php echo $tabid?>">
                                        <div class="tab_content_detila_back list">
                                        	<h1>Select two users to view their chat history</h1>
                                            
                                            <div class="form_back_container">
                                                <div class="facebook_form">
                                                        <div class="facebook_left">
                                                            <div class="clear"></div>
                                                            <div id="content_3" class="content3">
                                                            <ul class="left_face">
                                                                <li class=" active">
                                                                    <div class="user1">
                                                                            <select name="user1" class="drop_admin" id="user1">
                                                                                <option value="">Select a user</option>
                                                                                <?php foreach($all_users as $user):
                                                                                    echo '<option value="'.$user['id'].'">'.$user['name'].'</option>';			
                                                                                    endforeach;
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                </li>
                                                                <li>
                                                                   <div class="user1">
                                                                        <select name="user2" class="drop_admin" id="user2">
                                                                        <option value="">Select a user</option>
                                                                            <?php foreach($all_users as $user):
                                                                                echo '<option value="'.$user['id'].'">'.$user['name'].'</option>';			
                                                                                endforeach;
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                	<input type="button" id="view" value="View" class="blue-button action">
                                                                </li>
                                                                
                                                                 
                                                                
                                                                
                                                                
                                                            </ul>
                                                            </div>
                                                        </div>
                                                        <div class="facebook_right">
                                                       	 <div id="content_4" class="content4 mesage_history_all">
                                                            <ul class="right_face">
                                                                
                                                                
                                                            </ul>
                                                            </div>
                                                            <div class="clear"></div>
                                                        </div>
                                                    <div class="clear"></div>
                                                </div>
                                            </div>
                                            
                                            
                                            
                                            
                                            
                                            
                                        </div>
                                     </div>
                                      
                                         
                                         
</div>