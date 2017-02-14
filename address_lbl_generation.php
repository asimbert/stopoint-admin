<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
session_start();
require_once('../fedex-common.php5');
$path_to_wsdl = "../ShipService_v15.wsdl";

function addShipper(){

	$shipper = array(
		'Contact' => array(
			'PersonName' => $_SESSION['from_name'],
			'CompanyName' => $_SESSION['form_company'],
			'PhoneNumber' => $_SESSION['from_phone']),
		'Address' => array(
			'StreetLines' => array($_SESSION['f_address']),
			'City' => $_SESSION['from_city'],
			'StateOrProvinceCode' => $_SESSION['from_state'],
			'PostalCode' => $_SESSION['from_zip_code'],
			'CountryCode' => 'US')
		
	);
	return $shipper;
}

function addRecipient(){

	$recipient = array(
		'Contact' => array(
			'PersonName' => $_SESSION['to_name'],
			'CompanyName' => $_SESSION['to_company'],
			'PhoneNumber' => $_SESSION['to_phone']),
			'Address' => array(
				'StreetLines' => array($_SESSION['to_address']),
				'City' => $_SESSION['to_city'],
				'StateOrProvinceCode' => $_SESSION['to_state'],
				'PostalCode' => $_SESSION['to_zip_code'],
				'CountryCode' => 'US')
	);

	return $recipient;

}

function addLabelSpecification(){
	$labelSpecification = array(
		'LabelFormatType' => 'COMMON2D', // valid values COMMON2D, LABEL_DATA_ONLY
		'ImageType' => 'PNG',  // valid values DPL, EPL2, PDF, ZPLII and PNG
		'LabelStockType' => 'PAPER_4X6');
	return $labelSpecification;
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

$trackingcode = "STP".mt_rand();
$trackingcode = str_replace('-', '', $trackingcode);

define('SHIP_LABEL', 'lbl_manual_generation/'.$_SESSION['to_name'].'-'.date("Y-m-d").'.png');  // PNG label file. Change to file-extension .pdf for creating a PDF label (e.g. shiplabel.pdf)

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
		
		if ($response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR')
			{
				
				$TrackingNumber = $response->CompletedShipmentDetail->CompletedPackageDetails->TrackingIds->TrackingNumber;
				$fp = fopen(SHIP_LABEL, 'w');
				fwrite($fp, $response->CompletedShipmentDetail->CompletedPackageDetails->Label->Parts->Image); //Create PNG or PDF file
				
				function RotateJpg($filename = '',$angle = 0,$savename = false)
					{
						ini_set('memory_limit', '-1');	//Added because it was giving issue in AWS
						// Your original file
						header('Content-type: image/png');
						$original   =   @imagecreatefrompng($filename);
						$srcsize = @getimagesize($filename);
						$dest_x = 2000; 
						$dest_y = (2000 / $srcsize[0]) * $srcsize[1]; 
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
				
					$filename   = "lbl_manual_generation/".$_SESSION['to_name'].'-'.date("Y-m-d").".png";
					// Destination, including document root (you may have a defined root to use)
					$saveto     =   "lbl_manual_generation/".$_SESSION['to_name'].'-'.date("Y-m-d").".png";
					// Apply function					
					RotateJpg($filename,90,$saveto);
					//define image path
					fclose($fp);
					
					require_once('../tcpdf.php');
					$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
					$pdf->AddPage();
					// set document information
					$pdf->SetCreator(PDF_CREATOR);				
					$pdf->SetAuthor('Syed Noman Ahmed');					
					$pdf->SetTitle('PDF');					
					$pdf->SetSubject('Shippping Label');					
					$pdf->SetKeywords('Shipping Label');
					
					$pdf->setPrintHeader(false);
					$pdf->setPrintFooter(false);
					
					$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
					$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
					$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
					
					if (file_exists(dirname(__FILE__).'/lang/eng.php')) {
					require_once(dirname(__FILE__).'/lang/eng.php');
					$pdf->setLanguageArray($l);}
					
					$pdf->StartTransform();
					$pdf->Translate(5, 160);
					$file="lbl_manual_generation/".$_SESSION['to_name'].'-'.date("Y-m-d").".png";
					$pdf->setImageScale(1.53);
					$pdf->SetXY(25, 20);
					$pdf->Image($file, '', '', 150, 95, '', '', 'T', false, 600, '', false, false, 1, false, false, false);
					$pdf->StopTransform();
					
			
					//ob_clean();
					if (ob_get_length() > 0) { ob_end_clean(); } 
					$pdf->Output($_SESSION['to_name'].date("Y-m-d").'.pdf', 'I');
			}
			
		else{
				$Errormessage = $response->Notifications;
				print_r($Errormessage);
				
			}
	}


catch (SoapFault $exception) 
	{
		echo $exception;
	}

?>