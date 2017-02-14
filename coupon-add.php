<?ini_set("display_errors", "1");error_reporting(E_ALL);$action_msg = "";

//require_once(dirname(__FILE__) . '/allow-access.php');

require_once(dirname(__FILE__) . '/inc/core.php');
require_once(dirname(__FILE__) . '/classes/class.table_form.php');

include(dirname(__FILE__) . '/html/header.php');
include(dirname(__FILE__) . '/html/menu.php');

?>

<div id="main-content"> <!-- Main Content Section with everything -->
	<a href="<?=$_SERVER['PHP_SELF']?>"><h2>Coupon</h2></a>
	<?php if ($action_msg) echo $action_msg; ?>

	<br>		
	<div class="clear"></div> <!-- End .clear -->
	<div class="content-box"><!-- Start Content Box -->
		<div class="content-box-header">
			<h3>Add Coupon</h3>
			<div class="clear"></div>
		</div> <!-- End .content-box-header -->
		<div class="content-box-content">
			<form action="" method="post" onsubmit="return validateCouponForm()">
				<fieldset>
					<p>
						<label>Coupon code : </label>
						<input id="CouponCode" class="text-input small-input" name="CouponCode" size="20" type="text">
					</p>
					<p>
						<label>Coupon Category : </label>
						<?php
							$cat=mysql_query("select * from productcategory");
						?>
							<select id="CategoryId" class="small-input" name="CategoryId[]" multiple>
						<?php
							while($c=mysql_fetch_array($cat))
							{
								?><option value="<?php echo $c['Id'] . "{|}" .$c['Name']; ?>"><?php echo $c['Name']; ?></option> <?php
							}
						?>
						</select>
					</p>										<p>						<label>Coupon Brand : </label>						<?php											?>							<select id="BrandId" class="small-input" name="BrandId[]" multiple>														<?php													?>						</select>					</p>					<p>						<label>Products : </label>													<select id="FamilyId" class="small-input" name="FamilyId[]" multiple>						</select>					</p>					
					<p>
						<label>Coupon Type : </label>
						<select id="CoupType" class="small-input" name="CoupType" onchange="getdistype(this.value)">
							<option value="">-- Please Select --</option>														<option value="onetime"> One Time </option>
							<option value="lifetime"> Life Time </option>							<option value="fixtime"> Fix Time </option>							
						</select>
						<script type="text/javascript">
							function getdistype(val) {
								if(val=='fixtime')
								{
									document.getElementById('hidcoptype').style.display='block';
									document.getElementById('hidcoptypeto').style.display='block';
								}
								else
								{
									document.getElementById('hidcoptype').style.display='none';
									document.getElementById('hidcoptypeto').style.display='none';
								}
							}
						</script>
					</p>
					<p id="hidcoptype" style="display:none">
						<label>Valid From : </label><input type="text" name="valid_frm" id="valid_frm" 
						class="text-input small-input" autocomplete=off />
					</p>
					<p id="hidcoptypeto" style="display:none">
						<label>Valid To : </label><input type="text" name="valid_to" id="valid_to" 
						class="text-input small-input" autocomplete=off />
					</p>
					<p>
						<label>Coupon Amount : </label>
						<select id="Coupdis" class="small-input" name="Coupdis" 
						onchange="getdis(this.value)" >
							<option value="">-- Please Select --</option>
							<option value="per"> Percentage </option>
							<option value="amount"> Amount </option>
						</select>
						<script type="text/javascript">
							function getdis(val)
							{
								if(val=='per')
								{
									document.getElementById('hidcopamt').style.display='none';
									document.getElementById('hidcopper').style.display='block';
								}
								else if(val=='amount')
								{
									document.getElementById('hidcopper').style.display='none';
									document.getElementById('hidcopamt').style.display='block';
								}
								else
								{
									document.getElementById('hidcopper').style.display='none';
									document.getElementById('hidcopamt').style.display='none';
								}
							}
						
							/*function getdis(val) { 
								$.ajax({
									type: "POST",
									url: "get_coupon.php",
									data:{type: 'discount', dis: val},
									success: function(data){
										$("#hidcopdis").html(data);
									}
								});
							}*/
						</script>
					</p>
					<!--<p id="hidcopdis"></p>-->
					<p id="hidcopper" style="display:none">
						<label>Percentage : </label>
						<input id="coup_per" class="text-input small-input" name="coup_per" size="20" type="text">
					</p>
					<p id="hidcopamt" style="display:none">
						<label>Amount : </label>
						<input id="coup_amt" class="text-input small-input" name="coup_amt" size="20" type="text">
					</p>
					<p>
						<input class="button" type="submit" name="submit" value="Submit" />
					</p>
				</fieldset>

				<div class="clear"></div><!-- End .clear -->
			</form>
		</div> <!-- End .content-box-content -->
	</div> <!-- End .content-box -->

<?php include("html/footer.php"); ?>

<link rel="stylesheet" href="<?php echo $base_url; ?>/css/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="<?php echo $base_url; ?>/js/jquery-ui.js"></script>
<script>
  $( function() {
    $( "#valid_frm" ).datepicker();
	$( "#valid_to" ).datepicker();
  } );    $('#BrandId').change('change',function(){	var data = "";	$.ajax({		type:"GET",		url : "fetchAsDropdown.php",		data : "category_id="+$("#CategoryId").val() + "&dropdown=brand&brand_id="+$(this).val(),		async: false,		success : function(response) {			data = response;			return response;		},		error: function() {			alert('Error occured');		}	});	$('#FamilyId').html(data);});    $('#CategoryId').change('change',function(){	$('#FamilyId').html(""); var data = "";	$.ajax({		type:"GET",		url : "fetchAsDropdown.php",		data : "category_id="+$(this).val() + "&dropdown=category",		async: false,		success : function(response) {			data = response;			return response;		},		error: function() {			alert('Error occured');		}	});	$('#BrandId').html(data);});    function validateCouponForm(){	  var CouponCode = $("#CouponCode").val();	  	  if(CouponCode == ""){		  alert("Coupon Code is mandatory");		  $("#CouponCode").focus();		  return false;	  }  }  </script>

<?php
	
	if(isset($_POST['CouponCode']) && $_POST['CouponCode']!='')
	{
		if($_POST['Coupdis']=='per')
		{
			$Coupdis_value=$_POST['coup_per'];
		}
		if($_POST['Coupdis']=='amount')
		{
			$Coupdis_value=$_POST['coup_amt'];
		}								$ins = false;						$cat_txt = "";		$brand_txt = "";		$family_txt = "";		$FamilyId = "";		$all_product = false;				foreach ($_POST['CategoryId'] as $CategoryId){			$CategoryArr = explode("{|}",$CategoryId);			$category_id = $CategoryArr[0];			$CategoryName = $CategoryArr[1];						$cat_txt .= $CategoryName . "{|}";						if(isset($_POST['BrandId'])){				foreach ($_POST['BrandId'] as $BrandId){					$BrandArr = explode("{|}",$BrandId);					$brand_id = $BrandArr[0];					$BrandName = $BrandArr[1];								$brand_txt .= $BrandName . "{|}";										if(isset($_POST['FamilyId'])){						foreach ($_POST['FamilyId'] as $FamilyId){							$FamilyArr = explode("{|}",$FamilyId);							$FamilyName = $FamilyArr[1];														$family_txt .= $FamilyName . "{|}";						}					}else{						$all_product = true;												$query = "SELECT DISTINCT productfamily.id, productfamily.Name from product INNER JOIN `productfamily` ON productfamily.id=product.FamilyId WHERE product.CategoryId = $category_id AND product.BrandId=$brand_id";											$result2=mysql_query($query) or die(mysql_error());						while($row2=mysql_fetch_array($result2)){							$FamilyName = $row2['Name'];							$family_id = $row2['id'];													$family_txt .= $FamilyName . "{|}";												}					}									}			}else{				$all_product = true;								$query = "SELECT DISTINCT productbrand.id, productbrand.Name from product INNER JOIN `productbrand` ON productbrand.id=product.BrandId WHERE product.CategoryId = $category_id";								$result=mysql_query($query) or die(mysql_error());				while($row=mysql_fetch_array($result)){					$BrandName = $row['Name'];					$brand_id = $row['id'];					$brand_txt .= $BrandName . "{|}";										$query = "SELECT DISTINCT productfamily.id, productfamily.Name from product INNER JOIN `productfamily` ON productfamily.id=product.FamilyId WHERE product.CategoryId = $category_id AND product.BrandId=$brand_id";										$result2=mysql_query($query) or die(mysql_error());					while($row2=mysql_fetch_array($result2)){						$FamilyName = $row2['Name'];						$family_id = $row2['id'];												$family_txt .= $FamilyName . "{|}";											}				}			}							}				if($all_product){			$family_txt .= "ALL_PRODUCT{|}";		}		$ins = mysql_query("insert into Coupon (CouponCode,FamilyId,CoupType,valid_frm,valid_to,Coupdis,Coupdis_value, cat_txt, brand_txt, family_txt) value('".$_POST['CouponCode']."','".$FamilyId."','".$_POST['CoupType']."','".$_POST['valid_frm']."','".$_POST['valid_to']."','".$_POST['Coupdis']."','".$Coupdis_value."', '$cat_txt', '$brand_txt', '$family_txt')");				if($ins)		{			header("Location:coupon-manage.php?saved");		}		else		{			header("Location:coupon-manage.php?err");		}				/*foreach ($_POST['FamilyId'] as $FamilyId){									$ins = mysql_query("insert into Coupon (CouponCode,FamilyId,CoupType,valid_frm,valid_to,Coupdis,Coupdis_value) value('".$_POST['CouponCode']."','".$FamilyId."','".$_POST['CoupType']."','".$_POST['valid_frm']."','".$_POST['valid_to']."','".$_POST['Coupdis']."','".$Coupdis_value."')");				}
		
		if($ins)
		{
			header("Location:coupon-manage.php?saved");
		}
		else
		{
			//header("Location:coupon-manage.php?err");
		}*/
	}

?>