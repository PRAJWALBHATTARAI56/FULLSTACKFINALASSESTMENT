<?php
require_once '../config/db.php';
include '../includes/header.php';

requireLogin();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username'];
    $type = $_POST['issue_type'];
    $priority = $_POST['priority'];
    $desc = $_POST['description'];

    $stmt = $pdo->prepare("INSERT INTO tickets (username, issue_type, priority, description) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $type, $priority, $desc]);

    echo "<script>alert('Ticket Created!'); window.location='search.php';</script>";
}
?>

<h2>Create New Ticket</h2>
<form method="POST">
    <label>Issue Type</label>
    <select name="issue_type">
        <option>Technical</option>
        <option>Billing</option>
        <option>General</option>
    </select>
    
    <label>Priority</label>
    <select name="priority">
        <option>Low</option>
        <option>Medium</option>
        <option>High</option>
    </select>
    
    <label>Description</label>
    <textarea name="description" rows="5" required></textarea>
    
    <button type="submit">Submit Ticket</button>
</form>

<?php include '../includes/footer.php'; ?>