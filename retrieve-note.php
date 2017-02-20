<?php
session_start();
ob_start();

require_once(dirname(__FILE__) . '/inc/core.php');

require_once(dirname(__FILE__) . '/classes/class.table_form.php');

$order_id = $_REQUEST['order_id'];
$response = "<h3>Notes Area</h3>";
$response .= "<table border=1>";
$response .= "<tr>";
$response .= "<th><h5>Name</h5></th>";
$response .= "<th><h5>Notes</h5></th>";
$response .= "<th><h5>Timestamp</h5></th>";
$response .= "</tr>";

$sql = "SELECT user.FirstName, user.LastName, note.notes, note.created_date FROM order_notes AS note INNER JOIN user AS user ON note.user_id=user.id WHERE order_id = $order_id ORDER BY created_date DESC";
$res = mysql_query($sql) or die(mysql_error());

while($rows = mysql_fetch_assoc($res)){	
	$first_name = $rows['FirstName'];
	$last_name = $rows['LastName'];
	$notes = $rows['notes'];
	$created_date = $rows['created_date'];
	
	$response .= "<tr>";
	$response .= "<td>$first_name $last_name</td>";
	$response .= "<td>$notes</td>";
	$response .= "<td>$created_date</td>";
	$response .= "</tr>";	
	
}

$num_rows = mysql_num_rows($res);
if($num_rows == 0){
	$response .= "<td colspan=3>No notes found.</td>";
}

$response .= "</table>";
echo $response;
?>