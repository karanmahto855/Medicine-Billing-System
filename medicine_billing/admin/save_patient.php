<?php
require '../config.php';

$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];

$stmt = $conn->prepare("INSERT INTO patients (name, phone, email) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $phone, $email);

if ($stmt->execute()) {
    header('Location: view_patients.php?msg=Patient added successfully');
    exit();
} else {
    echo "Error: " . $stmt->error;
    
}
?>
