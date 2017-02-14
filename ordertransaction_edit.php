<?php
require_once(dirname(__FILE__) . '/inc/core.php');
require_once(dirname(__FILE__) . '/classes/class.table_form.php');

//require_once '/src/lib/Thrift/Exception/TTransportException.php';
$table_name = 'ordertrasactions';
$action = sanitize($_GET['action']);
$id = sanitize($_GET['id']);
//$catid = sanitize($_GET['catid']);
$form = new table_form($table_name, "form");

if($action == "edit" && $id) {
	$row = $form->get_row("id",$id);
	$form->setValues($row);
}

$form->add_hidden('id');
$form->add_readonly('Order No : ', 'OrderId');
$form->add_readonly('Transaction No : ', 'TransactionId');
$form->add_readonly('Fedex Tracking Id : ', 'FedexCode');
$form->add_readonly('Customer Name : ', 'UserId');
$form->add_readonly('Account Number : ', 'AccountNumber');
$form->add_readonly('Check Number : ', 'ChequeNumber');
$form->add_readonly('Amount Paid : ', 'AmountPaid');
$form->add_readonly('Payment Method : ', 'PaymentMethod');
$form->add_readonly('Date Paid : ', 'DatePaid');
$form->add_textarea('Comments : ', 'Comments', $row['Comments']);
$form->add_hidden('image_url');

if($action == "edit" && $id) {
	$row = $form->get_row("id",$id);
	$form->setValues($row);
}

$fields = array('OrderId','TransactionId','AmountPaid','DatePaid','Comments');
//$fields = array('title','content','date','image_url');

if (isset($_POST['submit'])) {
	$url = url_page();	
	if (isset($_GET['p']))
		$p = $_GET['p'];
	else
		$p = 1;
	if ($id) {		
//print_r($venuesadd);
        	
			
			$form->setPostFields($fields,'id', $id);
			
			header("Location: $url?saved&p=$p&catid=$catid&sort=id&desc");
	}
	else { 
			$form->setPostFields($fields,'id','',true);
			header("Location: $url?added&catid=$catid&sort=id&desc");
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
		$('#video_thumb').after('<img src="product_images/<?php echo $row['image_url'] ?>" id="thumb" border="0" style="width:252px;height:148px">')
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

				'uploader' : 'uploadify.php?page=product_images',

				'onUploadComplete' : function(file) {

           $('#image_url').val(file.name);

		   $('#video_thumb').before('<br><p style="color: #61A700;font-weight:bold;">Image uploaded succesfully,click Submit below to save your changes </p>');

		   $('img#thumb').attr('src','product_images/' + file.name);

		  // alert("Uploading Complete");

        }

				



			});

		});

});

</script>

<div id="main-content"> <!-- Main Content Section with everything -->

			

			<!-- Page Head -->

			<a href=""><h2>Order Transaction</h2></a>

						

			<?php if ($action_msg) echo $action_msg; ?>

			<br>		

			<div class="clear"></div> <!-- End .clear -->

			

			<div class="content-box"><!-- Start Content Box -->

					

				<div class="content-box-header">

					<!--<div class="clear"></div>-->

					

				</div> <!-- End .content-box-header -->

				

				<div class="content-box-content">

					

							<form action="" method="post">



								<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->



									<!--<input class="hidden" type="hidden" name="CategoryId" id="CategoryId" value="<? //$catid ?>"  />-->
									<?php while ($field = $form->get_field()) {	?>

										<p>
                                        
                                            <label>
											<?php
											if($field[name]=='Account Number : '){
												if($row['PaymentMethod']==2){
											?>
											<?=$form->show_label($field)?></label>
                                            <?php
												}
											}elseif($field[name]=='Check Number : '){
												if($row['PaymentMethod']==2){
											?>
											<?=$form->show_label($field)?></label>
                                            <?php
												}
											}else{
											?>
                                            <?=$form->show_label($field)?></label>
                                            <?php
											}
											?>
                                            <?php
											if($field[name]=='Payment Method : '){
												if($row['PaymentMethod']==1){
											?>
											<input class="text-input small-input" type="text" name="PaymentMethod" id="PaymentMethod" value="Paypal" readonly="readonly" />
                                            <?php
												}
												else{
											?>
                                            <input class="text-input small-input" type="text" name="PaymentMethod" id="PaymentMethod" value="Check" readonly="readonly" />
                                            <?php
												}
											}
											elseif($field[name]=='Customer Name : '){
												$query = "SELECT * FROM `user` WHERE id = ".$row['UserId'];
												$reuser = mysql_query($query);
												$weuser = mysql_fetch_assoc($reuser);
											?>
                                            <input class="text-input small-input" type="text" name="CustomerName" id="CustomerName" value="<?=$weuser['FirstName'].' '.$weuser['LastName']?>" readonly="readonly" />
                                            
                                           <div class="shipping" style="float:left; width:50%;">
                                        	<p>
											<label>Shipping Address 1 : </label>
											<input class="text-input small-input" style="width: 50% !important;" type="text" name="saddress1" id="saddress1" size="20" value="<?php echo $weuser['S_AddressLine1'];  ?>" readonly="readonly">
										    </p>
                                        
                                            <p>
											<label>Shipping Address 2 : </label>
											<input class="text-input small-input" style="width: 50% !important;" type="text" name="saddress2" id="saddress2" size="20" value="<?php echo $weuser['S_AddressLine2'];  ?>" readonly="readonly">
										    </p>
                                        
                                            <p>
											<label>Shipping City : </label>
											<input class="text-input small-input" style="width: 50% !important;" type="text" name="scity" id="scity" size="20" value="<?php echo $weuser['S_City'];  ?>" readonly="readonly">
										    </p>
                                        
                                            <p>
											<label>Shipping State : </label>
											<input class="text-input small-input" style="width: 50% !important;" type="text" name="sstate" id="sstate" size="20" value="<?php echo $weuser['S_State'];  ?>" readonly="readonly">
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
                                            
                                            <?php
											}
											elseif($field[name]=='Account Number : '){
												if($row['PaymentMethod']==2){
											?>
                                            <?=$form->show_input($field)?>
                                            <?php	
												}
											}elseif($field[name]=='Check Number : '){
												if($row['PaymentMethod']==2){
											?>
                                            <?=$form->show_input($field)?>
                                            <?php	
												}
											}
											else{
											?>
                                            <?=$form->show_input($field)?>
                                            <?php
											}
											?>

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