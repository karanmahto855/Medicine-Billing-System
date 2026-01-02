<?php
require 'config.php';
header('Content-Type: application/json');

// Make sure session is active so we can read logged-in doctor
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Validate POST data
if (!isset($_POST['patient_id'], $_POST['items'])) {
    echo json_encode(['success' => false, 'error' => 'Missing patient or items']);
    exit();
}

$patient_id = (int)$_POST['patient_id'];
$items = json_decode($_POST['items'], true);

if (!is_array($items) || count($items) === 0) {
    echo json_encode(['success' => false, 'error' => 'No bill items']);
    exit();
}

// Logged-in user (doctor or admin)
$doctor_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

// 2. Create bill with total = 0 first
$total = 0.0;

$stmt = $conn->prepare("INSERT INTO bills (patient_id, total, doctor_id) VALUES (?, 0, ?)");
if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'Prepare bills failed']);
    exit();
}
$stmt->bind_param("ii", $patient_id, $doctor_id);
if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'error' => 'Insert bill failed']);
    exit();
}
$bill_id = $stmt->insert_id;

// 3. Insert bill items + update stock
foreach ($items as $item) {
    $med_id = (int)$item['id'];
    $qty    = (int)$item['qty'];
    $price  = (float)$item['price'];

    if ($qty <= 0) {
        continue;
    }

    // Insert into bill_items
    $stmt_item = $conn->prepare(
        "INSERT INTO bill_items (bill_id, medicine_id, qty, price) VALUES (?, ?, ?, ?)"
    );
    if (!$stmt_item) {
        echo json_encode(['success' => false, 'error' => 'Prepare bill_items failed']);
        exit();
    }
    $stmt_item->bind_param("iiid", $bill_id, $med_id, $qty, $price);
    $stmt_item->execute();

    // Deduct stock from medicines
    $stmt_stock = $conn->prepare(
        "UPDATE medicines SET quantity = quantity - ? WHERE id = ? AND quantity >= ?"
    );
    if ($stmt_stock) {
        $stmt_stock->bind_param("iii", $qty, $med_id, $qty);
        $stmt_stock->execute();
    }

    $total += $qty * $price;
}

// 4. Update final total in bills
$stmt_total = $conn->prepare("UPDATE bills SET total = ? WHERE id = ?");
if ($stmt_total) {
    $stmt_total->bind_param("di", $total, $bill_id);
    $stmt_total->execute();
}

// 5. Return JSON result for JS
echo json_encode(['success' => true, 'bill_id' => $bill_id]);
exit();
?>
