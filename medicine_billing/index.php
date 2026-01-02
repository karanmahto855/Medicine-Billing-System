<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-4 mx-auto">
            <div class="card p-4">
                <h3>Login</h3>
                
                <!-- ADD THIS HERE - Success/Error Messages -->
                <?php if(isset($_GET['msg'])): ?>
                    <div class="alert alert-success"><?php echo $_GET['msg']; ?></div>
                <?php endif; ?>
                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?php echo $_GET['error']; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="login_process.php">
                    Username: <input type="text" name="username" class="form-control mb-3" required><br>
                    Password: <input type="password" name="password" class="form-control mb-3" required><br>
                    <button class="btn btn-primary w-100">Login</button>
                </form>
                
                <p class="mt-3 text-center">
                    No account? <a href="register.php">Register</a>
                </p>
                
                
            </div>
        </div>
    </div>
</div>
</body>
</html>
