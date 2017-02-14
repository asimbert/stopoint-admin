<?php
require_once(dirname(__FILE__) . '/inc/core.php');
require_once(dirname(__FILE__) . '/classes/class.table_form.php');

$table_name = 'ticket';

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


$dbn = new Database();

// getting backend settings;

$vas = $dbn->select("SELECT * FROM event WHERE id=$_GET[a_id]") or die($db->error());
//print_r($vas);exit;


$form->add_text('Name: ', 'name');	
$form->add_text('Price ($): ', 'price');
$form->add_dropdown('Status','status',array(array('name'=>'Available','value'=>'Available'),array('name'=>'Sold','value'=>'Sold')));	

//$form->add_select('Auditorium','auditorium_id','auditorium','id','name','name','','venue_id='.$vas['venue_id']);
if($action == "edit"){
$form->add_hidden_value('auditorium_id',$vas['auditorium_id']);
$form->add_hidden_value('auditorium_section_id',$vas['auditorium_section_id']);
$form->add_hidden_value('section_row_id',$vas['section_row_id']);
$form->add_hidden_value('row_seat_id',$vas['row_seat_id']);
}
else{
	$form->add_select('Auditorium','auditorium_id','auditorium','id','name','name','','id='.$vas['auditorium_id']);
	}

$form->add_hidden_value('event_id',$_GET['a_id']);
$form->add_hidden_value('artist_id',$vas['artist_id']);
$form->add_hidden_value('venue_id',$vas['venue_id']);


if($action == "edit" && $id) {
	$row = $form->get_row("id",$id);
	$form->setValues($row);
}
$fields = array('name','price','status','event_id','artist_id','venue_id','auditorium_id','auditorium_section_id','section_row_id','row_seat_id');
//$fields = array('title','content','date','image_url');

if (isset($_POST['submit'])) {

	$url = url_page();	

	if (isset($_GET['p']))
		$p = $_GET['p'];
	else
		$p = 1;
		
	if ($id) {
        	$form->setPostFields($fields,'id', $id);
			header("Location: $url?saved&p=$p&sort=id&desc");
	}
	else {
		if(!($_POST['section_row_id'])){
			$_POST['section_row_id']=-1;
			}
		if(!($_POST['row_seat_id'])){
			$_POST['row_seat_id']=-1;
			}	
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
function get_rows(val){
		$.ajax({
      url: "get_rows.php",
      type: "POST",
      data: {"q":val},
	  dataType: 'text',
 
      success: function(data){
		  
		  $("#rowi").html(data);
		 // alert(data);
		            
      },
      error:function(){
		  alert('error');
      }   
    }); 
	}
function get_seats(val){
		$.ajax({
      url: "get_seats.php",
      type: "POST",
      data: {"q":val},
	  dataType: 'text',
 
      success: function(data){
		  
		  $("#seati").html(data);
		 // alert(data);
		            
      },
      error:function(){
		  alert('error');
      }   
    }); 
	}	
$(document).ready(function() {
	<?php if(!($action == "edit")){ ?>
	$("#auditorium_id").val(function () {
    return $(this).find('option:eq(1)').attr('value')
});
var gg=document.getElementById('auditorium_id').value;
$.ajax({
      url: "get_sections.php",
      type: "POST",
      data: {"q":gg},
	  dataType: 'text',
 
      success: function(data){
		  
		  $("#secctioni").html(data);
		 // alert(data);
		            
      },
      error:function(){
		  alert('error');
      }   
    }); 
	<?php } /*?>$( "#auditorium_section_id" )
  .change(function () {
	  alert('dsd');
	  	$.ajax({
      url: "get_rows.php",
      type: "POST",
      data: {"q":this.value},
	  dataType: 'text',
 
      success: function(data){
		  
		  $("#rowi").html(data);
		 // alert(data);
		            
      },
      error:function(){
		  alert('error');
      }   
    }); 
	
  // alert(this.value);
  });
	<?php */?>
	
	$( "#auditorium_id" )
  .change(function () {
	  
	  	$.ajax({
      url: "get_sections.php",
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
  
  
 // .change();
	//alert('test');
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
			<a href="<?=$_SERVER['PHP_SELF']?>"><h2>Ticket</h2></a>
						
			<?php if ($action_msg) echo $action_msg; ?>
			<br>		
			<div class="clear"></div> <!-- End .clear -->
			
			<div class="content-box"><!-- Start Content Box -->
					
				<div class="content-box-header">
					
					<h3><?php echo getTitle($action); ?> Ticket</h3>
					
					<div class="clear"></div>
					
				</div> <!-- End .content-box-header -->
				
				<div class="content-box-content">
					
							<form action="" method="post">

								<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->

									<?php while ($field = $form->get_field()) {	?>
										<p <?php if($field['id']=='event_id' || $field ['id']=='venue_id' || $field ['id']=='artist_id' || ($action == "edit" && $field['id']=='auditorium_id') || ($action == "edit" && $field['id']=='auditorium_section_id') || ($action == "edit" && $field['id']=='section_row_id')){ ?>  style="display:none" <?php }?> >
                                        
											<label><?=$form->show_label($field)?></label>
											<?=$form->show_input($field)?>
										</p>
											<?php } ?>
                                            <div id="secctioni"></div>
                                            <div id="rowi"></div>
                                            <div id="seati"></div>
									<p>
										<input class="button" type="submit"  name="submit" value="Submit" />
									</p>

								</fieldset>

								<div class="clear"></div><!-- End .clear -->

							</form>
					
				</div> <!-- End .content-box-content -->
				
			</div> <!-- End .content-box -->
			
<?php include("html/footer.php"); ?>