<?php
require_once '../config/db.php';
include '../includes/header.php';

requireLogin();

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM tickets WHERE id = ?");
$stmt->execute([$id]);
$t = $stmt->fetch();

if (!$t) die("Ticket not found.");

// Access Control
if ($_SESSION['role'] !== 'admin' && $t['username'] !== $_SESSION['username']) {
    die("Access Denied");
}
?>

<h2>Ticket #<?= e($t['id']) ?> Details</h2>
<a href="search.php">Back to Dashboard</a>

<div style="background:#fff; padding:20px; border:1px solid #ddd; margin-top:20px;">
    <p><strong>Status:</strong> <?= e($t['status']) ?></p>
    <p><strong>Priority:</strong> <?= e($t['priority']) ?></p>
    <hr>
    <h3>Description</h3>
    <p><?= nl2br(e($t['description'])) ?></p>
    
    <?php if ($t['admin_response']): ?>
        <div style="background:#e8f5e9; padding:15px; margin-top:20px;">
            <strong>Admin Reply:</strong><br>
            <?= nl2br(e($t['admin_response'])) ?>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>