<?php require '../config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
  <div class="dashboard-header d-flex justify-content-between align-items-center mb-3">

    <h1 class="mb-0">🏥 Medical Billing System</h1>

    <p class="mb-0">
        Welcome, <strong><?php echo strtoupper($_SESSION['role']); ?></strong> |
        <a href="../logout.php" class="btn btn-danger btn-sm">🚪 Logout</a>
    </p>

</div>



    <?php if($_SESSION['role'] == 'admin'): ?>
        <div class="btn-grid">
            <a href="add_patient.php" class="btn btn-primary">
                👤 <h4>Add Patient</h4>
                <small>Register new patients</small>
            </a>
            <a href="add_medicine.php" class="btn btn-success">
                💊 <h4>Add Medicine</h4>
                <small>Update inventory</small>
            </a>
            <a href="add_bill.php" class="btn btn-info">
                💰 <h4>Create Bill</h4>
                <small>Generate invoices</small>
            </a>
            <a href="view_patients.php" class="btn btn-warning">
                📋 <h4>View Patients</h4>
                <small>Patient records</small>
            </a>
            <a href="view_medicines.php" class="btn btn-secondary">
                📦 <h4>Stock Check</h4>
                <small>Inventory status</small>
            </a>
            <a href="view_bills.php" class="btn btn-dark">
                📊 <h4>Billing Reports</h4>
                <small>All transactions</small>
            </a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
