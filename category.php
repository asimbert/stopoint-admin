<?php

require_once(dirname(__FILE__) . '/inc/core.php');
require_once(dirname(__FILE__) . '/classes/class.table.php');

$table_name = 'event_category';
$table = new table($table_name);
$table->addField('ID', 'id', true, '', ''); 
$table->addField('Name', 'name', true, '', ''); 

// which fields to be searchable
$table->searchable = array('id','name'); 

// checks for delete/add and displays notifications
if (isset($_GET['delete'])) {
	$table->delete_entries('id',sanitize($_GET['delete']));
	$action_msg = show_notification("The entry you selected was deleted succesfully","success",true);
}

if (isset($_GET['added'])   && isset($_GET['sort']))
	$action_msg = show_notification("The entry was added succesfully","success",true);

if (isset($_GET['saved'])  && (!isset($_GET['p']) && !isset($_GET['sort'])))
	$action_msg = show_notification("The entry was edited succesfully","success",true);

// handle multiple actions (delete and edit)

if (isset($_POST['action_submit'])) {
	
	$checkboxes = array();
	$checkboxes = $_POST['checkbox'];
	$action_type = $_POST['action_type'];

	$action_msg = '';
	
	if (count($checkboxes) == 0) 
		$action_msg = show_notification("You have to select at least one entry to peform action","error",true);
	
	if ($action_type == "noaction" && count($checkboxes) > 0) 
		$action_msg = show_notification("You have to select an action for the applied entries","error",true);
		
	if ($action_type == "delete") {
		$table->delete_entries('id',$checkboxes);
		$action_msg = show_notification("The entries you selected were deleted succesfully","success",true);
	}
	
}

// how many rows to display

if (isset($_GET['rows']))
	$table->howmany_rows = sanitize($_GET['rows']);

include(dirname(__FILE__) . '/html/header.php');
include(dirname(__FILE__) . '/html/menu.php');

?>
		<div id="main-content"> <!-- Main Content Section with everything -->
			
			<!-- Page Head -->
			<a href="<?=$_SERVER['PHP_SELF']?>"><h2>Categories</h2></a>
			
			<br>	
			<div style="padding: 4px;width: auto;">
			<?php $table->search();?>
			<?php $table->display_records();?>
			</div>			
			<br>
			<br>
			<br>
			<br>
			<?php if ($action_msg) echo $action_msg; ?>
			<br>		
			<div class="clear"></div> <!-- End .clear -->
			
			<div class="content-box"><!-- Start Content Box -->
				
				<div id="dialog" title="Delete this item?" style="display: none;">
					<p>Are you sure you want to delete this entry?</p>
				</div>
				
				<div class="content-box-header">
					
					<h3>Manage Categories</h3>
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
						
						<a href="<?=edit_page()?>" class="link_button" style="font-size: 20px;">Add entry</a>
						<br>
						<br>
						<form name="actions" method="POST" action="<?=$_SERVER['PHP_SELF']?>">
						<table>
							
							<thead>
								<tr>
								   <th><input class="check-all" type="checkbox" /></th>
								   <?php foreach ($table->columns as $value) { ?>
										<?php if (!strstr($value,'ID')): ?>
									   <th><?=$value?></th>
										<?php endif; ?>
								   <?php } ?>

								   <th>Actions</th>
								</tr>
								
							</thead>
					 
							<tbody>
								<?php while ($row = $table->fetch()) { ?>
								
									
								<tr>
									<td><input type="checkbox" name="checkbox[]" value="<?=$row['id']?>"></td>
									<?php  foreach ($row as $key => $value) { ?>
											<?php if ($key != 'id'): ?>
										  <td>
												<?php echo short_text($value); ?>
										  </td>
										<?php endif; ?>
									<?php } ?>
									
									<td>
										&nbsp;
										 <a href="<?=edit_url(urlencode($table->current_id)); ?>" title="Edit"><img src="<?php echo $base_url; ?>/images/icons/pencil.png" alt="Edit" /></a>
										
										<a class="confirm_link" id="delete_link" href="<?=$_SERVER['PHP_SELF']?>?delete=<?php echo urlencode($table->current_id);?>" title="Delete"><img src="<?php echo $base_url; ?>/images/icons/cross.png" alt="Delete" /></a>
									
									</td>
								</tr>
																
								<?php } ?>
							</tbody>
							
								<tfoot>
									<tr>
										<td colspan="6">
											<div class="bulk-actions align-left">
												<select name="action_type">
													<option value="noaction">Choose an action...</option>
													<option value="delete">Delete</option>
												</select>
												<input type="submit" class="button" value="Apply to selected" name="action_submit"></input>
											</div>
											</form>
											
											<div class="pagination">
												<?php $table->pagination(); ?>
											</div> <!-- End .pagination -->
											<div class="clear"></div>
										</td>
									</tr>
								</tfoot>
							
						</table>
	
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->

			
<?php include("html/footer.php"); ?>