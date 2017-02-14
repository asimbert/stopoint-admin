<?php

require_once(dirname(__FILE__) . '/inc/core.php');
require_once(dirname(__FILE__) . '/classes/class.table.php');
//`id`, ``, ``, ``, `dob`, `password`, `phone`, `address1`, `address2`, `city`, `state`, `postcode`, `subscribefornewsletter`
$_GET['sort'] = 'id';
$_GET['sort'] = 'DatePaid';
$table_name = 'ordertrasactions';
$table = new table($table_name);
$table->addField('Id', 'id', false, '', '');  
$table->addField('Order No', 'OrderId', false, '', ''); 

//$table->addJoined('Last Name', 'UserId', false, 'user', 'LastName', 'id'); 
$table->addField('Transaction No', 'TransactionId', false, '', ''); $table->addField('Check', 'ChequeNumber', false, '', ''); 

$table->addField('Amount Paid', 'AmountPaid', false, '',''); 
$table->addJoined('Name', 'UserId', false, 'user', 'FirstName', 'id');  
$table->addField('Payment Method', 'PaymentMethod', true, '', ''); 
$table->addField('Date Paid', 'DatePaid', true, '', ''); 
//$table->addField('Order Date', 'OrderDate', true, '', ''); 

// which fields to be searchable
$table->searchable = array('TransactionId','PaymentMethod','OrderId'); 

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
			<a href=""><h2>Order Transactions</h2></a>
			
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
					
					<h3>Manage Order Transactions</h3>
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
						
						<!--<a href="<? //edit_page()?>" class="link_button" style="font-size: 20px;">Add entry</a>-->
						<br>
						<br>
						<form name="actions" method="GET" action="<?=$_SERVER['PHP_SELF']?> ">
						<table>
							
							<thead>
								<tr>
								   <th><input class="check-all" type="checkbox" /></th>
								   <?php foreach ($table->columns as $value) { ?>
									<?php if (!strstr($value,'Id')): ?>
								   <th><?
								   echo $value;
								   //if($value=='Name'){
									   ?>
                                       <!--<th>Product Model </th>
                                       <th>Product Code </th>-->
                                       <?php
								  // }
								   ?></th>
									<?php endif; ?>
								   <?php } ?>

								   
								   <th>Actions</th>
								</tr>
								
							</thead>
					 
							<tbody>
								<?php while ($row = $table->fetch()) { 
								//$signups = $db->select("SELECT count(*) As count from signups where event_id = '{$row['id']}'")
								
								?>
								
								<tr id="events_<?=$row['id']?>">
									<td><input type="checkbox" name="checkbox[]" value="<?=$row['id']?>"></td>
									<?php  foreach ($row as $key => $value) { ?>
											

											<?php if ($key != 'id'): ?>

                                            <td>

											<?php

 											if($key == "UserId"){
												
												
													$vasname = "SELECT * FROM `user` WHERE FirstName = '".$value."'";

													$rename=mysql_query($vasname);
													
													$wename=mysql_fetch_assoc($rename);
													
													echo html_entity_decode(stripslashes($wename['FirstName']." ".$wename['LastName']));
													
												
												
											}
											elseif($key=='PaymentMethod'){
														if($value==1){
															echo "Paypal";
														}
														else{
															echo "Check";
														}
													}
											elseif($key=='OrderId'){														echo '<a href="order_edit.php?action=edit&id='.$value.'&p=1&key=paid" target="_blank">'.$value.'</a>';													}
												else{

											 ?>

										  

												<?php echo html_entity_decode(stripslashes($value)); ?>

                                                <?php } ?>

										  </td>
												  
												<?php 
												#echo html_entity_decode(stripslashes($value)); ?>
										  </td>
										<?php endif; ?>
									<?php 
									
								 	
									} ?>
									
									
                                    <td>

									

										

										

										 <a href="<?php echo edit_url(urlencode($table->current_id)); ?>" title="Edit"><img src="<?=$base_url; ?>/images/icons/pencil.png" alt="Edit" /></a>

										

										<a class="confirm_link" id="delete_link" href="<?=$_SERVER['PHP_SELF']?>?delete=<?php echo urlencode($table->current_id);?>" onclick="return confirm('Are you sure?')" title="Delete"><img src="<?php echo $base_url; ?>/images/icons/cross.png" alt="Delete" /></a>

									

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