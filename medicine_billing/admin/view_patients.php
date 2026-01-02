<?php
require '../config.php';

$result = $conn->query("SELECT * FROM patients ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>All Patients</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" >
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container mt-5">
   <div class="d-flex justify-content-between align-items-center mb-3">

    <h3 class="mb-0">Patient List</h3>

    <div class="d-flex align-items-center gap-2">
        <a href="index.php" class="btn btn-primary">Back to Dashboard</a>
        <a href="logout.php" class="btn btn-danger">🚪 Logout</a>
    </div>

</div>

    <div class="card p-3">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>#<?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
