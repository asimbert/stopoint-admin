<?php
session_start();
ob_start();

require_once(dirname(__FILE__) . '/inc/core.php');

require_once(dirname(__FILE__) . '/classes/class.table_form.php');

$note_txt = $_REQUEST['note_txt'];
$note_txt = str_replace("amp;", "&", $note_txt);
$note_txt = str_replace("'", "&apos;", $note_txt);

$order_id = $_REQUEST['order_id'];
$user_id = $_SESSION['userid'];
$created_date = date('Y-m-d H:i:s');

$sql = "INSERT INTO order_notes(order_id, user_id, notes, created_date) VALUES ($order_id, $user_id, '".$note_txt."', '".$created_date."')";
$res = mysql_query($sql) or die(mysql_error());
echo $res;
?>