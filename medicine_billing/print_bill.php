<?php
require 'config.php';

if (!isset($_GET['id'])) {
    die('Bill ID missing');
}
$bill_id = (int)$_GET['id'];

// Get bill + patient + doctor
$stmt = $conn->prepare("
    SELECT b.*, 
           p.name  AS patient_name, 
           p.phone AS patient_phone, 
           p.email AS patient_email,
           u.username AS doctor_name
    FROM bills b
    JOIN patients p ON b.patient_id = p.id
    LEFT JOIN users u ON b.doctor_id = u.id
    WHERE b.id = ?
");
$stmt->bind_param("i", $bill_id);
$stmt->execute();
$bill = $stmt->get_result()->fetch_assoc();

if (!$bill) {
    die('Bill not found');
}

// Get bill items
$stmt_items = $conn->prepare("
    SELECT bi.*, m.name AS medicine_name, m.batch_no 
    FROM bill_items bi
    JOIN medicines m ON bi.medicine_id = m.id
    WHERE bi.bill_id = ?
");
$stmt_items->bind_param("i", $bill_id);
$stmt_items->execute();
$items = $stmt_items->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Print Bill #<?php echo $bill_id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #fff; }
        }
        .invoice-box {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="no-print mb-3 d-flex justify-content-between">
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'doctor'): ?>
            <a href="doctor/index.php" class="btn btn-secondary">← Doctor Dashboard</a>
        <?php else: ?>
            <a href="admin/index.php" class="btn btn-secondary">← Admin Dashboard</a>
        <?php endif; ?>
        <button onclick="window.print()" class="btn btn-primary">🖨 Print</button>
    </div>

    <div class="invoice-box">
        <div class="d-flex justify-content-between">
            <div>
                <h2>🏥 Medical Billing System</h2>
                <p><strong>Bill No:</strong> <?php echo $bill['id']; ?></p>
                <p><strong>Date:</strong> <?php echo $bill['bill_date']; ?></p>
                <p><strong>Doctor:</strong> <?php echo $bill['doctor_name'] ?: '-'; ?></p>
            </div>
            <div class="text-end">
                <h5>Patient Details</h5>
                <p><?php echo htmlspecialchars($bill['patient_name']); ?></p>
                <p><?php echo $bill['patient_phone']; ?></p>
                <p><?php echo $bill['patient_email']; ?></p>
            </div>
        </div>

        <hr>

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Medicine</th>
                    <th>Batch</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Line Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $i = 1;
                $subtotal = 0;
                while($row = $items->fetch_assoc()):
                    $line_total = $row['qty'] * $row['price'];
                    $subtotal += $line_total;
                ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo htmlspecialchars($row['medicine_name']); ?></td>
                    <td><?php echo $row['batch_no']; ?></td>
                    <td><?php echo $row['qty']; ?></td>
                    <td>₹<?php echo number_format($row['price'], 2); ?></td>
                    <td>₹<?php echo number_format($line_total, 2); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <?php
        $tax = $subtotal * 0.18;
        $grand = $subtotal + $tax;
        ?>
        <div class="row mt-3">
            <div class="col-md-6">
                <p><strong>Payment Status:</strong> <?php echo ucfirst($bill['status']); ?></p>
            </div>
            <div class="col-md-6 text-end">
                <p>Subtotal: ₹<?php echo number_format($subtotal, 2); ?></p>
                <p>Tax (18%): ₹<?php echo number_format($tax, 2); ?></p>
                <h4>Grand Total: ₹<?php echo number_format($grand, 2); ?></h4>
            </div>
        </div>

        <p class="mt-4 text-center">Thank you for visiting. Get well soon!</p>
    </div>
</div>
</body>
</html>
