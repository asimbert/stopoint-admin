<?php
session_start();
ob_start();

require_once(dirname(__FILE__) . '/inc/core.php');

require_once(dirname(__FILE__) . '/classes/class.table_form.php');

e_reporting(); // error reporting ALL
$dropdown = $_REQUEST['dropdown'];

$query = "";
if($dropdown === "category"){
	
	$category_str = $_REQUEST['category_id'];	

	$category_arr = explode("{|}",$category_str);
	
	$category_id = $category_arr[0];
	$query = "SELECT DISTINCT productbrand.id, productbrand.Name from product INNER JOIN `productbrand` ON productbrand.id=product.BrandId WHERE product.CategoryId = $category_id";
}else{
	$category_str = $_REQUEST['category_id'];	
	$category_arr = explode("{|}",$category_str);
	$category_id = $category_arr[0];
	
	$brand_str = $_REQUEST['brand_id'];	
	$brand_arr = explode("{|}",$brand_str);
	$brand_id = $brand_arr[0];
	
	//$query = "select t1.id, t2.Name from product t1, productfamily t2 WHERE t1.FamilyId=t2.id AND t1.CategoryId=$category_id AND t1.BrandId=$brand_id";
	
	$query = "SELECT DISTINCT productfamily.id, productfamily.Name from product INNER JOIN `productfamily` ON productfamily.id=product.FamilyId WHERE product.CategoryId = $category_id AND product.BrandId=$brand_id";
}
$product=mysql_query($query) or die(mysql_error());

while($b=mysql_fetch_array($product))

{

	?><option value="<?php echo $b['id'] . "{|}" . $b['Name']; ?>"><?php echo $b['Name']; ?></option> <?php

}
?>