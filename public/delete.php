<?php
require_once '../config/db.php';
require_once '../includes/functions.php';

session_start();
requireLogin();
requireAdmin(); // ONLY Admins can delete

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM tickets WHERE id = ?");
    $stmt->execute([$_GET['id']]);
}

header("Location: search.php"); // Go back to dashboard
exit;
?>