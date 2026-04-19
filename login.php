<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT id, username, password, role FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            // Success
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Bus Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width: 400px;">
    <h2 class="text-center mb-4">Login</h2>

    <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <div class="card p-4 shadow-sm">
        <form method="POST" action="">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?= (isset($_GET['role']) && $_GET['role'] === 'admin') ? 'admin' : '' ?>" <?= (isset($_GET['role']) && $_GET['role'] === 'admin') ? '' : 'autofocus' ?> required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" <?= (isset($_GET['role']) && $_GET['role'] === 'admin') ? 'autofocus' : '' ?> required>
                <?php if(isset($_GET['role']) && $_GET['role'] === 'admin'): ?>
                    <small class="text-muted d-block mt-1">Hint: Password is <b>admin123</b></small>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-success w-100">Login</button>
        </form>
        <div class="mt-3 text-center">
            <a href="register.php">Need an account? Register</a>
        </div>
    </div>
</div>

</body>
</html>
