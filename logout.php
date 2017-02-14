<?php
ob_start();
include("classes/class.database.php");

$db = new Database();

session_start();

setcookie("autologin", "", time() - 10);

$user = $_SESSION['user_info']['username'];
$db->query("UPDATE users SET cookie='' WHERE username='$user'");

session_destroy();
unset($_SESSION);

header("Location: login.php");
?>