<?php
session_start();
require_once "../../config/database.php";

$db = (new Database())->connect();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember_me']);

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        $stmt = $db->prepare("SELECT id, username, password_hash, role_id, employee_id FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role_id'] = $user['role_id'];
            $_SESSION['employee_id'] = $user['employee_id'];

            if ($user['role_id'] == 1) {
                header("Location: ../employee/update.php?id=" . $user['employee_id']);
            } else {
                header("Location: ../employee/dashboard.php");
            }
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/hrmis/">
    <title>Login - HRMIS</title>
    <meta name="description" content="Login to the Human Resource Management Information System.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>
    <div class="background-gradient"></div>
    
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1 class="text-gradient">Welcome Back</h1>
                <p>Login to your HRMIS account</p>
            </div>

            <?php if ($error): ?>
                <div class="message error">
                    <span>⚠</span>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="auth-form" id="loginForm">
                <div class="form-group">
                    <label for="username">Username <span class="required">*</span></label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        required 
                        autocomplete="username"
                        placeholder="Enter your username"
                        value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password <span class="required">*</span></label>
                    <div class="password-input-wrapper">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required 
                            autocomplete="current-password"
                            placeholder="Enter your password"
                        >
                        <button type="button" class="toggle-password" data-target="password">
                            <span class="material-symbols-outlined">
                                visibility
                            </span>
                        </button>
                    </div>
                </div>

                <div class="remember-me">
                    <input type="checkbox" id="remember_me" name="remember_me">
                    <label for="remember_me">Remember me</label>
                </div>

                <button type="submit" class="btn btn-primary btn-full">
                    <span class="btn-text">Login</span>
                    <span class="btn-icon">→</span>
                </button>
            </form>

            <div class="auth-footer">
                <p>Don't have an account? <a href="app/auth/register.php" class="auth-link">Register here</a></p>
            </div>
        </div>
    </div>

    <script src="assets/js/scripts.js"></script>
    <script src="assets/js/auth.js"></script>
</body>
</html>
