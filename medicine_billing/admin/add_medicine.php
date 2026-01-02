<?php require '../config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Medicine (Queue)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4">
                <h3>🧪 Add Medicine Batch</h3>
                <form method="POST" action="save_medicine.php">
                    <input type="text" name="name" class="form-control mb-3" placeholder="Medicine Name" required>
                    <input type="text" name="category" class="form-control mb-3" placeholder="Category" required>
                    <input type="number" step="0.01" name="price" class="form-control mb-3" placeholder="Price ₹" required>
                    <input type="text" name="batch_no" class="form-control mb-3" placeholder="Batch No (B001)" required>
                    <input type="number" name="quantity" class="form-control mb-3" placeholder="Quantity" required>
                    <input type="date" name="expiry_date" class="form-control mb-3" required>
                    
                    <button class="btn btn-success w-100">➕ Add to Queue</button>
                </form>
                <a href="index.php" class="btn btn-secondary w-100 mt-2">← Dashboard</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
