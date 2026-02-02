<?php
require_once '../config/db.php';
include '../includes/header.php';

requireLogin();
requireAdmin(); // Security Check

$id = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'];
    $reply = $_POST['admin_response'];
    
    $stmt = $pdo->prepare("UPDATE tickets SET status=?, admin_response=? WHERE id=?");
    $stmt->execute([$status, $reply, $id]);
    
    echo "<script>alert('Updated!'); window.location='search.php';</script>";
}

$stmt = $pdo->prepare("SELECT * FROM tickets WHERE id=?");
$stmt->execute([$id]);
$t = $stmt->fetch();
?>

<h2>Edit Ticket #<?= e($t['id']) ?></h2>

<form method="POST">
    <label>Status</label>
    <select name="status">
        <option <?= $t['status']=='Open'?'selected':'' ?>>Open</option>
        <option <?= $t['status']=='Resolved'?'selected':'' ?>>Resolved</option>
    </select>
    
    <label>Admin Reply</label>
    <textarea name="admin_response" rows="4"><?= e($t['admin_response']) ?></textarea>
    
    <button type="submit">Update Ticket</button>
</form>

<a href="delete.php?id=<?= $t['id'] ?>" onclick="return confirm('Are you sure?')" style="color:red; display:block; margin-top:20px;">Delete This Ticket</a>

<?php include '../includes/footer.php'; ?>