<?php
function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /hrmis/app/auth/login.php");
        exit();
    }
}

function requireRole($allowedRoles) {
    requireLogin();
    
    if (!is_array($allowedRoles)) {
        $allowedRoles = [$allowedRoles];
    }
    
    if (!in_array($_SESSION['role_id'], $allowedRoles)) {
        header("Location: /hrmis/app/auth/unauthorized.html");
        exit();
    }
}

function requireAdmin() {
    requireRole(3);
}

function requireSupervisor() {
    requireRole([2, 3]);
}