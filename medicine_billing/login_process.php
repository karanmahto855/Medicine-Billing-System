<?php
require 'config.php';

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if($user = $result->fetch_assoc()){
   if (password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];

    if ($user['role'] == 'admin') {
        header('Location: admin/index.php');
    } else {
        header('Location: doctor/index.php');
    }
    exit();
}

}

header('Location: index.php');
?>
