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


$table_name = 'event';

$action = sanitize($_GET['action']);
$id = sanitize($_GET['id']);
$form = new table_form($table_name, "form");

if($action == "edit" && $id) {
	$row = $form->get_row("id",$id);
	$form->setValues($row);
}

$form->add_hidden('id');
//$form->add_text('Person Name : ', 'person_name');
$form->add_text('Name : ', 'name');
$form->add_select('Artist','artist_id','artist','id','name','name');
$form->add_select('Venue','venue_id','venue','id','name','name');
$form->add_dropdown('Event Type','type',array(array('name'=>'Ticketing System','value'=>'Ticketing System'),array('name'=>'General Entry','value'=>'General Entry')));
$form->add_select('Event Category','category_id','event_category','id','name','name');
$form->add_text('Service Fee : ', 'servicefee');
$form->add_text('Capacity : ', 'capacity');
$form->add_text('Minimum Age : ', 'minimumage');
$form->add_textarea('Description : ', 'description',$row['description']);
$form->add_text('Date : ', 'date');

$form->add_image('Image  (<span style="font-size: 10px;">to change image upload a new image</span>)','video_thumb');	
//$form->add_text('');
$form->add_hidden('image_url');
//$form->add_text('Company : ', 'company');
//$form->add_select('Category','category_id','categories','id','name','name');
//$form->add_dropdown('State/Province : ','state',$states);
//$form->add_image('Photo','video_thumb');	
//$form->add_textarea('Content : ', 'content',$row['content']);
//$form->add_hidden('photo');

if($action == "edit" && $id) {
	$row = $form->get_row("id",$id);
	$form->setValues($row);
}

$fields = array('name','artist_id','venue_id','description','category_id','servicefee','capacity','minimumage','image_url','date','type','auditorium_id');

if (isset($_POST['submit'])) {

	$url = url_page();	

	if (isset($_GET['p']))
		$p = $_GET['p'];
	else
		$p = 1;
		
	if ($id) {
		
		$name = $_POST['name'];
		 $description = $_POST['description'];
		 $personAccountId = $_POST['artist_id'];
		 $spaceId = $_POST['auditorium_id'];
		$startTime = $_POST['date'];
		$categoryId = $_POST['category_id'];
		$typeId = $_POST['type'];
		$maxCapacity = $_POST['capacity'];
		$minimumAge = $_POST['minimumage'];
		
		$serviceFee = 220;
$urladdress = "http://ticketservice.com";
$eventadd = $eventsClient->addEvent($id, $name, $startTime, $endTime, $description, $url, $logoUrl, $organizationId, $personAccountId, $additionalInfo, $categoryId, $subcategoryId, $typeId, $minimumAge, $maxCapacity);


        	$form->setPostFields($fields,'id', $id);
			header("Location: $url?saved&p=$p&sort=id&desc");
	}
	else {
		
		$name = $_POST['name'];
		 $description = $_POST['description'];
		 $personAccountId = $_POST['artist_id'];
		 $spaceId = $_POST['auditorium_id'];
		$startTime = $_POST['date'];
		$categoryId = $_POST['category_id'];
		$typeId = $_POST['type'];
		$maxCapacity = $_POST['capacity'];
		$minimumAge = $_POST['minimumage'];
		
		$serviceFee = 220;
$urladdress = "http://ticketservice.com";
$eventadd = $eventsClient->addEvent($spaceId, $name, $startTime, $endTime, $description, $url, $logoUrl, $organizationId, $personAccountId, $additionalInfo, $categoryId, $subcategoryId, $typeId, $minimumAge, $maxCapacity);
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
	
	

//document.getElementById('date').style.display='none';
<?php if(!($action == "edit" && $id)) { ?>
//	document.getElementById('date_added').value=today ;
	<?php }  if ($row['image_url']){?>
		$('#video_thumb').after('<img src="event_images/<?php echo $row['image_url'] ?>" id="thumb" border="0" style="width:252px;height:148px">');
	<?php }else { ?>
	$('#video_thumb').after('<img src="images/no_image.png" id="thumb" border="0" style="width:252px;height:148px">');
	<?php } ?>
	<?php $timestamp = time();?>
		$(function() {
			$('#video_thumb').uploadify({
				'formData'     : {
					'timestamp' : '<?php echo $timestamp;?>',
					'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
				},
				'swf'      : 'uploadify.swf',
				'uploader' : 'uploadify.php?page=event_images',
				'onUploadComplete' : function(file) {
           $('#image_url').val(file.name);
		   $('#video_thumb').before('<br><p style="color: #61A700;font-weight:bold;">Image uploaded succesfully,click Submit below to save your changes </p>');
		   $('img#thumb').attr('src','event_images/' + file.name);
		  // alert("Uploading Complete");
        }
				

			});
		});
});
</script>
<script src="js/jquery.js"></script>

<script src="js/jquery-ui.js"></script>	
<script type="text/javascript">
var test=$.noConflict();
test(function() {
	test('#date').datetimepicker({
		dateFormat: "yy-mm-dd",
	timeFormat: 'HH:mm:ss',
	stepHour: 2,
	stepMinute: 10,
	stepSecond: 10
});
});


test(document).ready(function() {
	test( "#venue_id" )
  .change(function () {
	  
	  	test.ajax({
      url: "get_auditoriums.php",
      type: "POST",
      data: {"q":this.value},
	  dataType: 'text',
 
      success: function(data){
		  
		  $("#secctioni").html(data);
		 // alert(data);
		            
      },
      error:function(){
		  alert('error');
      }   
    }); 
	
  // alert(this.value);
  });
});
  </script>

<?php /*?>
<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>	
<link href="js/jquery-ui.css" rel="stylesheet">
  <script>
  $(function() {
	$('#date').datetimepicker({
		dateFormat: "yy-mm-dd",
	timeFormat: 'HH:mm:ss',
	stepHour: 2,
	stepMinute: 10,
	stepSecond: 10
});
});
  </script>
   			
<script type="text/javascript">
$(document).ready(function() {
	$( "#venue_id" )
  .change(function () {
	  
	  	$.ajax({
      url: "get_auditoriums.php",
      type: "POST",
      data: {"q":this.value},
	  dataType: 'text',
 
      success: function(data){
		  
		  $("#secctioni").html(data);
		 // alert(data);
		            
      },
      error:function(){
		  alert('error');
      }   
    }); 
	
  // alert(this.value);
  });
	
	<?php if ($row['photo']): $row['photo'] = strip_tags($row['photo']); ?>
		$('#video_thumb').after('<img src="/images/testimonials/<?php echo $row[photo]; ?>" border="0">');
	<?php endif; ?>
	
	$("#video_thumb").uploadify({
		'uploader'       : '/uploadify.swf',
		'script'         : '/uploadify.php?page=testimonials',
		'cancelImg'      : 'images/cancel.png',
		'auto'           : true,
		'multi'          : true,

		'onComplete'	 : function(event,queueID,fileObj,response) {

			$('#photo').val(response);
			$('#video_thumb').after('<p><img src="/images/testimonials/'+response+'" border="0"></p>');
			
			return true;

		 }
	});
});
</script><?php */?>
<div id="main-content"> <!-- Main Content Section with everything -->
			
			<!-- Page Head -->
			<a href="<?=$_SERVER['PHP_SELF']?>"><h2>Events</h2></a>
						
			<?php if ($action_msg) echo $action_msg; ?>
			<br>		
			<div class="clear"></div> <!-- End .clear -->
			
			<div class="content-box"><!-- Start Content Box -->
					
				<div class="content-box-header">
					
					<h3><?php echo getTitle($action); ?> Events</h3>
					
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
                                        <?php if($field['id']=='venue_id'){ ?> <div id="secctioni"></div> <?php } ?>
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