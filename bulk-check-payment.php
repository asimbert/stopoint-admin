<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(dirname(__FILE__) . '/inc/core.php');
require_once(dirname(__FILE__) . '/classes/class.table_form.php');

$payment_method = 2;

if($payment_method == 1){
	$payment_method_str = 'PayPal';
}else{
	$payment_method_str = 'Check';
}

$temp_file_name = "";
$html2 = "";
$num_rows2 = 0;
if(isset($_POST['orderid2'])){
	$total_order = count($_POST['orderid2']);
	$orderid_str = "";
	for($i = 0; $i < $total_order; $i++){
		$orderid = $_POST['orderid2'][$i];
		if($orderid != null && $orderid != "")
			$orderid_str .= $orderid . ",";
	}
	$orderid_str = substr($orderid_str, 0, strlen($orderid_str)-1);
	$sql2 = "
	SELECT 
		order.id, 
		user.FirstName, 
		user.LastName, 
		order.OrderAmount, 
		order.AdjustedAmount, 
		product.Description
	FROM 
		`order` 
	INNER JOIN user ON user.id = order.UserId 
	INNER JOIN product ON order.ProductId = product.ProductCode	
	WHERE 
		user.PaymentMethod = $payment_method AND
		order.id IN ($orderid_str) AND
		order.OrderStatus = 5
	";
	
	$res2 = mysql_query($sql2) or die(mysql_error());	
	$num_rows2 = mysql_num_rows($res2);
		
}

else if(isset($_POST['orderid'])){
	$total_order = count($_POST['orderid']);
	$orderid_str = "";
	for($i = 0; $i < $total_order; $i++){
		$orderid = $_POST['orderid'][$i];
		if($orderid != null && $orderid != "")
			$orderid_str .= $orderid . ",";
	}
	$orderid_str = substr($orderid_str, 0, strlen($orderid_str)-1);
	$sql = "
	SELECT 
		order.id, 
		user.id AS userid,
		user.FirstName, 
		user.LastName, 
		user.EmailAddress, 
		order.OrderAmount, 
		order.AdjustedAmount, 
		product.Description,
		user.S_AddressLine1,
		user.S_AddressLine2,
		user.S_City,
		user.S_State,
		user.S_PostalCode,
		user.S_Country,
		productfamily.Name as FamilyName,
		order.OrderDate,
		order.TrackingCode
	FROM 
		`order` 
	INNER JOIN user ON user.id = order.UserId 
	INNER JOIN product ON order.ProductId = product.ProductCode	
	INNER JOIN productfamily ON productfamily.id = product.FamilyId	
	WHERE 
		user.PaymentMethod = $payment_method AND
		order.id IN ($orderid_str) AND
		order.OrderStatus = 5
	";
	
	$res = mysql_query($sql) or die(mysql_error());	
	
	$temp_check_body = "";
	$temp_file_name = "check-temp-".rand(111111,999999);
	
	while($rows = mysql_fetch_assoc($res)){					
		$orderid = $rows['id'];
		$userid = $rows['userid'];
		
		$adjusted_amount = $rows['AdjustedAmount'];
		$order_amount = $rows['OrderAmount'];
		if($adjusted_amount != null && $adjusted_amount != ""){
			$amount = $adjusted_amount;
		}else{
			$amount = $order_amount;
		}
		
		$user_email = $rows['EmailAddress'];
		
		$checknumber = rand(111111,999999);		
		
		$transactionid = "";
		
		mysql_query("UPDATE ordertrasactions SET active=0 WHERE OrderId=$orderid AND active=1");
		mysql_query("INSERT INTO ordertrasactions (`OrderId`,`UserId`,`PaidById`,`AmountPaid`,`PaymentMethod`,`ChequeNumber`,`TransactionId`) VALUES('$orderid','$userid',10,'$amount','$payment_method','$checknumber','$transactionid')") or die(mysql_error());
		$orderpaid = date('Y-m-d H:i:s');
		
		add_orderlog($orderid,6,1);
		
		$queryhistory = "UPDATE `orderstatushistory` SET `orderid` = ".$orderid.", `datepaid`='".$orderpaid."' WHERE orderid=".$orderid;
	
		$orderhistory = mysql_query($queryhistory) or die(mysql_error());
			
			
		//create check
		$check_element_arr = array();
		$check_element_arr['price'] 			= $amount;
		$check_element_arr['s_addressline1'] 	= $rows['S_AddressLine1'];
		$check_element_arr['s_addressline2'] 	= $rows['S_AddressLine2'];
		$check_element_arr['s_city'] 			= $rows['S_City'];
		$check_element_arr['s_state'] 			= $rows['S_State'];
		$check_element_arr['s_postalcode'] 		= $rows['S_PostalCode'];
		$check_element_arr['s_country'] 		= $rows['S_Country'];
		$check_element_arr['familyname'] 		= $rows['FamilyName'];
		$check_element_arr['firstname'] 		= $rows['FirstName'];
		$check_element_arr['lastname'] 			= $rows['LastName'];
		$check_element_arr['checknumber'] 		= $checknumber;
		
		$temp_check_body .= create_check($check_element_arr);
		
		//send email
		$add_days = 30;
		$my_date = date('m/d/y',strtotime($rows['OrderDate']));	  	 
		//$my_date = date('m/d/y',strtotime($my_date.' +'.$add_days.' days'));
		
		$body_element_arr = array();
		$body_element_arr['firstname'] 			= $rows['FirstName'];
		$body_element_arr['lastname'] 			= $rows['LastName'];
		$body_element_arr['description'] 		= $rows['Description'];
		$body_element_arr['amount'] 			= $amount;
		$body_element_arr['order_date'] 		= $my_date;
		$body_element_arr['tracking_code'] 		= $rows['TrackingCode'];
		
		$subject = "We have released payment for your ".$rows['Description'];
		$body = get_emailbody_check_payment($body_element_arr);
		
		send_email($subject, $body, $user_email);
		
		mysql_query("UPDATE `order` SET OrderStatus=6 WHERE id=$orderid");
		
		$html2 .= "<tr>";					
		$html2 .= "<td>".$rows['FirstName'] . " " . $rows['LastName'] ."</td>";
		$html2 .= "<td>".$checknumber ."</td>";
		$html2 .= "<td>".$amount."</td>";
		$html2 .= "<td>".$rows['Description']."</td>";		
		$html2 .= "</tr>";
		
	}
	
	//Create temporary pdf having all checks
	create_temp_check($temp_check_body, $temp_file_name);	
}

$_GET['sort'] = 'id';
$_GET['sort'] = 'OrderDate';

//$payment_method = 1; //PayPal
//$payment_method = 2; //Check

$sql = "
	SELECT 
		order.id, 
		user.FirstName, 
		user.LastName, 
		order.OrderAmount, 
		order.AdjustedAmount, 
		product.Description
	FROM 
		`order` 
	INNER JOIN user ON user.id = order.UserId 
	INNER JOIN product ON order.ProductId = product.ProductCode	
	WHERE 
		user.PaymentMethod = $payment_method AND
		order.OrderStatus = 5
	";

$res = mysql_query($sql);	
$num_rows = mysql_num_rows($res);

include(dirname(__FILE__) . '/html/header.php');
include(dirname(__FILE__) . '/html/menu.php');
?>

<div id="main-content">
<!-- Main Content Section with everything -->
<!-- Page Head -->
<h2> Release Payment Orders (<?php echo $payment_method_str; ?>) </h2>

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
	<div class="content-box-header">
	<h3> Confirm following check orders</h3>
	<div class="clear"></div>
	</div><!-- End .content-box-header -->
		
	<div class="content-box-content">		
		<br><br>
		<?php if($num_rows2 > 0){ ?>
		<table>
			<thead>
				<tr>
					<th>
						&nbsp;
					</th>					
					<th>Name </th>					
					<th>Amount </th>
					<th>Description </th>
				</tr>
			</thead>
			<tbody>
				<?php
				$html = "";
				$html .= "<form name='' action='' method='post' onsubmit='return generateCheckConfirmation()'>";
				while($rows2 = mysql_fetch_assoc($res2)){					
					$html .= "<tr id='events_".$rows2['id']."'>";
					
					$adjusted_amount = $rows2['AdjustedAmount'];
					$order_amount = $rows2['OrderAmount'];
					if($adjusted_amount != null && $adjusted_amount != ""){ 
						$amount = $adjusted_amount;
					}else{
						$amount = $order_amount;
					}
					
					$html .= "<td><input type='hidden' name='orderid[]' value='".$rows2['id']."'></td>";					
					$html .= "<td>".$rows2['FirstName'] . " " . $rows2['LastName'] ."</td>";
					$html .= "<td>".$amount."</td>";
					$html .= "<td>".$rows2['Description']."</td>";
					
					$html .= "</tr>";					
				}
				
				$html .= '<tr>
							<td colspan="4">
								<input type="submit" name="" id="create-check-btn" value="Create Check" />
								<a href="paid-order.php"><--&nbsp; Go Back</a>
							</td>
						</tr>';
				$html .= "</form>";
				echo $html;
				?>
			</tbody>
		</table>
		<?php } else if($html2 !== ""){ ?>
		<table>
			<thead>
				<tr>					
					<th>Name </th>					
					<th>Check Number </th>					
					<th>Amount </th>
					<th>Description </th>
				</tr>
			</thead>
			<tbody>
				<?php
				echo $html2;
				?>
				
				<?php if($temp_file_name !== ""){ ?>
					<tr>
						<td colspan="4">
							<a href="pdf/stopointfilesystemsxgdlj123-<?php echo $temp_file_name;?>.pdf" target="_blank">Print</a> 
						</td>
					</tr>
				<?php } ?>

			</tbody>
		</table>
		<?php } ?>
		
<script>
function generateCheckConfirmation(){
	var txt;
	var r = confirm("Are you sure you want to create checks ?\n\n Confirmation email will be sent to these customers.");
	if (r == true) {
		document.getElementById("create-check-btn").disabled = true;
	} else {
		return false;
	}
}
</script>		