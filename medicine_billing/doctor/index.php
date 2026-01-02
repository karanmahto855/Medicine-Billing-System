<?php
require '../config.php';

if ($_SESSION['role'] != 'doctor') {
    header('Location: ../login.php?error=Access denied');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Doctor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container mt-4">
    <h1>👨‍⚕️ Doctor Dashboard</h1>
    <p>Welcome, Doctor! | <a href="../logout.php" class="btn btn-danger">Logout</a></p>
    
    <div class="btn-grid">
        <a href="view_patients.php" class="btn btn-primary">👥 View Patients</a>
        <a href="add_bill.php" class="btn btn-success">💰 Create Bill</a>
        <a href="my_bills.php" class="btn btn-info">📋 My Bills</a>
    </div>
</div>
</body>
</html>
