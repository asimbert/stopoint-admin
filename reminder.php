<?php
	require_once(dirname(__FILE__) . '/inc/core.php');

require_once(dirname(__FILE__) . '/classes/class.table_form.php');

 $query = "SELECT order.id as OrderId, order.TrackingCode as TrackingCode, order.FedexCode as FedexCode, order.OrderDate as OrderDate, order.OrderAmount as OrderAmount , order.AdjustedAmount as AdjustedAmount, order.OrderStatus as OrderStatus, product.ProductModel as ProductModel , product.ProductCode as ProductCode, product.Description as ProductDescription, productfamily.image_url as ProductImage, user.FirstName as FirstName, user.LastName as LastName, user.EmailAddress as EmailAddress, user.S_AddressLine1 as S_AddressLine1, user.S_AddressLine2 as S_AddressLine2 , user.S_City as S_City, user.S_State as S_State, user.S_Country as S_Country,user.S_PostalCode as S_PostalCode, user.PaypalEmail as PaypalEmail, user.Phone as Phone ,
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
  WHEN '2' THEN 'Good'
  WHEN '1' THEN 'Fair'
 ELSE 'Good'
 END as OrderCondition
FROM `order` INNER JOIN `product` ON product.ProductCode=order.ProductId INNER JOIN `user` ON user.id=order.UserId INNER JOIN `productbrand` ON productbrand.id=product.BrandId INNER JOIN `productfamily` ON productfamily.Id=product.FamilyId INNER JOIN `productcategory` ON productcategory.id=product.CategoryId WHERE order.OrderStatus = 1";
$rowsorder = mysql_query($query);
//$roworder = mysql_fetch_assoc($rowsorder);
 while($roworder=mysql_fetch_array($rowsorder)){
	  $add_days = 30;
	  $my_date = date('m/d/Y',strtotime($roworder['OrderDate']));
	  
	  
	  $my_date = date('m/d/Y',strtotime($my_date.' +'.$add_days.' days'));
	  
	  
	  //$my_date = date("+".$days." days",strtotime($my_date));
	   
	   $now = time(); // or your date as well
     $your_date = strtotime($roworder['OrderDate']);
     $datediff = $now - $your_date;
	 $daysleft = $datediff/86400;  // 86400 seconds in one day

		// and you might want to convert to integer
		$daysleft = intval($daysleft);
	  	$daysleft = 30-$daysleft;
	  if($daysleft == 27 || $daysleft == 23 || $daysleft == 16 || $daysleft == 9 || $daysleft == 2 ){
			 $message = file_get_contents('reminder.html');
        $message = str_replace('%name%', $roworder['FirstName'], $message);
		$message = str_replace('%product%', $roworder['ProductDescription'], $message);
		$message = str_replace('%amount%', $roworder['OrderAmount'], $message);
		$message = str_replace('%orderdate%', date('m/d/Y',strtotime($roworder['OrderDate'])), $message);
		$message = str_replace('%daysleft%', $daysleft, $message);
		$message = str_replace('%expiration%',$my_date, $message);
		$message = str_replace('%trak%', $roworder['TrackingCode'], $message);
			$help = 'https://stopoint.com/help';
				$message = str_replace('%help%', $help, $message);
			 
$subject = "Reminder Email Regarding ".$roworder['ProductDescription'];
$txt = $message;
//$email_from = "stopointsupport";
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=ISO-8859-1" . "\r\n";
$headers .= 'From: STOPOINT <info@stopoint.com>' . "\r\n";

if (!mail('dev.fahad000@gmail.com',$subject,$txt,$headers)){
	echo 'Message could not be sent to '.$roworder['EmailAddress'];
	
                   $er=0;
}
	  }
 }