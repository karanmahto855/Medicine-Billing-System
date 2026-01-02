<?php
require 'config.php';
header('Content-Type: application/json');

$patient_id = $_POST['patient_id'];
$items_json = $_POST['items'];
$items = json_decode($items_json, true);

if (empty($items)) {
    echo json_encode(['success' => false, 'error' => 'No medicines selected']);
    exit();
}

$total = 0;

// Create bill
$stmt = $conn->prepare("INSERT INTO bills (patient_id, total) VALUES (?, 0)");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$bill_id = $conn->insert_id;

// Process items (FIFO deduction)
foreach ($items as $item) {
    // Add bill item
    $stmt = $conn->prepare("INSERT INTO bill_items (bill_id, medicine_id, qty, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiid", $bill_id, $item['id'], $item['qty'], $item['price']);
    $stmt->execute();
    
    // Deduct stock (oldest first)
    $stmt = $conn->prepare("UPDATE medicines SET quantity = quantity - ? WHERE id = ?");
    $stmt->bind_param("ii", $item['qty'], $item['id']);
    $stmt->execute();
    
    $total += $item['qty'] * $item['price'];
}

// Update total
$stmt = $conn->prepare("UPDATE bills SET total = ? WHERE id = ?");
$stmt->bind_param("di", $total, $bill_id);
$stmt->execute();

echo json_encode(['success' => true, 'bill_id' => $bill_id]);
?>
