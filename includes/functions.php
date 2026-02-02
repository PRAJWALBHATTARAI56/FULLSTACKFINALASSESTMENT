<?php
// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to check if user is logged in
function requireLogin() {
    if (!isset($_SESSION['username'])) {
        header("Location: index.php");
        exit;
    }
}

// Function to check if user is Admin
function requireAdmin() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        die("Access Denied");
    }
}

// Function to escape output 
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>