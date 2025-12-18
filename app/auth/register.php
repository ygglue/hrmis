<?php
session_start();
require_once "../../config/database.php";

$db = (new Database())->connect();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $role_id = 1;
    $employee_id = null;

    // Validation
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } else {
        // Check if username already exists
        $checkStmt = $db->prepare("SELECT id FROM users WHERE username = :username");
        $checkStmt->execute([':username' => $username]);

        if ($checkStmt->fetch()) {
            $error = "Username already exists. Please choose a different username.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $db->prepare(
                "INSERT INTO users (username, password_hash, role_id, employee_id)
                 VALUES (:username, :password, :role, :employee)"
            );

            try {
                $stmt->execute([
                    ':username' => $username,
                    ':password' => $hash,
                    ':role' => $role_id,
                    ':employee' => $employee_id
                ]);
            } catch (PDOException $e) {
                $error = "Registration failed. Please try again.";
            }

            $_SESSION['user_id'] = $db->lastInsertId();
            $_SESSION['username'] = $username;
            $_SESSION['role_id'] = $role_id;

            header("Loc\ation: ../../dashboard.php");
            exit();
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
    <title>Register - HRMIS</title>
    <meta name="description" content="Create a new account for the Human Resource Management Information System.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/auth.css">
</head>

<body>
    <div class="background-gradient"></div>

    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1 class="text-gradient">Create Account</h1>
                <p>Join the HRMIS platform</p>
            </div>

            <?php if ($error): ?>
                <div class="message error">
                    <span>⚠</span>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="message success">
                    <span>✓</span>
                    <span><?php echo htmlspecialchars($success); ?></span>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="auth-form" id="registerForm">
                <div class="form-group">
                    <label for="username">Username <span class="required">*</span></label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        required
                        autocomplete="username"
                        placeholder="Enter your username"
                        value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="password">Password <span class="required">*</span></label>
                    <div class="password-input-wrapper">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            placeholder="Enter your password"
                            minlength="8">
                        <button type="button" class="toggle-password" data-target="password">
                            <span class="material-symbols-outlined">
                                visibility
                            </span>
                        </button>
                    </div>
                    <small class="input-hint">Minimum 8 characters</small>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password <span class="required">*</span></label>
                    <div class="password-input-wrapper">
                        <input
                            type="password"
                            id="confirm_password"
                            name="confirm_password"
                            required
                            autocomplete="new-password"
                            placeholder="Confirm your password"
                            minlength="8">
                        <button type="button" class="toggle-password" data-target="confirm_password">
                            <span class="material-symbols-outlined">
                                visibility
                            </span>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-full">
                    <span class="btn-text">Create Account</span>
                    <span class="btn-icon">→</span>
                </button>
            </form>

            <div class="auth-footer">
                <p>Already have an account? <a href="app/auth/login.php" class="auth-link">Login here</a></p>
            </div>
        </div>
    </div>

    <script src="assets/js/scripts.js"></script>
    <script src="assets/js/auth.js"></script>
</body>

</html>