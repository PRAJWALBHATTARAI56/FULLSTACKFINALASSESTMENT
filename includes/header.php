<?php require_once 'functions.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Support Ticket System</title>
    <link rel="stylesheet" href="../assets/css/style.css"> <script src="../assets/js/script.js" defer></script>
</head>
<body>
<nav>
    <?php if (isset($_SESSION['username'])): ?>
        <a href="search.php">Dashboard</a> <a href="add.php">New Ticket</a>
        <a href="logout.php" style="margin-left: auto;">Logout (<?= e($_SESSION['username']) ?>)</a>
    <?php else: ?>
        <a href="index.php" style="margin-left: auto;">Login</a>
    <?php endif; ?>
</nav>
<div class="container">