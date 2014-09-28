<?php
	//include our library and start drawing the page
	require_once("../../../php_include/functions.php");
	$page_name = "manage_badge";
	print_header($page_name, true);
	print_navbar();
?>

<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<h2>Manage Badge</h2>
		<div>
	</div>
<?php	
	$db = get_db();
	$badge_id = intval(@$_GET['id']);
	
	if (!(canManageUsers())) {
		echo '<div class="row"><div class="col-md-12"><h3 class="text-warning">You do not have permission to be here</h3></div></div>';
			print_footnote();
			echo "</div>";
			print_footer();
			exit();
	}

	if ($badge_id == 0) {
		$query = "INSERT INTO `openrobotics`.`badges` (`category`, `visible`) VALUES ('1', '0');";
		if (!$db->query($query)) {
			echo '<div class="row"><div class="col-md-12"><h3 class="text-danger">DB Error. Sorry...</h3></div></div>';
			print_footnote();
			echo "</div>";
			print_footer();
			exit();
		}
		$badge_id = $db->insert_id;
	}

	$query = "SELECT * FROM `badges` WHERE `id`='$badge_id';";
	
	$result = $db->query($query);
	$post_data = $result->fetch_assoc();

	if ($result->num_rows < 1) {
		echo '<div class="row"><div class="col-md-12"><h3 class="text-danger">Invalid ID!</h3></div></div>';
		print_footnote();
		echo "</div>";
		print_footer();
		exit();
	}
?>
	<div class="row">
		<div class="col-sm-8">
			<p id="status_message"></p>
			<div class="checkbox">
				<label>
					<input type="checkbox" id="form_visible" <?php if (@$post_data['visible'])echo 'checked'?>>
					Enable Badge
				</label>
			</div>
			<div class="form-group">
				<label for="form_name">Name</label>
				<input type="text" class="form-control" placeholder="Name" id="form_name" value="<?php echo @$post_data['name'];?>">
			</div>
			<div class="form-group">
				<label for="form_difficulty">Difficulty</label>
				<input type="text" class="form-control" placeholder="Difficulty" id="form_difficulty" value="<?php echo @$post_data['difficulty'];?>">
			</div>
			<label>Category</label>
			<select class="form-control" id="form_category">
				<?php
					$query = "SELECT * FROM `badge_categories`;";
					$result2 = $db->query($query);
					$num = $result2->num_rows;
					while ($row2 = $result2->fetch_assoc()) {
						echo '<option data-id="'.$row2['id'].'" '.($post_data['category']==$row2['id']?'selected':'').'>'.ucwords(strtolower($row2['category_name'])).'</option>';
					}
				?>
				<option data-id="0" <?php if(!$num) echo "selected";?>>New Category</option>
			</select><br />
			<div class="form-group" id="new_category_group" <?php if ($num) echo 'style="display:none;"'; ?>>
				<label for="form_new_category">New Category</label>
				<input type="text" class="form-control" placeholder="Mechanical" rows="5" id="form_new_category"><?php echo @$post_data['new_category'];?>
			</div>

			<div class="form-group">
				<label for="form_description">Description</label>
				<textarea class="form-control" placeholder="Description" rows="5" id="form_description"><?php echo @$post_data['description'];?></textarea>
			</div>
			<div class="form-group">
				<label for="form_instructions">Instructions</label>
				<textarea class="form-control" placeholder="Instructions" rows="5" id="form_instructions"><?php echo @$post_data['instructions'];?></textarea>
			</div>
			<button class="btn btn-default" id="form_submit">Update</button><br /><br />

			<button class="btn btn-default" id="delete_popover">Delete Post</button><br /><br />
		</div>
		<div class="col-sm-4">
			<p><a href="/badge?id=<?php echo $badge_id;?>"><button class="btn btn-primary">View Badge Page</button></a></p>

			<img class="img-responsive" src="/upload_content/badge_images/large/<?php echo $badge_id; ?>.png" />


			<p>Badge Icon<p>
			<span class="btn btn-default fileinput-button">
				<i class="glyphicon glyphicon-plus"></i>
				<span>Upload...</span>
				<input id="icon_upload" type="file" name="file" data-url="/manage/badges/badge/assets/cgi/badges.php?task=2">
			</span>
			<br />
			<br />
			<div id="icon_upload_progress" class="progress">
				<div class="progress-bar progress-bar-striped active"></div>
			</div>
		</div>
	</div>

<?php
$db->close();
print_footnote();
?>

</div><!-- /.container -->


<?php 
	//print the footer	
	print_footer();
?>