<?php

require_once(dirname(__FILE__) . '/inc/core.php');

require_once(dirname(__FILE__) . '/classes/class.table_form.php');

require_once('../fedex-common.php5');
$path_to_wsdl = "../ShipService_v15.wsdl";

function addShipper(){
	
	$shipper = array(
		'Contact' => array(
			'PersonName' => $_SESSION['payto'] //doesn't process at all if at least this one isn't a text value
), 
		'Address' => array(
			'StreetLines' => array($_SESSION['address1'], $_SESSION['address2'] ),
			'City' => $_SESSION['city'],
			'StateOrProvinceCode' => $_SESSION['state'],
			'PostalCode' => $_SESSION['zip'],
			'CountryCode' => 'US')
	);
	
	return $shipper;
}
function addRecipient(){
	$recipient = array(
		'Contact' => array(
			'PersonName' => 'Stopoint',
			'CompanyName' => 'Stopoint',
			'PhoneNumber' => '1-888-246-4919'),
		'Address' => array(
			'StreetLines' => array('12795 NE 10th Avenue #14'),
			'City' => 'North Miami ',
			'StateOrProvinceCode' => 'FL',
			'PostalCode' => '33161',
			'CountryCode' => 'US')
	);
	return $recipient;
}
function addShippingChargesPayment(){
    $shippingChargesPayment = array(
        'PaymentType' => 'SENDER', // valid values RECIPIENT, SENDER and THIRD_PARTY
        'Payor' => array(
            'ResponsibleParty' => array(
                'AccountNumber' => getProperty('billaccount'),
                'CountryCode' => 'US'
            )
        )
    );
    return $shippingChargesPayment;
}
function addLabelSpecification(){
	$labelSpecification = array(
		'LabelFormatType' => 'COMMON2D', // valid values COMMON2D, LABEL_DATA_ONLY
		'ImageType' => 'PNG',  // valid values DPL, EPL2, PDF, ZPLII and PNG
		'LabelStockType' => 'PAPER_4X6');
	return $labelSpecification;
}
function addSpecialServices(){
	$specialServices =  array(
	'SpecialServiceTypes' => 'RETURN_SHIPMENT', 
	'ReturnShipmentDetail' => array(
		'ReturnType' => 'FEDEX_TAG',
      	'Rma' => array(
      		'Number' => '012', 
      		'Reason' => 'reason'
      	)));
	return $specialServices;
}
function addPackageLineItem1(){
	$packageLineItem = array(
		'SequenceNumber'=>1,
		'GroupPackageCount'=>1,
		'Weight' => array(
			'Value' => 1.0,
			'Units' => 'LB')
	);
	return $packageLineItem;
}






$table_name = 'order';



$action = sanitize($_GET['action']);

$id = sanitize($_GET['id']);

$key = sanitize($_GET['key']);

$form = new table_form($table_name, "form");



if($action == "edit" && $id) {

	$row = $form->get_row("id",$id);

	$form->setValues($row);

}



$vas = "SELECT * FROM `order` WHERE id=".$_GET['id'];
$re=mysql_query($vas);
$we=mysql_fetch_assoc($re);
$_SESSION['stdcode']=$we['TrackingCode'];

$form->add_hidden('id');

//$form->add_text('Person Name : ', 'person_name');

$form->add_text('Tracking Code : ', 'TrackingCode');

$form->add_text('Fedex Tracking Id : ', 'FedexCode');
$form->add_text('Fedex Return Id : ', 'Returnlabel');
//$form->add_textarea('Description : ', 'description',$row['description']);
if($_GET['key'] == 'release'){
$form->add_text('Order Amount : ', 'OrderAmount');
}
else{
$form->add_text('Order Amount : ', 'OrderAmount');
}


//$form->add_select('First Name','UserId','user','id','FirstName','FirstName');
//$form->add_select('Last Name','UserId','user','id','LastName','LastName');
//$form->add_edittext('Product Model','ProductId','product','id','ProductModel','ProductModel');

$form->add_text('Order Date : ', 'OrderDate');

if($_GET['key'] == 'all'){
$form->add_dropdown('Order Status','OrderStatus',array(array('name'=>'Pending','value'=>'1'),array('name'=>'Received','value'=>'2'),array('name'=>'Adjusted Price','value'=>'3'),array('name'=>'Return Order','value'=>'4'),array('name'=>'Release Payment','value'=>'5'),array('name'=>'Pay Order','value'=>'6'),array('name'=>'Cancelled','value'=>'7'),array('name'=>'Expired Order','value'=>'8'),array('name'=>'Return Completed','value'=>'9'),array('name'=>'Activation Lock','value'=>'10'),array('name'=>'Installment payment','value'=>'11'),array('name'=>'IMEI Check','value'=>'12'),array('name'=>'Activation Lock Inspection','value'=>'13'),array('name'=>'Blacklisted','value'=>'14'),array('name'=>'Adjusted Price Inspection','value'=>'15')));

if($we['AdjustedAmount'] != ""){
if($we['OrderStatus'] == 5 || $we['OrderStatus'] == 6){
$form->add_text('Adjusted Price : ', 'AdjustedAmount');
}else{
$form->add_text('Adjusted Price : ', 'AdjustedAmount');
}
}
}

else if($_GET['key'] == 'pending')
{
	$form->add_dropdown('Order Status','OrderStatus',array(array('name'=>'Pending','value'=>'1'),array('name'=>'Received','value'=>'2'),array('name'=>'Cancelled','value'=>'7')));
}

else if($_GET['key'] == 'received')
{
	$form->add_dropdown('Order Status','OrderStatus',array(array('name'=>'Received','value'=>'2'),array('name'=>'Release Payment','value'=>'5'),array('name'=>'Adjusted Price','value'=>'3'),array('name'=>'Return Order','value'=>'4'),array('name'=>'Activation Lock','value'=>'10'),array('name'=>'IMEI Check','value'=>'12'))); 
	
}

else if($_GET['key'] == 'activation')
{
	//Commented for Release paymnt will be replaced by Activation Lock Inspection
	//$form->add_dropdown('Order Status','OrderStatus',array(array('name'=>'Release Payment','value'=>'5'),array('name'=>'Adjusted Price','value'=>'3'),array('name'=>'Return Order','value'=>'4'))); 
	
	$form->add_dropdown('Order Status','OrderStatus',array(array('name'=>'Activation Lock Inspection','value'=>'13'),array('name'=>'Adjusted Price','value'=>'3'),array('name'=>'Return Order','value'=>'4'))); 
	
}

else if($_GET['key'] == 'returned')
{
	$form->add_dropdown('Order Status','OrderStatus',array(array('name'=>'Returned','value'=>'4'),array('name'=>'Cancelled','value'=>'7'),array('name'=>'Return Completed','value'=>'9')));
	
}

else if($_GET['key'] == 'returncompleted')
{
	$form->add_dropdown('Order Status','OrderStatus',array(array('name'=>'Returned','value'=>'4'),array('name'=>'Cancelled','value'=>'7'),array('name'=>'Return Completed','value'=>'9')));
	
}

else if($_GET['key'] == 'adjusted')
{
	$form->add_dropdown('Order Status','OrderStatus',array(array('name'=>'Return Order','value'=>'4'),array('name'=>'Release Payment','value'=>'5'),array('name'=>'Adjusted Price Inspection','value'=>'15')));

$form->add_text('Adjusted Price : ', 'AdjustedAmount');


}

else if($_GET['key'] == 'release')
{
	$form->add_dropdown('Order Status','OrderStatus',array(array('name'=>'Release Payment','value'=>'5'),array('name'=>'Pay Order','value'=>'6'))); 
	if($we['AdjustedAmount'] != ""){

$form->add_text('Adjusted Price : ', 'AdjustedAmount');
}
}

else if($_GET['key'] == 'paid')
{
	$form->add_dropdown('Order Status','OrderStatus',array(array('name'=>'Paid','value'=>'6')));
	if($we['AdjustedAmount'] != ""){

$form->add_text('Adjusted Price : ', 'AdjustedAmount');
}
}

else if($_GET['key'] == 'imei')
{
	$form->add_dropdown('Order Status','OrderStatus',array(array('name'=>'Unlock - Release Payment','value'=>'5'),array('name'=>'Installment payment','value'=>'11'),array('name'=>'Blacklisted','value'=>'14'))); 
	
		if($we['AdjustedAmount'] != ""){
if($we['OrderStatus'] == 5 || $we['OrderStatus'] == 6){
$form->add_readonly('Adjusted Price : ', 'AdjustedAmount');
}else{
$form->add_text('Adjusted Price : ', 'AdjustedAmount');
}
}
	
}

else if($_GET['key'] == 'installment')
{
	$form->add_dropdown('Order Status','OrderStatus',array(array('name'=>'IMEI Check','value'=>'12'),array('name'=>'Return Order','value'=>'4'))); 
	
}

else if($_GET['key'] == 'activation-lock')
{
	$form->add_dropdown('Order Status','OrderStatus',array(array('name'=>'Release Payment','value'=>'5'), array('name'=>'Activation Lock','value'=>'10'),array('name'=>'Return Order','value'=>'4'),array('name'=>'IMEI Check','value'=>'12'))); 
	
}

else if($_GET['key'] == 'blacklisted')
{
	$form->add_dropdown('Order Status','OrderStatus',array(array('name'=>'Return Order','value'=>'4'))); 
	
}

else if($_GET['key'] == 'adjusted-price')
{
	$form->add_dropdown('Order Status','OrderStatus',array(array('name'=>'Release Payment','value'=>'5'),array('name'=>'IMEI Check','value'=>'12'),array('name'=>'Return Order','value'=>'4'))); 
	
	if($we['AdjustedAmount'] != ""){
if($we['OrderStatus'] == 5 || $we['OrderStatus'] == 6){
$form->add_readonly('Adjusted Price : ', 'AdjustedAmount');
}else{
$form->add_text('Adjusted Price : ', 'AdjustedAmount');
}
}
}

$form->add_text('Product Serial# : ', 'ProductSerial');
if($action == "edit" && $id) {

	$row = $form->get_row("id",$id);

	$form->setValues($row);

}


if(isset($_POST['PaymentMethod']) && $_POST['PaymentMethod'] !== ""){
	mysql_query("UPDATE user SET PaymentMethod = " . $_POST['PaymentMethod'] . " WHERE id = " . $we['UserId']);
}

$fields = array('TrackingCode','OrderStatus','OrderDate','AdminComments','ProductSerial');

				
					 
$product = $we['ProductId'];	
$vasuser = "SELECT * FROM `user` WHERE id=".$we['UserId'];
$reuser=mysql_query($vasuser);
$weuser=mysql_fetch_assoc($reuser);

/*$vasproduct = "SELECT product.ProductCode as ProductCode,product.GoodPrice as GoodPrice,product.Description as Description,product.ProductModel as ProductModel,productbrand.Name as brandname,productcategory.Name as categoryname, productfamily.Name as familyname, productfamily.image_url as image_url FROM `product` INNER JOIN productbrand on product.BrandId = productbrand.id INNER JOIN productfamily on product.FamilyId = productfamily.id INNER JOIN productcategory on product.CategoryId = productcategory.id WHERE product.ProductCode = ".$product;
*/

$vasproduct = "SELECT product.ProductCode as ProductCode,product.GoodPrice as GoodPrice,product.Description as Description,product.ProductModel as ProductModel,productbrand.Name as brandname,productcategory.Name as categoryname, productfamily.Name as familyname, product.image_url as image_url FROM `product` INNER JOIN productbrand on product.BrandId = productbrand.id INNER JOIN productfamily on product.FamilyId = productfamily.id INNER JOIN productcategory on product.CategoryId = productcategory.id WHERE product.ProductCode = ".$product;
$reproduct=mysql_query($vasproduct);
$weproduct=mysql_fetch_assoc($reproduct);

$amount_paid_ot = "";
$txn_id_ot = "";
$sql_ot = "SELECT ChequeNumber, AmountPaid,TransactionId FROM ordertrasactions WHERE OrderId=$id AND ChequeNumber != '' AND active=1";
$res_ot = mysql_query($sql_ot);
$num_row_ot = mysql_num_rows($res_ot);
$is_check_exist = false;
if($num_row_ot > 0){
	$rows_ot = mysql_fetch_assoc($res_ot);
	$ra = $rows_ot['ChequeNumber'];
	$amount_paid_ot = $rows_ot['AmountPaid'];
	$txn_id_ot = $rows_ot['TransactionId'];
	$is_check_exist = true;
}else{
	$ra=rand(111111,999999);
}	
$checknumber=$ra;
if(!$is_check_exist && isset($_SESSION[$_SESSION['stdcode']]))
	$checknumber=$_SESSION[$_SESSION['stdcode']];

if($_GET['res']==1)
{
$checknumber = rand(111111,999999);
$_SESSION[$_SESSION['stdcode']] = $checknumber;

$userid = $weuser['id'];
				$orderid = $_GET['id'];
				
				$orderamount = $amount_paid_ot;
				$adjustedamount = $_POST['AdjustedAmount'];
				$paymentmethod = $weuser['PaymentMethod'];
				$transactionid = $txn_id_ot;

		
				
		mysql_query("UPDATE ordertrasactions SET active=0 WHERE OrderId=$id AND active=1");
				mysql_query("INSERT INTO ordertrasactions (`OrderId`,`UserId`,`PaidById`,`AmountPaid`,`PaymentMethod`,`ChequeNumber`,`TransactionId`) VALUES('$orderid','$userid',10,'$orderamount','$paymentmethod','$checknumber','$transactionid')") or die(mysql_error());
			$orderpaid = date('Y-m-d H:i:s');
			add_orderlog($_GET['id'],6);
			 $queryhistory = "UPDATE `orderstatushistory` SET `orderid` = ".$_GET['id'].", `datepaid`='".$orderpaid."' WHERE orderid=".$_GET['id'];
	
			$orderhistory = mysql_query($queryhistory) or die(mysql_error());
			
			
				
/*	$updatequ="UPDATE `product` SET product.ProductCode=".$ra." WHERE product.ProductCode= ".$product;
	echo $updatequ;
	echo $updatequ;
	echo $updatequ;
	echo $updatequ;
	echo $updatequ;
	echo $updatequ;
	$reproduct=mysql_query($updatequ);
	$we['ProductId']=$ra;	
	$re=mysql_query($vas);
	echo $vas;
	echo $vas;
	echo $vas;
	echo $vas;
	echo $vas;
	echo $vas;

	$vasproduct = "SELECT product.ProductCode as ProductCode,product.GoodPrice as GoodPrice,product.Description as Description,product.ProductModel as ProductModel,productbrand.Name as brandname,productcategory.Name as categoryname, productfamily.Name as familyname, product.image_url as image_url FROM `product` INNER JOIN productbrand on product.BrandId = productbrand.id INNER JOIN productfamily on product.FamilyId = productfamily.id INNER JOIN productcategory on product.CategoryId = productcategory.id WHERE product.ProductCode = ".$product;
$reproduct=mysql_query($vasproduct);
$weproduct=mysql_fetch_assoc($reproduct);*/
//	$checknumber=$ra;
}

if (isset($_POST['submit_reply'])) {

$name = $_POST['form_name'];
    $emailuserid = $_POST['useridmessage'];
	$subject = $_POST['form_subject'];
    $message_body = $_POST['Adminmessage'];	
	$orderidmessage = $_POST['orderidmessage'];
	
	mysql_query("INSERT INTO messages (`FromId`,`ToId`,`OrderId`,`Subject`,`Comments`,`IsRead`,`parentid`) VALUES(10,'$emailuserid','$orderidmessage','$subject','$message_body','0','0')") or die(mysql_error());
}


if (isset($_POST['submit'])) {

	//echo $_POST['AdminComments']; 
	//echo $row['AdminComments'];
	//exit;
	
	$url = url_page();
	//$url1 = "http://unidevphp.com/stopoint/admin/order_edit.php?action=edit";	



	if (isset($_GET['p']))

		$p = $_GET['p'];

	else

		$p = 1;

		

	if ($id) {
		
		

        	if ('"'.$_POST['AdminComments'].'"'!='"'.$row['AdminComments'].'"') {
			
				
				$to = $weuser['EmailAddress'];
				$email_from = "stopointsupport";
				$subject = "Admin Comments on your Order";
				
				$message = '<html><body>';
				$message .= '<h4>Dear, '.$weuser['FirstName'].' '.$weuser['LastName'].'!</h4>';
				$message .= '<p>Order:'.$weproduct['ProductModel'].'</p><br>';
				$message .= '<p>'.$_POST['AdminComments'].'</p><br>';
				$message .= '<p>Thanks</p>';
				$message .= '<p>From: STOPOINT</p>';
				$message .= '</body></html>';
			 require_once '../PHPMailer-master/PHPMailerAutoload.php';
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->SMTPDebug = 0;
		$mail->SMTPSecure = 'ssl';
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 465; 
		$mail->Username = "stopoint@stopoint.com";  
		$mail->Password = EMAIL_CREDENTIAL;
		$mail->From = "info@stopoint.com";
		$mail->FromName = "STOPOINT ";
		$mail->AddAddress($to);
		$mail->IsHTML(true);
		$mail->Subject = $subject;
		$mail->Body = $message;
		$mail->XMailer = ' ';
		$sent = $mail->Send();
		
				if($sent){
					$action_msg = show_notification("Email successfully sent","success",true); 
					}
			 
				else{
					$action_msg = show_notification("Email is not sent, there is some error!","error",true); 
					}	
			}
			
			//if($_POST['OrderStatus']==6){}
			
			
			if($_POST['userid']){
				$fname = $_POST['FName'];
				$lname = $_POST['LName'];
				$saddress1 = $_POST['saddress1'];
				$saddress2 = $_POST['saddress2'];
				$scity = $_POST['scity'];
				$sstate = $_POST['sstate'];
				$spostal = $_POST['spostal'];
				$scountry = $_POST['scountry'];
				
				$baddress1 = $_POST['baddress1'];
				$baddress2 = $_POST['baddress2'];
				$bcity = $_POST['bcity'];
				$bstate = $_POST['bstate'];
				$bpostal = $_POST['bpostal'];
				$bcountry = $_POST['bcountry'];
				
				
				mysql_query("UPDATE user SET `FirstName` = '$fname',`LastName` = '$lname' ,`S_AddressLine1` = '$saddress1' ,`S_AddressLine2` = '$saddress2' ,`S_City` = '$scity' ,`S_State` = '$sstate' ,`S_PostalCode` = '$spostal' ,`S_Country` = '$scountry' ,`B_AddressLine1` = '$baddress1' ,`B_AddressLine2` = '$baddress2' ,`B_City` = '$bcity' ,`B_State` = '$bstate' ,`B_PostalCode` = '$bpostal' ,`B_Country` = '$bcountry' WHERE id=".$_POST['userid']) or die(mysql_error());
				
				}
				
				if($_POST['adjustprice'] != "" || $_POST['AdjustedAmount'] != ""){
					if($_POST['adjustprice'] == "Enter Adjusted Price")
{
	$_POST['adjustprice']="";
	
	}
	if($_POST['AdjustedAmount'] != ""){
		
		$adjustprice  = $_POST['AdjustedAmount'];
		}
		else{			
		$adjustprice  = $_POST['adjustprice'];
		}
					mysql_query("UPDATE `order` SET `AdjustedAmount` = '$adjustprice' WHERE id=".$id) or die(mysql_error());
					
					}
		
		$vasexpired = "SELECT * FROM `order` WHERE id=".$id;
$reexpired=mysql_query($vasexpired);
$weexpired=mysql_fetch_assoc($reexpired);

$today =  date("D M j G:i:s T Y");
$subdate= $_POST['OrderDate'];
 $days = strtotime($today) - strtotime($subdate);
$days = floor($days/(60*60*24));
 
if($days >=30 && ($_POST['OrderStatus'] == 1 || $_POST['OrderStatus'] == 2 || $_POST['OrderStatus'] == 3 || $_POST['OrderStatus'] == 4 || $_POST['OrderStatus'] == 5 || $_POST['OrderStatus'] == 6 || $_POST['OrderStatus'] == 7)){
	
	mysql_query("UPDATE `order` SET `OrderStatus` = 8 WHERE id=".$id) or die(mysql_error());
	
	
	}


///////////////////////Payment to user Paypal/////////////////////////////////

if($_POST['OrderStatus'] == 6 && $weexpired['OrderStatus'] == 5 && $_POST['PaymentMethod'] == '2'){
	
	$userid = $weuser['id'];
				$orderid = $_GET['id'];
				
				$orderamount = $_POST['OrderAmount'];
				$adjustedamount = $_POST['AdjustedAmount'];
				$paymentmethod = $weuser['PaymentMethod'];
				//$accountnumber = $_POST['AccountNumber'];
				$chequenumber = $_POST['ChequeNumber'];
				if($weuser['PaymentMethod'] == '2'){
					$transactionid = $_POST['TrackingNumber'];
				}else{
					$transactionid = "TR_".rand(11111111,99999999);
				}
				
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
FROM `order` INNER JOIN `product` ON product.ProductCode=order.ProductId INNER JOIN `user` ON user.id=order.UserId INNER JOIN `productbrand` ON productbrand.id=product.BrandId INNER JOIN `productfamily` ON productfamily.Id=product.FamilyId INNER JOIN `productcategory` ON productcategory.id=product.CategoryId WHERE order.TrackingCode= '".$_POST['TrackingCode']."'";
mysql_query("SET SQL_BIG_SELECTS=1");
$rowsorder1 = mysql_query($query);
$roworder1 = mysql_fetch_assoc($rowsorder1);

 $add_days = 30;
	  $my_date = date('m/d/y',strtotime($roworder1['OrderDate']));
	  
	  
	  $my_date = date('m/d/y',strtotime($my_date.' +'.$add_days.' days'));
				 /* send review email */
	  
	  /*$messagereview = file_get_contents('review.html');
        $messagereview = str_replace('%name%', $roworder1['FirstName'], $messagereview);
		$messagereview = str_replace('%product%', $roworder1['ProductDescription'], $messagereview);
		$messagereview = str_replace('%amount%', $roworder1['OrderAmount'], $messagereview);
		$messagereview = str_replace('%expiration%',$my_date, $messagereview);
		$messagereview = str_replace('%user_id%',$userid, $messagereview);
		$messagereview = str_replace('%trak%', $_POST['TrackingCode'], $messagereview);
			$helpreview = 'https://stopoint.com/help';
				$messagereview = str_replace('%help%', $helpreview, $messagereview);

mysql_query("INSERT INTO tp_review_email_reminder (order_id, user_id, count, status) VALUES($id, $userid, 1, 1)");
				
$subjectreview = "Your opinion matters to stopoint.com";
$txtreview = $messagereview;

require_once '../PHPMailer-master/PHPMailerAutoload.php';
		$mailreview = new PHPMailer();
		$mailreview->IsSMTP();
		$mailreview->SMTPAuth = true;
		$mailreview->SMTPDebug = 0;
		$mailreview->SMTPSecure = 'ssl';
		$mailreview->Host = "smtp.gmail.com";
		$mailreview->Port = 465; 
		$mailreview->Username = "stopoint@stopoint.com";  
		$mailreview->Password = EMAIL_CREDENTIAL;
		$mailreview->From = "info@stopoint.com";
		$mailreview->FromName = "STOPOINT ";
		$mailreview->AddAddress($roworder1['EmailAddress']);
		$mailreview->IsHTML(true);
		$mailreview->Subject = $subjectreview;
		$mailreview->Body = $txtreview;
		$mailreview->XMailer = ' ';
		
		$sentreview = $mailreview->Send();
		
		if (!$sentreview){
					echo 'Message could not be sent to '.$roworder1['EmailAddress'];
					
								   $er=0;
				}*/
/* send review email */
				$message = file_get_contents('paid1.html');
				$message = str_replace('%name%', $roworder1['FirstName'], $message);
				$message = str_replace('%product%', $roworder1['ProductDescription'], $message);
				$message = str_replace('%amount%', $roworder1['OrderAmount'], $message);
				$message = str_replace('%expiration%',$my_date, $message);
				$message = str_replace('%trak%', $_POST['TrackingCode'], $message);
				$help = 'https://stopoint.com/help';
				$message = str_replace('%help%', $help, $message);
				
				$subject = "We have released payment for your ".$roworder1['ProductDescription'];
				$txt = $message;
				require_once '../PHPMailer-master/PHPMailerAutoload.php';
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->SMTPDebug = 0;
		$mail->SMTPSecure = 'ssl';
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 465; 
		$mail->Username = "stopoint@stopoint.com";  
		$mail->Password = EMAIL_CREDENTIAL;
		$mail->From = "info@stopoint.com";
		$mail->FromName = "STOPOINT";
		$mail->AddAddress($roworder1['EmailAddress']);
		$mail->IsHTML(true);
		$mail->Subject = $subject;
		$mail->Body = $txt;
		$mail->XMailer = ' ';
		
		$sent = $mail->Send();
				if (!$sent){
					echo 'Message could not be sent to '.$roworder1['EmailAddress'];
					
								   $er=0;
				}
				
			
	if($_POST['OrderAmount']){
		
		mysql_query("UPDATE ordertrasactions SET active=0 WHERE OrderId=$id AND active=1");
				mysql_query("INSERT INTO ordertrasactions (`OrderId`,`UserId`,`PaidById`,`AmountPaid`,`PaymentMethod`,`ChequeNumber`,`TransactionId`) VALUES('$orderid','$userid',10,'$orderamount','$paymentmethod','$chequenumber','$transactionid')") or die(mysql_error());
			$orderpaid = date('Y-m-d H:i:s');
			
			add_orderlog($_GET['id'],6);
			
			 $queryhistory = "UPDATE `orderstatushistory` SET `orderid` = ".$_GET['id'].", `datepaid`='".$orderpaid."' WHERE orderid=".$_GET['id'];
	
			$orderhistory = mysql_query($queryhistory) or die(mysql_error());
			
			}
			else{
				
				mysql_query("UPDATE ordertrasactions SET active=0 WHERE OrderId=$id AND active=1");
				
				mysql_query("INSERT INTO ordertrasactions (`OrderId`,`UserId`,`PaidById`,`AmountPaid`,`PaymentMethod`,`ChequeNumber`,`TransactionId`) VALUES('$orderid','$userid',10,'$adjustedamount','$paymentmethod','$chequenumber','$transactionid')") or die(mysql_error());
				}
	
}


if($_POST['OrderStatus'] == 6 && $weexpired['OrderStatus'] == 5 && $_POST['PaymentMethod'] == '1'){

$vEmailSubject = 'Paypal Payment';

//$environment = 'sandbox'; // or 'beta-sandbox' or 'live'.

function PPHttpPost($methodName_, $nvpStr_)
{
 global $environment;

 // Set up your API credentials, PayPal end point, and API version.
 // How to obtain API credentials:
 // https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_NVPAPIBasics#id084E30I30RO
 $API_UserName = urlencode('payment_api1.stopoint.com');
 $API_Password = urlencode('SDTP5A79SS6VZNXL');
 $API_Signature = urlencode('A0Gqqqp8jRW2lmq0SQmWBK4-ldIEAGRnYTVJ6yGG77f7QwS.65nxua2f');
 $API_Endpoint = "https://api-3t.paypal.com/nvp";
 if("sandbox" === $environment || "beta-sandbox" === $environment)
 {
  $API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
 }
 $version = urlencode('51.0');

 // Set the curl parameters.
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
 curl_setopt($ch, CURLOPT_VERBOSE, 1);

 // Turn off the server and peer verification (TrustManager Concept).
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($ch, CURLOPT_POST, 1);

 // Set the API operation, version, and API signature in the request.

 $nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";

 // Set the request as a POST FIELD for curl.
 curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq."&".$nvpStr_);

 // Get response from the server.
 $httpResponse = curl_exec($ch);

 if( !$httpResponse)
 {
  echo $methodName_ . ' failed: ' . curl_error($ch) . '(' . curl_errno($ch) .')';
 }

 // Extract the response details.
 $httpResponseAr = explode("&", $httpResponse);

 $httpParsedResponseAr = array();
 foreach ($httpResponseAr as $i => $value)
 {
  $tmpAr = explode("=", $value);
  if(sizeof($tmpAr) > 1)
  {
   $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
  }
 }

 /*if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr))
 {
  exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
 }*/

 return $httpParsedResponseAr;
}

// Set request-specific fields.
$transactionid = "TR_".rand(11111111,99999999);
$emailSubject = urlencode($vEmailSubject);
$receiverType = urlencode('EmailAddress');
$currency = urlencode('USD'); // or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
$receiveremail = $_POST['PaypalEmail'];
if($_POST['AdjustedAmount'] == ""){
$amount = $_POST['OrderAmount'];
}
else{
	$amount = $_POST['AdjustedAmount'];
	}
// Receivers
// Use '0' for a single receiver. In order to add new ones: (0, 1, 2, 3...)
// Here you can modify to obtain array data from database.
$receivers = array(
  0 => array(
    'receiverEmail' => "$receiveremail", 
    'amount' => "$amount",
    'uniqueID' => "$transactionid", // 13 chars max
    'note' => "Payment to User") // I recommend use of space at beginning of string.
  
);

$receiversLenght = count($receivers);

// Add request-specific fields to the request string.
$nvpStr="&EMAILSUBJECT=$emailSubject&RECEIVERTYPE=$receiverType&CURRENCYCODE=$currency";

$receiversArray = array();

for($i = 0; $i < $receiversLenght; $i++)
{
 $receiversArray[$i] = $receivers[$i];
}

foreach($receiversArray as $i => $receiverData)
{
 $receiverEmail = urlencode($receiverData['receiverEmail']);
 $amount = urlencode($receiverData['amount']);
 $uniqueID = urlencode($receiverData['uniqueID']);
 $note = urlencode($receiverData['note']);
 $nvpStr .= "&L_EMAIL$i=$receiverEmail&L_Amt$i=$amount&L_UNIQUEID$i=$uniqueID&L_NOTE$i=$note";
}

// Execute the API operation; see the PPHttpPost function above.
$httpParsedResponseAr = PPHttpPost('MassPay', $nvpStr);
//print_r($httpParsedResponseAr);

if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
{
 echo 'MassPay Completed Successfully: ' . $httpParsedResponseAr;



				$userid = $weuser['id'];
				$orderid = $_GET['id'];
				
				$orderamount = $_POST['OrderAmount'];
				$adjustedamount = $_POST['AdjustedAmount'];
				$paymentmethod = $weuser['PaymentMethod'];
				//$accountnumber = $_POST['AccountNumber'];
				$chequenumber = $_POST['ChequeNumber'];
				
					
				
	
	
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
FROM `order` INNER JOIN `product` ON product.ProductCode=order.ProductId INNER JOIN `user` ON user.id=order.UserId INNER JOIN `productbrand` ON productbrand.id=product.BrandId INNER JOIN `productfamily` ON productfamily.Id=product.FamilyId INNER JOIN `productcategory` ON productcategory.id=product.CategoryId WHERE order.TrackingCode= '".$_POST['TrackingCode']."'";
mysql_query("SET SQL_BIG_SELECTS=1");
$rowsorder = mysql_query($query);
$roworder = mysql_fetch_assoc($rowsorder);
 
	  $add_days = 30;
	  $my_date = date('m/d/y',strtotime($roworder['OrderDate']));
	  
	  
	  $my_date = date('m/d/y',strtotime($my_date.' +'.$add_days.' days'));
	  
	  
	  //$my_date = date("+".$days." days",strtotime($my_date));
	   
	   $orderpaid = date('Y-m-d H:i:s');
	   
	   add_orderlog($_GET['id'],6);
	   
	   $queryhistory = "UPDATE `orderstatushistory` SET `orderid` = ".$_GET['id'].", `datepaid`='".$orderpaid."' WHERE orderid=".$_GET['id'];
	
			$orderhistory = mysql_query($queryhistory) or die(mysql_error());
	  /* send review email */
	  
	  /*$messagereview = file_get_contents('review.html');
        $messagereview = str_replace('%name%', $roworder['FirstName'], $messagereview);
		$messagereview = str_replace('%product%', $roworder['ProductDescription'], $messagereview);
		$messagereview = str_replace('%amount%', $roworder['OrderAmount'], $messagereview);
		$messagereview = str_replace('%expiration%',$my_date, $messagereview);
		$messagereview = str_replace('%user_id%',$userid, $messagereview);
		$messagereview = str_replace('%trak%', $_POST['TrackingCode'], $messagereview);
			$helpreview = 'https://stopoint.com/help';
				$messagereview = str_replace('%help%', $helpreview, $messagereview);
			 
$subjectreview = "Your opinion matters to stopoint.com";
$txtreview = $messagereview;

mysql_query("INSERT INTO tp_review_email_reminder (order_id, user_id, count, status) VALUES($id, $userid, 1, 1)");

require_once '../PHPMailer-master/PHPMailerAutoload.php';
		$mailreview = new PHPMailer();
		$mailreview->IsSMTP();
		$mailreview->SMTPAuth = true;
		$mailreview->SMTPDebug = 0;
		$mailreview->SMTPSecure = 'ssl';
		$mailreview->Host = "smtp.gmail.com";
		$mailreview->Port = 465; 
		$mailreview->Username = "stopoint@stopoint.com";  
		$mailreview->Password = EMAIL_CREDENTIAL;
		$mailreview->From = "info@stopoint.com";
		$mailreview->FromName = "STOPOINT ";
		$mailreview->AddAddress($roworder['EmailAddress']);
		$mailreview->IsHTML(true);
		$mailreview->Subject = $subjectreview;
		$mailreview->Body = $txtreview;
		$mailreview->XMailer = ' ';
		
		$sentreview = $mailreview->Send();*/
/* send review email */
	  
			 $message = file_get_contents('paid.html');
        $message = str_replace('%name%', $roworder['FirstName'], $message);
		$message = str_replace('%product%', $roworder['ProductDescription'], $message);
		$message = str_replace('%amount%', $roworder['OrderAmount'], $message);
		$message = str_replace('%expiration%',$my_date, $message);
		$message = str_replace('%trak%', $_POST['TrackingCode'], $message);
			$help = 'https://stopoint.com/help';
				$message = str_replace('%help%', $help, $message);
			 
$subject = "We have released payment for your ".$roworder['ProductDescription'];
$txt = $message;
require_once '../PHPMailer-master/PHPMailerAutoload.php';
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->SMTPDebug = 0;
		$mail->SMTPSecure = 'ssl';
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 465; 
		$mail->Username = "stopoint@stopoint.com";  
		$mail->Password = EMAIL_CREDENTIAL;
		$mail->From = "info@stopoint.com";
		$mail->FromName = "STOPOINT ";
		$mail->AddAddress($roworder['EmailAddress']);
		$mail->IsHTML(true);
		$mail->Subject = $subject;
		$mail->Body = $txt;
		$mail->XMailer = ' ';
		
		$sent = $mail->Send();
if (!$sent){
	echo 'Message could not be sent to '.$roworder['EmailAddress'];
	
                   $er=0;
}

	
				//echo "INSERT INTO ordertrasactions (`OrderId`,`UserId`,`PaidById`,`AmountPaid`,`PaymentMethod`,`TransactionId`) VALUES('$orderid','$userid',10,'$orderamount','$paymentmethod','$transactionid')"; exit;
				if($_POST['OrderAmount']){
				mysql_query("INSERT INTO ordertrasactions (`OrderId`,`UserId`,`PaidById`,`AmountPaid`,`PaymentMethod`,`ChequeNumber`,`TransactionId`) VALUES('$orderid','$userid',10,'$orderamount','$paymentmethod','$chequenumber','$transactionid')") or die(mysql_error());
			
			
			}
			else{
				mysql_query("INSERT INTO ordertrasactions (`OrderId`,`UserId`,`PaidById`,`AmountPaid`,`PaymentMethod`,`ChequeNumber`,`TransactionId`) VALUES('$orderid','$userid',10,'$adjustedamount','$paymentmethod','$chequenumber','$transactionid')") or die(mysql_error());
				}
			


	header("Location: $url?key=$key&saved&p=$p&sort=id&success=paypal&desc");
	
		
		


}
else
{
 echo '\nMassPay failed: ';
 print_r($httpParsedResponseAr);
 $_POST['OrderStatus'] = 5;
header("Location: $url?key=$key&p=$p&sort=id&success=error&desc");
}
			
			
			
			

}


$form->setPostFields($fields,'id', $id);

/////////////////////////////////////////


if($_POST['OrderStatus'] == 1){
	
	add_orderlog($_GET['id'],1);
	
	$vascheckhistory = "SELECT * FROM `orderstatushistory` WHERE orderid=".$_GET['id']." AND `dateordered` IS NOT NULL";
$recheckhistory=mysql_query($vascheckhistory);
$wehistory= mysql_num_rows($recheckhistory);
if($wehistory > 0){ }
else{
	$orderdate = date('Y-m-d H:i:s');
	
 $queryhistory = "UPDATE `orderstatushistory` SET `orderid` = ".$_GET['id'].", `dateordered`='".$orderdate."' WHERE orderid=".$_GET['id'];
	
			$orderhistory = mysql_query($queryhistory) or die(mysql_error());
}
}

if($_POST['OrderStatus'] == 3){
	
	add_orderlog($_GET['id'],3);
	
	$vascheckhistory = "SELECT * FROM `orderstatushistory` WHERE orderid=".$_GET['id']." AND `dateadjusted` IS NOT NULL";
$recheckhistory=mysql_query($vascheckhistory);
$wehistory= mysql_num_rows($recheckhistory);
if($wehistory > 0){ }
//else{
	$orderadjusted = date('Y-m-d H:i:s');
	
$queryhistory = "UPDATE `orderstatushistory` SET `orderid` = ".$_GET['id'].", `dateadjusted`='".$orderadjusted."' WHERE orderid=".$_GET['id'];
	
			$orderhistory = mysql_query($queryhistory) or die(mysql_error());
			
			$query = "SELECT order.id as OrderId, order.TrackingCode as TrackingCode, order.FedexCode as FedexCode, order.OrderDate as OrderDate, order.OrderAmount as OrderAmount , order.AdjustedAmount as AdjustedAmount , product.ProductModel as ProductModel , product.ProductCode as ProductCode, product.Description as ProductDescription, productfamily.image_url as ProductImage, user.FirstName as FirstName, user.LastName as LastName, user.EmailAddress as EmailAddress, user.S_AddressLine1 as S_AddressLine1, user.S_AddressLine2 as S_AddressLine2 , user.S_City as S_City, user.S_State as S_State, user.S_Country as S_Country,user.S_PostalCode as S_PostalCode, user.PaypalEmail as PaypalEmail, user.Phone as Phone ,
 CASE user.PaymentMethod
  WHEN '2' THEN 'Cheque'
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
FROM `order` INNER JOIN `product` ON product.ProductCode=order.ProductId INNER JOIN `user` ON user.id=order.UserId INNER JOIN `productbrand` ON productbrand.id=product.BrandId INNER JOIN `productfamily` ON productfamily.Id=product.FamilyId INNER JOIN `productcategory` ON productcategory.id=product.CategoryId WHERE order.TrackingCode= '".$_POST['TrackingCode']."'";
mysql_query("SET SQL_BIG_SELECTS=1");
$rowsorder = mysql_query($query);
$roworder = mysql_fetch_assoc($rowsorder);
			
			 $message = file_get_contents('adjust.html');
        $message = str_replace('%name%', $roworder['FirstName'], $message);
		$message = str_replace('%product%', $roworder['ProductDescription'], $message);
		$message = str_replace('%amount%', $roworder['AdjustedAmount'], $message);
		$message = str_replace('%expiration%',$my_date, $message);
		$message = str_replace('%trak%', $_POST['TrackingCode'], $message);
			$help = 'https://stopoint.com/help';
				$message = str_replace('%help%', $help, $message);
			 
$subject = "Urgent: Action Required on Your ".$roworder['ProductDescription'];
$txt = $message;
require_once '../PHPMailer-master/PHPMailerAutoload.php';
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->SMTPDebug = 0;
		$mail->SMTPSecure = 'ssl';
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 465; 
		$mail->Username = "stopoint@stopoint.com";  
		$mail->Password = EMAIL_CREDENTIAL;
		$mail->From = "info@stopoint.com";
		$mail->FromName = "STOPOINT ";
		$mail->AddAddress($roworder['EmailAddress']);
		$mail->IsHTML(true);
		$mail->Subject = $subject;
		$mail->Body = $txt;
		$mail->XMailer = ' ';
		
		$sent = $mail->Send();
		
if (!$sent){
	echo 'Message could not be sent to '.$roworder['EmailAddress'];
	
                   $er=0;
}
			
//}
	
}
if($_POST['OrderStatus'] == 5){
	
	add_orderlog($_GET['id'],5);
	
	$vascheckhistory = "SELECT * FROM `orderstatushistory` WHERE orderid=".$_GET['id']." AND `datereleased` IS NOT NULL";

$recheckhistory=mysql_query($vascheckhistory);
$wehistory= mysql_num_rows($recheckhistory);
if($wehistory > 0){ }
else{
	$orderreleased = date('Y-m-d H:i:s');		
	
 $queryhistory = "UPDATE `orderstatushistory` SET `orderid` = ".$_GET['id'].", `datereleased`='".$orderreleased."' WHERE orderid=".$_GET['id'];
	
			$orderhistory = mysql_query($queryhistory) or die(mysql_error());
}
}

if($_POST['OrderStatus'] == 7){
	
	add_orderlog($_GET['id'],7);
	
	$vascheckhistory = "SELECT * FROM `orderstatushistory` WHERE orderid=".$_GET['id']." AND `datecancelled` IS NOT NULL";
$recheckhistory=mysql_query($vascheckhistory);
$wehistory= mysql_num_rows($recheckhistory);
if($wehistory > 0){ }
else{
	$ordercancel = date('Y-m-d H:i:s');		
	
$queryhistory = "UPDATE `orderstatushistory` SET `orderid` = ".$_GET['id'].", `datecancelled`='".$ordercancel."' WHERE orderid=".$_GET['id'];
	
			$orderhistory = mysql_query($queryhistory) or die(mysql_error());
}
}

//expired
if($_POST['OrderStatus'] == 8)
{
	add_orderlog($_GET['id'],8);
	
	$vascheckhistory = "SELECT * FROM `orderstatushistory` WHERE orderid=".$_GET['id']." AND `dateexpired` IS NOT NULL";
	$recheckhistory=mysql_query($vascheckhistory);
	$wehistory= mysql_num_rows($recheckhistory);
	if($wehistory > 0){ }
	else
	{
		$dateexpired = date('Y-m-d H:i:s');				
		
		$queryhistory = "UPDATE `orderstatushistory` SET `orderid` = ".$_GET['id'].", `dateexpired`='".$dateexpired."' WHERE orderid=".$_GET['id'];
		
		$orderhistory = mysql_query($queryhistory) or die(mysql_error());
	}
}
//end expired
//return completed
if($_POST['OrderStatus'] == 9)
{
	add_orderlog($_GET['id'],9);
	
	$vascheckhistory = "SELECT * FROM `orderstatushistory` WHERE orderid=".$_GET['id']." AND `datereturncompleted` IS NOT NULL";
	$recheckhistory=mysql_query($vascheckhistory);
	$wehistory= mysql_num_rows($recheckhistory);
	if($wehistory > 0)
	{ 
	}
	else
	{
		$datereturncompleted = date('Y-m-d H:i:s');				
		
		$queryhistory = "UPDATE `orderstatushistory` SET `orderid` = ".$_GET['id'].", `datereturncompleted`='".$datereturncompleted."' WHERE orderid=".$_GET['id'];
		$orderhistory = mysql_query($queryhistory) or die(mysql_error());
		
		//mail code
			$query = "SELECT order.id as OrderId, order.TrackingCode as TrackingCode, order.FedexCode as FedexCode, order.OrderDate as OrderDate, order.OrderAmount as OrderAmount , order.AdjustedAmount as AdjustedAmount , product.ProductModel as ProductModel , product.ProductCode as ProductCode, product.Description as ProductDescription, productfamily.image_url as ProductImage, user.FirstName as FirstName, user.LastName as LastName, user.EmailAddress as EmailAddress, user.S_AddressLine1 as S_AddressLine1, user.S_AddressLine2 as S_AddressLine2 , user.S_City as S_City, user.S_State as S_State, user.S_Country as S_Country,user.S_PostalCode as S_PostalCode, user.PaypalEmail as PaypalEmail, user.Phone as Phone ,
	 CASE user.PaymentMethod
	  WHEN '2' THEN 'Cheque'
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
	FROM `order` INNER JOIN `product` ON product.ProductCode=order.ProductId INNER JOIN `user` ON user.id=order.UserId INNER JOIN `productbrand` ON productbrand.id=product.BrandId INNER JOIN `productfamily` ON productfamily.Id=product.FamilyId INNER JOIN `productcategory` ON productcategory.id=product.CategoryId WHERE order.TrackingCode= '".$_POST['TrackingCode']."'";
		mysql_query("SET SQL_BIG_SELECTS=1");
		$rowsorder = mysql_query($query);
		$roworder = mysql_fetch_assoc($rowsorder);
		$add_days = 30;
		$my_date = date('m/d/y',strtotime($roworder['OrderDate']));
		$my_date = date('m/d/y',strtotime($my_date.' +'.$add_days.' days'));
		//$my_date = date("+".$days." days",strtotime($my_date));
		$message = file_get_contents('return.html');
		$message = str_replace('%name%', $roworder['FirstName'], $message);
		$message = str_replace('%product%', $roworder['ProductDescription'], $message);
		$message = str_replace('%fedex%', $roworder['FedexCode'], $message);
		$message = str_replace('%amount%', $roworder['OrderAmount'], $message);
		$message = str_replace('%expiration%',$my_date, $message);
		$message = str_replace('%trak%', $_POST['TrackingCode'], $message);
		$help = 'https://stopoint.com/help';
		$message = str_replace('%help%', $help, $message);
		$subject = "We have returned your ".$roworder['ProductDescription']." back to you";
		$txt = $message;
		require_once '../PHPMailer-master/PHPMailerAutoload.php';
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->SMTPDebug = 0;
		$mail->SMTPSecure = 'ssl';
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 465; 
		$mail->Username = "stopoint@stopoint.com";  
		$mail->Password = EMAIL_CREDENTIAL;
		$mail->From = "info@stopoint.com";
		$mail->FromName = "STOPOINT ";
		$mail->AddAddress($roworder['EmailAddress']);
		$mail->IsHTML(true);
		$mail->Subject = $subject;
		$mail->Body = $txt;
		$mail->XMailer = ' ';
		$sent = $mail->Send();
		if (!$sent)
		{
			echo 'Message could not be sent to '.$roworder['EmailAddress'];
			$er=0;
		}
		//end for mail code
	}
}
//end return completed
if($_POST['OrderStatus'] == 4)
{
	add_orderlog($_GET['id'],4);
	
	$vascheckhistory = "SELECT * FROM `orderstatushistory` WHERE orderid=".$_GET['id']." AND `datereturned` IS NOT NULL";
	$recheckhistory=mysql_query($vascheckhistory);
	$wehistory= mysql_num_rows($recheckhistory);
	if($wehistory > 0){ }
	else
	{
		$orderreturned = date('Y-m-d H:i:s');				
		
		$queryhistory = "UPDATE `orderstatushistory` SET `orderid` = ".$_GET['id'].", `datereturned`='".$orderreturned."' WHERE orderid=".$_GET['id'];
		$orderhistory = mysql_query($queryhistory) or die(mysql_error());

	/*	$query = "SELECT order.id as OrderId, order.TrackingCode as TrackingCode, order.FedexCode as FedexCode, order.OrderDate as OrderDate, order.OrderAmount as OrderAmount , order.AdjustedAmount as AdjustedAmount , product.ProductModel as ProductModel , product.ProductCode as ProductCode, product.Description as ProductDescription, productfamily.image_url as ProductImage, user.FirstName as FirstName, user.LastName as LastName, user.EmailAddress as EmailAddress, user.S_AddressLine1 as S_AddressLine1, user.S_AddressLine2 as S_AddressLine2 , user.S_City as S_City, user.S_State as S_State, user.S_Country as S_Country,user.S_PostalCode as S_PostalCode, user.PaypalEmail as PaypalEmail, user.Phone as Phone ,
 CASE user.PaymentMethod
  WHEN '2' THEN 'Cheque'
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
FROM `order` INNER JOIN `product` ON product.ProductCode=order.ProductId INNER JOIN `user` ON user.id=order.UserId INNER JOIN `productbrand` ON productbrand.id=product.BrandId INNER JOIN `productfamily` ON productfamily.Id=product.FamilyId INNER JOIN `productcategory` ON productcategory.id=product.CategoryId WHERE order.TrackingCode= '".$_POST['TrackingCode']."'";
	$rowsorder = mysql_query($query);
	$roworder = mysql_fetch_assoc($rowsorder);
 	$add_days = 30;
	$my_date = date('m/d/y',strtotime($roworder['OrderDate']));
	$my_date = date('m/d/y',strtotime($my_date.' +'.$add_days.' days'));
	//$my_date = date("+".$days." days",strtotime($my_date));
	$message = file_get_contents('return.html');
    $message = str_replace('%name%', $roworder['FirstName'], $message);
	$message = str_replace('%product%', $roworder['ProductDescription'], $message);
	$message = str_replace('%fedex%', $roworder['FedexCode'], $message);
	$message = str_replace('%amount%', $roworder['OrderAmount'], $message);
	$message = str_replace('%expiration%',$my_date, $message);
	$message = str_replace('%trak%', $_POST['TrackingCode'], $message);
	$help = 'https://stopoint.com/help';
	$message = str_replace('%help%', $help, $message);
	$subject = "We have returned your ".$roworder['ProductDescription']." back to you";
	$txt = $message;
	require_once '../PHPMailer-master/PHPMailerAutoload.php';
	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->SMTPDebug = 0;
	$mail->SMTPSecure = 'ssl';
	$mail->Host = "smtp.gmail.com";
	$mail->Port = 465; 
	$mail->Username = "stopoint@stopoint.com";  
	$mail->Password = EMAIL_CREDENTIAL;
	$mail->From = "info@stopoint.com";
	$mail->FromName = "STOPOINT ";
	$mail->AddAddress($roworder['EmailAddress']);
	$mail->IsHTML(true);
	$mail->Subject = $subject;
	$mail->Body = $txt;
	$mail->XMailer = ' ';
	$sent = $mail->Send();
	if (!$sent)
	{
		echo 'Message could not be sent to '.$roworder['EmailAddress'];
		$er=0;
	} */
	}
}

if($_POST['OrderStatus'] == 2){
	
	add_orderlog($_GET['id'],2);
	
	$vascheckhistory = "SELECT * FROM `orderstatushistory` WHERE orderid=".$_GET['id']." AND `datereceived` IS NOT NULL";
$recheckhistory=mysql_query($vascheckhistory);
$wehistory= mysql_num_rows($recheckhistory);
if($wehistory > 0){}
else{
	$orderreceived = date('Y-m-d H:i:s');		
	
$queryhistory = "UPDATE `orderstatushistory` SET `orderid` = ".$_GET['id'].", `datereceived`='".$orderreceived."' WHERE orderid=".$_GET['id'];
	
			$orderhistory = mysql_query($queryhistory) or die(mysql_error());
}
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
FROM `order` INNER JOIN `product` ON product.ProductCode=order.ProductId INNER JOIN `user` ON user.id=order.UserId INNER JOIN `productbrand` ON productbrand.id=product.BrandId INNER JOIN `productfamily` ON productfamily.Id=product.FamilyId INNER JOIN `productcategory` ON productcategory.id=product.CategoryId WHERE order.TrackingCode= '".$_POST['TrackingCode']."'";
mysql_query("SET SQL_BIG_SELECTS=1");
$rowsorder = mysql_query($query);
$roworder = mysql_fetch_assoc($rowsorder);
 
	  $add_days = 30;
	  $my_date = date('m/d/y',strtotime($roworder['OrderDate']));
	  
	  
	  $my_date = date('m/d/y',strtotime($my_date.' +'.$add_days.' days'));
	  
			 $message = file_get_contents('received.html');
        $message = str_replace('%name%', $roworder['FirstName'], $message);
		$message = str_replace('%product%', $roworder['ProductDescription'], $message);
		$message = str_replace('%amount%', $roworder['OrderAmount'], $message);
		$message = str_replace('%expiration%',$my_date, $message);
		$message = str_replace('%trak%', $_POST['TrackingCode'], $message);
			$help = 'https://stopoint.com/help';
				$message = str_replace('%help%', $help, $message);
			 



//$to = $roworder['EmailAddress'];
$email_from = "info@stopoint.com";

//$headers  = 'MIME-Version: 1.0' . "\r\n";
//$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
//$headers .= 'From: Stopoint <support@stopoint.com>' . "\r\n";
$subject = "We Received Your ".$roworder['ProductDescription'];
$txt = $message;

require_once '../PHPMailer-master/PHPMailerAutoload.php';
		$mail = new PHPMailer();
		$mail->IsHTML(true);
		$mail->CharSet = "text/html; charset=UTF-8;";
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->SMTPDebug = 0;
		$mail->SMTPSecure = 'ssl';
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 465; 
		$mail->Username = "stopoint@stopoint.com";  
		$mail->Password = EMAIL_CREDENTIAL;
		$mail->From = $email_from;
		$mail->FromName = "Stopoint ";
		$mail->AddAddress($roworder['EmailAddress']);
		$mail->Subject = $subject;
		$mail->Body = $txt;
		
		
		$sent = $mail->Send();
		
		//$sent = mail($to, $subject, $txt, $headers);
		
		//print_r($sent);
		//exit;
		
if (!$sent){
	echo 'Message could not be sent to '.$roworder['EmailAddress'];
	
                   $er=0;
}


	}
	
	
	if($_POST['OrderStatus'] == 10){
	
	/*$vascheckhistory = "SELECT * FROM `orderstatushistory` WHERE orderid=".$_GET['id']." AND `datereceived` IS NOT NULL";
$recheckhistory=mysql_query($vascheckhistory);
$wehistory= mysql_num_rows($recheckhistory);
if($wehistory > 0){}
else{
	$orderreceived = date('Y-m-d H:i:s');
$queryhistory = "UPDATE `orderstatushistory` SET `orderid` = ".$_GET['id'].", `datereceived`='".$orderreceived."' WHERE orderid=".$_GET['id'];
	
			$orderhistory = mysql_query($queryhistory) or die(mysql_error());
}*/ 

add_orderlog($_GET['id'],10);

//activation lock
$vascheckhistory = "SELECT * FROM `orderstatushistory` WHERE orderid=".$_GET['id']." AND `dateactivationlock` IS NOT NULL";
		$recheckhistory=mysql_query($vascheckhistory);
		$wehistory= mysql_num_rows($recheckhistory);
		if($wehistory > 0){}
		else
		{
			$orderreceived = date('Y-m-d H:i:s');						
			
			$queryhistory = "UPDATE `orderstatushistory` SET `orderid` = ".$_GET['id'].", `dateactivationlock`='".$orderreceived."' WHERE orderid=".$_GET['id'];
			$orderhistory = mysql_query($queryhistory) or die(mysql_error());
		}
//end activation lock
	$query = "SELECT order.id as OrderId, order.TrackingCode as TrackingCode, order.FedexCode as FedexCode, order.OrderDate as OrderDate, order.OrderAmount as OrderAmount , order.AdjustedAmount as AdjustedAmount , product.ProductModel as ProductModel , product.ProductCode as ProductCode, product.Description as ProductDescription, productfamily.image_url as ProductImage, user.FirstName as FirstName, user.LastName as LastName, user.EmailAddress as EmailAddress, user.S_AddressLine1 as S_AddressLine1, user.S_AddressLine2 as S_AddressLine2 , user.S_City as S_City, user.S_State as S_State, user.S_Country as S_Country,user.S_PostalCode as S_PostalCode, user.PaypalEmail as PaypalEmail, user.Phone as Phone ,
 CASE user.PaymentMethod
  WHEN '2' THEN 'Check'
  WHEN '1' THEN 'Paypal'
 ELSE 'Nothing'
 END as PaymentMethod,
 CASE order.OrderStatus
  WHEN '10' THEN 'Activation Lock'
  WHEN '7' THEN 'Cancelled' 
  WHEN '6' THEN 'Paid'
  WHEN '5' THEN 'Release Payment'
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
FROM `order` INNER JOIN `product` ON product.ProductCode=order.ProductId INNER JOIN `user` ON user.id=order.UserId INNER JOIN `productbrand` ON productbrand.id=product.BrandId INNER JOIN `productfamily` ON productfamily.Id=product.FamilyId INNER JOIN `productcategory` ON productcategory.id=product.CategoryId WHERE order.TrackingCode= '".$_POST['TrackingCode']."'";
mysql_query("SET SQL_BIG_SELECTS=1");
$rowsorder = mysql_query($query);
$roworder = mysql_fetch_assoc($rowsorder);
 
	  $add_days = 30;
	  $my_date = date('m/d/y',strtotime($roworder['OrderDate']));
	  
	  
	  $my_date = date('m/d/y',strtotime($my_date.' +'.$add_days.' days'));
	  
			 $message = file_get_contents('activation.html');
        $message = str_replace('%name%', $roworder['FirstName'], $message);
		$message = str_replace('%product%', $roworder['ProductDescription'], $message);
		$message = str_replace('%amount%', $roworder['OrderAmount'], $message);
		$message = str_replace('%expiration%',$my_date, $message);
		$message = str_replace('%trak%', $_POST['TrackingCode'], $message);
		$message = str_replace('%serial%', $_POST['ProductSerial'], $message);
			$help = 'https://stopoint.com/help';
				$message = str_replace('%help%', $help, $message);
			 



//$to = $roworder['EmailAddress'];
$email_from = "info@stopoint.com";

//$headers  = 'MIME-Version: 1.0' . "\r\n";
//$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
//$headers .= 'From: Stopoint <support@stopoint.com>' . "\r\n";
$subject = "Urgent: Action Required on Your ".$roworder['ProductDescription'];
$txt = $message;

require_once '../PHPMailer-master/PHPMailerAutoload.php';
		$mail = new PHPMailer();
		$mail->IsHTML(true);
		$mail->CharSet = "text/html; charset=UTF-8;";
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->SMTPDebug = 0;
		$mail->SMTPSecure = 'ssl';
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 465; 
		$mail->Username = "stopoint@stopoint.com";  
		$mail->Password = EMAIL_CREDENTIAL;
		$mail->From = $email_from;
		$mail->FromName = "Stopoint ";
		$mail->AddAddress($roworder['EmailAddress']);
		$mail->Subject = $subject;
		$mail->Body = $txt;
		
		
		$sent = $mail->Send();
		
		//$sent = mail($to, $subject, $txt, $headers);
		
		//print_r($sent);
		//exit;
		
if (!$sent){
	echo 'Message could not be sent to '.$roworder['EmailAddress'];
	
                   $er=0;
} 


	}

/*
Started code for Order Status starting from 11 till 14
*/

//Installment Payment	
	if($_POST['OrderStatus'] == 11){

	add_orderlog($_GET['id'],11);

//activation lock
$vascheckhistory = "SELECT * FROM `orderstatushistory` WHERE orderid=".$_GET['id']." AND `dateinstallment` IS NOT NULL";
		$recheckhistory=mysql_query($vascheckhistory);
		$wehistory= mysql_num_rows($recheckhistory);
		if($wehistory > 0){}
		else
		{
			$orderreceived = date('Y-m-d H:i:s');						
			
			$queryhistory = "UPDATE `orderstatushistory` SET `orderid` = ".$_GET['id'].", `dateinstallment`='".$orderreceived."' WHERE orderid=".$_GET['id'];
			$orderhistory = mysql_query($queryhistory) or die(mysql_error());
		}
//end activation lock
	$query = "SELECT order.id as OrderId, order.TrackingCode as TrackingCode, order.FedexCode as FedexCode, order.OrderDate as OrderDate, order.OrderAmount as OrderAmount , order.AdjustedAmount as AdjustedAmount , product.ProductModel as ProductModel , product.ProductCode as ProductCode, product.Description as ProductDescription, productfamily.image_url as ProductImage, user.FirstName as FirstName, user.LastName as LastName, user.EmailAddress as EmailAddress, user.S_AddressLine1 as S_AddressLine1, user.S_AddressLine2 as S_AddressLine2 , user.S_City as S_City, user.S_State as S_State, user.S_Country as S_Country,user.S_PostalCode as S_PostalCode, user.PaypalEmail as PaypalEmail, user.Phone as Phone ,
 CASE user.PaymentMethod
  WHEN '2' THEN 'Check'
  WHEN '1' THEN 'Paypal'
 ELSE 'Nothing'
 END as PaymentMethod,
 CASE order.OrderStatus
  WHEN '11' THEN 'Installment Payment'
  WHEN '7' THEN 'Cancelled' 
  WHEN '6' THEN 'Paid'
  WHEN '5' THEN 'Release Payment'
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
FROM `order` INNER JOIN `product` ON product.ProductCode=order.ProductId INNER JOIN `user` ON user.id=order.UserId INNER JOIN `productbrand` ON productbrand.id=product.BrandId INNER JOIN `productfamily` ON productfamily.Id=product.FamilyId INNER JOIN `productcategory` ON productcategory.id=product.CategoryId WHERE order.TrackingCode= '".$_POST['TrackingCode']."'";
mysql_query("SET SQL_BIG_SELECTS=1");
$rowsorder = mysql_query($query);
$roworder = mysql_fetch_assoc($rowsorder);
 
	  $add_days = 30;
	  $my_date = date('m/d/y',strtotime($roworder['OrderDate']));
	  
	  
	  $my_date = date('m/d/y',strtotime($my_date.' +'.$add_days.' days'));
	  
			 $message = file_get_contents('installment.html');
        $message = str_replace('%name%', $roworder['FirstName'], $message);
		$message = str_replace('%product%', $roworder['ProductDescription'], $message);
		$message = str_replace('%amount%', $roworder['OrderAmount'], $message);
		$message = str_replace('%expiration%',$my_date, $message);
		$message = str_replace('%trak%', $_POST['TrackingCode'], $message);
		$message = str_replace('%serial%', $_POST['ProductSerial'], $message);
			$help = 'https://stopoint.com/help';
				$message = str_replace('%help%', $help, $message);
			 



//$to = $roworder['EmailAddress'];
$email_from = "info@stopoint.com";

//$headers  = 'MIME-Version: 1.0' . "\r\n";
//$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
//$headers .= 'From: Stopoint <support@stopoint.com>' . "\r\n";
$subject = "Urgent: Action Required on Your ".$roworder['ProductDescription'];
$txt = $message;

require_once '../PHPMailer-master/PHPMailerAutoload.php';
		$mail = new PHPMailer();
		$mail->IsHTML(true);
		$mail->CharSet = "text/html; charset=UTF-8;";
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->SMTPDebug = 0;
		$mail->SMTPSecure = 'ssl';
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 465; 
		$mail->Username = "stopoint@stopoint.com";  
		$mail->Password = EMAIL_CREDENTIAL;
		$mail->From = $email_from;
		$mail->FromName = "Stopoint ";
		$mail->AddAddress($roworder['EmailAddress']);
		$mail->Subject = $subject;
		$mail->Body = $txt;
		
		
		$sent = $mail->Send();
		
		//$sent = mail($to, $subject, $txt, $headers);
		
		//print_r($sent);
		//exit;
		
if (!$sent){
	echo 'Message could not be sent to '.$roworder['EmailAddress'];
	
                   $er=0;
} 


	}

	//IMEI Check
	if($_POST['OrderStatus'] == 12){

	add_orderlog($_GET['id'],12);

//IMEI Check
$vascheckhistory = "SELECT * FROM `orderstatushistory` WHERE orderid=".$_GET['id']." AND `dateimei` IS NOT NULL";
		$recheckhistory=mysql_query($vascheckhistory);
		$wehistory= mysql_num_rows($recheckhistory);
		if($wehistory > 0){}
		else
		{
			$orderreceived = date('Y-m-d H:i:s');						
			
			$queryhistory = "UPDATE `orderstatushistory` SET `orderid` = ".$_GET['id'].", `dateimei`='".$orderreceived."' WHERE orderid=".$_GET['id'];
			$orderhistory = mysql_query($queryhistory) or die(mysql_error());
		}
//end IMEI Check
	}
	
	//activation-lock inspection
	if($_POST['OrderStatus'] == 13){

	add_orderlog($_GET['id'],13);

//activation-lock inspection
$vascheckhistory = "SELECT * FROM `orderstatushistory` WHERE orderid=".$_GET['id']." AND `dateactivationlockinspection` IS NOT NULL";
		$recheckhistory=mysql_query($vascheckhistory);
		$wehistory= mysql_num_rows($recheckhistory);
		if($wehistory > 0){}
		else
		{
			$orderreceived = date('Y-m-d H:i:s');						
			
			$queryhistory = "UPDATE `orderstatushistory` SET `orderid` = ".$_GET['id'].", `dateactivationlockinspection`='".$orderreceived."' WHERE orderid=".$_GET['id'];
			$orderhistory = mysql_query($queryhistory) or die(mysql_error());
		}
//end activation-lock inspection
	}
	
	//Blacklisted
	if($_POST['OrderStatus'] == 14){

	add_orderlog($_GET['id'],14);

	//Blacklisted
	$vascheckhistory = "SELECT * FROM `orderstatushistory` WHERE orderid=".$_GET['id']." AND `dateblacklisted` IS NOT NULL";
			$recheckhistory=mysql_query($vascheckhistory);
			$wehistory= mysql_num_rows($recheckhistory);
			if($wehistory > 0){}
			else
			{
				$orderreceived = date('Y-m-d H:i:s');								
				
				$queryhistory = "UPDATE `orderstatushistory` SET `orderid` = ".$_GET['id'].", `dateblacklisted`='".$orderreceived."' WHERE orderid=".$_GET['id'];
				$orderhistory = mysql_query($queryhistory) or die(mysql_error());
			}
			
$query = "SELECT order.id as OrderId, order.TrackingCode as TrackingCode, order.FedexCode as FedexCode, order.OrderDate as OrderDate, order.OrderAmount as OrderAmount , order.AdjustedAmount as AdjustedAmount , product.ProductModel as ProductModel , product.ProductCode as ProductCode, product.Description as ProductDescription, productfamily.image_url as ProductImage, user.FirstName as FirstName, user.LastName as LastName, user.EmailAddress as EmailAddress, user.S_AddressLine1 as S_AddressLine1, user.S_AddressLine2 as S_AddressLine2 , user.S_City as S_City, user.S_State as S_State, user.S_Country as S_Country,user.S_PostalCode as S_PostalCode, user.PaypalEmail as PaypalEmail, user.Phone as Phone ,
 CASE user.PaymentMethod
  WHEN '2' THEN 'Check'
  WHEN '1' THEN 'Paypal'
 ELSE 'Nothing'
 END as PaymentMethod,
 CASE order.OrderStatus
  WHEN '14' THEN 'Blacklisted'
  WHEN '7' THEN 'Cancelled' 
  WHEN '6' THEN 'Paid'
  WHEN '5' THEN 'Release Payment'
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
FROM `order` INNER JOIN `product` ON product.ProductCode=order.ProductId INNER JOIN `user` ON user.id=order.UserId INNER JOIN `productbrand` ON productbrand.id=product.BrandId INNER JOIN `productfamily` ON productfamily.Id=product.FamilyId INNER JOIN `productcategory` ON productcategory.id=product.CategoryId WHERE order.TrackingCode= '".$_POST['TrackingCode']."'";
mysql_query("SET SQL_BIG_SELECTS=1");
$rowsorder = mysql_query($query);
$roworder = mysql_fetch_assoc($rowsorder);
 
	  $add_days = 30;
	  $my_date = date('m/d/y',strtotime($roworder['OrderDate']));
	  
	  
	  $my_date = date('m/d/y',strtotime($my_date.' +'.$add_days.' days'));
	  
			 $message = file_get_contents('blacklisted.html');
        $message = str_replace('%name%', $roworder['FirstName'], $message);
		$message = str_replace('%product%', $roworder['ProductDescription'], $message);
		$message = str_replace('%amount%', $roworder['OrderAmount'], $message);
		$message = str_replace('%expiration%',$my_date, $message);
		$message = str_replace('%trak%', $_POST['TrackingCode'], $message);
		$message = str_replace('%serial%', $_POST['ProductSerial'], $message);
			$help = 'https://stopoint.com/help';
				$message = str_replace('%help%', $help, $message);
			 



//$to = $roworder['EmailAddress'];
$email_from = "info@stopoint.com";

//$headers  = 'MIME-Version: 1.0' . "\r\n";
//$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
//$headers .= 'From: Stopoint <support@stopoint.com>' . "\r\n";
$subject = "Important Information Concerning Your ".$roworder['ProductDescription']." for blacklisted";
$txt = $message;

require_once '../PHPMailer-master/PHPMailerAutoload.php';
		$mail = new PHPMailer();
		$mail->IsHTML(true);
		$mail->CharSet = "text/html; charset=UTF-8;";
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->SMTPDebug = 0;
		$mail->SMTPSecure = 'ssl';
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 465; 
		$mail->Username = "stopoint@stopoint.com";  
		$mail->Password = EMAIL_CREDENTIAL;
		$mail->From = $email_from;
		$mail->FromName = "Stopoint ";
		$mail->AddAddress($roworder['EmailAddress']);
		$mail->Subject = $subject;
		$mail->Body = $txt;
		
		
		$sent = $mail->Send();
		
		//$sent = mail($to, $subject, $txt, $headers);
		
		//print_r($sent);
		//exit;
		
if (!$sent){
	echo 'Message could not be sent to '.$roworder['EmailAddress'];
	
                   $er=0;
} 			
	//end blacklisted
		}		
		
		
		
		//adjusted-price inspection
	if($_POST['OrderStatus'] == 15){

	add_orderlog($_GET['id'],15);

//adjusted-price inspection
$vascheckhistory = "SELECT * FROM `orderstatushistory` WHERE orderid=".$_GET['id']." AND `dateadjustedpriceinspection` IS NOT NULL";
		$recheckhistory=mysql_query($vascheckhistory);
		$wehistory= mysql_num_rows($recheckhistory);
		if($wehistory > 0){}
		else
		{
			$orderreceived = date('Y-m-d H:i:s');						
			
			$queryhistory = "UPDATE `orderstatushistory` SET `orderid` = ".$_GET['id'].", `dateadjustedpriceinspection`='".$orderreceived."' WHERE orderid=".$_GET['id'];
			$orderhistory = mysql_query($queryhistory) or die(mysql_error());
		}
//end adjusted-price inspection
	}
	
	

/*
Started code for Order Status starting from 11 till 14
*/	

	
	
	//header("Location: $url?key=$key&saved&p=$p&sort=id&desc");



	
	}

	else {

			$form->setPostFields($fields,'id','',true);

			header("Location: $url?added&sort=id&desc");	

	}

}

if (isset($_POST['submit_reply1'])) {
	if($_POST['AdminReply']!=''){
	$AdminReply = $_POST['AdminReply'];
	$mainid = $_POST['mainid'];
	$userid = $_POST['userid'];
	$orderid = $_GET['id'];
       
	mysql_query("INSERT INTO messages (`FromId`,`ToId`,`OrderId`,`Comments`,`IsRead`,`parentid`) VALUES(10,'$userid','$orderid','$AdminReply',0,'$mainid')") or die(mysql_error());
	
	mysql_query("UPDATE messages SET `ToId` = 10,`IsRead` = 1 WHERE id=".$mainid) or die(mysql_error());
	}
}

include(dirname(__FILE__) . '/html/header.php');

include(dirname(__FILE__) . '/html/menu.php');



?>

<script src="js/jquery.js"></script>


<script>

var test=$.noConflict();

</script>
<script src="js/jquery-ui.js"></script>	

<link href="js/jquery-ui.css" rel="stylesheet">

  <script>

  $(function() {

    $( "#reg_date" ).datepicker();

  });



test(document).ready(function() {
	
	test( "#OrderStatus" ).change(function () {
		
		if(this.value == '6'){

	  	//test("#adprice").css({ display: "block" });
		test("#submit").css({ display: "none" });
		test("#popup_window").css({ display: "block" });

	  }

	  else if(this.value == '3'){
//var AdjustedAmount = test( "#AdjustedAmount" ).val();
//if(AdjustedAmount == ""){
	
	<?php
	if($we['AdjustedAmount'] == ""){
	
	?>
	  	document.getElementById('adjustprice').value = "";
		test("#adprice").css({ display: "block" });
//}		
<?php } ?>
	  }

		else{
			document.getElementById('adjustprice').value = "Enter Adjusted Price";
		
		test("#adprice").css({ display: "none" });
			test("#submit").css({ display: "block" });
		test("#popup_window").css({ display: "none" });
		}
  // alert(this.value);

  });

});




  </script>

   <script>

  $(function() {

    $( "#end_date" ).datepicker();

  });

  </script>
<?php
	$_SESSION[$_SESSION['stdcode']]=$checknumber;
?>
  
<script type="text/javascript">

function btnpdf1()
{


	window.location = "http://www.stopoint.com/stopointsxgdlj123/order_edit.php?action=edit&id=<?php echo $_GET['id'];?>&p=<?php echo $_GET['p'];?>&key=<?php echo $_GET['key'];?>&res=1";
}

function btnpdf2()
{
	var TrackingCode = document.getElementById("TrackingCode").value;
	
	var elem = document.getElementById("TrackingNumber");
	
	//Tracking Number is STP code...
	//elem.value = <?php echo $checknumber; ?>;
	elem.value = TrackingCode;
	
	var elem = document.getElementById("checknumber");
	elem.value = <?php echo $checknumber; ?>;
	var elem = document.getElementById("ChequeNumber");
	elem.value = <?php echo $checknumber; ?>;
	
	btnpdf();
}


function btnpdf()
{
	window.open("pdf/stopointfilesystemsxgdlj123-<?php echo $checknumber?>.pdf","_blank");

}



function formValidation()  
{ 
	
 if(document.getElementById('AdjustedAmount').value == "" && document.getElementById('OrderStatus').value 
 != 4 && document.getElementById('OrderStatus').value == 5){
	 alert("Enter Adjusted Amount");
return false;	 
	 }
else{
	return true;
	}

}



function buttonclick(id){

       $(".reply_"+id).toggle();
    }
	

$(document).ready(function() {


							

	

	<?php if ($row['photo']): $row['photo'] = strip_tags($row['photo']); ?>

		$('#photo_testimonial').after('<img src="/images/testimonials/<?php echo $row[photo]; ?>" border="0">');

	<?php endif; ?>

	

	$("#photo_testimonial").uploadify({

		'uploader'       : '/uploadify.swf',

		'script'         : '/uploadify.php?page=testimonials',

		'cancelImg'      : 'images/cancel.png',

		'auto'           : true,

		'multi'          : true,



		'onComplete'	 : function(event,queueID,fileObj,response) {



			$('#photo').val(response);

			$('#photo_testimonial').after('<p><img src="/images/testimonials/'+response+'" border="0"></p>');

			

			return true;



		 }

	});

});

</script>
<script src="js/jquery-1.9.1.js"></script>
<style>
#popup_window{
padding: 10px;
background: #267E8A;
cursor: pointer;
color: #FCFCFC;
/*margin: 200px 0px 0px 200px;*/
}
.popup-overlay {
    width: 100%;
    height: 100%;
    position: fixed;
    background: rgba(196, 196, 196, .85);
    top: 0;
    left: 100%;
    opacity: 0;
    -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
    -webkit-transition: opacity .2s ease-out;
    -moz-transition: opacity .2s ease-out;
    -ms-transition: opacity .2s ease-out;
    -o-transition: opacity .2s ease-out;
    transition: opacity .2s ease-out;
}
.overlay .popup-overlay {
    opacity: 1;
    left: 0
}
.popup {
    position: fixed;
    top: 25%;
    left: 50%;
    z-index: -9999;
}
.popup .popup-body {
    background: #ffffff;
    background: -moz-linear-gradient(top, #ffffff 0%, #f7f7f7 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #ffffff), color-stop(100%, #f7f7f7));
    background: -webkit-linear-gradient(top, #ffffff 0%, #f7f7f7 100%);
    background: -o-linear-gradient(top, #ffffff 0%, #f7f7f7 100%);
    background: -ms-linear-gradient(top, #ffffff 0%, #f7f7f7 100%);
    background: linear-gradient(to bottom, #ffffff 0%, #f7f7f7 100%);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#f7f7f7', GradientType=0);
    opacity: 0;
    min-height: 150px;
    width: 400px;
    margin-left: -200px;
    padding: 20px;
    -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
    -webkit-transition: opacity .2s ease-out;
    -moz-transition: opacity .2s ease-out;
    -ms-transition: opacity .2s ease-out;
    -o-transition: opacity .2s ease-out;
    transition: opacity .2s ease-out;
    position: relative;
    -moz-box-shadow: 1px 2px 3px 1px rgb(185, 185, 185);
    -webkit-box-shadow: 1px 2px 3px 1px rgb(185, 185, 185);
    box-shadow: 1px 2px 3px 1px rgb(185, 185, 185);
    text-align: center;
    border: 1px solid #e9e9e9;
}
.popup.visible, .popup.transitioning {
    z-index: 9999;
}
.popup.visible .popup-body {
    opacity: 1;
    -ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
}
/*.popup .popup-exit {
    cursor: pointer;
    display: block;
    width: 24px;
    height: 24px;
    position: absolute;
    top: 150px;
    right: 195px;
    background: url("images/quit.png") no-repeat;
    
}*/
.popup .popup-content {
    overflow-y: auto;
}
.popup-content .popup-title {
    font-size: 24px;
    border-bottom: 1px solid #e9e9e9;
    padding-bottom: 10px;
}
.popup-content p {
    font-size: 13px;
    text-align: justify;
}
</style>

<div id="main-content"> <!-- Main Content Section with everything -->

			<?php if ($action_msg) echo $action_msg; ?>

			<!-- Page Head -->

			<h2>Order</h2>

						

			<?php if ($action_msg) echo $action_msg; ?>

			<br>		

			<div class="clear"></div> <!-- End .clear -->

			

			<div class="content-box"><!-- Start Content Box -->

					

				<div class="content-box-header">

					

					<h3><?php echo getTitle($action); ?> Order</h3>

					

					<div class="clear"></div>

					

				</div> <!-- End .content-box-header -->

				

				<div class="content-box-content">

							<form action="" method="post" >



								<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->



									<?php while ($field = $form->get_field()) {	?>

										<p>

											<label><?=$form->show_label($field)?></label>
											<?php
											if($field[name]=='Order Status'){
											?>
											<?=$form->show_input($field)?>
                                            <a href="" class="button" name="payorder" id="payorder" style="display:none;">Pay Order</a>
                                            <?php 
											if($row['OrderStatus']==6){
												//echo "SELECT * FROM `ordertrasactions` WHERE ( UserId = ".$row['UserId']."AND OrderId = ".$row['id'].")"; exit;
												$OrderTransaction = "SELECT * FROM `ordertrasactions` WHERE ( UserId = ".$row['UserId']." AND OrderId = ".$row['id'].")";
												$reOrderTransaction=mysql_query($OrderTransaction);
												$weOrderTransaction=mysql_fetch_assoc($reOrderTransaction);
											?>
                                            <a href="https://www.stopoint.com/admin/ordertransaction_edit.php?action=edit&id=<?=$weOrderTransaction['id']?>&p=1" class="button" name="transaction" id="transaction">Order Transaction</a>
                                            <?php
											}
											}else{
											?>
                                            <?=$form->show_input($field)?>
                                            <?php
											}
											?>

										</p>

											<?php }
											?>
                                             <p id="adprice" style="display:none;">

											<label>Adjusted Price</label>
                                            <input class="text-input small-input" type="text" value="Enter Adjusted Price" name="adjustprice" id="adjustprice" size="20">
                                            </p>
                                            <?php
											
											if($weproduct['categoryname'] == "Computers"){
												?>
                                                 <p>

											<label>Order Product Condition</label>
											
											<select class="small-input" name="Condition" id="Condition">
                                            <option value="">-- Please Select --</option>
                                            <option value="1"
                                           <?php
											if($we['Condition'] == '1'){
											?>
                                             selected="selected"
                                            <?php } ?>  
                                            >Fair</option>
                                            <option value="2"
                                            <?php
											if($we['Condition'] == '2'){
											?>
                                             selected="selected"
                                            <?php } ?> 
                                            >Good</option>
                                            <option value="3"
                                            <?php
											if($we['Condition'] == '3'){
											?>
                                             selected="selected"
                                            <?php } ?> 
                                             >Flawless</option>
                                            
                                            <option value="4"
                                            <?php
											if($we['Condition'] == '4'){
											?>
                                             selected="selected"
                                            <?php } ?> 
                                             >Broken(Yes)</option>
                                             
                                             <option value="5"
                                            <?php
											if($we['Condition'] == '5'){
											?>
                                             selected="selected"
                                            <?php } ?> 
                                             >Broken(No)</option>
                                            </select>
										</p>
                                                <?php
												
												}
												else{
											 ?>
                                             
                                             <p>

											<label>Order Product Condition</label>
											
											<select class="small-input" name="Condition" id="Condition">
                                            <option value="">-- Please Select --</option>
                                            <option value="1"
                                            <?php
											if($we['Condition'] == '1'){
											?>
                                             selected="selected"
                                            <?php } ?>  
                                            >Fair</option> 
                                            <option value="2"
                                            <?php
											if($we['Condition'] == '2'){
											?>
                                             selected="selected"
                                            <?php } ?> 
                                            >Good</option>
                                            <option value="3"
                                            <?php
											if($we['Condition'] == '3'){
											?>
                                             selected="selected"
                                            <?php } ?> 
                                            >Flawless</option>
                                            
                                            <option value="4"
                                            <?php
											if($we['Condition'] == '4'){
											?>
                                             selected="selected"
                                            <?php } ?> 
                                            >Broken(Yes)</option>
                                            
                                            <option value="5"
                                            <?php
											if($we['Condition'] == '5'){
											?>
                                             selected="selected"
                                            <?php } ?> 
                                            >Broken(No)</option>
                                            </select>
										</p>
                                             <?php } ?>
                                             <br />
                                     <h3> User</h3>
                                             <p>
 
											<label>First Name : </label>
											
											<input class="text-input small-input" type="text" readonly="readonly" name="FName" id="Fname" size="20" value="<?php echo $weuser['FirstName'];  ?>">
                                            <input type="hidden" name="userid" id="userid" value="<?php echo $weuser['id']; ?>" />
										</p>
                                        
                                        <p>
 
											<label>Last Name : </label>
											
											<input class="text-input small-input" readonly="readonly" type="text" name="LName" id="LName" size="20" value="<?php echo $weuser['LastName'];  ?>">
										</p>
                                        
                                        <p>
 
											<label>Email Address : </label>
											
											<input class="text-input small-input" type="text" readonly="readonly" name="emailaddress" id="emailaddress" size="20" value="<?php echo $weuser['EmailAddress'];  ?>">
										</p>
                                        
                                        <p>
 
											<label>Phone : </label>
											
											<input class="text-input small-input" type="text" readonly="readonly" name="emailaddress" id="emailaddress" size="20" value="<?php echo $weuser['Phone'];  ?>">
										</p>
                                        <div class="shipping" style="float:left; width:50%;">
                                        <p>
 
											<label>Shipping Address 1 : </label>
											
											<input class="text-input small-input" style="width: 50% !important;" type="text" name="saddress1" id="saddress1" size="20" value="<?php echo $weuser['S_AddressLine1'];  ?>">
										</p>
                                        
                                        <p>
 
											<label>Shipping Address 2 : </label>
											
											<input class="text-input small-input" style="width: 50% !important;" type="text" name="saddress2" id="saddress2" size="20" value="<?php echo $weuser['S_AddressLine2'];  ?>">
										</p>
                                        
                                        <p>
 
											<label>Shipping City : </label>
											
											<input class="text-input small-input" style="width: 50% !important;" type="text" name="scity" id="scity" size="20" value="<?php echo $weuser['S_City'];  ?>">
										</p>
                                        
                                         <p>
 
											<label>Shipping State : </label>
											
											<input class="text-input small-input" style="width: 50% !important;" type="text" name="sstate" id="sstate" size="20" value="<?php echo $weuser['S_State'];  ?>">
										</p>
                                        
                                        <p>
 
											<label>Shipping Postal Code : </label>
											
											<input class="text-input small-input" style="width: 50% !important;" type="text" name="spostal" id="spostal" size="20" value="<?php echo $weuser['S_PostalCode'];  ?>">
										</p>
                                        
                                        <p>
 
											<label>Shipping Country : </label>
											
											<input class="text-input small-input" style="width: 50% !important;" type="text" name="scountry" id="scountry" size="20" value="<?php echo $weuser['S_Country'];  ?>">
										</p>
                                    </div>
                                    <div class="buying">
                                    <p>
 
											<label>Billing Address 1 : </label>
											
											<input class="text-input small-input" type="text" name="baddress1" id="baddress1" size="20" value="<?php echo $weuser['B_AddressLine1'];  ?>">
										</p>
                                        
                                        <p>
 
											<label>Biling Address 2 : </label>
											
											<input class="text-input small-input" type="text" name="baddress2" id="baddress2" size="20" value="<?php echo $weuser['B_AddressLine2'];  ?>">
										</p>
                                        
                                        <p>
 
											<label>Billing City : </label>
											
											<input class="text-input small-input" type="text" name="bcity" id="bcity" size="20" value="<?php echo $weuser['B_City'];  ?>">
										</p>
                                        
                                         <p>
 
											<label>Billing State : </label>
											
											<input class="text-input small-input" type="text" name="bstate" id="bstate" size="20" value="<?php echo $weuser['B_State'];  ?>">
										</p>
                                        
                                         <p>
 
											<label>Billing PostalCode : </label>
											
											<input class="text-input small-input" type="text" name="bpostal" id="bpostal" size="20" value="<?php echo $weuser['B_PostalCode'];  ?>">
										</p>
                                        
                                        <p>
 
											<label>Billing Country : </label>
											
											<input class="text-input small-input" type="text" name="bcountry" id="bcountry" size="20" value="<?php echo $weuser['B_Country'];  ?>">
										</p>
                                    </div>
                                            <br />
                                            
                                     <h3>Transaction Details</h3>
                                     <div style="float:left; width:50%;">
                                     <p>
 
											<label>Paypal Email : </label>
											
											<input style="width: 50% !important;" class="text-input small-input" type="text" name="PaypalEmail" id="PaypalEmail" size="20" readonly="readonly" value="<?php echo $weuser['PaypalEmail'];  ?>">
										</p>
                                      </div>
                                      <div>
                                        <p>
 
											<label>Payment Method : </label>
											<input type="hidden" name="PaymentMethod" value="<?php echo $weuser['PaymentMethod']; ?>" />
											<select class="small-input" name="PaymentMethod" id="PaymentMethod">
                                            <option value="">-- Please Select --</option>
                                            <option value="1"
                                           <?php
											if($weuser['PaymentMethod'] == '1'){
											?>
                                             selected="selected"
                                            <?php } ?>  
                                            >Paypal</option>
                                            <option value="2"
                                            <?php
											if($weuser['PaymentMethod'] == '2'){
											?>
                                             selected="selected"
                                            <?php } ?> 
                                            >Check</option>
                                            </select>
										</p>
                                        </div>
                                        <br /><label>Check number : </label>
<?php	
$real=$checknumber;
if($_GET['key']=="release")
$real = ""; 
?>
									<input class="text-input small-input" type="text" name="checknumber" id="checknumber" size="20" readonly="readonly" value="<?php echo $real;?>"/>
					<br/>
 <br /><label>Paypal Transaction Number : </label>


									<input class="text-input small-input" type="text" name="paypaltransactionnumber" size="20"  readonly="readonly"/>
					<br/>

					<br/>


                                        
                                     <h3> Product</h3>
                                     
                                     
                                     
									<p>
 
											<label>Product Code : </label>
											
											<input class="text-input small-input" type="text" name="ProductCode" id="ProductCode" readonly="readonly" size="20" value="<?php echo $weproduct['ProductCode'];  ?>">
										</p>
                                        
                                        <p>
 
											<label>Product Model : </label>
											
											<input class="text-input small-input" type="text" readonly="readonly" name="ProductCode" id="ProductCode" size="20" value="<?php echo $weproduct['ProductModel'];  ?>">
										</p>
                                        
                                        
                                        <p>

											<label>Product Family : </label>
											
											<input class="text-input small-input" readonly="readonly" type="text" name="FamilyName" id="FamilyName" size="20" value="<?php echo $weproduct['familyname'];  ?>">
										</p>
                                        
                                         <p>

											<label> Product Category : </label>
											
											<input class="text-input small-input" readonly="readonly" type="text" name="FamilyName" id="FamilyName" size="20" value="<?php echo $weproduct['categoryname'];  ?>">
										</p>
                                        
                                        
                                        <p>

											<label>Product Brand : </label>
											
											<input class="text-input small-input" type="text" readonly="readonly" name="FamilyName" id="FamilyName" size="20" value="<?php echo $weproduct['brandname'];  ?>">
										</p>
                                        
                                        <p>

											<label>Product Description : </label>
											
											<input class="text-input small-input" type="text" readonly="readonly" name="FamilyName" id="FamilyName" size="30" value="<?php echo htmlspecialchars($weproduct['Description']);  ?>">
										</p>
                                        
                                         <p>

											<label>Product Image : </label>
											
											<img height="150" width="150" src="<?=$baseurl?>productimages/<?php echo $weproduct['image_url']; ?>" />
										</p>
                                        
                                       <br />
                                        <h3> User comment</h3>
                                             <p>
 											<?php echo $we['UserComments']; ?>
										</p>
                                        
                                        <h3> Admin comment</h3>
                                             <p style="width:50%">
										
                                        <textarea class="text" name="AdminComments" cols="5" rows="5" id="AdminComments"><?php echo $we['AdminComments']; ?></textarea>
										</p>
<?php
if($_GET['key'] == 'paid' && $weuser['PaymentMethod']!=1)

echo '<input class="button" type="button" id="buttonpdf"  name="buttonpdf" value="Reprint Check" onclick="btnpdf();" /><input class="button" type="button" id="buttonpdf"  name="buttonpdf" value="ReCreate Check" onclick=" btnpdf1();" />';
?>

										<input class="button" style="display:none;" type="button" id="popup_window" data-popup-target="#example-popup"  name="submit" value="Submit" />
                                        <input class="button" type="submit" id="submit"  name="submit" value="Submit" onclick=" return formValidation();" />
<input class="button" style="display:none;" type="button" id="popup_window" data-popup-target="#example-popup"  name="submit" value="Submit" />
                                        


									</p>
     <?php if($_GET['key'] == 'returned' || $_GET['key'] == 'returncompleted'){
		 ?>
         

<script type="text/javascript">
function getComboAS(sel) {
	
	var optProcessor = document.getElementById('screensize');
	$.ajax({
       url: 'generatelabel.php',
       data: {"subId":sel},
       type: 'post',
       success:function(data){
		  //alert(data);
			 //optProcessor.html(data);
			 
			 document.getElementById("screensize").innerHTML = data;
			 optProcessor.style.display = 'block';
          
		   //alert(data);
       }
    });
}
   
		
	
	

</script>

<?php
$filename = 'returnlabels/label_'.$_GET['id'].'.png';

if (file_exists($filename)) {
	function ak_img_resize($target, $newcopy, $w, $h, $ext) {
    list($w_orig, $h_orig) = getimagesize($target);
    $scale_ratio = $w_orig / $h_orig;
    if (($w / $h) > $scale_ratio) {
           $w = $h * $scale_ratio;
    } else {
           $h = $w / $scale_ratio;
    }
    $img = "";
    $ext = strtolower($ext);
    if ($ext == "gif"){ 
      $img = imagecreatefromgif($target);
    } else if($ext =="png"){ 
      $img = imagecreatefrompng($target);
    } else { 
      $img = imagecreatefromjpeg($target);
    }
    $tci = imagecreatetruecolor($w, $h);
    // imagecopyresampled(dst_img, src_img, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
    imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
	$rotated    =   imagerotate($tci, 90, 0);
    imagejpeg($rotated, $newcopy, 100);
}
$target_file = "returnlabels/label_".$_GET['id'].".png";
$resized_file = "returnlabels/newlabel_".$_GET['id'].".png";
$srcsize = getimagesize($target_file);
$wmax = 280;
$hmax = (280 / $srcsize[0]) * $srcsize[1];
if (!file_exists("returnlabels/newlabel_".$_GET['id'].".png")) {
ak_img_resize($target_file, $resized_file, $wmax, $hmax, 'png');
}
   ?>
 <!--  <a href="returnlabels/newlabel_<?php echo $_GET['id'] ?>.png" style="font-size:14px;" target="_blank">Download Label</a> -->
   <a href="return-lbl.php?orderid=<?=$_GET['id']?>&trackingid=<?=$_SESSION['stdcode']?>" target="_blank">Download Label</a>
   <?php
} else {
    ?>
      <input type="button" name="generatelabel" value="Generate Label" id="<?php echo $_GET['id'] ?>" onclick="getComboAS(this.id);" />
<div id="screensize" style="display:none">

</div>
    <?php
}
?>
      
    
         <?php
		 }?>
                                    <div id="example-popup" class="popup">
    <?php
	if($weuser['PaymentMethod'] == '1'){
	?>
    <div class="popup-body">	<!--<span class="popup-exit"></span>-->

        <div class="popup-content">
            	<h2 class="popup-title">Payment through Paypal</h2>
<p><strong>Paypal Email:</strong> <?php echo $weuser['PaypalEmail'];  ?></p>
<p><strong>Order Amount:</strong> <?php

if($we['AdjustedAmount'] == ""){
echo $we['OrderAmount'];	
	}
	else{
echo $we['AdjustedAmount'];		
		}

   ?></p>
<p>If you want to confirm the payment through Paypal please press confirm button</p>

<input class="button" type="submit" id="submit"  name="submit" value="Confirm" />
<input class="button popup-exit" type="button" id="submit"  name="submit" value="Cancel" />
        </div>
    </div>
    
    <?php
	}
	if($weuser['PaymentMethod'] == '2'){
	?>
    <div class="popup-body">	<span class="popup-exit"></span>

        <div class="popup-content">
            	<h2 class="popup-title">Payment through Check</h2>
<p><strong>Pay:</strong> <?php echo $weuser['FirstName']." ".$weuser['LastName'];  ?></p>
<!--<p>
<label for="AccountNumber">Account Number :</label>
<input type="text" class="text-input" style="width:25%" name="AccountNumber" id="AccountNumber">
</p>-->
>


<p>

<label for="TrackingNumber">Track Payment(usps) :</label>
<input type="text" class="text-input" style="width:25%" name="TrackingNumber" id="TrackingNumber">


</p>
<p>
<label for="ChequeNumber">Check Number :</label>
<input type="text" class="text-input" style="width:25%" name="ChequeNumber" id="ChequeNumber" >
</p>
<p><strong>Order Amount:</strong> <?php

if($we['AdjustedAmount'] == ""){
echo $we['OrderAmount'];	
	}
	else{
echo $we['AdjustedAmount'];		
		}

   ?></p>
<p>If you want to confirm the payment through Check please press confirm button</p>
<input class="button" type="submit" id="submit"  name="submit" value="Confirm" />
<input class="button" type="button" id="buttonpdf"  name="buttonpdf" value="Create Check" onclick=" btnpdf2();" />
        </div>
    </div>
    <?php
	}
	?>
</div>
                            
								</fieldset>
</form>

<br />


<!--<button id="popup_window" style="display:none" data-popup-target="#example-popup">Open The Light Weight Popup Modal</button>-->


<div class="popup-overlay"></div>
<script type='text/javascript'>//<![CDATA[ 
$(window).load(function(){
$(document).ready(function ($) {


    $('[data-popup-target]').click(function () {
		
        $('html').addClass('overlay');
        var activePopup = $(this).attr('data-popup-target');
        $(activePopup).addClass('visible');

    }); 

    $(document).keyup(function (e) {
        if (e.keyCode == 27 && $('html').hasClass('overlay')) {
            clearPopup();
        }
    });

    $('.popup-exit').click(function () {
        clearPopup();

    });

    $('.popup-overlay').click(function () {
        clearPopup();
    });

    function clearPopup() {
        $('.popup.visible').addClass('transitioning').removeClass('visible');
        $('html').removeClass('overlay');

        setTimeout(function () {
            $('.popup').removeClass('transitioning');
        }, 200);
    }

});
});//]]>  

</script>

<!--Note-->
<h3> Notes</h3>
<form style="width:50%">
<textarea class="text" name="note_txt" cols="5" rows="5" id="note_txt"></textarea>
<input type="button" value="Add Note" name="" id="note_submit_btn" />
<input type="hidden" id="order_id" value="<?php echo $id; ?>">
</form>
<br>
<div id="note-list">

</div>
<script>
	$(document).ready(function(){
		var order_id = $("#order_id").val();
		var data = "order_id="+order_id;		
		var url2 = "retrieve-note.php";
		
		$.post(url2, data).done(function(response2) {
			$("#note-list").html(response2);
		}).fail(function(data) {
			
		});
		
		$("#note_submit_btn").click(function(){
			var note_txt = $("#note_txt").val();
			if(note_txt != ""){
				note_txt = note_txt.replace(/&/g, "amp;");
				data = "note_txt="+note_txt+"&order_id="+order_id;
				var url = "submit-note.php";
				
				$.post(url, data).done(function(response) {
					$("#note_txt").val("");
					$.post(url2, data).done(function(response2) {
						$("#note-list").html(response2);
					}).fail(function(data) {
						
					});
				}).fail(function(data) {
					
				});
			}			
		});
	});
</script>
<div class="clear"></div><!-- End .clear -->
<br>
<!--msg-->
<h3> Messages</h3>
<?php
$vasmessage = "SELECT messages.id as id,messages.Subject as Subject,messages.Comments as Comments,messages.ToId as ToId,messages.Date as Date, user.id as userid, user.image_url as image FROM `messages` INNER JOIN user on messages.FromId = user.id INNER JOIN `order` on messages.OrderId = order.id WHERE messages.OrderId = ".$_GET['id']." AND parentid=0 ORDER BY id DESC";
$remessage=mysql_query($vasmessage);
//$wemessage=mysql_fetch_assoc($remessage);?>
<?php
while($wemessage1=mysql_fetch_array($remessage))
{?>
	<p>
		<label style="font-size:14px; font-weight:bold">Subject : </label>
    	<span style="font-size:14px"><span id="Subject"><?php echo $wemessage1['Subject']; ?></span></span>
	</p>
	<p>
    	<span style="font-size:14px"><img height="50" width="50" src="<?=$baseurl?>images/users/<?=$wemessage1['image']?>" style="border-radius:50%; float:left;" /></span>
        <span style="margin-left:15px;"> <?php echo $wemessage1['Date']; ?><br />
			<label style="font-size:14px; font-weight:bold; margin-left:15px;">Comments : </label>
            <span style="font-size:14px"> <?php echo $wemessage1['Comments']; ?></span>
        </span>
	</p><br />
    <button class="button" id="button_<?php echo $wemessage1['id']; ?>" onClick="buttonclick(this.id);">Reply</button>
    <div class="reply_button_<?php echo $wemessage1['id']; ?>" style="display:none;">
    	<form name="form1" id="reply" method="post" action="" style="border: 1px solid #ccc; padding:15px; margin-top:10px;">
        	<textarea class="text" name="AdminReply" cols="5" rows="5" id="AdminReply"></textarea>
            <input type="hidden" name="mainid" value="<?php echo $wemessage1['id']; ?>" />
            <input type="hidden" name="userid" value="<?php echo $wemessage1['userid']; ?>" />
            <input id="submit" class="button" name="submit_reply1" type="submit" value="Send">
      	</form>
  	</div><?php 
	if($wemessage1['ToId'] > 0)
	{
		$vasadmin = "SELECT messages.id as id,messages.Subject as Subject,messages.Comments as Comments,messages.ToId as ToId,messages.Date as Date, user.FirstName as fname, user.image_url as image FROM `messages` INNER JOIN user on messages.FromId = user.id INNER JOIN `order` on messages.OrderId = order.id WHERE messages.OrderId = ".$_GET['id']." AND parentid > 0 ORDER BY id DESC";
		$readmin=mysql_query($vasadmin);
		//$weadmin=mysql_fetch_assoc($readmin);
		while($weadmin=mysql_fetch_array($readmin))
		{?>
			<p style="margin-left:50px; height:100px;">
				<span style="font-size:14px"><img height="50" width="50" src="../images/users/<?php echo $weadmin['image']; ?>" style="border-radius:50%; float:left;" width="45px" height="45px" /></span>
				<span style="margin-left:15px;"><?php echo $weadmin['Date']; ?><br />	
					<label style="font-size:14px; font-weight:bold; margin-left:15px;">Comments : </label>
					<span style="font-size:14px"> <?php echo $weadmin['Comments']; ?></span>
				</span>
				<button class="button" id="button_<?php echo $wemessage1['id']; ?>" onClick="buttonclick(this.id);">Reply</button>
			</p><?php
		}?><hr /><?php 
	} 
} ?>
<!--end for msg-->
<input type="button" value="Send message" name="adminmessage" id="adminmessage" />

<script>
								$(document).ready(function(){
    								$("#adminmessage").click(function(){
        								$(".reply").toggle();
    								});
								});
							</script>
<div class="reply" style="display:none;">
                            <form name="form1" id="reply" method="post" action="" style="border: 1px solid #ccc; padding:15px; margin-top:10px;">
                            
                               <label style="display: block; padding: 10px 0px 10px; font-weight: bold; font-size: 14px;" for="form_name">Name :</label>
                               <input readonly="readonly" class="text-input" style="width:25%" name="form_name" id="form_name" type="text" value="<?php echo $weuser['FirstName']." ".$weuser['LastName'];?>" >
                            
                              <label style="display: block; padding: 10px 0px 10px; font-weight: bold; font-size: 14px;" for="form_email">Email :</label>
                              <input readonly="readonly" class="text-input" style="width:25%" name="form_email" id="form_email" type="email" value="<?=$weuser['EmailAddress'];?>" >
                              <input type="hidden" name="useridmessage" id="useridmessage" value="<?php echo $weuser['id']; ?>" />
                              
                              <input type="hidden" name="orderidmessage" id="orderidmessage" value="<?php echo $_GET['id']; ?>" />
                              
                              
                              <label style="display: block; padding: 10px 0px 10px; font-weight: bold; font-size: 14px;" for="form_subject">Subject :</label>
                              <input class="text-input" style="width:37%" name="form_subject" id="form_subject" type="text" >
                              <label style="display: block; padding: 10px 0px 10px; font-weight: bold; font-size: 14px;" for="form_email">Message :</label>
                            <textarea class="text" name="Adminmessage" cols="5" rows="5" id="Adminmessage"></textarea>
                            <input id="submit" class="button" name="submit_reply" type="submit" value="Send">
							</form>
							</div>
								<div class="clear"></div><!-- End .clear -->



							

					

				</div> <!-- End .content-box-content -->
<div class="content-box-content">
<?php
$vashistory = "SELECT * FROM `orderstatushistory` WHERE orderid=".$_GET['id'];
$rehistory=mysql_query($vashistory);
$wehistory=mysql_fetch_assoc($rehistory);
?>
				<h3>Order History</h3>
                <?php
				if($wehistory['datepaid'] != ""){
				 ?>
                <p><?php echo date('m/d/y h:i:s',strtotime($wehistory['datepaid']));  ?>: Order Paid</p>
                <?php }
                //activation lock
				if($wehistory['dateactivationlock'] != "")
				{
				 ?>
				 
                <p><?php echo date('m/d/y h:i:s',strtotime($wehistory['dateactivationlock']));  ?>: Activation Lock </p>
                
                <?php }
				//end activation lock
				?>
				
				<?php
				//adjusted-price inspection
				if($wehistory['dateadjustedpriceinspection'] != "")
				{
				 ?>
				 
                <p><?php echo date('m/d/y h:i:s',strtotime($wehistory['dateadjustedpriceinspection']));  ?>: Adjusted Price Inspection </p>
                
                <?php }
				//end adjusted-price inspection
				?>
				
				<?php
				//Start Date IMEI				
				if($wehistory['dateimei'] != ""){
				?>
					<p><?php echo date('m/d/y h:i:s',strtotime($wehistory['dateimei']));  ?>: IMEI Check </p>
				<?php				
				}					
				//End Date IMEI					
				?>
				
				<?php
				//Start Installment Payment				
				if($wehistory['dateinstallment'] != ""){
				?>
					<p><?php echo date('m/d/y h:i:s',strtotime($wehistory['dateinstallment']));  ?>: Installment Payment </p>
				<?php				
				}					
				//End Installment Payment				
				?>
				
				<?php
				//Start Activation-Lock Inspection			
				if($wehistory['dateactivationlockinspection'] != ""){
				?>
					<p><?php echo date('m/d/y h:i:s',strtotime($wehistory['dateactivationlockinspection']));  ?>: Activation-Lock Inspection </p>
				<?php				
				}					
				//End Activation-Lock Inspection				
				?>
				
				<?php
				//Start Blacklisted			
				if($wehistory['dateblacklisted'] != ""){
				?>
					<p><?php echo date('m/d/y h:i:s',strtotime($wehistory['dateblacklisted']));  ?>: Blacklisted </p>
				<?php				
				}					
				//End Blacklisted				
				?>
				
				
				<?php
				//return completed
				if($wehistory['datereturncompleted'] != "")
				{
				 ?>
				 
                <p><?php echo date('m/d/y h:i:s',strtotime($wehistory['datereturncompleted']));  ?>: Return Completed </p>
                
                <?php }
				//end return completed
                //expired
				if($wehistory['dateexpired'] != "")
				{
				 ?>
				 
                <p><?php echo date('m/d/y h:i:s',strtotime($wehistory['dateexpired']));  ?>: Order Expired </p>
                
                <?php }
				//end expired
                //cancel
			if($wehistory['datecancelled'] != "")
				{
				 ?>
				 
                <p><?php echo date('m/d/y h:i:s',strtotime($wehistory['datecancelled']));  ?>: Order Cancelled </p>
                
                <?php }
				//end cancel
				if($wehistory['datereleased'] != ""){
				 ?>
				 
                <p><?php echo date('m/d/y h:i:s',strtotime($wehistory['datereleased']));  ?>: Order Release Payment</p>
                
                <?php }
				if($wehistory['datereturned'] != ""){
				 ?>
                <p><?php echo date('m/d/y h:i:s',strtotime($wehistory['datereturned']));  ?>: Order Returned</p>
                <?php } 
				if($wehistory['dateadjusted'] != ""){
				?>
                <p><?php echo date('m/d/y h:i:s',strtotime($wehistory['dateadjusted']));  ?>: Order Adjusted</p>
                <?php }
				if($wehistory['datereceived'] != ""){
				 ?>
                <p><?php echo date('m/d/y h:i:s',strtotime($wehistory['datereceived']));  ?>: Order Received</p>
                <?php }
				if($wehistory['dateordered'] != ""){
				 ?>
                <p><?php echo date('m/d/y h:i:s',strtotime($wehistory['dateordered']));  ?>: Order Pending</p>
                <?php } ?>
</div>
			</div> <!-- End .content-box -->
<?php
	use Dompdf\Dompdf;
	if($weuser['PaymentMethod'] == '2'){
					$transactionid = $_POST['TrackingNumber'];
				}else{
					$transactionid = "TR_".rand(11111111,99999999);
				}

	$price=$we['AdjustedAmount'];;
	if($we['AdjustedAmount'] == "")
	$price=$we['OrderAmount'];

	$nwords = array( "zero", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine", "ten", "eleven", "twelve", "thirteen","fourteen", "fifteen", "sixteen", "seventeen", "eighteen","nineteen", "twenty", 30 => "thirty", 40 => "forty", 50 => "fifty", 60 => "sixty", 70 => "seventy", 80 => "eighty",90 => "ninety" );
	$w = '';
	//$x=$we['OrderAmount'];
	$x=$price;
	$flag=1;
      if($x < 21)  
         $w .= $nwords[$x];
      else if($x < 100) {
         $w .= $nwords[10 * floor($x/10)];
         $r = fmod($x, 10);
         if($r > 0)
            $w .= ' '. $nwords[$r];
      } else if($x < 1000) {
         $w .= $nwords[floor($x/100)] .' hundred';
         $r = fmod($x, 100);
         if($r > 0)
            $w .= ' ';
	    if($r<21)
			$w .= $nwords[$r];
			else 
		{
			 $w .= $nwords[10 * floor($r/10)];
				 $r = fmod($r, 10);
				 if($r > 0)
					$w .= ' '. $nwords[$r];
		}
      }
	require_once 'dompdf/autoload.inc.php';
$address=$weuser['S_AddressLine1'];
if($weuser['S_AddressLine2']!="")
{
	$address=$address.'<br/>'.$weuser['S_AddressLine2'];
}

// instantiate and use the dompdf class
$dompdf = new Dompdf();
$dompdf->loadHtml('
<html>
<head>
	<style type="text/css">
	@font-face {
		font-family: "OCR A Extended";
		src: url(OCRAEXT.TTF) format("truetype");
	}
	@font-face {
		font-family: "Lucida Grande";
		src: url(Lucida Grande.ttf) format("truetype");
	}
	
	body{
		font-family:"Lucida Grande";
	}
	
	.dollar{
		font-weight:bold;
		font-size:20px;
	}
	
	</style>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	<table style="margin-left:-16;margin-top:-19">
		<tr>
			<td width="70">
				<img src="images/logo.png" width="90%" style="margin-top:-27">
			</td>
			<td width="180" style="font-size:14px; line-height:0.8;">
				<span style="font-size:16px;">STOPOINT INC</span><br/>
				<span style="font-size:15px;">1175 NE 125TH ST STE 211</span><br/>
				<span style="font-size:15px;">NORTH MIAMI, FL 33161</span><br/>
				<span style="font-size:15px;">(888) 246-4919</span>
			</td>
			<td width="33">
				<br/>
			</td>
			<td width="26" align="center">
				<img src="images/2.jpg" width="90%" style="margin-top:-25;">
			</td>
			<td width="130" style="font-size:13px; line-height:0.8;">
							<span>Wells Fargo</span> <br/>
							12700 BISCAYNE BLVD <br/>
							NORTH MIAMI, FL 33181<br/>
							(305) 507-0858
						
						<p align="right" style="font-size:10px;margin-top:-3px;">
							DATE
							</p>
				
			</td>
			<td width="113" colspan="2">			
			<p style="text-align: right; margin-top:-15; margin-bottom:23;  font-size:17px;">'.$checknumber.'</p>
			<p style="width:100%; border-bottom:2px solid grey; text-align:center; font-size:16px;">'.date('m/d/Y').'</p>
			</td>
		</tr>
		</table>
		<table style="margin-left:-15;margin-top:0">
		<tr>
			<td style="font-size:12px" width="53" align="left">
				PAY TO THE<br/>
				ORDER OF
			</td>
			<td width="405">
				<p style="padding:0 20;margin-top:8; border-bottom:2px solid grey; font-size:16px;">
                                  '.$weuser['FirstName'].' '.$weuser['LastName'].'</p>
			</td>
			<td>
				<span class="dollar">&#36;</span>
			</td>
			<td width="90">
				<p style="margin:0;width:100%;margin-top:8; border-bottom:2px solid grey; float:right; font-size:16px;">**'.$price.'.00</p>
			</td>
		</tr>
		</table>
		<table style="margin-left:-15;margin-top:0">
		<tr>
			<td  width="525" colspan="4" style="border-bottom:2px solid grey; background:url(r.png); background-repeat:repeat-x;">
				<span style="margin:0;background:white;">'.ucfirst(strtolower($w)).' Dollars & no/100 </span>
			</td>
			<td style="font-size:12px;padding-bottom:-10px;">
			DOLLARS
			</td>
		</tr>
		</table>
		<table style="margin-top:-10;">
		<tr>
			<td width="53">
			</td>
			<td width="140" style="font-size:16px; line-height:0.98;">
				<br/>'.$weuser['FirstName'].' '.$weuser['LastName'].'<br/>
				'.$address.'<br/>
				'.$weuser['S_City'].' '.$weuser['S_State'].' '.$weuser['S_PostalCode'].'<br/>
				'.$weuser['S_Country'].'<br/>
			</td>
			<td width="100">
			</td>
			<td colspan="3">
				<table style="margin-top:15">
				<tr>
					<td align="right" style="font-size:14px;" width="130" >
						VOID AFTER 90 DAYS
					</td>
					<td width="100">
					</td>
				</tr>
				<tr>
					<td colspan="2" >
						<img src="images/a.png" width="125" style="margin-top:-10;margin-bottom:-20; margin-left:110;">
						<p style="width:200; border-bottom:2px solid grey;  margin-left:50;"></p>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<p style="margin-left:110; font-size:9px;">AUTHORIZED SIGNATURE</p>
					</td>
				</tr>
				</table>
			</td>
		</td>
	</table>
	<table style="margin-top:5;">
		<tr>
			<td style="width:110px">
				&nbsp;
			</td>
			<td style="width:410px;font-size:25px; font-family:'."'MICR E13 B;'".'" >
				<span style="margin-top:-3px;font-family:'."'MICR E13 B;'".'">C</span>'.$checknumber.'<span style="font-family:'."'MICR E13 B;'".'">C</span>      <span style="font-family:'."'MICR E13 B;'".'">A</span>063107513<span style="font-family:'."'MICR E13 B;'".'">A</span> 3822204032<span style="font-family:'."'MICR E13 B;'".'">C</span> 
			</td>

		</tr>
	</table>
	<table style="font-size:14px; margin-left:-5; margin-top:15; line-height:0.95">
		<tr>
			<td>
				<br/>
			</td>
		</tr>
		<tr  style="font-size:16px;">
			<td colspan="5">
				STOPOINT INC
			</td>
			<td align="right">
				'.$checknumber.'
			</td>
		</tr>
		<tr style="font-size:13px;">
			<td width="90">
				Date: '.date("M/d/y").'<br/>
				<u>Account/Category</u><br/>
				'.$weproduct['familyname'].'
			</td>
			<td width="125">
				Vendor:'.$weuser['FirstName'].' '.$weuser['LastName'].'<br/>
				<p align="center" style="margin:0"><u>Amount</u><br/>
				'.$price.'.00</p>
			</td>
			<td width="110">
				Check Total: $'.$price.'.00<br/>
				<p align="center" style="margin:0"><u>Memo</u><br/>
				'.$weproduct['familyname'].'</p>
			</td>
			<td width="105">
			</td>
			<td width="50">
				<u>Class/Job</u>
			</td>
			<td width="60">
			</td>
		</tr>
	</table>
	<br/>
	<br/>
	<br/>
	<br/>
	<br/>
	<br/>
	<br/>
	<br/>
	<br/>
	<br/>
	<br/>
	<br/>
	<br/>

	<table style="font-size:14px; margin-left:-5; margin-top:55; line-height:0.95">
		<tr  style="font-size:16px;">
			<td colspan="5">
				STOPOINT INC
			</td>
			<td align="right">
				'.$checknumber.'
			</td>
		</tr>
		<tr style="font-size:13px;">
			<td width="90">
				Date: '.date("M/d/y").'<br/>
				<u>Account/Category</u><br/>
				'.$weproduct['familyname'].'
			</td>
			<td width="125">
				Vendor:'.$weuser['FirstName'].' '.$weuser['LastName'].'<br/>
				<p align="center" style="margin:0"><u>Amount</u><br/>
				'.$price.'.00</p>
			</td>
			<td width="110">
				Check Total: $'.$price.'.00<br/>
				<p align="center" style="margin:0"><u>Memo</u><br/>
				'.$weproduct['familyname'].'</p>
			</td>
			<td width="105">
			</td>
			<td width="50">
				<u>Class/Job</u>
			</td>
			<td width="60">
			</td>
		</tr>
	</table>

	
</body>
</html>
');


// (Optional) Setup the paper size and orientation
$dompdf->setPaper(array(0,0, 8.5 * 72, 11 * 72), 'portrait');

// Render the HTML as PDF
$dompdf->render();
$output = $dompdf->output();
$rab=rand("111111","999999");
if(!file_exists("pdf/stopointfilesystemsxgdlj123-".$checknumber.".pdf")){
	file_put_contents("pdf/stopointfilesystemsxgdlj123-".$checknumber.".pdf", $output);
}	


?>


			<?php
if($_GET['res']==1)
{
	echo '<script type="text/javascript">window.open("pdf/stopointfilesystemsxgdlj123-'.$checknumber.'.pdf","_blank");</script>';
}

?>

<?php include("html/footer.php"); ?>