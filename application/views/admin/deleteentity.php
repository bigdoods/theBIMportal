<?php
	if($data && !empty($data)){
	?><ul class="head">
        <li><p>Select</p></li>        
        <li><p>Name</p></li>
    </ul>
    <form name="deleteEntity" id="deleteEntity">
	<?php 
		foreach($data as $row){
	?>
		<ul class="details">
            <li><p><input type="checkbox" name="<?php echo $type.'['.$row['id'].']'?>"/></p></li>        
            <li><p><?php echo $row['name'];?></p></li>
        </ul>
	<?php
		}		
	?>
	
    <input type="submit" class="blue-button action" value="Delete">
    </form>
	<?php
	}else{
		echo '<h3>No data found</h3>';
	}
?>