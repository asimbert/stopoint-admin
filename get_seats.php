<?php
require_once(dirname(__FILE__) . '/inc/core.php');
require_once(dirname(__FILE__) . '/classes/class.table_form.php');
$id=$_POST['q'];
//$dbn = new Database();
//SELECT * FROM `section_row` WHERE `section_id`=1
$vas = "SELECT * FROM `row_seats` where `row_id`=".$id;
$re=mysql_query($vas);
$select='<p><label>Select Seat</label><select class="small-input" name="row_seat_id" id="row_seat_id" > 
<option value="-1">ALL</option>
';
if (mysql_num_rows($re)!=0){
	
while($we=mysql_fetch_assoc($re)){
	$select.='<option value="'.$we['id'].'">'.$we['name'].'</option>';
	//print_r($we);
	}
	$select.='</select></p>';
}
	echo $select;
//$sql='';
  ?>