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
    $is_existing = $_POST['is_existing_employee'] ?? 'no';

    // Validation
    if ($is_existing === 'yes') {
        $idemployees = trim($_POST['employee_id'] ?? '');
        if (empty($username) || empty($password) || empty($confirm_password) || empty($idemployees)) {
            $error = "All fields, including Employee ID, are required.";
        } else {
            // Check if employee exists
            $stmtEmp = $db->prepare("SELECT idemployees FROM employees WHERE idemployees = ?");
            $stmtEmp->execute([$idemployees]);
            $emp = $stmtEmp->fetch(PDO::FETCH_ASSOC);
            
            if (!$emp) {
                $error = "Employee ID not found. Please verify with HR.";
            } else {
                $employee_id = $emp['idemployees'];
                // Check if already linked
                $stmtCheck = $db->prepare("SELECT id FROM users WHERE employee_id = ?");
                $stmtCheck->execute([$employee_id]);
                if ($stmtCheck->fetch()) {
                    $error = "This employee ID is already linked to an existing account.";
                }
            }
        }
    } else {
        $error = "Please link an existing employee record to register.";
    }

    if (empty($error)) {
        if ($password !== $confirm_password) {
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
            $_SESSION['employee_id'] = $employee_id;

            header("Location: ../employee/update.php?id=" . $employee_id);
            exit();
        }
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
    <style>
        .status-checker {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 24px;
        }
        .check-option {
            cursor: pointer;
        }
        .check-option input {
            display: none;
        }
        .option-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            padding: 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            transition: all var(--transition-fast);
        }
        .check-option input:checked + .option-content {
            background: rgba(102, 126, 234, 0.1);
            border-color: #667eea;
            color: #667eea;
        }
        .hidden {
            display: none !important;
        }
        .input-with-icon {
            position: relative;
        }
        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 20px;
        }
        .input-with-icon input {
            padding-left: 44px;
        }
    </style>
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
                    <label>Are you an existing employee?</label>
                    <div class="status-checker">
                        <label class="check-option">
                            <input type="radio" name="is_existing_employee" value="no" <?php echo (!isset($_POST['is_existing_employee']) || $_POST['is_existing_employee'] === 'no') ? 'checked' : ''; ?>>
                            <div class="option-content">
                                <span class="material-symbols-outlined">person_off</span>
                                <span>No</span>
                            </div>
                        </label>
                        <label class="check-option">
                            <input type="radio" name="is_existing_employee" value="yes" <?php echo (isset($_POST['is_existing_employee']) && $_POST['is_existing_employee'] === 'yes') ? 'checked' : ''; ?>>
                            <div class="option-content">
                                <span class="material-symbols-outlined">person_check</span>
                                <span>Yes</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Case: Not an employee -->
                <div id="noEmployeeSection" class="<?php echo (isset($_POST['is_existing_employee']) && $_POST['is_existing_employee'] === 'yes') ? 'hidden' : ''; ?>">
                    <div class="message info" style="margin-bottom: 24px;">
                        <span class="material-symbols-outlined">info</span>
                        <span>You need an employee record to create an account.</span>
                    </div>
                    <a href="app/employee/add.php" class="btn btn-secondary btn-full">
                        <span class="material-symbols-outlined">person_add</span>
                        <span class="btn-text">Add Employee Record</span>
                    </a>
                </div>

                <!-- Case: Existing employee -->
                <div id="existingEmployeeSection" class="<?php echo (!isset($_POST['is_existing_employee']) || $_POST['is_existing_employee'] === 'no') ? 'hidden' : ''; ?>">
                    <div class="form-group">
                        <label for="employee_id">Employee ID <span class="required">*</span></label>
                        <div class="input-with-icon">
                            <span class="material-symbols-outlined input-icon">badge</span>
                            <input type="text" id="employee_id" name="employee_id" placeholder="Enter your ID" value="<?php echo htmlspecialchars($_POST['employee_id'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="username">Username <span class="required">*</span></label>
                        <div class="input-with-icon">
                            <span class="material-symbols-outlined input-icon">person</span>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                autocomplete="username"
                                placeholder="Choose a username"
                                value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                        </div>
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
                </div>

                <button type="submit" class="btn btn-primary btn-full <?php echo (!isset($_POST['is_existing_employee']) || $_POST['is_existing_employee'] === 'no') ? 'hidden' : ''; ?>" id="registerSubmit">
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
    <script>
        document.querySelectorAll('input[name="is_existing_employee"]').forEach(radio => {
            radio.addEventListener('change', (e) => {
                const isExisting = e.target.value === 'yes';
                document.getElementById('existingEmployeeSection').classList.toggle('hidden', !isExisting);
                document.getElementById('noEmployeeSection').classList.toggle('hidden', isExisting);
                document.getElementById('registerSubmit').classList.toggle('hidden', !isExisting);
                
                // Toggle required attributes
                const inputs = document.querySelectorAll('#existingEmployeeSection input');
                inputs.forEach(input => {
                    if (isExisting) input.setAttribute('required', '');
                    else input.removeAttribute('required');
                });
            });
        });
    </script>
    <script src="assets/js/auth.js"></script>
</body>

</html>