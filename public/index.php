<?php
require_once '../config/db.php';
require '../includes/functions.php';

// If logged in, go to dashboard (search.php)
if (isset($_SESSION['username'])) { header("Location: search.php"); exit; }

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: search.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}

include '../includes/header.php';
?>

<h2>Login System</h2>
<?php if($error) echo "<p style='color:red;'>$error</p>"; ?>

<form method="POST" style="max-width: 400px; margin: 0 auto;">
    <label>Username</label>
    <input type="text" name="username" required>
    
    <label>Password</label>
    <input type="password" name="password" required>
    
    <button type="submit">Login</button>
</form>

<p style="text-align:center;">
    New here? <a href="register.php">Create an Account</a>
</p>

<?php include '../includes/footer.php'; ?>