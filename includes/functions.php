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

// 🔒 CSRF 1: Generate a Token
function getCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// 🔒 CSRF 2: Verify the Token
function verifyCsrfToken($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        die("CSRF Validation Failed: Security Token Invalid.");
    }
}
?>