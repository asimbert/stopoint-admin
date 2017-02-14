<?php

require_once(dirname(__FILE__) . '/inc/core.php');
require_once(dirname(__FILE__) . '/classes/class.table.php');
//`id`, ``, ``, ``, `dob`, `password`, `phone`, `address1`, `address2`, `city`, `state`, `postcode`, `subscribefornewsletter`
$_GET['sort'] = 'id';
$table_name = 'product';
$table = new table($table_name);

if($_GET['catid'] == 'all'){
$table->addField('Id', 'id', false, '', '');
$table->addJoined('Category', 'CategoryId', false, 'productcategory', 'Name', 'id');
$table->addField('Product Code', 'ProductCode', true, '', '');
$table->addJoined('Brand', 'BrandId', false, 'productbrand', 'Name', 'id');      
$table->addField('Model Number', 'ProductModel', true, '', '');
$table->addJoined('Product Family', 'FamilyId', false, 'productfamily', 'Name', 'id');
$table->addJoined('Carrier', 'CarrierId', false, 'carriers', 'Name', 'id');
$table->addField('Good', 'GoodPrice', true, '', '');
$table->addField('Flawless', 'FlawessPrice', true, '', '');
//$table->addField('Thumb', 'image_url', true, '', '');
}


if($_GET['catid'] == 1){
$table->addField('Id', 'id', false, '', '');
$table->addJoined('Category', 'CategoryId', false, 'productcategory', 'Name', 'id');
$table->addField('Product Code', 'ProductCode', true, '', '');
$table->addJoined('Brand', 'BrandId', false, 'productbrand', 'Name', 'id');      
$table->addField('Model Number', 'ProductModel', true, '', '');
$table->addJoined('Product Family', 'FamilyId', false, 'productfamily', 'Name', 'id');
$table->addJoined('Carrier', 'CarrierId', false, 'carriers', 'Name', 'id');
$table->addField('Good', 'GoodPrice', true, '', '');
$table->addField('Flawless', 'FlawessPrice', true, '', '');
//$table->addField('Thumb', 'image_url', true, '', '');
}
if($_GET['catid'] == 2){
$table->addField('Id', 'id', false, '', '');
$table->addJoined('Category', 'CategoryId', false, 'productcategory', 'Name', 'id'); 
$table->addField('Product Code', 'ProductCode', false, '', '');
$table->addJoined('Brand', 'BrandId', false, 'productbrand', 'Name', 'id');     
$table->addField('Order Number', 'OrderNumber', false, '', '');
$table->addField('Model Number', 'ProductModel', true, '', '');
$table->addJoined('Product Family', 'FamilyId', false, 'productfamily', 'Name', 'id');
$table->addField('Screen Size', 'ScreenSize', true, '', '');
$table->addField('Fair', 'AcceptablePrice', true, '', '');
$table->addField('Good', 'GoodPrice', true, '', '');
$table->addField('Flawless', 'FlawessPrice', true, '', '');
//$table->addField('Thumb', 'image_url', true, '', '');
}
if($_GET['catid'] == 3){
$table->addField('Id', 'id', false, '', ''); 
$table->addJoined('Category', 'CategoryId', false, 'productcategory', 'Name', 'id');  
$table->addField('Product Code', 'ProductCode', true, '', '');
$table->addJoined('Brand', 'BrandId', false, 'productbrand', 'Name', 'id');
$table->addJoined('Product Family', 'FamilyId', false, 'productfamily', 'Name', 'id');
$table->addField('Generation', 'Generation', true, '', '');
$table->addJoined('Carrier', 'CarrierId', false, 'carriers', 'Name', 'id');
$table->addField('Good', 'GoodPrice', true, '', '');
$table->addField('Flawless', 'FlawessPrice', true, '', '');
//$table->addField('Thumb', 'image_url', true, '', '');
}
if($_GET['catid'] == 23){
$table->addField('Id', 'id', false, '', '');
$table->addJoined('Category', 'CategoryId', false, 'productcategory', 'Name', 'id'); 
$table->addField('Product Code', 'ProductCode', true, '', ''); 
$table->addJoined('Brand', 'BrandId', false, 'productbrand', 'Name', 'id');
$table->addField('Model Number', 'ProductModel', true, '', '');
$table->addJoined('Product Family', 'FamilyId', false, 'productfamily', 'Name', 'id');
$table->addField('Generation', 'Generation', true, '', '');
$table->addField('Good', 'GoodPrice', true, '', '');
$table->addField('Flawless', 'FlawessPrice', true, '', '');
//$table->addField('Thumb', 'image_url', true, '', '');
}
if($_GET['catid'] == 5){
$table->addField('Id', 'id', false, '', '');
$table->addJoined('Category', 'CategoryId', false, 'productcategory', 'Name', 'id');
$table->addField('Product Code', 'ProductCode', true, '', '');
$table->addJoined('Brand', 'BrandId', false, 'productbrand', 'Name', 'id'); 
$table->addJoined('Product Family', 'FamilyId', false, 'productfamily', 'Name', 'id');     
$table->addField('Product Model', 'ProductModel', true, '', '');
$table->addField('Band', 'Band', true, '', '');
$table->addField('Screen Size', 'ScreenSize', true, '', '');
$table->addField('Good', 'GoodPrice', true, '', '');
$table->addField('Flawless', 'FlawessPrice', true, '', '');
//$table->addField('Thumb', 'image_url', true, '', '');
}
if($_GET['catid'] == 24){
$table->addField('Id', 'id', false, '', '');
$table->addJoined('Category', 'CategoryId', false, 'productcategory', 'Name', 'id');  
$table->addField('Product Code', 'ProductCode', true, '', '');
$table->addJoined('Brand', 'BrandId', false, 'productbrand', 'Name', 'id');    
$table->addField('Model Number', 'ProductModel', true, '', '');
$table->addJoined('Product Family', 'FamilyId', false, 'productfamily', 'Name', 'id');
$table->addField('Generation', 'Generation', true, '', '');
$table->addField('Good', 'GoodPrice', true, '', '');
$table->addField('Flawless', 'FlawessPrice', true, '', '');
//$table->addField('Thumb', 'image_url', true, '', '');
}
/*$table->addField('Thumb', 'image_url', true, '', ''); 
$table->addField('Place', 'place', true, '', ''); */
//$table->addField('Capacity', 'capacity', true, '', ''); 
//$table->addField('Map', 'image_url', true, '', ''); 
// which fields to be searchable
$table->searchable = array('id','ProductModel','ProductCode','GoodPrice','FlawessPrice','AcceptablePrice'); 

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

if (isset($_GET['action_submit'])) {
	
	$checkboxes = array();
	$checkboxes = $_GET['checkbox'];
	$action_type = $_GET['action_type'];
	$catid = $_GET['catid'];

	$action_msg = '';
	
	if (count($checkboxes) == 0) 
		$action_msg = show_notification("You have to select at least one entry to peform action","error",true);
	
	if ($action_type == "noaction" && count($checkboxes) > 0) 
		$action_msg = show_notification("You have to select an action for the applied entries","error",true);
	
	if ($action_type == "delete") {
		
		foreach($checkboxes as $i=>$ev)
		{
			$signups = $db->select("SELECT count(*) As count from signups where event_id = '{$ev}'");
			if($signups['count'] > 0)
				unset($checkboxes[$i]);
		}
		$table->delete_entries('id',$checkboxes);
		$action_msg = show_notification("The entries you selected were deleted succesfully","success",true);
		header("Location: $url?msg=1&catid=$catid&sort=id&desc");
	}
	if ($action_type == "order") {
		foreach ($_POST['events'] as $position => $item) { 
			$db = $table->_db;
			$db->query("UPDATE `$table_name` SET `event_order` = ".intval($position)." WHERE `id` = ".intval($item)); 
		}
		echo show_notification("The order was saved succesfully","success",true);
		exit;
	}
	
}

// how many rows to display

if (isset($_GET['rows']))
	$table->howmany_rows = sanitize($_GET['rows']);

include(dirname(__FILE__) . '/html/header.php');
include(dirname(__FILE__) . '/html/menu.php');

?>
<script type="text/javascript">
$(document).ready(function(){ 

	$(function() {
		var fixHelper = function(e, ui) {
			ui.children().each(function() {
			$(this).width($(this).width());
		});
		return ui;
		};
 
		$('.content-box table tbody').sortable({ helper: fixHelper, opacity: 0.6, cursor: 'move', update: function() {
			var order = $(this).sortable("serialize"); 
			var params = 'action_type=order&action_submit=1&'+order;			
			$.post("events.php", params, function(theResponse){
				$("#main-content").prepend(theResponse);
				$('.notification').fadeTo(3000,1).fadeOut(1000, function(){$(this).remove();});
			});
		}
		}).disableSelection();
	});

});
</script> 
		<div id="main-content"> <!-- Main Content Section with everything -->
			
			<!-- Page Head -->
			
             <?php
					if($_GET['catid'] == 'all'){
					?>
            <h2>All products</h2>
            <?php } ?> 
            
            <?php
					if($_GET['catid'] == 1){
					?>
            <h2>Cell Phones</h2>
            <?php } ?>
            
            <?php
					if($_GET['catid'] == 2){
					?>
            <h2>Computers</h2>
            <?php } ?>
            
            <?php
					if($_GET['catid'] == 3){
					?>
            <h2>Tablets</h2>
            <?php } ?>
            
            <?php
					if($_GET['catid'] == 23){
					?>
            <h2>iPod</h2>
            <?php } ?>
            
            <?php
					if($_GET['catid'] == 5){
					?>
            <h2>Watches</h2>
            <?php } ?>
            
            <?php
					if($_GET['catid'] == 24){
					?>
            <h2>Others</h2>
            <?php } ?>
           
            
			
			<br>	
			<div style="padding: 4px;width: auto;">
			<?php $table->search();?>
			<?php $table->display_records();?>
			</div>			
			<br>
			<br>
			<br>
			<br>
			<?php
			if($_GET['msg'] ==1){
				$action_msg = show_notification("The entries you selected were deleted succesfully","success",true);
				}
			 if ($action_msg || $_GET['msg'] ==1) echo $action_msg; ?>
			<br>		
			<div class="clear"></div> <!-- End .clear -->
			
			<div class="content-box"><!-- Start Content Box -->
				
				<div id="dialog" title="Delete this item?" style="display: none;">
					<p>Are you sure you want to delete this entry?</p>
				</div>
				
				<div class="content-box-header">
					
                    <?php
					if($_GET['catid'] == 'all'){
					?>
					<h3>All Products</h3>
					<?php } ?>
                    
					<?php
					if($_GET['catid'] == 1){
					?>
					<h3>Manage Cellphones</h3>
					<?php } ?>
                    
                    <?php
					if($_GET['catid'] == 2){
					?>
					<h3>Manage Computers</h3>
					<?php } ?>
                    
                    <?php
					if($_GET['catid'] == 3){
					?>
					<h3>Manage Tablets</h3>
					<?php } ?>
                    
                    <?php
					if($_GET['catid'] == 23){
					?>
					<h3>Manage iPod</h3>
					<?php } ?>
                    
                    <?php
					if($_GET['catid'] == 5){
					?>
					<h3>Manage Watches</h3>
					<?php } ?>
                    
                    <?php
					if($_GET['catid'] == 24){
					?>
					<h3>Manage Gadgets</h3>
					<?php } ?>
                    <div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
						<form name="actions" method="GET" action="<?=$_SERVER['PHP_SELF']?> ">
						<table>
							
							<thead>
								<tr>
								   <th><input class="check-all" type="checkbox" /></th>
								   <?php foreach ($table->columns as $value) { ?>
									<?php if (!strstr($value,'Id')): ?>
								   <th><?=$value?></th>
									<?php endif; ?>
								   <?php } ?>

								   
								   <th>Actions</th>
								</tr>
								
							</thead>
					 
							<tbody>
								<?php while ($row = $table->fetch()) { 
								
								//$signups = $db->select("SELECT count(*) As count from signups where event_id = '{$row['id']}'")
								
								if($_GET['catid'] == 'all'){
								
								?>
                                
								
								<tr id="events_<?=$row['id']?>">
									<td><input type="checkbox" name="checkbox[]" value="<?=$row['id']?>"></td>
									<?php  foreach ($row as $key => $value) { ?>
											<?php if ($key != 'id' && $key != 'event_order'): ?>
										  <td>
												<?php
												if($key=='image_url'){ 
												echo '<a href="'.$base_url.'/product_images/'.$value.'" >';
												echo '<img src="'.$base_url.'/product_images/'.$value.'" width="70">';
												echo '</a>';
												//echo html_entity_decode(stripslashes($value));
												}
												else{
												 echo html_entity_decode(stripslashes($value));
												}
												  ?>
										  </td>
										<?php endif; ?>
									<?php } ?>
									
									
									<td>
									
										
                                        
										 <a href="<?php echo edit_url(urlencode($table->current_id).'&catid='.$_GET['catid']); ?>" title="Edit"><img src="<?=$base_url; ?>/images/icons/pencil.png" alt="Edit" /></a>
										
										<a class="confirm_link" id="delete_link" href="<?=$_SERVER['PHP_SELF']?>?delete=<?php echo urlencode($table->current_id);?>&catid=<?php echo $_GET['catid']; ?>" onclick="return confirm('Are you sure?')" title="Delete"><img src="<?php echo $base_url; ?>/images/icons/cross.png" alt="Delete" /></a>
									
									</td>
								</tr>
																
								<?php
								}
								
								if($row['CategoryId'] == 'Cell Phone' && $_GET['catid'] == 1){
								
								?>
                                
								
								<tr id="<?=$row['id']?>">
									<td><input type="checkbox" name="checkbox[]" value="<?=$row['id']?>"></td>
									<?php  foreach ($row as $key => $value) { ?>
											<?php if ($key != 'id' && $key != 'event_order'): ?>
										  <td>
												<?php
												if($key=='image_url'){ 
												echo '<a href="'.$base_url.'/product_images/'.$value.'" >';
												echo '<img src="'.$base_url.'/product_images/'.$value.'" width="70">';
												echo '</a>';
												//echo html_entity_decode(stripslashes($value));
												}
												else{
												 echo html_entity_decode(stripslashes($value));
												}
												  ?>
										  </td>
										<?php endif; ?>
									<?php } ?>
									
									
									<td>
									
										
                                        
										 <a href="<?php echo edit_url(urlencode($table->current_id).'&catid='.$_GET['catid']); ?>" title="Edit"><img src="<?=$base_url; ?>/images/icons/pencil.png" alt="Edit" /></a>
										
										<a class="confirm_link" id="delete_link" href="<?=$_SERVER['PHP_SELF']?>?delete=<?php echo urlencode($table->current_id);?>&catid=<?php echo $_GET['catid']; ?>" onclick="return confirm('Are you sure?')" title="Delete"><img src="<?php echo $base_url; ?>/images/icons/cross.png" alt="Delete" /></a>
									
									</td>
								</tr>
																
								<?php
								}
								 
								 
								 if($row['CategoryId'] == 'Computers' && $_GET['catid'] == 2){
								?>
                                
								
								<tr id="events_<?=$row['id']?>">
									<td><input type="checkbox" name="checkbox[]" value="<?=$row['id']?>"></td>
									<?php  foreach ($row as $key => $value) { ?>
											<?php if ($key != 'id' && $key != 'event_order'): ?>
										  <td>
												<?php
												if($key=='image_url'){ 
												echo '<a href="'.$base_url.'/product_images/'.$value.'" >';
												echo '<img src="'.$base_url.'/product_images/'.$value.'" width="70">';
												echo '</a>';
												//echo html_entity_decode(stripslashes($value));
												}
												else{
												 echo html_entity_decode(stripslashes($value));
												}
												  ?>
										  </td>
										<?php endif; ?>
									<?php } ?>
									
									
									<td>
									
										
                                        
										 <a href="<?php echo edit_url(urlencode($table->current_id).'&catid='.$_GET['catid']); ?>" title="Edit"><img src="<?=$base_url; ?>/images/icons/pencil.png" alt="Edit" /></a>
										
										<a class="confirm_link" id="delete_link" href="<?=$_SERVER['PHP_SELF']?>?delete=<?php echo urlencode($table->current_id);?>&catid=<?php echo $_GET['catid']; ?>" onclick="return confirm('Are you sure?')" title="Delete"><img src="<?php echo $base_url; ?>/images/icons/cross.png" alt="Delete" /></a>
									
									</td>
								</tr>
																
								<?php
								}
								
								if($row['CategoryId'] == 'Tablets' && $_GET['catid'] == 3){
								?>
                                
								
								<tr id="events_<?=$row['id']?>">
									<td><input type="checkbox" name="checkbox[]" value="<?=$row['id']?>"></td>
									<?php  foreach ($row as $key => $value) { ?>
											<?php if ($key != 'id' && $key != 'event_order'): ?>
										  <td>
												<?php
												if($key=='image_url'){ 
												echo '<a href="'.$base_url.'/product_images/'.$value.'" >';
												echo '<img src="'.$base_url.'/product_images/'.$value.'" width="70">';
												echo '</a>';
												//echo html_entity_decode(stripslashes($value));
												}
												else{
												 echo html_entity_decode(stripslashes($value));
												}
												  ?>
										  </td>
										<?php endif; ?>
									<?php } ?>
									
									
									<td>
									
										
                                        
										 <a href="<?php echo edit_url(urlencode($table->current_id).'&catid='.$_GET['catid']); ?>" title="Edit"><img src="<?=$base_url; ?>/images/icons/pencil.png" alt="Edit" /></a>
										
										<a class="confirm_link" id="delete_link" href="<?=$_SERVER['PHP_SELF']?>?delete=<?php echo urlencode($table->current_id);?>&catid=<?php echo $_GET['catid']; ?>" onclick="return confirm('Are you sure?')" title="Delete"><img src="<?php echo $base_url; ?>/images/icons/cross.png" alt="Delete" /></a>
									
									</td>
								</tr>
																
								<?php
								}
								
								if($row['CategoryId'] == 'iPod' && $_GET['catid'] == 23){
								?>
                                
								
								<tr id="events_<?=$row['id']?>">
									<td><input type="checkbox" name="checkbox[]" value="<?=$row['id']?>"></td>
									<?php  foreach ($row as $key => $value) { ?>
											<?php if ($key != 'id' && $key != 'event_order'): ?>
										  <td>
												<?php
												if($key=='image_url'){ 
												echo '<a href="'.$base_url.'/product_images/'.$value.'" >';
												echo '<img src="'.$base_url.'/product_images/'.$value.'" width="70">';
												echo '</a>';
												//echo html_entity_decode(stripslashes($value));
												}
												else{
												 echo html_entity_decode(stripslashes($value));
												}
												  ?>
										  </td>
										<?php endif; ?>
									<?php } ?>
									
									
									<td>
									
										
                                        
										 <a href="<?php echo edit_url(urlencode($table->current_id).'&catid='.$_GET['catid']); ?>" title="Edit"><img src="<?=$base_url; ?>/images/icons/pencil.png" alt="Edit" /></a>
										
										<a class="confirm_link" id="delete_link" href="<?=$_SERVER['PHP_SELF']?>?delete=<?php echo urlencode($table->current_id);?>&catid=<?php echo $_GET['catid']; ?>" onclick="return confirm('Are you sure?')" title="Delete"><img src="<?php echo $base_url; ?>/images/icons/cross.png" alt="Delete" /></a>
									
									</td>
								</tr>
																
								<?php
								}
								
								if($row['CategoryId'] == 'Watches' && $_GET['catid'] == 5){
								?>
                                
								
								<tr id="events_<?=$row['id']?>">
									<td><input type="checkbox" name="checkbox[]" value="<?=$row['id']?>"></td>
									<?php  foreach ($row as $key => $value) { ?>
											<?php if ($key != 'id' && $key != 'event_order'): ?>
										  <td>
												<?php
												if($key=='image_url'){ 
												echo '<a href="'.$base_url.'/product_images/'.$value.'" >';
												echo '<img src="'.$base_url.'/product_images/'.$value.'" width="70">';
												echo '</a>';
												//echo html_entity_decode(stripslashes($value));
												}
												else{
												 echo html_entity_decode(stripslashes($value));
												}
												  ?>
										  </td>
										<?php endif; ?>
									<?php } ?>
									
									
									<td>
									
										
                                        
										 <a href="<?php echo edit_url(urlencode($table->current_id).'&catid='.$_GET['catid']); ?>" title="Edit"><img src="<?=$base_url; ?>/images/icons/pencil.png" alt="Edit" /></a>
										
										<a class="confirm_link" id="delete_link" href="<?=$_SERVER['PHP_SELF']?>?delete=<?php echo urlencode($table->current_id);?>&catid=<?php echo $_GET['catid']; ?>" onclick="return confirm('Are you sure?')" title="Delete"><img src="<?php echo $base_url; ?>/images/icons/cross.png" alt="Delete" /></a>
									
									</td>
								</tr>
																
								<?php
								}
								
								if($row['CategoryId'] == 'Gadgets' && $_GET['catid'] == 24){
								?>
                                
								
								<tr id="events_<?=$row['id']?>">
									<td><input type="checkbox" name="checkbox[]" value="<?=$row['id']?>"></td>
									<?php  foreach ($row as $key => $value) { ?>
											<?php if ($key != 'id' && $key != 'event_order'): ?>
										  <td>
												<?php
												if($key=='image_url'){ 
												echo '<a href="'.$base_url.'/product_images/'.$value.'" >';
												echo '<img src="'.$base_url.'/product_images/'.$value.'" width="70">';
												echo '</a>';
												//echo html_entity_decode(stripslashes($value));
												}
												else{
												 echo html_entity_decode(stripslashes($value));
												}
												  ?>
										  </td>
										<?php endif; ?>
									<?php } ?>
									
									
									<td>
									
										
                                        
										 <a href="<?php echo edit_url(urlencode($table->current_id).'&catid='.$_GET['catid']); ?>" title="Edit"><img src="<?=$base_url; ?>/images/icons/pencil.png" alt="Edit" /></a>
										
										<a class="confirm_link" id="delete_link" href="<?=$_SERVER['PHP_SELF']?>?delete=<?php echo urlencode($table->current_id);?>&catid=<?php echo $_GET['catid']; ?>" onclick="return confirm('Are you sure?')" title="Delete"><img src="<?php echo $base_url; ?>/images/icons/cross.png" alt="Delete" /></a>
									
									</td>
								</tr>
																
								<?php
								}
								
								
								}
								  ?>
							</tbody>
							
								<tfoot>
									<tr>
										<td colspan="6">
											<div class="bulk-actions align-left">
                                            <input class="hidden" type="hidden" name="catid" value="<?=$_GET['catid'] ?>"  />
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