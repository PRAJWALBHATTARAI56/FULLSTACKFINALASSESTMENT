<?php
require_once '../config/db.php';
include '../includes/header.php';

// If already logged in, send to dashboard
if (isset($_SESSION['username'])) { header("Location: dashboard.php"); exit; }

$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // --- VALIDATION LOGIC ---
    // 1. Username must be at least 5 chars
    if (strlen($username) < 5) {
        $msg = "Username must be at least 5 characters long.";
    } 
    // 2. Password must be at least 6 chars
    elseif (strlen($password) < 6) {
        $msg = "Password must be at least 6 characters long.";
    }
    else {
        // Check if username already exists
        $check = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $check->execute([$username]);
        
        if ($check->fetch()) {
            $msg = "Username already exists! Please try another.";
        } else {
            // Hash password & Create Account
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
            
            if ($stmt->execute([$username, $hash])) {
                echo "<script>alert('Account Created! Login now.'); window.location='index.php';</script>";
                exit;
            } else {
                $msg = "Error creating account.";
            }
        }
    }
}
?>

<h2>Create Account</h2>

<?php if($msg): ?>
    <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border: 1px solid #f5c6cb; border-radius: 4px;">
        <?= htmlspecialchars($msg) ?>
    </div>
<?php endif; ?>

<form method="POST" style="max-width: 400px; margin: 0 auto;">
    <label>Choose Username <small style="color:#666;">(Min 5 chars)</small></label>
    <input type="text" name="username" required value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
    
    <label>Choose Password <small style="color:#666;">(Min 6 chars)</small></label>
    <input type="password" name="password" required>
    
    <button type="submit" style="width:100%; background: #27ae60;">Register</button>
</form>

<p style="text-align:center;">Already have an account? <a href="index.php">Login</a></p>

<?php include '../includes/footer.php'; ?>