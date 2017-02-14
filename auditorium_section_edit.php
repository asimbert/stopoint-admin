<?php
require_once(dirname(__FILE__) . '/inc/core.php');
require_once(dirname(__FILE__) . '/classes/class.table_form.php');

require_once(dirname(__FILE__) . '/src/lib/Thrift/Exception/TException.php');
require_once (dirname(__FILE__) . '/src/lib/Thrift/StringFunc/TStringFunc.php');
require_once (dirname(__FILE__) . '/src/lib/Thrift/StringFunc/Core.php');
require_once (dirname(__FILE__) . '/src/lib/Thrift/Factory/TStringFuncFactory.php');
require_once (dirname(__FILE__) . '/src/lib/Thrift/Type/TType.php');
require_once (dirname(__FILE__) . '/src/lib/Thrift/Type/TMessageType.php');
require_once (dirname(__FILE__) . '/src/lib/Thrift/Protocol/TProtocol.php');
require_once (dirname(__FILE__) . '/src/lib/Thrift/Protocol/TBinaryProtocol.php');
require_once (dirname(__FILE__) . '/src/lib/Thrift/Transport/TTransport.php');
require_once (dirname(__FILE__) . '/src/lib/Thrift/Transport/TBufferedTransport.php');
require_once (dirname(__FILE__) . '/src/lib/Thrift/Transport/TSocket.php');

require_once (dirname(__FILE__) . '/src/lib/TicketService/VenuesClient.php');
require_once (dirname(__FILE__) . '/src/lib/TicketService/EventsClient.php');
require_once (dirname(__FILE__) . '/src/lib/TicketService/TicketsClient.php');

$url = "bryanlcampbell.asuscomm.com";
$portVenues = 6100;
$portEvents = 6101;
$portTickets = 6102;

$venuesClient = TicketService\VenuesApiClient::getInstance($url, $portVenues);
$eventsClient = TicketService\EventsApiClient::getInstance($url, $portEvents);
$ticketsClient = TicketService\TicketsApiClient::getInstance($url, $portTickets);
$address = new \TicketService\Generated\Address();


$table_name = 'auditorium_sections';

$action = sanitize($_GET['action']);
$id = sanitize($_GET['id']);
$form = new table_form($table_name, "form");

if($action == "edit" && $id) {
	$row = $form->get_row("id",$id);
	$form->setValues($row);
}


$form->add_hidden('id');
//$form->add_text('Name : ', 'name');
//$form->add_text('Place: ', 'place');	
if(isset($_GET['a_id'])){
	
	$idd='='.$_GET['a_id'];
	}	
	else{
		$idd='<> 1';
		}
$form->add_select('Auditorium','auditorium_id','auditorium','id','name','name','','id'. $idd);
$form->add_text('Name: ', 'name');	
$form->add_text('Number of Rows: ', 'no_of_rows');	
//$form->add_select('Type','type_id','property_type','id','name','name','','language_id=0');
//$form->add_select('District','district_id','property_district','id','title','title','','language_id=0');
//$form->add_dropdown('Is Featured','is_featured',array(array('name'=>'Yes','value'=>'Yes'),array('name'=>'No','value'=>'No')));
//$form->add_textarea('Short Description : ', 'short_description',$row['short_description']);
//$form->add_textarea('Long Description : ', 'long_description',$row['long_description']);
//$form->add_text('Short Description : ', 'short_description');
//$form->add_text('Long Description : ', 'long_description');		
//$form->add_text('Date Added : ', 'date_added');
//$form->add_text('Date Available : ', 'date_available');
//$form->add_text('Rooms : ', 'no_of_rooms');
//$form->add_text('Price : ', 'price');
//$form->add_text('Price Post Fix : ', 'price_postfix_text');
//$form->add_text('Size : ', 'size');
//$form->add_text('Parking : ', 'parking');
//$form->add_text('Address : ', 'address');
//$form->add_dropdown('Status','status',array(array('name'=>'Rent','value'=>'Rent'),array('name'=>'Sale','value'=>'Sale')));
//$form->add_text('Status : ', 'status');

//$form->add_hidden('photo');
//$form->add_image('Image  (<span style="font-size: 10px;">to change image upload a new image</span>)','video_thumb');	
//$form->add_text('');
//$form->add_hidden('image_url');
if($action == "edit" && $id) {
	$row = $form->get_row("id",$id);
	$form->setValues($row);
}
$fields = array('auditorium_id','name','no_of_rows');
//$fields = array('title','content','date','image_url');

if (isset($_POST['submit'])) {

	$url = url_page();	

	if (isset($_GET['p']))
		$p = $_GET['p'];
	else
		$p = 1;
		
	if ($id) {
		
		$name = $_POST['name'];
		$maxCapacity = $_POST['no_of_rows'];
		$spaceId = $_POST['auditorium_id'];
		//$reservationType = $_POST['type'];
		$generalAdmission = 1;
		$seatPreference = 1;
		$spaceadd = $venuesClient->editSection($sectionId, $spaceId, $name, $generalAdmission, $maxCapacity, $seatPreference);
		
        	$form->setPostFields($fields,'id', $id);
			header("Location: $url?saved&p=$p&sort=id&desc");
	}
	else {
		
		$name = $_POST['name'];
		$maxCapacity = $_POST['no_of_rows'];
		$spaceId = $_POST['auditorium_id'];
		//$reservationType = $_POST['type'];
		$generalAdmission = 1;
		$seatPreference = 1;
		$spaceadd = $venuesClient->addSection($sectionId, $spaceId, $name, $generalAdmission, $maxCapacity, $seatPreference);
			$form->setPostFields($fields,'id','',true);
			header("Location: $url?added&sort=id&desc");	
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
  
  		
<script type="text/javascript">
$(document).ready(function() {
	var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd
} 

if(mm<10) {
    mm='0'+mm
} 

today = mm+'/'+dd+'/'+yyyy;
//document.getElementById('date').style.display='none';
<?php if(!($action == "edit" && $id)) { ?>
//	document.getElementById('date_added').value=today ;
	<?php }  if ($row['image_url']){?>
		$('#video_thumb').after('<img src="map_images/<?php echo $row['image_url'] ?>" id="thumb" border="0" style="width:252px;height:148px">')
	<?php }else { ?>
	$('#video_thumb').after('<img src="images/no_image.png" id="thumb" border="0" style="width:252px;height:148px">')
	<?php } ?>
	<?php $timestamp = time();?>
		$(function() {
			$('#video_thumb').uploadify({
				'formData'     : {
					'timestamp' : '<?php echo $timestamp;?>',
					'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
				},
				'swf'      : 'uploadify.swf',
				'uploader' : 'uploadify.php?page=map_images',
				'onUploadComplete' : function(file) {
           $('#image_url').val(file.name);
		   $('#video_thumb').before('<br><p style="color: #61A700;font-weight:bold;">Image uploaded succesfully,click Submit below to save your changes </p>');
		   $('img#thumb').attr('src','map_images/' + file.name);
		  // alert("Uploading Complete");
        }
				

			});
		});
});
</script>
<div id="main-content"> <!-- Main Content Section with everything -->
			
			<!-- Page Head -->
			<a href="<?=$_SERVER['PHP_SELF']?>"><h2>Sections</h2></a>
						
			<?php if ($action_msg) echo $action_msg; ?>
			<br>		
			<div class="clear"></div> <!-- End .clear -->
			
			<div class="content-box"><!-- Start Content Box -->
					
				<div class="content-box-header">
					
					<h3><?php echo getTitle($action); ?> Sections</h3>
					
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					
							<form action="" method="post">

								<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->

									<?php while ($field = $form->get_field()) {	?>
										<p>
											<label><?=$form->show_label($field)?></label>
											<?=$form->show_input($field)?>
										</p>
											<?php } ?>
									<p>
										<input class="button" type="submit"  name="submit" value="Submit" />
									</p>

								</fieldset>

								<div class="clear"></div><!-- End .clear -->

							</form>
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->
			
<?php include("html/footer.php"); ?>