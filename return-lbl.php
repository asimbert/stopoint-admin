<?php 

// Include the main TCPDF library (search for installation path).

require_once('tcpdf.php');



// create new PDF document

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);



// set document information

$pdf->SetCreator(PDF_CREATOR);

$pdf->SetAuthor('Sahib');

$pdf->SetTitle('PDF');

$pdf->SetSubject('PDF Generate');

$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

 

// set default header data

// remove default header/footer

$pdf->setPrintHeader(false);

$pdf->setPrintFooter(true);



// set default monospaced font

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);



// set margins

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);



// set auto page breaks

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);



// set image scale factor

//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$pdf->setImageScale(1.53);

// set some language-dependent strings (optional)

if (file_exists(dirname(__FILE__).'/lang/eng.php')) {

	require_once(dirname(__FILE__).'/lang/eng.php');

	$pdf->setLanguageArray($l);

}



// ---------------------------------------------------------



// set font



// add a page
$orderid=$_GET['orderid'];
$trackingid=$_GET['trackingid'];
//$trackingid='STP414241934';
//$orderid='1385';
$pdf->AddPage();

// set text shadow effect

$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.0, 'depth_h'=>0.0, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

$pdf->StartTransform();
$pdf->Translate(5, 160);
$file="returnlabels/label_$orderid.png";
$pdf->setImageScale(1.53);
$pdf->SetXY(25, 20);
$pdf->Image($file, '', '', 150, 95, '', '', 'T', false, 600, '', false, false, 1, false, false, false);
$pdf->StopTransform();

$style = array(
	'position' => '',
	'align' => 'C',
	'stretch' => false,
	'fitwidth' => true,
	'cellfitalign' => '',
	'border' => false,
	'hpadding' => 'auto',
	'vpadding' => 4,
	'fgcolor' => array(0,0,0),
	'bgcolor' => false, //array(255,255,255),
	'text' => true,
	'font' => 'helvetica',
	'fontsize' => 8,
	'stretchtext' => 4
);

$pdf->StartTransform();
$pdf->Translate(60, 375);
$pdf->Rotate(90, 0, 25);
$pdf->write1DBarcode($trackingid, 'C128', '', '', '', 18, 0.4, $style, 'N');
$pdf->StopTransform();



// Close and output PDF document

// This method has several options, check the source code documentation for more information.

ob_clean();

$pdf->Output('PDF_FILE.pdf', 'I');



//============================================================+

// END OF FILE

//============================================================+