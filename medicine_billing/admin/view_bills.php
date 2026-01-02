<?php
require '../config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php?error=Access denied');
    exit();
}

$query = "
    SELECT b.id, b.total, b.bill_date, b.status,
           p.name AS patient_name, p.phone,
           u.username AS doctor_name
    FROM bills b
    JOIN patients p ON b.patient_id = p.id
    LEFT JOIN users u ON b.doctor_id = u.id
    ORDER BY b.bill_date DESC, b.id DESC
";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>All Bills (Admin)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h3>📊 All Bills History</h3>
        <a href="index.php" class="btn btn-primary">🏠 Admin Dashboard</a>
    </div>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Bill ID</th>
                        <th>Date</th>
                        <th>Patient</th>
                        <th>Phone</th>
                        <th>Doctor</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Print</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $row['id']; ?></td>
                        <td><?php echo $row['bill_date']; ?></td>
                        <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td><?php echo $row['doctor_name'] ?: '-'; ?></td>
                        <td>₹<?php echo number_format($row['total'], 2); ?></td>
                        <td>
                            <span class="badge <?php echo $row['status']=='paid' ? 'bg-success' : 'bg-warning'; ?>">
                                <?php echo ucfirst($row['status']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="../print_bill.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">🖨</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php if($result->num_rows == 0): ?>
                    <tr><td colspan="8" class="text-center text-muted">No bills yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
