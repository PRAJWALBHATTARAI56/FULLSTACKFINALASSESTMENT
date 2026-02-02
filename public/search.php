<?php
require_once '../config/db.php';
include '../includes/header.php'; // Starts session & includes functions

// Security: User must be logged in
requireLogin();

$isAdmin = ($_SESSION['role'] === 'admin');
$currentUser = $_SESSION['username'];

// ==========================================
// 1. AJAX HANDLER (Live Dropdown)
//    This runs when JavaScript asks for data
// ==========================================
if (isset($_GET['ajax_query'])) {
    $search = trim($_GET['ajax_query']);
    $term = "%" . $search . "%";
    
    // Search in Issue Type OR Description
    $sql = "SELECT id, issue_type, status, description FROM tickets WHERE (issue_type LIKE ? OR description LIKE ?)";
    $params = [$term, $term];

    // Restrict to current user if not Admin
    if (!$isAdmin) {
        $sql .= " AND username = ?";
        $params[] = $currentUser;
    }
    
    $sql .= " LIMIT 5";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll();
    
    // Send JSON and stop
    header('Content-Type: application/json');
    echo json_encode($results);
    exit; 
}

// ==========================================
// 2. MAIN PAGE LOGIC (Table & Filtering)
// ==========================================

$sql = "SELECT * FROM tickets WHERE 1=1";
$params = [];

// A. User Restriction
if (!$isAdmin) {
    $sql .= " AND username = ?";
    $params[] = $currentUser;
}

// B. Search Keyword (When user hits Enter)
if (!empty($_GET['q'])) {
    $term = "%" . $_GET['q'] . "%";
    // Search both columns
    $sql .= " AND (issue_type LIKE ? OR description LIKE ?)";
    $params[] = $term;
    $params[] = $term;
}

// C. Dropdown Filters
if (!empty($_GET['priority'])) {
    $sql .= " AND priority = ?";
    $params[] = $_GET['priority'];
}
if (!empty($_GET['status'])) {
    $sql .= " AND status = ?";
    $params[] = $_GET['status'];
}

$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tickets = $stmt->fetchAll();
?>

<h2><?= $isAdmin ? "Admin Dashboard" : "My Ticket History" ?></h2>

<form method="GET" style="background:#f9f9f9; padding:15px; border:1px solid #ddd; border-radius:5px; position: relative;">
    
    <div style="display:inline-block; position:relative; margin-right:15px;">
        <input type="text" 
               id="live_search" 
               name="q" 
               placeholder="🔍 Search keyword..." 
               autocomplete="off" 
               value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>"
               style="padding:8px; width:200px; border:1px solid #ccc; border-radius:4px;">
        
        <div id="search_results" style="display:none; position:absolute; top:38px; left:0; background:white; border:1px solid #ccc; width:200px; z-index:1000; box-shadow:0 4px 6px rgba(0,0,0,0.1);"></div>
    </div>

    <strong>Filter:</strong>
    <select name="priority" style="width:auto; display:inline-block;">
        <option value="">All Priorities</option>
        <option value="Low" <?= (isset($_GET['priority']) && $_GET['priority'] == 'Low') ? 'selected' : '' ?>>Low</option>
        <option value="High" <?= (isset($_GET['priority']) && $_GET['priority'] == 'High') ? 'selected' : '' ?>>High</option>
    </select>
    
    <select name="status" style="width:auto; display:inline-block;">
        <option value="">All Statuses</option>
        <option value="Open" <?= (isset($_GET['status']) && $_GET['status'] == 'Open') ? 'selected' : '' ?>>Open</option>
        <option value="Resolved" <?= (isset($_GET['status']) && $_GET['status'] == 'Resolved') ? 'selected' : '' ?>>Resolved</option>
    </select>
    
    <button type="submit" style="width:auto; background: #007bff; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer;">Search</button>
    
    <?php if(!empty($_GET['q']) || !empty($_GET['priority']) || !empty($_GET['status'])): ?>
        <a href="search.php" style="color: red; margin-left: 10px; text-decoration: underline;">Clear Filters</a>
    <?php endif; ?>
</form>

<?php if (count($tickets) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Subject</th>
                <th>Status</th>
                <?php if ($isAdmin): ?> <th>User</th> <?php endif; ?>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($tickets as $t): ?>
            <tr>
                <td>#<?= e($t['id']) ?></td>
                <td>
                    <strong><?= e($t['issue_type']) ?></strong><br>
                    <small style="color:#666;"><?= substr(e($t['description']), 0, 40) ?>...</small>
                </td>
                <td>
                    <span class="status-badge <?= strtolower(str_replace(' ', '-', $t['status'])) ?>">
                        <?= e($t['status']) ?>
                    </span>
                </td>
                <?php if ($isAdmin): ?>
                    <td><?= e($t['username']) ?></td>
                <?php endif; ?>
                <td>
                    <a href="view.php?id=<?= $t['id'] ?>" style="color:#3498db; font-weight:bold;">View</a>
                    <?php if ($isAdmin): ?>
                        <span style="color:#ccc;">|</span> 
                        <a href="edit.php?id=<?= $t['id'] ?>" style="color:#e67e22;">Edit</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div style="text-align:center; padding:40px; border:2px dashed #ddd; margin-top:20px; color:#777;">
        <p>No tickets found matching your search.</p>
        <?php if (!$isAdmin): ?>
            <a href="add.php" style="background:#27ae60; color:white; padding:10px 20px; text-decoration:none; border-radius:4px;">Create New Ticket</a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>