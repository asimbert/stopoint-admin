<?php

require_once(dirname(__FILE__) . '/inc/core.php');

require_once(dirname(__FILE__) . '/classes/class.table_form.php');

$edit=$_POST['q'];

//$dbn = new Database();

if($edit != ""){

	$vas = "SELECT * FROM `roles` WHERE ParentId= 0";

	

	$re=mysql_query($vas);

//$select='<p><label>Select Roles</label><select class="small-input" name="auditorium_id" id="auditorium_id">

//<option value="">-- Please Select --</option>

//';

if (mysql_num_rows($re)!=0){
	

while($we=mysql_fetch_assoc($re)){

$select.='<br><div>';
$select.='<span style="font-size:14px; font-weight:bold;">'.$we['RoleName'].'</span>';
$vassub = "SELECT * FROM `roles` WHERE ParentId = ".$we['id'];
$resub=mysql_query($vassub);
while($wesub=mysql_fetch_assoc($resub)){
	$vasrole = "SELECT * FROM `userroles` WHERE ( RoleId = ".$wesub['id']." AND UserId=".$edit." )";
	$rerole=mysql_query($vasrole);
	if (mysql_num_rows($rerole)!=0){
		$select.='<br><input type="checkbox" checked="checked" name="checkboxvar[]" value="'.$wesub['id'].'"/> '.$wesub['RoleName'];
	}
	else{

	$select.='<br/><input type="checkbox" name="checkboxvar[]"  value="'.$wesub['id'].'"/> '.$wesub['RoleName'];

	}
}
$select.='</div>';
}
	//print_r($we);

	//$select.='</select></p>';

}



}

else{

$vas = "SELECT * FROM `roles` WHERE ParentId = 0";

	

$re=mysql_query($vas);

//$select='<p><label>Select Roles</label><select class="small-input" name="auditorium_id" id="auditorium_id">

//<option value="">-- Please Select --</option>

//';

if (mysql_num_rows($re)!=0){

	

while($we=mysql_fetch_assoc($re)){

	$select.='<br><div>';
	$select.='<span style="font-size:14px; font-weight:bold;">'.$we['RoleName'].'</span>';
	
	$vassub = "SELECT * FROM `roles` WHERE ParentId = ".$we['id'];
	$resub=mysql_query($vassub);
	while($wesub=mysql_fetch_assoc($resub)){
		$select.='<br><input type="checkbox" name="checkboxvar[]"  value="'.$wesub['id'].'"/> '.$wesub['RoleName'];
	}
	$select.='</div>';
	
	

	

	//print_r($we);

	}

	//$select.='</select></p>';

}

	}

	echo $select;

//$sql='';

  ?>