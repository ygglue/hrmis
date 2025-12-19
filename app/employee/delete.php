<?php
session_start();
require_once "../../config/database.php";
require_once "../../app/auth/require.php";

requireSupervisor();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $db = (new Database())->connect();
    $id = $_POST['id'];

    try {
        // Prepare current user session variable for database triggers
        $stmtUser = $db->prepare("SET @current_user_id = ?");
        $stmtUser->execute([$_SESSION['user_id']]);

        // Delete the employee record
        $stmt = $db->prepare("DELETE FROM employees WHERE idemployees = ?");
        
        if ($stmt->execute([$id])) {
            // Redirect back to dashboard with success indicator
            header("Location: ../admin/dashboard.php?status=deleted");
            exit();
        } else {
            header("Location: ../admin/dashboard.php?status=error");
            exit();
        }
    } catch (PDOException $e) {
        // Log the error if necessary and redirect
        header("Location: ../admin/dashboard.php?status=error&error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Redirect to dashboard if accessed incorrectly
    header("Location: ../admin/dashboard.php");
    exit();
}
?>
