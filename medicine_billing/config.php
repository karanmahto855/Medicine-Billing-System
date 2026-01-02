<?php
$conn = new mysqli('localhost','root','','medical_billing');
if($conn->connect_error) die("Connection failed");
session_start();
if(!isset($_SESSION['user_id']) && !in_array(basename($_SERVER['PHP_SELF']), ['login.php','register.php','login_process.php','register_process.php'])){
    header('Location: index.php'); exit();
}
?>
