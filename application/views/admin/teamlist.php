<div id="tab<?php echo $tabid?>">
	<div class="tab_content1 team-List">

			<select name="project_id" id="selected-project-id">
				<?php foreach($projectdetails as $project){ ?>
					<option value="<?php echo $project['id'] ?>" <?php echo ($project_id == $project['id'] ? 'selected="selected"' : '') ?>><?php echo $project['name'] ?></option>
				<?php } ?>
			</select>

			<div class="tab_content_detila_back">
				<form action="<?php echo base_url('/admin/saveTeam') ?>" method="POST" name="team_edit">
					<input type="hidden" value="<?php echo $project_id ?>" id="project_id" name="project_id" />

					<div id="team-member-list">
				<?php foreach($teamdetails as $index => $team_member){ ?>
						<?php $this->load->view("admin/team_member.php", array('team_member' => $team_member, 'index' => ($index + 1), 'designations' => $designations)); ?>
					<?php } ?>
					</div>

					<?php $this->load->view("admin/team_member.php", array('team_member' => array(), 'index' => 0, 'designations' => $designations)); ?>

					<button>Save</button>
				</form>
			</div>
 
 	</div>
	
</div>
