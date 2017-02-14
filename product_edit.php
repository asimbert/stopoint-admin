<?php
require_once(dirname(__FILE__) . '/inc/core.php');
require_once(dirname(__FILE__) . '/classes/class.table_form.php');

//require_once '/src/lib/Thrift/Exception/TTransportException.php';
$table_name = 'product';
$action = sanitize($_GET['action']);
$id = sanitize($_GET['id']);
$catid = sanitize($_GET['catid']);
$form = new table_form($table_name, "form");

if($action == "edit" && $id) {
	$row = $form->get_row("id",$id);
	$form->setValues($row);
}

if($catid=='all'){
$form->add_hidden('id');
$form->add_text('Product Model : ', 'ProductModel');
$form->add_text('Product Code : ', 'ProductCode');
$form->add_select('Product Family : ','FamilyId','productfamily','id','Name','Name');
$form->add_select('Product Brand : ','BrandId','productbrand','id','Name','Name');
$form->add_select('Carrier : ','CarrierId','carriers','id','Name','Name');
//$form->add_select('Product Category : ','CategoryId','productcategory','id','Name','Name');
$form->add_text('Like New Price : ', 'FlawessPrice');
$form->add_text('Good Price : ', 'GoodPrice');
$form->add_text('Generation : ', 'Generation');
$form->add_text('Storage Capacity : ', 'StorageCapacity');
$form->add_text('CPU : ', 'CPU');
$form->add_text('Screen Size : ', 'ScreenSize');
$form->add_text('RAM : ', 'RAM');
//$form->add_text('Band : ', 'Band');
$form->add_textarea('Description : ', 'Description',$row['Description']);
$form->add_image('Image  (<span style="font-size: 10px;">to change image upload a new image</span>)','video_thumb');
$form->add_textarea('Meta Description : ', 'meta_description',$row['meta_description']);
$form->add_textarea('Meta Keyword : ', 'meta_keyword',$row['meta_keyword']);	
}

if($catid==1){
$form->add_hidden('id');
$form->add_text('Product Model : ', 'ProductModel');
$form->add_text('Product Code : ', 'ProductCode');
$form->add_select('Product Family : ','FamilyId','productfamily','id','Name','Name');
$form->add_select('Product Brand : ','BrandId','productbrand','id','Name','Name');
$form->add_select('Carrier : ','CarrierId','carriers','id','Name','Name');
//$form->add_select('Product Category : ','CategoryId','productcategory','id','Name','Name');
$form->add_text('Like New Price : ', 'FlawessPrice');
$form->add_text('Good Price : ', 'GoodPrice');
$form->add_text('Generation : ', 'Generation');
$form->add_text('Storage Capacity : ', 'StorageCapacity');
$form->add_text('CPU : ', 'CPU');
$form->add_text('Screen Size : ', 'ScreenSize');
$form->add_text('RAM : ', 'RAM');
//$form->add_text('Band : ', 'Band');
$form->add_textarea('Description : ', 'Description',$row['Description']);
$form->add_image('Image  (<span style="font-size: 10px;">to change image upload a new image</span>)','video_thumb');
$form->add_textarea('Meta Description : ', 'meta_description',$row['meta_description']);
$form->add_textarea('Meta Keyword : ', 'meta_keyword',$row['meta_keyword']);
}
if($catid==2){
$form->add_hidden('id');
$form->add_text('Product Model : ', 'ProductModel');
$form->add_text('Product Code : ', 'ProductCode');
$form->add_select('Product Family : ','FamilyId','productfamily','id','Name','Name');
$form->add_select('Product Brand : ','BrandId','productbrand','id','Name','Name');
//$form->add_select('Carrier : ','CarrierId','carriers','id','Name','Name');
//$form->add_select('Product Category : ','CategoryId','productcategory','id','Name','Name');
$form->add_text('Like New Price : ', 'FlawessPrice');
$form->add_text('Good Price : ', 'GoodPrice');
$form->add_text('Acceptable Price : ', 'AcceptablePrice');
$form->add_text('Generation : ', 'Generation');
$form->add_text('Storage Capacity : ', 'StorageCapacity');
$form->add_text('CPU : ', 'CPU');
$form->add_text('Screen Size : ', 'ScreenSize');
//$form->add_text('RAM : ', 'RAM');
$form->add_text('Band : ', 'Band');
$form->add_textarea('Description : ', 'Description',$row['Description']);
$form->add_image('Image  (<span style="font-size: 10px;">to change image upload a new image</span>)','video_thumb');	
$form->add_textarea('Meta Description : ', 'meta_description',$row['meta_description']);
$form->add_textarea('Meta Keyword : ', 'meta_keyword',$row['meta_keyword']);
}
if($catid==3){
$form->add_hidden('id');
$form->add_text('Product Model : ', 'ProductModel');
$form->add_text('Product Code : ', 'ProductCode');
$form->add_select('Product Family : ','FamilyId','productfamily','id','Name','Name');
$form->add_select('Product Brand : ','BrandId','productbrand','id','Name','Name');
$form->add_select('Carrier : ','CarrierId','carriers','id','Name','Name');
//$form->add_select('Product Category : ','CategoryId','productcategory','id','Name','Name');
$form->add_text('Like New Price : ', 'FlawessPrice');
$form->add_text('Good Price : ', 'GoodPrice');
$form->add_text('Generation : ', 'Generation');
$form->add_text('Storage Capacity : ', 'StorageCapacity');
$form->add_text('CPU : ', 'CPU');
$form->add_text('Screen Size : ', 'ScreenSize');
$form->add_text('RAM : ', 'RAM');
//$form->add_text('Band : ', 'Band');
$form->add_textarea('Description : ', 'Description',$row['Description']);
$form->add_image('Image  (<span style="font-size: 10px;">to change image upload a new image</span>)','video_thumb');	
$form->add_textarea('Meta Description : ', 'meta_description',$row['meta_description']);
$form->add_textarea('Meta Keyword : ', 'meta_keyword',$row['meta_keyword']);
}
if($catid==23){
$form->add_hidden('id');
$form->add_text('Product Model : ', 'ProductModel');
$form->add_text('Product Code : ', 'ProductCode');
$form->add_select('Product Family : ','FamilyId','productfamily','id','Name','Name');
$form->add_select('Product Brand : ','BrandId','productbrand','id','Name','Name');
//$form->add_select('Carrier : ','CarrierId','carriers','id','Name','Name');
//$form->add_select('Product Category : ','CategoryId','productcategory','id','Name','Name');
$form->add_text('Like New Price : ', 'FlawessPrice');
$form->add_text('Good Price : ', 'GoodPrice');
$form->add_text('Generation : ', 'Generation');
//$form->add_text('Storage Capacity : ', 'StorageCapacity');
//$form->add_text('CPU : ', 'CPU');
$form->add_text('Screen Size : ', 'ScreenSize');
//$form->add_text('RAM : ', 'RAM');
//$form->add_text('Band : ', 'Band');
$form->add_textarea('Description : ', 'Description',$row['Description']);
$form->add_image('Image  (<span style="font-size: 10px;">to change image upload a new image</span>)','video_thumb');	
$form->add_textarea('Meta Description : ', 'meta_description',$row['meta_description']);
$form->add_textarea('Meta Keyword : ', 'meta_keyword',$row['meta_keyword']);
}
if($catid==5){
$form->add_hidden('id');
$form->add_text('Product Model : ', 'ProductModel');
$form->add_text('Product Code : ', 'ProductCode');
$form->add_select('Product Family : ','FamilyId','productfamily','id','Name','Name');
$form->add_select('Product Brand : ','BrandId','productbrand','id','Name','Name');
//$form->add_select('Carrier : ','CarrierId','carriers','id','Name','Name');
//$form->add_select('Product Category : ','CategoryId','productcategory','id','Name','Name');
$form->add_text('Like New Price : ', 'FlawessPrice');
$form->add_text('Good Price : ', 'GoodPrice');
$form->add_text('Generation : ', 'Generation');
//$form->add_text('Storage Capacity : ', 'StorageCapacity');
//$form->add_text('CPU : ', 'CPU');
$form->add_text('Screen Size : ', 'ScreenSize');
//$form->add_text('RAM : ', 'RAM');
$form->add_text('Band : ', 'Band');
$form->add_textarea('Description : ', 'Description',$row['Description']);
$form->add_image('Image  (<span style="font-size: 10px;">to change image upload a new image</span>)','video_thumb');	
$form->add_textarea('Meta Description : ', 'meta_description',$row['meta_description']);
$form->add_textarea('Meta Keyword : ', 'meta_keyword',$row['meta_keyword']);
}
if($catid==24){
$form->add_hidden('id');
$form->add_text('Product Model : ', 'ProductModel');
$form->add_text('Product Code : ', 'ProductCode');
$form->add_select('Product Family : ','FamilyId','productfamily','id','Name','Name');
$form->add_select('Product Brand : ','BrandId','productbrand','id','Name','Name');
$form->add_select('Carrier : ','CarrierId','carriers','id','Name','Name');
//$form->add_select('Product Category : ','CategoryId','productcategory','id','Name','Name');
$form->add_text('Like New Price : ', 'FlawessPrice');
$form->add_text('Good Price : ', 'GoodPrice');
$form->add_text('Generation : ', 'Generation');
$form->add_text('Storage Capacity : ', 'StorageCapacity');
$form->add_text('CPU : ', 'CPU');
$form->add_text('Screen Size : ', 'ScreenSize');
$form->add_text('RAM : ', 'RAM');
$form->add_text('Band : ', 'Band');
$form->add_textarea('Description : ', 'Description',$row['Description']);
$form->add_image('Image  (<span style="font-size: 10px;">to change image upload a new image</span>)','video_thumb');	
$form->add_textarea('Meta Description : ', 'meta_description',$row['meta_description']);
$form->add_textarea('Meta Keyword : ', 'meta_keyword',$row['meta_keyword']);
}
//$form->add_text('');
//$form->add_hidden('image_url');	
//$form->add_text('Capacity: ', 'capacity');	
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
$form->add_hidden('image_url');

if($action == "edit" && $id) {
	$row = $form->get_row("id",$id);
	$form->setValues($row);
}

$fields = array('ProductModel','ProductCode','FlawessPrice','GoodPrice','image_url','FamilyId','BrandId','CarrierId','CategoryId','Generation','StorageCapacity','CPU','ScreenSize','RAM','Band','AcceptablePrice','Description','meta_description','meta_keyword');
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
		$('#video_thumb').after('<img src="productimages/<?php echo $row['image_url'] ?>" id="thumb" border="0" style="width:252px;height:148px">')
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

				'uploader' : 'uploadify.php?page=productimages',

				'onUploadComplete' : function(file) {

           $('#image_url').val(file.name);

		   $('#video_thumb').before('<br><p style="color: #61A700;font-weight:bold;">Image uploaded succesfully,click Submit below to save your changes </p>');

		   $('img#thumb').attr('src','productimages/' + file.name);

		  // alert("Uploading Complete");

        }

				



			});

		});

});

</script>

<div id="main-content"> <!-- Main Content Section with everything -->

			

			<!-- Page Head -->

			<a href=""><h2>Products</h2></a>

						

			<?php if ($action_msg) echo $action_msg; ?>

			<br>		

			<div class="clear"></div> <!-- End .clear -->

			

			<div class="content-box"><!-- Start Content Box -->

					

				<div class="content-box-header">

					
					<?php if($catid=='all'){ ?>
                    <h3><?php echo getTitle($action); ?> Products(All)</h3>
                    <?php }?>
					<?php if($catid==1){ ?>
                    <h3><?php echo getTitle($action); ?> Products(Cell Phones)</h3>
                    <?php }?>
                    <?php if($catid==2){ ?>
                    <h3><?php echo getTitle($action); ?> Products(Computers)</h3>
                    <?php }?>
                    <?php if($catid==3){ ?>
                    <h3><?php echo getTitle($action); ?> Products(Tablets)</h3>
                    <?php }?>
                    <?php if($catid==23){ ?>
                    <h3><?php echo getTitle($action); ?> Products(iPod)</h3>
                    <?php }?>
                    <?php if($catid==5){ ?>
                    <h3><?php echo getTitle($action); ?> Products(Watches)</h3>
                    <?php }?>
                    <?php if($catid==24){ ?>
                    <h3><?php echo getTitle($action); ?> Products(Gadgets)</h3>
                    <?php }?>

					

					<!--<div class="clear"></div>-->

					

				</div> <!-- End .content-box-header -->

				

				<div class="content-box-content">

					

							<form action="" method="post">



								<fieldset> <!-- Set class to "column-left" or "column-right" on fieldsets to divide the form into columns -->



									<input class="hidden" type="hidden" name="CategoryId" id="CategoryId" value="<?=$catid ?>"  />
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