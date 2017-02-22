<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(dirname(__FILE__) . '/inc/core.php');
require_once(dirname(__FILE__) . '/classes/class.table_form.php');

/*if(isset($_SESSION['UserType']) && $_SESSION['UserType'] === 'Super Admin' && isset($_SESSION['UserType']) && $_SESSION['EmailAddress'] === 'asimbert1@gmail.com'){
	
}else{
	header("Location: index.php");
}*/

if(isset($_POST['email']) && $_POST['email'] !== '' && isset($_POST['amount']) && $_POST['amount'] !== ''){
	//Insert into the database
	
	$vEmailSubject = 'Paypal Payment';
	$emailSubject = urlencode($vEmailSubject);
	$receiverType = urlencode('EmailAddress');
	$currency = urlencode('USD'); // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
	$receivers = array();
		
	$paypal_email = trim($_POST['email']);
	$amount = trim($_POST['amount']);
	$description = trim($_POST['description']);
	$status = 'Pending';
	$addedby = $_SESSION['userid'];
	$dateadded = date('Y-m-d H:i:s');	
	$transactionid = "TR_".rand(11111111,99999999);
	
	$temp_receiver = array(
	'receiverEmail' => "$paypal_email", 
	'amount' => "$amount",
	'uniqueID' => "$transactionid", // 13 chars max
	'note' => "Payment to User");
		
	array_push($receivers, $temp_receiver);
	
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
		$status = $httpParsedResponseAr["ACK"];
		mysql_query("INSERT INTO paymentlogs (`paypal_email`,`amount`,`description`,`status`,`transactionid`,`addedby`,`dateadded`) VALUES('$paypal_email','$amount','$description','$status','$transactionid','$addedby','$dateadded')") or die(mysql_error());	

		header("Location: paypal-payment?success");
	}
	else
	{
	 echo '\nMassPay failed: ';
	 $status = $httpParsedResponseAr["ACK"];
	 mysql_query("INSERT INTO paymentlogs (`paypal_email`,`amount`,`description`,`status`,`transactionid`,`addedby`,`dateadded`) VALUES('$paypal_email','$amount','$description','$status','$transactionid','$addedby','$dateadded')") or die(mysql_error());
	 
	 $paypal_error = $httpParsedResponseAr;
		
		header("Location: paypal-payment?failed");
	}		
}

$sql = "
	SELECT 
		*
	FROM 
		`paymentlogs`
	ORDER BY dateadded DESC
	";
$res = mysql_query($sql);	
$num_rows = mysql_num_rows($res);

include(dirname(__FILE__) . '/html/header.php');
include(dirname(__FILE__) . '/html/menu.php');
?>

<div id="main-content">
<!-- Main Content Section with everything -->
<!-- Page Head -->
<h2> PayPal Payment </h2>

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
	<h3> Make payment to a paypal user</h3>
	<div class="clear"></div>
	</div><!-- End .content-box-header -->
		
	<div class="content-box-content">	
		<form action="" method="post" onsubmit="return payment_confirmation()" autocomplete="off">
			<fieldset>
				<p>
					<label>Email : </label>
					<input id="email" class="text-input small-input" name="email" type="text">
				</p>
				<p>
					<label>Amount : </label>
					<input id="amount" class="text-input small-input" name="amount" type="text">
				</p>
				<p>
					<label>Description (optional) : </label>
					<input id="description" class="text-input small-input" name="description" type="text">
				</p>
				<p>
					<input class="button" type="submit" id="submit" name="submit" value="Pay Now">
				</p>
		</form>
	</div>
		<br><br>
		<table>
			<thead>
				<tr>				
					<th>Transaction ID </th>					
					<th>Email </th>					
					<th>Amount </th>
					<th>Date </th>
					<th>Status </th>
					<th>Description </th>
				</tr>
			</thead>
			<tbody>
				<?php
				$html = "";
				$i = 1;
				while($rows = mysql_fetch_assoc($res)){					
					$html .= "<tr id='events_".$i."'>";
					
					$paypal_email = $rows['paypal_email'];					
					$amount = $rows['amount'];					
					$description = $rows['description'];					
					$status = $rows['status'];					
					$transactionid = $rows['transactionid'];					
					$dateadded = $rows['dateadded'];					
					
					$html .= "<td>".$transactionid."</td>";
					$html .= "<td>".$paypal_email."</td>";
					$html .= "<td>".$amount."</td>";
					$html .= "<td>".$dateadded."</td>";
					$html .= "<td>".$status."</td>";
					$html .= "<td>".$description."</td>";
					
					$html .= "</tr>";					
					$i++;
				}							
				echo $html;
				?>
			</tbody>
		</table>
		
<script>
function payment_confirmation(){
	var txt;
	var r = confirm("Are you sure you want to make this payment ?");
	if (r == true) {
		document.getElementById("submit").disabled = true;
	} else {
		return false;
	}
}
</script>		