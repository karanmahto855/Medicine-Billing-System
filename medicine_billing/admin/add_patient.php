<?php 
require '../config.php'; 
$error = ''; $success = '';

// Check duplicate on same page
if ($_POST) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    
    // Check if phone exists
    $check = $conn->prepare("SELECT id FROM patients WHERE phone = ?");
    $check->bind_param("s", $phone);
    $check->execute();
    
    if ($check->get_result()->num_rows > 0) {
        $error = "❌ Phone number '$phone' already registered!";
    } else {
        // Save patient
        $save = $conn->prepare("INSERT INTO patients (name, phone, email) VALUES (?, ?, ?)");
        $save->bind_param("sss", $name, $phone, $email);
        
        if ($save->execute()) {
            $success = "✅ Patient '$name' added successfully!";
        } else {
            $error = "❌ Error saving patient!";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Patient</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
</head>
<body>

   <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 >Add Patient</h3>
    <div>
        <a href="logout.php" class="btn btn-danger me-2">🚪 Logout</a>
    </div>
</div>

 
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4">
                <h3>👤 Add New Patient</h3>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <input type="text" name="name" class="form-control mb-3" placeholder="Patient Name" required 
                           value="<?php echo $_POST['name'] ?? ''; ?>">
                    <input type="text" name="phone" class="form-control mb-3" placeholder="Phone (Unique)" maxlength="15" required 
                           value="<?php echo $_POST['phone'] ?? ''; ?>">
                    <input type="email" name="email" class="form-control mb-3" placeholder="Email (optional)"
                           value="<?php echo $_POST['email'] ?? ''; ?>">
                    
                    <button type="submit" class="btn btn-success w-100">✅ Save Patient</button>
                </form>
                
                <a href="index.php" class="btn btn-secondary w-100 mt-3">← Dashboard</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
