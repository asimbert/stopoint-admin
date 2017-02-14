<?php

require_once(dirname(__FILE__) . '/inc/core.php');
require_once(dirname(__FILE__) . '/classes/class.table.php');

$table_name = 'Coupon';
$table = new table($table_name);
$table->addField('Id', 'id', false, '', '');
$table->addJoined('Coupon Code', 'CouponCode', false, '', '');
$table->addField('Coupon Category', 'FamilyId', true, '', '');    
$table->addField('Coupon Type', 'CoupType', true, '', '');
$table->addField('Coupon Discount', 'Coupdis', true, '', '');
$table->addField('Coupon Price', 'Coupdis_value', true, '', '');

if (isset($_GET['action_submit']))
{
	$checkboxes = array();
	$checkboxes = $_GET['checkbox'];
	$action_type = $_GET['action_type'];
	$catid = $_GET['catid'];
	
	$action_msg = '';
	if (count($checkboxes) == 0) 
	{
		header("Location: $url?chk");
		exit;
	}
	
	if ($action_type == "noaction" && count($checkboxes) > 0) 
	{
		//$action_msg = show_notification("You have to select an action for the applied entries","error",true);
		header("Location: $url?act");
		exit;
	}
		
	if($action_type == "delete") 
	{
		foreach($checkboxes as $i=>$ev)
		{
			$signups = $db->select("SELECT count(*) As count from signups where event_id = '{$ev}'");
			if($signups['count'] > 0)
				unset($checkboxes[$i]);
		}
		$table->delete_entries('id',$checkboxes);
		$action_msg = show_notification("The entries you selected were deleted succesfully","success",true);
		header("Location: $url?msg=1");
	}
}

if (isset($_GET['saved']))
	$action_msg = show_notification("The entry was added succesfully","success",true);

if (isset($_GET['err']))
	$action_msg = show_notification("Something wrong. Try again.","error",true);

if (isset($_GET['del']))
	$action_msg = show_notification("The entry was deleted succesfully","success",true);

if (isset($_GET['chk']))
	$action_msg = show_notification("You have to select at least one entry to peform action","error",true);
if (isset($_GET['act']))
	$action_msg = show_notification("You have to select an action for the applied entries","error",true);

// handle multiple actions (delete and edit)

if(isset($_GET['delete']))
{
	$del=mysql_query("delete from Coupon where Id='".$_GET['delete']."'");
	if($del)
	{
		header("Location:coupon-manage.php?del");
	}
	else
	{
		header("Location:coupon-manage.php?err");
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
		<div id="main-content"> 
			
			<h2>Coupon</h2>
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
					
					<h3>Manage Coupon</h3>
					
                    <div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
						<form name="actions" method="GET" action="<?=$_SERVER['PHP_SELF']?> ">
						<table>
							<thead>
								<tr>
								   <th><input class="check-all" type="checkbox" /></th>
								   <th>Coupon Code</th>
								   <th>Coupon Category</th>
								   <th>Coupon Type</th>
								   <th>Coupon Discount</th>
								   <th>Coupon Price</th>
								   <th>Action</th>
								</tr>
							</thead>
					 
							<tbody>
							<?php  
								while ($row = $table->fetch()) {
								?> <tr id="events_<?=$row['id']?>">
									<td><input type="checkbox" name="checkbox[]" value="<?=$row['id']?>"></td>
								<?
								foreach ($row as $key => $value) {									//echo $value.",".$key.":";
									if ($key != 'id'): ?>
										<td>
											<?php
												if ($key == 'CoupType')
												{
													echo $row['CoupType'];
													if($row['CoupType']=='fixtime')
													{
														$sel="select * from Coupon where id='".$row['id']."'";
														$rs=mysql_fetch_array(mysql_query($sel));
														echo '<br><b>From: </b>'.$rs['valid_frm'];
														echo '<br><b>to: </b>'.$rs['valid_to'];
													}
												}
												else if($key == 'FamilyId')
												{
													$cat=mysql_fetch_assoc(mysql_query("select t1.id, t2.Name from product t1, productfamily t2 WHERE t1.FamilyId=t2.id AND t1.id='".$row['FamilyId']."'"));
													echo $cat['id']."-".$cat['Name'];
												}
												else if($key == 'Coupdis')
												{
													if($row['Coupdis']=='amount')
													{ $cdis='Amount Wise'; }
													if($row['Coupdis']=='per')
													{ $cdis='Percentage Wise';}
													echo $cdis;
												}
												else if($key == 'Coupdis_value')
												{
													if($row['Coupdis']=='amount')
													{ $camt=$row['Coupdis_value']; }
													if($row['Coupdis']=='per')
													{ $camt=$row['Coupdis_value'].' %';}
													echo $camt;
												}
												else
												{
													echo html_entity_decode(stripslashes($value));
												}
											?>
										</td>
									<?php endif; ?>
									
								<?php } ?>
								<td>
									<a class="confirm_link" id="delete_link" href="<?=$_SERVER['PHP_SELF']?>?delete=<?php echo $row['id']?>" onclick="return confirm('Are you sure?')" title="Delete">
									<img src="<?php echo $base_url; ?>/images/icons/cross.png" alt="Delete" />
									</a>
								</td>
								</tr> <?php } ?>
								
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