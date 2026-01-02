<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">

</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-4 mx-auto">
            <div class="card p-4">
                <h3>Register</h3>
                <form method="POST" action="register_process.php">
                    Username: <input type="text" name="username" class="form-control mb-3" required><br>
                    Password: <input type="password" name="password" class="form-control mb-3" required><br>
                    Role: 
                    <select name="role" class="form-control mb-3">
                        <option value="doctor">Doctor</option>
                        <option value="admin">Admin</option>
                    </select><br>
                    <button class="btn btn-success w-100">Register</button>
                </form>
                <p class="mt-3 text-center"><a href="index.php">Back to Login</a></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
