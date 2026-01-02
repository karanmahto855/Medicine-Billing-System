<?php
require '../config.php';

$name = $_POST['name'];
$category = $_POST['category'];
$price = floatval($_POST['price']);
$quantity = intval($_POST['quantity']);
$expiry_date = $_POST['expiry_date'];
$batch_no = $_POST['batch_no'];

$stmt = $conn->prepare("
    INSERT INTO medicines (name, category, price, quantity, expiry_date, batch_no) 
    VALUES (?, ?, ?, ?, ?, ?)"
);

$stmt->bind_param("ssddss", $name, $category, $price, $quantity, $expiry_date, $batch_no);

if($stmt->execute()){
    header('Location: view_medicines.php?msg=Batch added');
    exit();
} else {
    echo "Error: " . $stmt->error;
}
?>
