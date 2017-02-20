<?php



require_once(dirname(__FILE__) . '/inc/core.php');



require_once(dirname(__FILE__) . '/classes/class.table_form.php');



require_once('fedex-common.php5');

$path_to_wsdl = "ShipService_v15.wsdl";



 $orderid = $_POST['subId']; 

  

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

  WHEN '2' THEN 'Good'

  WHEN '1' THEN 'Fair'

 ELSE 'Good'

 END as OrderCondition FROM `order` INNER JOIN `product` ON product.ProductCode=order.ProductId INNER JOIN `user` ON user.id=order.UserId INNER JOIN `productbrand` ON productbrand.id=product.BrandId INNER JOIN `productfamily` ON productfamily.Id=product.FamilyId INNER JOIN `productcategory` ON productcategory.id=product.CategoryId WHERE order.id= ".$orderid;



$rowsorder = mysql_query($query);

$roworder = mysql_fetch_assoc($rowsorder);

$personname = $roworder['FirstName']." ".$roworder['LastName'];

$_SESSION['personname'] = $personname;

$_SESSION['phone'] = $roworder['Phone'];



$_SESSION['address1'] = $roworder['S_AddressLine1'];



$_SESSION['address2'] = $roworder['S_AddressLine2'];



$_SESSION['postal'] = $roworder['S_PostalCode'];



$_SESSION['city'] = $roworder['S_City'];



$_SESSION['state'] = $roworder['S_State'];


$_SESSION['stdcode']=$roworder['TrackingCode'];
function addShipper(){

	

	$shipper = array(

		'Contact' => array(

			'PersonName' => 'Stopoint', //doesn't process at all if at least this one isn't a text value

			'CompanyName' => 'Stopoint',

'PhoneNumber' => '1-786-506-2217'), 

		'Address' => array(

			'StreetLines' => '1175 NE 125St Suite 211',

			'City' => 'Miami',

			'StateOrProvinceCode' => 'FL',

			'PostalCode' => '33161',

			'CountryCode' => 'US')

	);

	

	return $shipper;

}

function addRecipient(){

	$recipient = array(

		'Contact' => array(

			'PersonName' => $_SESSION['personname'],

			'PhoneNumber' => $_SESSION['phone']),

		'Address' => array(

			'StreetLines' => array($_SESSION['address1'], $_SESSION['address2']),

			'City' => $_SESSION['city'],

			'StateOrProvinceCode' => $_SESSION['state'],

			'PostalCode' => $_SESSION['postal'],

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



define('SHIP_LABEL', 'returnlabels/label_'.$orderid.'.png');  // PNG label file. Change to file-extension .pdf for creating a PDF label (e.g. shiplabel.pdf)

			

			

			////////////////////////////////////////////// Shipping label /////////////////////////

			

			

			

			ini_set("soap.wsdl_cache_enabled", "0");



			$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

			

			

			$request['WebAuthenticationDetail'] = array(

				'UserCredential' => array(

					'Key' => getProperty('key'),

					'Password' => getProperty('password')

				)

			);

			$request['ClientDetail'] = array(

				'AccountNumber' => getProperty('shipaccount'),

				'MeterNumber' => getProperty('meter')

			);

			$request['TransactionDetail'] = array('CustomerTransactionId' => '*** Express Domestic Shipping Request v15 using PHP ***');

			$request['Version'] = array(

				'ServiceId' => 'ship',

				'Major' => '15',

				'Intermediate' => '0',

				'Minor' => '0'

			);

			

			

			

			

			$request['RequestedShipment'] = array(

				'ShipTimestamp' => date('c'),

				'DropoffType' => 'REGULAR_PICKUP', // valid values REGULAR_PICKUP, REQUEST_COURIER, DROP_BOX, BUSINESS_SERVICE_CENTER and STATION

				'ServiceType' => 'FEDEX_GROUND', // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...

				'PackagingType' => 'YOUR_PACKAGING', // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...

				'TotalWeight' => array('Value' => 1.0, 'Units' => 'LB'), // valid values LB and KG

				'Shipper' => addShipper(),   ////////////////////**** FUNCTION CALL *****//////////////////////////

				'Recipient' => addRecipient(),

				'ShippingChargesPayment' => addShippingChargesPayment(),

				//'SpecialServicesRequested' => addSpecialServices(),

				'LabelSpecification' => addLabelSpecification(),

				'RateRequestTypes' => array('ACCOUNT'), // valid values ACCOUNT and LIST

				'PackageCount' => 1,

				'RequestedPackageLineItems' => array(

					'0' => addPackageLineItem1()

				)

			);

			

			

			try

			{

				if(setEndpoint('changeEndpoint'))

				{

					$newLocation = $client->__setLocation(setEndpoint('endpoint'));

				}

			

				$response = $client->processShipment($request);  // FedEx web service invocation

			//echo($response->TrackingIds);

			

			

			//$array = get_object_vars($response);

			

			//echo $array['TrackingNumber'];

			

if ($response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR')

{

///////////////Save Order /////////////////////////



$TrackingNumber = $response->CompletedShipmentDetail->CompletedPackageDetails->TrackingIds->TrackingNumber;

$updaterecord =  mysql_query("UPDATE `order` SET `Returnlabel` = '$TrackingNumber' WHERE id = ".$orderid) or die(mysql_error());



///////////////////////////////////////////////////					

					

					

					//printSuccess($client, $response);

			

					// Create PNG or PDF label 

					// Set LabelSpecification.ImageType to 'PDF' or 'PNG for generating a PDF or a PNG label

					$fp = fopen(SHIP_LABEL, 'w');

					

					fwrite($fp, $response->CompletedShipmentDetail->CompletedPackageDetails->Label->Parts->Image); //Create PNG or PDF file
		
					$filename   = "returnlabels/label_".$orderid.".png";
					$saveto     =   "returnlabels/label_".$orderid.".png";
					
					RotateJpg($filename,90,$saveto);

					?>

                     

                    <!--<a href="returnlabels/label_<?php //echo $orderid ?>.png" style="font-size:14px;" target="_blank">Download Label</a>-->
					<a href="return-lbl.php?orderid=<?=$orderid?>&trackingid=<?=$_SESSION['stdcode']?>" target="_blank">Download Label</a>

                    <?php

					fclose($fp);

					

					

					//echo getProperty('billaccount');

					//echo '<h3>This is your printable shipping label.</h3> <strong>For your convenience a copy has been sent to the email address provided.</strong><br /><img src="http://stopoint.com/'.SHIP_LABEL.'" />';

				

				}

				else

				{

					

					$Errormessage = $response->Notifications;

					print_r($Errormessage);

					//exit;

					//if($Errormessage == 'Shipper Postal-State Mismatch' || $Errormessage == 'Invalid Shipper Postal Code Format' || $Errormessage == 'Shipper Postal Code not found'){

						//echo "error";

						

						?>

                        

                        <div class="alert alert-danger">

    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

    <strong>Error!</strong> Zip code for the State or address mismatch please go back and enter correct details.

   </div>

                        <?php

						

						//}

					

					//echo "results:". $_SESSION['senderName']." ".$_SESSION['senderPhone']." ".$_SESSION['senderStreet']." ".$_SESSION['senderLine2']." ".$_SESSION['senderCity']." ".$_SESSION['senderState']." ".$_SESSION['senderZip'];

					//printError($client, $response);

					

				}

			

				writeToLog($client);    // Write to log file

			

			} catch (SoapFault $exception) {

//echo "results:". $_SESSION['senderName']." ".$_SESSION['senderPhone']." ".$_SESSION['senderStreet']." ".$_SESSION['senderLine2']." ".$_SESSION['senderCity']." ".$_SESSION['senderState']." ".$_SESSION['senderZip'];

				//printFault($exception, $client);

			}

function RotateJpg($filename = '',$angle = 0,$savename = false)

    {

        // Your original file

		header('Content-type: image/png');

        $original   =   @imagecreatefrompng($filename);

        

		//print_r($original);

		$srcsize = @getimagesize($filename);

		$dest_x = 2000; 

		$dest_y = (2000 / $srcsize[0]) * $srcsize[1]; 

		//echo $dest_x." ".$dest_y;

		$dst_img = @imagecreatetruecolor($dest_x, $dest_y);

		@imagecopyresampled($dst_img, $original, 0, 0, 0, 0,$dest_x, $dest_y, $srcsize[0], $srcsize[1]); 

		@imagedestroy($original);

		// Rotate

        $rotated    =   @imagerotate($dst_img, $angle, 0);

		//print_r($rotated);

		@imagedestroy($dst_img);

		

        // If you have no destination, save to browser

        if($savename == false) {

                header('Content-Type: image/png');

                @imagepng($rotated);

            }

        else

            // Save to a directory with a new filename

			

        @imagepng($rotated,$savename);

			

			//print_r( imagepng($rotated,$savename));

        // Standard destroy command

        imagedestroy($rotated);

    }



// Base image


?>

