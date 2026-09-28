<?php
include 'config.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "No account found with this email.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login - Resume Builder</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="navbar">
    <strong>Resume Builder</strong>
    <div><a href="register.php">Register</a></div>
</div>

<div class="container">
    <h2>Login to your account</h2>

    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn" style="width:100%;">Login</button>
    </form>

    <p style="margin-top:15px; text-align:center; font-size:14px;">
        Don't have an account? <a href="register.php">Register here</a>
    </p>
    <p style="margin-top:5px; text-align:center; font-size:13px;">
        <a href="admin/admin_login.php">Admin login</a>
    </p>
</div>

</body>
</html>
