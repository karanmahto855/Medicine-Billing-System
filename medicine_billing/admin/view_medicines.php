<?php
require '../config.php';
// Simple FIFO: Oldest first by ID
$result = $conn->query("SELECT * FROM medicines ORDER BY id ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Medicine Queue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-4">
        <h3>📦 Medicine Queue (FIFO)</h3>
        <div>
            <a href="add_medicine.php" class="btn btn-success me-2">➕ Add Batch</a>
            <a href="index.php" class="btn btn-primary">🏠 Dashboard</a>
        </div>
    </div>
    
    <?php if(isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?php echo $_GET['msg']; ?></div>
    <?php endif; ?>
    
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Batch</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Expiry</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><strong>#<?php echo $row['id']; ?></strong></td>
                        <td><?php echo $row['batch_no']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo $row['category']; ?></td>
                        <td>₹<?php echo $row['price']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td><?php echo $row['expiry_date']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
