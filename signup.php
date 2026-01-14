<?php
require_once 'includes/auth.php';

$error = '';
$success = '';

if (isLoggedIn()) {
    header("Location: views/dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        $result = register($username, $password, $full_name);
        if ($result === true) {
            $success = "Account created successfully! You can now login.";
        } else {
            $error = $result;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - EduSpace</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1 class="auth-title">Create Account</h1>
                <p class="auth-subtitle">Join us to manage resources efficiently</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert" style="background-color: #D1FAE5; color: #065F46; border: 1px solid #A7F3D0;">
                    <?php echo htmlspecialchars($success); ?>
                    <a href="login.php" style="margin-left: 0.5rem; font-weight: 700; color: #065F46;">Log In</a>
                </div>
            <?php else: ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label class="form-label" for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" class="form-input" required
                            placeholder="John Doe">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-input" required placeholder="johndoe">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-input" required
                            placeholder="••••••••">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-input" required
                            placeholder="••••••••">
                    </div>

                    <button type="submit" class="btn btn-primary" style="margin-bottom: 1.5rem;">
                        Sign Up
                    </button>

                    <div style="text-align: center; color: var(--text-light); font-size: 0.95rem;">
                        Already have an account? <a href="login.php"
                            style="color: var(--primary); font-weight: 600; text-decoration: none;">Log In</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>