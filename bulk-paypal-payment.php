<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(dirname(__FILE__) . '/inc/core.php');
require_once(dirname(__FILE__) . '/classes/class.table_form.php');

$payment_method = 1;

if($payment_method == 1){
	$payment_method_str = 'PayPal';
}else{
	$payment_method_str = 'Check';
}

$temp_file_name = "";
$html2 = "";
$paypal_error = array();
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
		product.Description,
		user.PaypalEmail
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
	$vEmailSubject = 'Paypal Payment';
	// Set request-specific fields.	
	$emailSubject = urlencode($vEmailSubject);
	$receiverType = urlencode('EmailAddress');
	$currency = urlencode('USD'); // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
	$receivers = array();
	
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
		order.TrackingCode,
		user.PaypalEmail
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
	
	while($rows = mysql_fetch_assoc($res)){					
		$orderid = $rows['id'];
		$userid = $rows['userid'];
		$receiveremail = $rows['PaypalEmail'];
		$transactionid = "TR_".rand(11111111,99999999);
		
		$adjusted_amount = $rows['AdjustedAmount'];
		$order_amount = $rows['OrderAmount'];
		if($adjusted_amount != null && $adjusted_amount != ""){
			$amount = $adjusted_amount;
		}else{
			$amount = $order_amount;
		}
		
		$user_email = $rows['EmailAddress'];
		$temp_receiver = array(
		'receiverEmail' => "$receiveremail", 
		'amount' => "$amount",
		'uniqueID' => "$transactionid", // 13 chars max
		'note' => "Payment to User",
		'orderid' => "$orderid",
		'userid' => "$userid");
		
		array_push($receivers, $temp_receiver);					
	}

	$receiversLenght = count($receivers);
	
	// Add request-specific fields to the request string.
	$nvpStr="&EMAILSUBJECT=$emailSubject&RECEIVERTYPE=$receiverType&CURRENCYCODE=$currency";
	$receiversArray = array();
	for($i = 0; $i < $receiversLenght; $i++){
		$receiversArray[$i] = $receivers[$i];
	}

	foreach($receiversArray as $i => $receiverData){
		$receiverEmail = urlencode($receiverData['receiverEmail']);
		$amount = urlencode($receiverData['amount']);
		$uniqueID = urlencode($receiverData['uniqueID']);
		$note = urlencode($receiverData['note']);
		$nvpStr .= "&L_EMAIL$i=$receiverEmail&L_Amt$i=$amount&L_UNIQUEID$i=$uniqueID&L_NOTE$i=$note";
	}
	
	// Execute the API operation; see the PPHttpPost function above.
	$httpParsedResponseAr = paypal_http_post('MassPay', $nvpStr);
	//print_r($httpParsedResponseAr);
	
	if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
	{
		foreach($receiversArray as $i => $receiverData){			
			$userid = $receiverData['userid'];
			$orderid = $receiverData['orderid'];
			$transactionid = $receiverData['uniqueID'];
			
			$amount = $receiverData['amount'];			
			$chequenumber = "";
					
			mysql_query("UPDATE `order` SET OrderStatus=6 WHERE id=$orderid");
			mysql_query("UPDATE ordertrasactions SET active=0 WHERE OrderId=$orderid AND active=1");
			
			mysql_query("INSERT INTO ordertrasactions (`OrderId`,`UserId`,`PaidById`,`AmountPaid`,`PaymentMethod`,`ChequeNumber`,`TransactionId`) VALUES('$orderid','$userid',10,'$amount','$payment_method','$chequenumber','$transactionid')");
			
			$orderpaid = date('Y-m-d H:i:s');
			
			add_orderlog($_GET['id'],6);
			
			$queryhistory = "UPDATE `orderstatushistory` SET `orderid` = ".$orderid.", `datepaid`='".$orderpaid."' WHERE orderid=".$orderid;
		
			mysql_query($queryhistory);			
		}
		
		foreach($receiversArray as $i => $receiverData){			
			$userid = $receiverData['userid'];
			$orderid = $receiverData['orderid'];
			$transactionid = $receiverData['uniqueID'];
			
			$amount = $receiverData['amount'];			
			$chequenumber = "";
			
			$query = "SELECT order.id as OrderId, order.TrackingCode as TrackingCode, order.FedexCode as FedexCode, order.OrderDate as OrderDate, order.OrderAmount as OrderAmount , order.AdjustedAmount as AdjustedAmount , product.ProductModel as ProductModel , product.ProductCode as ProductCode, product.Description as ProductDescription, productfamily.image_url as ProductImage, user.FirstName as FirstName, user.LastName as LastName, user.EmailAddress as EmailAddress, user.S_AddressLine1 as S_AddressLine1, user.S_AddressLine2 as S_AddressLine2 , user.S_City as S_City, user.S_State as S_State, user.S_Country as S_Country,user.S_PostalCode as S_PostalCode, user.PaypalEmail as PaypalEmail, user.Phone as Phone ,
			CASE user.PaymentMethod
			WHEN '2' THEN 'Check'
			WHEN '1' THEN 'Paypal'
			ELSE 'Nothing'
			END as PaymentMethod,
			CASE order.OrderStatus
			WHEN '7' THEN 'Cancelled' 
			WHEN '6' THEN 'Paid'
			WHEN '5' THEN 'Release Payment'
			WHEN '4' THEN 'Return Order'
			WHEN '3' THEN 'Adjusted Price'
			WHEN '2' THEN 'Received'
			WHEN '1' THEN 'Pending'
			ELSE 'Pending'
			END as OrderStatus,
			CASE order.Condition
			WHEN '3' THEN 'Flawless'
			WHEN '4' THEN 'Broken(Yes)'
			WHEN '5' THEN 'Broken(No)'
			WHEN '2' THEN 'Good'
			WHEN '1' THEN 'Fair'
			ELSE 'Good'
			END as OrderCondition
			FROM `order` INNER JOIN `product` ON product.ProductCode=order.ProductId INNER JOIN `user` ON user.id=order.UserId INNER JOIN `productbrand` ON productbrand.id=product.BrandId INNER JOIN `productfamily` ON productfamily.Id=product.FamilyId INNER JOIN `productcategory` ON productcategory.id=product.CategoryId WHERE order.id= '".$orderid."'";
			mysql_query("SET SQL_BIG_SELECTS=1");
			$rowsorder = mysql_query($query);
			$roworder = mysql_fetch_assoc($rowsorder);
		 
			$my_date = date('m/d/y',strtotime($roworder['OrderDate']));
			
			$body_element_arr = array();
			$body_element_arr['firstname'] 			= $roworder['FirstName'];
			$body_element_arr['lastname'] 			= $roworder['LastName'];
			$body_element_arr['description'] 		= $roworder['ProductDescription'];
			$body_element_arr['amount'] 			= $amount;
			$body_element_arr['order_date'] 		= $my_date;
			$body_element_arr['tracking_code'] 		= $roworder['TrackingCode'];

			$subject = "We have released payment for your ".$roworder['ProductDescription'];
			$body = get_emailbody_paypal_payment($body_element_arr);			  				
			$user_email = $roworder['EmailAddress'];
			
			send_email($subject, $body, $user_email);
				
			$html2 .= "<tr>";					
			$html2 .= "<td>".$roworder['FirstName'] . " " . $rows['LastName'] ."</td>";
			$html2 .= "<td>".$roworder['TrackingCode'] ."</td>";
			$html2 .= "<td>".$transactionid."</td>";
			$html2 .= "<td>".$amount."</td>";
			$html2 .= "<td>".$roworder['ProductDescription']."</td>";		
			$html2 .= "</tr>";								
			
		}					
	}
	else
	{
	 echo '\nMassPay failed: ';
	 $paypal_error = $httpParsedResponseAr;
	 //$_POST['OrderStatus'] = 5;
	//header("Location: $url?key=$key&p=$p&sort=id&success=error&desc");
	}
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
	<h3> Confirm following PayPal orders</h3>
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
					<th>PayPal </th>					
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
					$html .= "<td>".$rows2['PaypalEmail']."</td>";
					$html .= "<td>".$amount."</td>";
					$html .= "<td>".$rows2['Description']."</td>";
					
					$html .= "</tr>";					
				}
				
				$html .= '<tr>
							<td colspan="4">
								<input type="submit" name="" id="create-check-btn" value="PayPal Mass Pay" />
								<a href="paid-order.php?payment_method=1"><--&nbsp; Go Back</a>
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
					<th>STP </th>					
					<th>TransactionID </th>
					<th>Amount </th>
					<th>Description </th>
				</tr>
			</thead>
			<tbody>
				<?php
				echo $html2;
				?>
			</tbody>
		</table>
		<?php }else if(count($paypal_error) > 0){ 
			print_r($paypal_error);
			echo "<br><br>Request:".$nvpStr;
		}
		?>
		
<script>
function generateCheckConfirmation(){
	var txt;
	var r = confirm("Are you sure you want to make payments through PayPal for mentioned orders ?");
	if (r == true) {
		document.getElementById("create-check-btn").disabled = true;
	} else {
		return false;
	}
}
</script>		