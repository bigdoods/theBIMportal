						<div class="team-member">
							<img class="profile-image" src="/upload/team/<?php echo @$team_member['id'] ?>/profile.jpg" />

							<div class="team-details">
								<input type="hidden" value="<?php echo @$team_member['id'] ?>" id="team_<?php echo $index ?>_id" name="team[<?php echo $index ?>][id]" />

								<div class="field-group">
									<label for="team_<?php echo $index ?>_name">Name</label>
									<input value="<?php echo ucwords(@$team_member['name'])?>" id="team_<?php echo $index ?>_name" name="team[<?php echo $index ?>][name]" />
								</div>

								<div class="field-group">
									<label for="team_<?php echo $index ?>_phone">Phone</label>
									<input value="<?php echo @$team_member['phone']?>" id="team_<?php echo $index ?>_phone" name="team[<?php echo $index ?>][phone]" />
								</div>

								<div class="field-group">
									<label for="team_<?php echo $index ?>_email">Email</label>
									<input value="<?php echo @$team_member['email']?>" id="team_<?php echo $index ?>_email" name="team[<?php echo $index ?>][email]" />
								</div>

								<div class="field-group">
									<label for="team_<?php echo $index ?>_join_date">Joining Date</label>
									<input value="<?php echo @$team_member['join_date']?>" id="team_<?php echo $index ?>_join_date" name="team[<?php echo $index ?>][join_date]" />
								</div>

								<div class="field-group">
									<label for="team_<?php echo $index ?>_activation_date">Activation Date</label>
									<input value="<?php echo @$team_member['activation_date']?>" id="team_<?php echo $index ?>_activation_date" name="team[<?php echo $index ?>][activation_date]" />
								</div>

								<div class="field-group">
									<label for="team_<?php echo $index ?>_company">Company</label>
									<input value="<?php echo ucwords(@$team_member['company'])?>" id="team_<?php echo $index ?>_company" name="team[<?php echo $index ?>][company]" />
								</div>

								<div class="field-group">
									<label for="team_<?php echo $index ?>_designation">Designation</label>
									<select id="team_<?php echo $index ?>_designation" name="team[<?php echo $index ?>][designation]">

										<?php foreach($designations as $designation){ ?>
											<option value="<?php echo $designation ?>" <?php echo( $designation == @$team_member['designation'] ? 'selected="selected"' : '') ?>><?php echo ucwords($designation) ?></option>
										<?php } ?>
									</select>
								</div>

							</div>
						</div>
