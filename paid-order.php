<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(dirname(__FILE__) . '/inc/core.php');
require_once(dirname(__FILE__) . '/classes/class.table_form.php');

$_GET['sort'] = 'id';
$_GET['sort'] = 'OrderDate';
$payment_method = isset($_GET['payment_method'])?$_GET['payment_method']:2;

if($payment_method == 1){
	$payment_method_str = 'PayPal';
	$bulk_payment_url = 'bulk-paypal-payment.php';
}else{
	$payment_method_str = 'Check';
	$bulk_payment_url = 'bulk-check-payment.php';
}
//$payment_method = 1; //PayPal
//$payment_method = 2; //Check

$sql = "
	SELECT 
		order.id, 
		order.TrackingCode, 
		order.ProductId, 
		product.Description, 
		user.FirstName, 
		user.LastName, 
		order.Condition, 
		order.OrderAmount, 
		order.AdjustedAmount, 
		order.OrderStatus, 
		order.ProductSerial, 
		orderstatushistory.datereleased,
		user.PaypalEmail
	FROM 
		`order` 
	INNER JOIN user ON user.id = order.UserId 
	INNER JOIN product ON order.ProductId = product.ProductCode	
	INNER JOIN orderstatushistory ON order.id = orderstatushistory.orderid	
	WHERE 
		user.PaymentMethod = $payment_method AND
		order.OrderStatus = 5
	ORDER BY orderstatushistory.datereleased ASC";

$res = mysql_query($sql);	
$num_rows = mysql_num_rows($res);
echo "ok:".$num_rows;

$html = "<table>";



$html .= "</table>";

//echo $html;

include(dirname(__FILE__) . '/html/header.php');
include(dirname(__FILE__) . '/html/menu.php');?>
?>

<div id="main-content">
<!-- Main Content Section with everything -->
<!-- Page Head -->
<h2> Release Payment Orders (<?php echo $payment_method_str; ?>) </h2>
<h5>Total Orders: <span id="total-items">0</span></h5>
<h5>Total Order Amount: <span id="total-order-amount">0</span></h5>
<h5>Total Payable Orders: <span id="total-payable-items">0</span></h5>
<h5>Total Payable Amount: <span id="total-payable-amount">0</span></h5>
<br>
<div style="padding: 4px;width: auto;">
	<?php //$table->search_new();?>	
	<?php //$table->display_records();?>
</div><br><br><br><br>
<?php //if ($action_msg) echo $action_msg; ?><br>
<div class="clear"></div>
<!-- End .clear -->

<div class="content-box">
	<!-- Start Content Box -->
	<div id="dialog" title="Delete this item?" style="display: none;">
		<p>Are you sure you want to delete this entry?</p>
	</div>
	<div class="content-box-header">
	<h3> Manage Release Payment Orders</h3>
	<div class="clear"></div>
	</div><!-- End .content-box-header -->
		
	<div class="content-box-content">		
		<br><br>
		<table>
			<thead>
				<tr>
					<th>
						<input class="check-all" type="checkbox" />
					</th>
					<th>Tracking Code </th>
					<th>Product Code </th>
					<th>Description </th>
					<th>Name </th>
					<?php if($payment_method == 1){?>
					<th>PayPal </th>
					<?php } ?>
					<th>Condition </th>
					<th>Amount </th>
					<th>Adjusted Price </th>
					<th>Order Status </th>
					<th>Product Serial </th>
					<th>Date Created </th>
				</tr>
			</thead>
			<tbody>
				<?php
				$html = "";
				$html .= "<form name='' action='".$bulk_payment_url."' method='post'>"; 
				$total_items = 0;
				$total_order_amount = 0;
				while($rows = mysql_fetch_assoc($res)){
					//$html = "";
					$html .= "<tr id='events_".$rows['id']."'>";
					
					
					$html .= "<td><input type='checkbox' name='orderid2[]' class='order-checkbox' value='".$rows['id']."'></td>";
					$html .= "<td>".$rows['TrackingCode']."</td>";
					$html .= "<td>".$rows['ProductId']."</td>";
					$html .= "<td>".$rows['Description']."</td>";
					$html .= "<td>".$rows['FirstName'] . " " . $rows['LastName'] ."</td>";
					
					if($payment_method == 1){
						$html .= "<td>".$rows['PaypalEmail']."</td>";
					}
					
					$html .= "<td>".get_condition_str($rows['Condition'])."</td>";
					$html .= "<td>".$rows['OrderAmount']."</td>";
					$html .= "<td>".$rows['AdjustedAmount']."</td>";
					$html .= "<td>".get_order_status_str($rows['OrderStatus'])."</td>"; 
					$html .= "<td>".$rows['ProductSerial']."</td>";
					$html .= "<td>".date('m-d-y',strtotime($rows['datereleased']))."</td>";									
					$html .= "</tr>";
					
					if($rows['AdjustedAmount'] !== ""){
						$total_order_amount += $rows['AdjustedAmount'];
						$html .= "<input type='hidden' id='amount-".$rows['id']."' value='".$rows['AdjustedAmount']."'/>";
					}else{
						$total_order_amount += $rows['OrderAmount'];
						$html .= "<input type='hidden' id='amount-".$rows['id']."' value='".$rows['OrderAmount']."'/>";
					}
					
					
					$total_items++;
				}
				
				$html .= '<tr>
					<td colspan="11">
						<input type="submit" name="" value="Process Payment" />
					</td>
				</tr>';
				$html .= "</form>";
				echo $html;
				?>
			</tbody>
		</table>
		<input type="hidden" id="total_count" value="<?php echo $total_items; ?>" />
		<input type="hidden" id="total_order_amount" value="<?php echo $total_order_amount; ?>" />
<script>
var total_items = $("#total_count").val();
$("#total-items").html(total_items);

var total_order_amount = $("#total_order_amount").val();
$("#total-order-amount").html(total_order_amount);

$('.order-checkbox').change(function(){
	var target = $(this);
    var target_value = target.val();
	var amount = parseInt($("#amount-"+target_value).val());
	var payable_items = parseInt($("#total-payable-items").html());
	var payable_amount = parseInt($("#total-payable-amount").html());
	if(target.prop("checked")){
		payable_items++;
		payable_amount += amount;
	}else{
		payable_items--;
		payable_amount -= amount;
	}
	$("#total-payable-items").html(payable_items);
	$("#total-payable-amount").html(payable_amount);
});

$('.check-all').change(function(){
	var target = $(this);
    if(target.prop("checked")){
		$('.order-checkbox').prop("checked","checked");
		$("#total-payable-items").html(total_items);
		$("#total-payable-amount").html(total_order_amount);
	}else{
		$("#total-payable-items").html(0);
		$("#total-payable-amount").html(0);
	}
});

</script>		