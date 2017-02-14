<?php

require_once(dirname(__FILE__) . '/inc/core.php');

require_once(dirname(__FILE__) . '/classes/class.table.php');

//`id`, ``, ``, ``, `dob`, `password`, `phone`, `address1`, `address2`, `city`, `state`, `postcode`, `subscribefornewsletter`

$_GET['sort'] = 'id';

$_GET['sort'] = 'OrderDate';

$table_name = 'order';

$table = new table($table_name);



$table->addField('Id', 'id', false, '', '');  

$table->addField('Tracking Code', 'TrackingCode', false, '', ''); 
$table->addField('Fedex Tracking Id', 'FedexCode', false, '', ''); 
$table->addField('', 'ProductId', false, '', '');   

$table->addJoined('Name', 'UserId', false, 'user', 'FirstName', 'id');

$table->addField('Condition', 'Condition', false, '', '');

$table->addField('Amount', 'OrderAmount', false, '', '');

$key1 = $_GET['key'];





$vasname1 = "SELECT * FROM `user` WHERE id = '".$_SESSION['userid']."'";

$rename1=mysql_query($vasname1);								

$wename1=mysql_fetch_assoc($rename1);



if($_GET['key'] == 'adjusted' || $_GET['key'] == 'release' || $_GET['key'] == 'all' || $_GET['key'] == 'paid' ){

	

	$table->addField('Adjusted Price', 'AdjustedAmount', false, '', '');

	}

 

$table->addField('Order Status', 'OrderStatus', false, '', '');

$table->addField('Product Serial', 'ProductSerial', false, '', ''); 

$table->addField('Date Created', 'OrderDate', true, '', ''); 

// which fields to be searchable

$table->searchable = array('id','TrackingCode','OrderStatus','UserId','ProductId','OrderAmount','OrderDate'); 



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

		//header('Location: '.$base_url.'/order.php?key=all&'.$action_msg);

		

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

<style>

.ui-datepicker{

	display:none;

	}

</style>

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



<script src="js/jquery.js"></script>



<script src="js/jquery-ui.js"></script>

<script type="text/javascript">



$(function() {

 $('#date_limit').datetimepicker({

  dateFormat: "yy-mm-dd",

 timeFormat: 'HH:mm:ss',

 stepHour: 1,

 stepMinute: 1,

 stepSecond: 1

});

});



$(function() {

 $('#date_limitto').datetimepicker({

  dateFormat: "yy-mm-dd",

 timeFormat: 'HH:mm:ss',

 stepHour: 1,

 stepMinute: 1,

 stepSecond: 1

});

});

</script>



		<div id="main-content"> <!-- Main Content Section with everything -->

			

			<!-- Page Head -->

            <?php

			if($_GET['key'] == 'pending'){

			 ?>

			<a href=""><h2> Pending Orders</h2></a>

			<?php } 

			

			else if($_GET['key'] == 'adjusted'){

			 ?>

			<a href=""><h2>Adjusted Orders</h2></a>

			<?php } 

			

			else if($_GET['key'] == 'received'){

			 ?>

			<a href=""><h2> Received Orders</h2></a>

			<?php }

			

			else if($_GET['key'] == 'release'){

			 ?>

             

			<a href=""><h2> Release Payment Orders</h2></a>

			<?php }

			

			else if($_GET['key'] == 'paid'){

			 ?>

			<a href=""><h2> Paid Orders</h2></a>

			<?php }

			

			

			else if($_GET['key'] == 'cancelled'){

			 ?>

			<a href=""><h2> Cancelled Orders</h2></a>

			<?php }

			

			

			else if($_GET['key'] == 'expired'){

			 ?>

			<a href=""><h2> Expired Orders</h2></a>

			<?php }
			
			else if($_GET['key'] == 'activation'){

			 ?>

			<a href=""><h2> Activation Lock Orders</h2></a>

			<?php }

			

			 ?>

			 

			

			

            

            

			<br>	

			<div style="padding: 4px;width: auto;">

			<?php $table->search_new();?>

            <form action="" name="exportcsv" method="post" style="float:left;">

            <input type="submit" name="exportcsv" class="button" value="Export CSV" style="margin-top:7px;" />

			</form>

			<?php $table->display_records();?>

			</div>			

			<br>

			<br>

			<br>

			<br>

			<?php if ($action_msg) echo $action_msg; ?>

			<br>		



			<div class="clear"></div> <!-- End .clear -->

			<?php

if($_GET['success']== "paypal"){

?>

 <div class="alert alert-success">

    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

    <strong>Success!</strong> Your payment has been successfully processed.

   </div>

<?php

}

?>



<?php

if($_GET['success']== "error"){

?>

 <div class="alert alert-success">

    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

    <strong>Error!</strong> Your payment has not been successfully processed, please check your balance or account credentials.

   </div>

<?php

}

?>

			<div class="content-box"><!-- Start Content Box -->

				

				<div id="dialog" title="Delete this item?" style="display: none;">



					<p>Are you sure you want to delete this entry?</p>



				</div>

				

				<div class="content-box-header">

					

					

                    

                    <?php

			if($_GET['key'] == 'pending'){

			 ?>

			<h3> Manage Pending Orders</h3>

			<?php } 

			

			else if($_GET['key'] == 'adjusted'){

			 ?>

			<h3>Manage Adjusted Orders</h3>

			<?php } 

			

			else if($_GET['key'] == 'all'){

			 ?>

			<h3>Manage Orders</h3>

			<?php } 

			

			else if($_GET['key'] == 'received'){

			 ?>

			<h3> Manage Received Orders</h3>

			<?php }

			

			else if($_GET['key'] == 'release'){

			 ?>

             

			<h3> Manage Release Payment Orders</h3></a>

			<?php }

			

			else if($_GET['key'] == 'paid'){

			 ?>

			<h3> Manage Paid Orders</h3>

			<?php }

			

			

			else if($_GET['key'] == 'cancelled'){

			 ?>

			<h3> Manage Cancelled Orders</h3>

			<?php }

			

			

			else if($_GET['key'] == 'expired'){

			 ?>

			<h3> Manage Expired Orders</h3>

			<?php }

			

			 ?>

					<div class="clear"></div>

					

				</div> <!-- End .content-box-header -->

				

				<div class="content-box-content">

						

						<!--<a href="<? //edit_page()?>" class="link_button" style="font-size: 20px;">Add entry</a>-->

						<br>

						<br>

						<form name="actions" method="GET" action="<?=$_SERVER['PHP_SELF']?> " id="formoverflow" style="overflow:scroll;">

                       

						<table>

							

							<thead>

								<tr>

								   <th><input class="check-all" type="checkbox" /></th>

								   <?php foreach ($table->columns as $value) { ?>

									<?php if (!strstr($value,'Id')): ?>

								   <th>

								   <?

								   if($value==''){

									   ?>

                                       Product Code

                                       

                                       <th>Description </th>

                                       <?php

								   }

								   ?>

								   <?php

								   echo $value;

								   ?></th>

									<?php endif; ?>

								   <?php } ?>



								   

								   <th>Actions</th>

								</tr>

								

							</thead>

					 

							<tbody>

								<?php 

								if($_GET['key'] == 'pending'){

									$where = 'OrderStatus = 1';

									}

								else if($_GET['key'] == 'received'){

									$where = 'OrderStatus = 2';

									}

									

								else if($_GET['key'] == 'adjusted'){

									$where = 'OrderStatus = 3';

									}

									

								else if($_GET['key'] == 'returned'){

									$where = 'OrderStatus = 4';

									}
									
								else if($_GET['key'] == 'returncompleted'){

									$where = 'OrderStatus = 9';

									}
									
								else if($_GET['key'] == 'activation'){

									$where = 'OrderStatus = 10';

									}

									

									else if($_GET['key'] == 'release'){

									$where = 'OrderStatus = 5';

									}

								else if($_GET['key'] == 'paid'){

									$where = 'OrderStatus = 6';

									}

									

								else if($_GET['key'] == 'cancelled'){

									$where = 'OrderStatus = 7';

									}

									

									else if($_GET['key'] == 'expired'){

									$where = 'OrderStatus = 8';

									}

									

									else{

									//	$where = 'id <> 1';

										}

								while (

								$row = $table->fetch($where)) { 

								//$signups = $db->select("SELECT count(*) As count from signups where event_id = '{$row['id']}'")

								

								if($_GET['key'] == 'pending' && $row['OrderStatus'] == 1){

									

								?>

								

								<tr id="events_<?=$row['id']?>">

									<td><input type="checkbox" name="checkbox[]" value="<?=$row['id']?>"></td>

									<?php  foreach ($row as $key => $value) {

										

										

										 ?>

											



											<?php if ($key != 'id'): ?>



                                            <td>



											<?php



 											if($key == "UserId"){

												

												

													$vasname = "SELECT * FROM `user` WHERE FirstName = '".$value."'";



													$rename=mysql_query($vasname);

													

													$wename=mysql_fetch_assoc($rename);

													

													echo html_entity_decode(stripslashes($wename['FirstName'].' '.$wename['LastName']));

													

												

												

											}

 

 

											elseif($key == "OrderStatus"){



												?>



                                                



                                                <?php



												if($value == 1){



													



													echo html_entity_decode(stripslashes('Pending'));



													



													}



												?>



                                                



                                                <?php



												}



												else if($key == "ProductId"){

													

													?>

                                                    

                                                  

													

                                                    <?php

													$vasproduct = "SELECT * FROM `product` WHERE ProductCode = ".$value;



													$reproduct=mysql_query($vasproduct);

													

													$weproduct=mysql_fetch_assoc($reproduct);

                                                    echo html_entity_decode(stripslashes($weproduct['ProductCode']));

													

													?>

                                                    </td>

                                                    <!--<td>

                                                    <?php

                                                    //echo html_entity_decode(stripslashes($weproduct['Generation']));

                                                    ?>

                                                    </td>-->

                                                    <td>

                                                    <?php

                                                    echo html_entity_decode(stripslashes($weproduct['Description']));

                                                    ?>

                                                    </td>

                                                    <?php

													}

													

													else if($key== "Condition"){

														if($value == 1){

															echo "Fair";

														}

														else if($value == 2){

															echo "Good";

														}

														

													else if($value == 4){

															echo "Broken(Yes)";

														}

														else if($value == 5){

															echo "Broken(No)";

														}

														else{

															echo "Flawless";

														}

													}

													

													else if($key== "OrderDate"){

						

													$value = date('m/d/Y h:i A', strtotime($value));

													echo $value;

													}



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



									



										



										



										 <a href="<?php echo edit_url(urlencode($table->current_id)).'&key=pending'; ?>" title="Edit"><img src="<?=$base_url; ?>/images/icons/pencil.png" alt="Edit" /></a>



										

<?php

if($wename1['UserType']=='Super Admin'){

?>

										<a class="confirm_link" id="delete_link" href="<?=$_SERVER['PHP_SELF']?>?delete=<?php echo urlencode($table->current_id);?>&key=pending&desc" onclick="return confirm('Are you sure?')" title="Delete"><img src="<?php echo $base_url; ?>/images/icons/cross.png" alt="Delete" /></a>

<?php

}

?>

									



									</td>



								</tr>

																

								<?php

								}

								

								else if($_GET['key'] == 'received' && $row['OrderStatus'] == 2){

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

													

													echo html_entity_decode(stripslashes($wename['FirstName'].' '.$wename['LastName']));

													

												

												

											}

 

 

											elseif($key == "OrderStatus"){



													echo html_entity_decode(stripslashes('Received'));



												?>



                                                



                                                <?php



												}



												else if($key == "ProductId"){

													

													?>

                                                    

                                                  

													

                                                    <?php

													$vasproduct = "SELECT * FROM `product` WHERE ProductCode = ".$value;



													$reproduct=mysql_query($vasproduct);

													

													$weproduct=mysql_fetch_assoc($reproduct);

													

													echo html_entity_decode(stripslashes($weproduct['ProductCode']));

													

													?>

                                                    </td>

                                                    <!--<td>

                                                    <?php

                                                    //echo html_entity_decode(stripslashes($weproduct['Generation']));

                                                    ?>

                                                    </td>-->

                                                    <td>

                                                    <?php

                                                    echo html_entity_decode(stripslashes($weproduct['Description']));

                                                    ?>

                                                    </td>

                                                    <?php

													}

													

													else if($key== "Condition"){

														if($value == 1){

															echo "Fair";

														}

														else if($value == 2){

															echo "Good";

														}

														else if($value == 4){

															echo "Broken(Yes)";

														}

														else if($value == 5){

															echo "Broken(No)";

														}

														else{

															echo "Flawless";

														}

													}

													

													elseif($key== "OrderDate"){

						

													$value = date('m/d/Y h:i A', strtotime($value));

													echo $value;

													}

													

													else{

						

											 		?>



										  



												<?php echo html_entity_decode(stripslashes($value));

												

													}?>



										  </td>

												  

												<?php 

												#echo html_entity_decode(stripslashes($value)); ?>

										  </td>

										<?php endif; ?>

									<?php 

									

								 	

									} ?>

									

									

                                    <td>



									



										



										



										 <a href="<?php echo edit_url(urlencode($table->current_id)).'&key=received'; ?>" title="Edit"><img src="<?=$base_url; ?>/images/icons/pencil.png" alt="Edit" /></a>



										

<?php

if($wename1['UserType']=='Super Admin'){

?>

										<a class="confirm_link" id="delete_link" href="<?=$_SERVER['PHP_SELF']?>?delete=<?php echo urlencode($table->current_id);?>&key=received&desc" onclick="return confirm('Are you sure?')" title="Delete"><img src="<?php echo $base_url; ?>/images/icons/cross.png" alt="Delete" /></a>

<?php

}

?>

									



									</td>



								</tr>

																

								<?php

								}

								

								else if($_GET['key'] == 'adjusted' && $row['OrderStatus'] == 3){

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

													

													echo html_entity_decode(stripslashes($wename['FirstName'].' '.$wename['LastName']));

													

												

												

											}

 

 

											else if($key == "OrderStatus"){



													echo html_entity_decode(stripslashes('Adjusted Price'));



												?>



                                                



                                                <?php



												}



												else if($key == "ProductId"){

													

													?>

                                                    

                                                  

													

                                                    <?php

													$vasproduct = "SELECT * FROM `product` WHERE ProductCode = ".$value;



													$reproduct=mysql_query($vasproduct);

													

													$weproduct=mysql_fetch_assoc($reproduct);

													

													echo html_entity_decode(stripslashes($weproduct['ProductCode']));

													

													?>

                                                    </td>

                                                    <!--<td>

                                                    <?php

                                                    //echo html_entity_decode(stripslashes($weproduct['Generation']));

                                                    ?>

                                                    </td>-->

                                                    <td>

                                                    <?php

                                                    echo html_entity_decode(stripslashes($weproduct['Description']));

                                                    ?>

                                                    </td>

                                                    <?php

													}

													

													else if($key== "Condition"){

														if($value == 1){

															echo "Fair";

														}

														else if($value == 2){

															echo "Good";

														}

														

														else if($value == 4){

															echo "Broken(Yes)";

														}

														else if($value == 5){

															echo "Broken(No)";

														}

														else{

															echo "Flawless";

														}

													}



												else if($key== "OrderDate"){

						

												$value = date('m/d/Y h:i A', strtotime($value));

												echo $value;

												}

												

											 else {

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



									



										



										



										 <a href="<?php echo edit_url(urlencode($table->current_id)).'&key='.$key1; ?>" title="Edit"><img src="<?=$base_url; ?>/images/icons/pencil.png" alt="Edit" /></a>



										

<?php

if($wename1['UserType']=='Super Admin'){

?>

										<a class="confirm_link" id="delete_link" href="<?=$_SERVER['PHP_SELF']?>?delete=<?php echo urlencode($table->current_id);?>&key=<?=$key1?>&desc" onclick="return confirm('Are you sure?')" title="Delete"><img src="<?php echo $base_url; ?>/images/icons/cross.png" alt="Delete" /></a>

<?php

}

?>

									



									</td>



								</tr>

																

								<?php

								}

								

								else if($_GET['key'] == 'returned' && $row['OrderStatus'] == 4){

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

													

													echo html_entity_decode(stripslashes($wename['FirstName'].' '.$wename['LastName']));

													

												

												

											}

 

 

											else if($key == "OrderStatus"){



													echo html_entity_decode(stripslashes('Returned Order'));



												?>



                                                



                                                <?php



												}



												else if($key == "ProductId"){

													

													?>

                                                    

                                                  

													

                                                    <?php

													$vasproduct = "SELECT * FROM `product` WHERE ProductCode = ".$value;



													$reproduct=mysql_query($vasproduct);

													

													$weproduct=mysql_fetch_assoc($reproduct);

													

													echo html_entity_decode(stripslashes($weproduct['ProductCode']));

													

													?>

                                                    </td>

                                                   <!-- <td>

                                                    <?php

                                                    //echo html_entity_decode(stripslashes($weproduct['Generation']));

                                                    ?>

                                                    </td>-->

                                                    <td>

                                                    <?php

                                                    echo html_entity_decode(stripslashes($weproduct['Description']));

                                                    ?>

                                                    </td>

                                                    <?php

													}

													

													else if($key== "Condition"){

														if($value == 1){

															echo "Fair";

														}

														else if($value == 2){

															echo "Good";

														}

														else if($value == 4){

															echo "Broken(Yes)";

														}

														else if($value == 5){

															echo "Broken(No)";

														}

														else{

															echo "Flawless";

														}

													}



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



									



										



										



										 <a href="<?php echo edit_url(urlencode($table->current_id)).'&key='.$key1; ?>" title="Edit"><img src="<?=$base_url; ?>/images/icons/pencil.png" alt="Edit" /></a>



										

<?php

if($wename1['UserType']=='Super Admin'){

?>

										<a class="confirm_link" id="delete_link" href="<?=$_SERVER['PHP_SELF']?>?delete=<?php echo urlencode($table->current_id);?>&key=<?=$key1?>&desc" onclick="return confirm('Are you sure?')" title="Delete"><img src="<?php echo $base_url; ?>/images/icons/cross.png" alt="Delete" /></a>

<?php

}

?>

									



									</td>



								</tr>

																

								<?php

								}

								

								else if($_GET['key'] == 'release' && $row['OrderStatus'] == 5){

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

													

													echo html_entity_decode(stripslashes($wename['FirstName'].' '.$wename['LastName']));

													

												

												

											}

 

 

											else if($key == "OrderStatus"){



												?>



                                                



                                                <?php



												if($value == 5){



													



													echo html_entity_decode(stripslashes('Release Payment'));



													



													}



												?>



                                                



                                                <?php



												}



												else if($key == "ProductId"){

													

													?>

                                                    

                                                  

													

                                                    <?php

													$vasproduct = "SELECT * FROM `product` WHERE ProductCode = ".$value;



													$reproduct=mysql_query($vasproduct);

													

													$weproduct=mysql_fetch_assoc($reproduct);

													

													echo html_entity_decode(stripslashes($weproduct['ProductCode']));

													

													?>

                                                    </td>

                                                    <!--<td>

                                                    <?php

                                                    //echo html_entity_decode(stripslashes($weproduct['Generation']));

                                                    ?>

                                                    </td>-->

                                                    <td>

                                                    <?php

                                                    echo html_entity_decode(stripslashes($weproduct['Description']));

                                                    ?>

                                                    </td>

                                                    <?php

													}

													

													else if($key== "Condition"){

														if($value == 1){

															echo "Fair";

														}

														else if($value == 2){

															echo "Good";

														}

														else if($value == 4){

															echo "Broken(Yes)";

														}

														else if($value == 5){

															echo "Broken(No)";

														}

														else{

															echo "Flawless";

														}

													}

													

													else if($key== "OrderDate"){

						

													$value = date('m/d/Y h:i A', strtotime($value));

													echo $value;

													}

											 

											 else {

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



									



										



										



										 <a href="<?php echo edit_url(urlencode($table->current_id)).'&key='.$key1; ?>" title="Edit"><img src="<?=$base_url; ?>/images/icons/pencil.png" alt="Edit" /></a>



										

<?php

if($wename1['UserType']=='Super Admin'){

?>

										<a class="confirm_link" id="delete_link" href="<?=$_SERVER['PHP_SELF']?>?delete=<?php echo urlencode($table->current_id);?>&key=<?=$key1?>&desc" onclick="return confirm('Are you sure?')" title="Delete"><img src="<?php echo $base_url; ?>/images/icons/cross.png" alt="Delete" /></a>

<?php

}

?>

									



									</td>



								</tr>

																

								<?php

								}

								

								else if($_GET['key'] == 'paid' && $row['OrderStatus'] == 6){

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

													

													echo html_entity_decode(stripslashes($wename['FirstName'].' '.$wename['LastName']));

													

												

												

											}

 

 

											else if($key == "OrderStatus"){



												?>



                                                



                                                <?php



												if($value == 6){



													



													echo html_entity_decode(stripslashes('Paid'));



													



													}



												?>



                                                



                                                <?php



												}



												else if($key == "ProductId"){

													

													?>

                                                    

                                                  

													

                                                    <?php

													$vasproduct = "SELECT * FROM `product` WHERE ProductCode = ".$value;



													$reproduct=mysql_query($vasproduct);

													

													$weproduct=mysql_fetch_assoc($reproduct);

													

													echo html_entity_decode(stripslashes($weproduct['ProductCode']));

													

													?>

                                                    </td>

                                                    <!--<td>

                                                    <?php

                                                    //echo html_entity_decode(stripslashes($weproduct['Generation']));

                                                    ?>

                                                    </td>-->

                                                    <td>

                                                    <?php

                                                    echo html_entity_decode(stripslashes($weproduct['Description']));

                                                    ?>

                                                    </td>

                                                    <?php

													}

													

													else if($key== "Condition"){

														if($value == 1){

															echo "Fair";

														}

														else if($value == 2){

															echo "Good";

														}

														else if($value == 4){

															echo "Broken(Yes)";

														}

														else if($value == 5){

															echo "Broken(No)";

														}

														else{

															echo "Flawless";

														}

													}



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



									



										



										



										 <a href="<?php echo edit_url(urlencode($table->current_id)).'&key='.$key1; ?>" title="Edit"><img src="<?=$base_url; ?>/images/icons/pencil.png" alt="Edit" /></a>



										

<?php

if($wename1['UserType']=='Super Admin'){

?>

										<a class="confirm_link" id="delete_link" href="<?=$_SERVER['PHP_SELF']?>?delete=<?php echo urlencode($table->current_id);?>&key=<?=$key1?>&desc" onclick="return confirm('Are you sure?')" title="Delete"><img src="<?php echo $base_url; ?>/images/icons/cross.png" alt="Delete" /></a>

<?php

}

?>

									



									</td>



								</tr>

																

								<?php

								}

								

								else if($_GET['key'] == 'cancelled' && $row['OrderStatus'] == 7){

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

													

													echo html_entity_decode(stripslashes($wename['FirstName'].' '.$wename['LastName']));

													

												

												

											}

 

 

											else if($key == "OrderStatus"){



												?>



                                                



                                                <?php



												if($value == 7){



													



													echo html_entity_decode(stripslashes('Cancelled'));



													



													}



												?>



                                                



                                                <?php



												}



												else if($key == "ProductId"){

													

													?>

                                                    

                                                  

													

                                                    <?php

													$vasproduct = "SELECT * FROM `product` WHERE ProductCode = ".$value;



													$reproduct=mysql_query($vasproduct);

													

													$weproduct=mysql_fetch_assoc($reproduct);

													

													echo html_entity_decode(stripslashes($weproduct['ProductCode']));

													

													?>

                                                    </td>

                                                    <!--<td>

                                                    <?php

                                                    //echo html_entity_decode(stripslashes($weproduct['Generation']));

                                                    ?>

                                                    </td>-->

                                                    <td>

                                                    <?php

                                                    echo html_entity_decode(stripslashes($weproduct['Description']));

                                                    ?>

                                                    </td>

                                                    <?php

													}

													

													else if($key== "Condition"){

														if($value == 1){

															echo "Fair";

														}

														else if($value == 2){

															echo "Good";

														}

														else if($value == 4){

															echo "Broken(Yes)";

														}

														else if($value == 5){

															echo "Broken(No)";

														}

														else{

															echo "Flawless";

														}

													}



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



									



										



										



										 <a href="<?php echo edit_url(urlencode($table->current_id)).'&key='.$key1; ?>" title="Edit"><img src="<?=$base_url; ?>/images/icons/pencil.png" alt="Edit" /></a>



										

<?php

if($wename1['UserType']=='Super Admin'){

?>

										<a class="confirm_link" id="delete_link" href="<?=$_SERVER['PHP_SELF']?>?delete=<?php echo urlencode($table->current_id);?>&key=<?=$key1?>&desc" onclick="return confirm('Are you sure?')" title="Delete"><img src="<?php echo $base_url; ?>/images/icons/cross.png" alt="Delete" /></a>

<?php

}

?>

									



									</td>



								</tr>

																

								<?php

								}

							

								

								else if($_GET['key'] == 'expired' && $row['OrderStatus'] == 8){

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

													

													echo html_entity_decode(stripslashes($wename['FirstName'].' '.$wename['LastName']));

													

												

												

											}

 

 

											else if($key == "OrderStatus"){



												?>



                                                



                                                <?php



												if($value == 8){



													



													echo html_entity_decode(stripslashes('Expired'));



													



													}



												?>



                                                



                                                <?php



												}



												else if($key == "ProductId"){

													

													?>

                                                    

                                                  

													

                                                    <?php

													$vasproduct = "SELECT * FROM `product` WHERE ProductCode = ".$value;



													$reproduct=mysql_query($vasproduct);

													

													$weproduct=mysql_fetch_assoc($reproduct);

													

													echo html_entity_decode(stripslashes($weproduct['ProductCode']));

													

													?>

                                                    </td>

                                                    <!--<td>

                                                    <?php

                                                    //echo html_entity_decode(stripslashes($weproduct['Generation']));

                                                    ?>

                                                    </td>-->

                                                    <td>

                                                    <?php

                                                    echo html_entity_decode(stripslashes($weproduct['Description']));

                                                    ?>

                                                    </td>

                                                    <?php

													}

													else if($key== "Condition"){

														if($value == 1){

															echo "Fair";

														}

														else if($value == 2){

															echo "Good";

														}

														else if($value == 4){

															echo "Broken(Yes)";

														}

														else if($value == 5){

															echo "Broken(No)";

														}

														else{

															echo "Flawless";

														}

													}



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



									



										



										



										 <a href="<?php echo edit_url(urlencode($table->current_id))."&key=".$key1; ?>" title="Edit"><img src="<?=$base_url; ?>/images/icons/pencil.png" alt="Edit" /></a>



										

<?php

if($wename1['UserType']=='Super Admin'){

?>

										<a class="confirm_link" id="delete_link" href="<?=$_SERVER['PHP_SELF']?>?delete=<?php echo urlencode($table->current_id);?>&key=<?=$key1?>&desc" onclick="return confirm('Are you sure?')" title="Delete"><img src="<?php echo $base_url; ?>/images/icons/cross.png" alt="Delete" /></a>

<?php

}

?>

									



									</td>



								</tr>

																

								<?php

								}
								
								
								
								else if($_GET['key'] == 'returncompleted' && $row['OrderStatus'] == 9){

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

													

													echo html_entity_decode(stripslashes($wename['FirstName'].' '.$wename['LastName']));

													

												

												

											}

 

 

											else if($key == "OrderStatus"){



												?>



                                                



                                                <?php



												if($value == 9){



													



													echo html_entity_decode(stripslashes('Return Completed'));



													



													}



												?>



                                                



                                                <?php



												}



												else if($key == "ProductId"){

													

													?>

                                                    

                                                  

													

                                                    <?php

													$vasproduct = "SELECT * FROM `product` WHERE ProductCode = ".$value;



													$reproduct=mysql_query($vasproduct);

													

													$weproduct=mysql_fetch_assoc($reproduct);

													

													echo html_entity_decode(stripslashes($weproduct['ProductCode']));

													

													?>

                                                    </td>

                                                    <!--<td>

                                                    <?php

                                                    //echo html_entity_decode(stripslashes($weproduct['Generation']));

                                                    ?>

                                                    </td>-->

                                                    <td>

                                                    <?php

                                                    echo html_entity_decode(stripslashes($weproduct['Description']));

                                                    ?>

                                                    </td>

                                                    <?php

													}

													else if($key== "Condition"){

														if($value == 1){

															echo "Fair";

														}

														else if($value == 2){

															echo "Good";

														}

														else if($value == 4){

															echo "Broken(Yes)";

														}

														else if($value == 5){

															echo "Broken(No)";

														}

														else{

															echo "Flawless";

														}

													}



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



									



										



										



										 <a href="<?php echo edit_url(urlencode($table->current_id))."&key=".$key1; ?>" title="Edit"><img src="<?=$base_url; ?>/images/icons/pencil.png" alt="Edit" /></a>



										

<?php

if($wename1['UserType']=='Super Admin'){

?>

										<a class="confirm_link" id="delete_link" href="<?=$_SERVER['PHP_SELF']?>?delete=<?php echo urlencode($table->current_id);?>&key=<?=$key1?>&desc" onclick="return confirm('Are you sure?')" title="Delete"><img src="<?php echo $base_url; ?>/images/icons/cross.png" alt="Delete" /></a>

<?php

}

?>

									



									</td>



								</tr>

																

								<?php

								}
								
								
								
								else if($_GET['key'] == 'activation' && $row['OrderStatus'] == 10){

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

													

													echo html_entity_decode(stripslashes($wename['FirstName'].' '.$wename['LastName']));

													

												

												

											}

 

 

											else if($key == "OrderStatus"){



												?>



                                                



                                                <?php



												if($value == 10){



													



													echo html_entity_decode(stripslashes('Activation Lock'));



													



													}



												?>



                                                



                                                <?php



												}



												else if($key == "ProductId"){

													

													?>

                                                    

                                                  

													

                                                    <?php

													$vasproduct = "SELECT * FROM `product` WHERE ProductCode = ".$value;



													$reproduct=mysql_query($vasproduct);

													

													$weproduct=mysql_fetch_assoc($reproduct);

													

													echo html_entity_decode(stripslashes($weproduct['ProductCode']));

													

													?>

                                                    </td>

                                                    <!--<td>

                                                    <?php

                                                    //echo html_entity_decode(stripslashes($weproduct['Generation']));

                                                    ?>

                                                    </td>-->

                                                    <td>

                                                    <?php

                                                    echo html_entity_decode(stripslashes($weproduct['Description']));

                                                    ?>

                                                    </td>

                                                    <?php

													}

													else if($key== "Condition"){

														if($value == 1){

															echo "Fair";

														}

														else if($value == 2){

															echo "Good";

														}

														else if($value == 4){

															echo "Broken(Yes)";

														}

														else if($value == 5){

															echo "Broken(No)";

														}

														else{

															echo "Flawless";

														}

													}



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



									



										



										



										 <a href="<?php echo edit_url(urlencode($table->current_id))."&key=".$key1; ?>" title="Edit"><img src="<?=$base_url; ?>/images/icons/pencil.png" alt="Edit" /></a>



										

<?php

if($wename1['UserType']=='Super Admin'){

?>

										<a class="confirm_link" id="delete_link" href="<?=$_SERVER['PHP_SELF']?>?delete=<?php echo urlencode($table->current_id);?>&key=<?=$key1?>&desc" onclick="return confirm('Are you sure?')" title="Delete"><img src="<?php echo $base_url; ?>/images/icons/cross.png" alt="Delete" /></a>

<?php

}

?>

									



									</td>



								</tr>

																

								<?php

								}

								

								else if($_GET['key'] == 'all'){

								

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

													

													echo html_entity_decode(stripslashes($wename['FirstName'].' '.$wename['LastName']));

													

												

												

											}

 

 

											else if($key == "OrderStatus"){



												?>



                                                



                                                <?php



												if($value == 1){



													



													echo html_entity_decode(stripslashes('Pending'));



													



													} 

													

													if($value == 2){



													



													echo html_entity_decode(stripslashes('Received'));



													



													}

													

													

													if($value == 3){



													



													echo html_entity_decode(stripslashes('Adjusted Price'));



													



													}

													

													

													if($value == 4){

													

													
													


													echo html_entity_decode(stripslashes('Returned Order'));



													



													}

													

													if($value == 5){



													



													echo html_entity_decode(stripslashes('Release Payment'));



													



													}

													

													if($value == 6){



													



													echo html_entity_decode(stripslashes('Paid'));



													



													}

													

													if($value == 7){



													



													echo html_entity_decode(stripslashes('Cancelled'));



													



													}

													

													if($value == 9){



													



													echo html_entity_decode(stripslashes('Return Completed'));



													



													}
													
													if($value == 8){



													



													echo html_entity_decode(stripslashes('Expired'));



													



													}
													
													if($value == 10){



													



													echo html_entity_decode(stripslashes('Activation Locked'));



													



													}



												?>



                                                



                                                <?php



												}



												else if($key == "ProductId"){

													

													?>

                                                    

                                                  

													

                                                    <?php

													$vasproduct = "SELECT * FROM `product` WHERE ProductCode = ".$value;



													$reproduct=mysql_query($vasproduct);

													

													$weproduct=mysql_fetch_assoc($reproduct);

													

													echo html_entity_decode(stripslashes($weproduct['ProductCode']));

													

													?>

                                                    </td>

                                                    <!--<td>

                                                    <?php

                                                    //echo html_entity_decode(stripslashes($weproduct['Generation']));

                                                    ?>

                                                    </td>-->

                                                    <td>

                                                    <?php

                                                    echo html_entity_decode(stripslashes($weproduct['Description']));

                                                    ?>

                                                    </td>

                                                    <?php

													}

													

													else if($key== "Condition"){

														if($value == 1){

															echo "Fair";

														}

														else if($value == 2){

															echo "Good";

														}

														else if($value == 4){

															echo "Broken(Yes)";

														}

														else if($value == 5){

															echo "Broken(No)";

														}

														else{

															echo "Flawless";

														}

													}



														else if($key== "OrderDate"){

								

														$value = date('m/d/Y h:i A', strtotime($value));

														echo $value;

														}

													 

														else {

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



									



										



										



										 <a href="<?php echo edit_url(urlencode($table->current_id))."&key=".$key1; ?>" title="Edit"><img src="<?=$base_url; ?>/images/icons/pencil.png" alt="Edit" /></a>

                                         

                                         <a href="<?=$baseurl; ?>pdffile.php?id=<?=$row['TrackingCode']?>" target="_blank"><img class="t-icon" src="<?=$baseurl; ?>images/t-icon4.png" title="Packing List" alt="Icon"/></a>



										

<?php

if($wename1['UserType']=='Super Admin'){

?>

										<a class="confirm_link" id="delete_link" href="<?=$_SERVER['PHP_SELF']?>?delete=<?php echo urlencode($table->current_id);?>&key=<?=$key1?>&desc" onclick="return confirm('Are you sure?')" title="Delete"><img src="<?php echo $base_url; ?>/images/icons/cross.png" alt="Delete" /></a>

<?php

}

?>

									



									</td>



								</tr>

                                    <?php

									

								}

								 } ?>

							</tbody>

							

								<tfoot>

									<tr>

										<td colspan="6">

                                        <?php

										if($wename1['UserType']=='Super Admin' && $_GET['key'] == "all"){

										?>

											<div class="bulk-actions align-left">

												<select name="action_type">

													<option value="noaction">Choose an action...</option>

													<option value="delete">Delete</option>

												</select>

												<input type="submit" class="button" value="Apply to selected" name="action_submit"></input>

											</div>

                                            <?php

										}

											?>

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