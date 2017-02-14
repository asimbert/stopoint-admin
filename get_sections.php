<?php
require_once(dirname(__FILE__) . '/inc/core.php');
require_once(dirname(__FILE__) . '/classes/class.table_form.php');
$id=$_POST['q'];
//$dbn = new Database();
$vas = "SELECT * FROM `auditorium_sections` where `auditorium_id`=".$id;
$re=mysql_query($vas);
$select='<p><label>Select Section</label><select class="small-input" name="auditorium_section_id" id="auditorium_section_id" onchange="get_rows(this.value);">
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