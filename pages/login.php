<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once('../includes/config.php');

// Start a secure session
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_secure' => true, // Enable if using HTTPS
        'cookie_samesite' => 'Strict',
    ]);
}

// Generate CSRF token if it doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Initialize error message
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';

    // Validate CSRF token
    if (!hash_equals($_SESSION['csrf_token'], $csrfToken)) {
        $error = "Invalid request. Please try again.";
    } else {
        // Trim and sanitize input
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if (empty($username) || empty($password)) {
            $error = "Please fill in both fields.";
        } else {
            // Prepare SQL query
            $stmt = $db->prepare("SELECT id, username, password FROM users WHERE username = ?");
            if ($stmt) {
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result && $result->num_rows == 1) {
                    $user = $result->fetch_assoc();

                    // Verify password
                    if (password_verify($password, $user['password'])) {
                        // Set session variables
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];

                        // Redirect to the intended page or profile.php
                        $redirectUrl = $_SESSION['redirect_url'] ?? '/pages/profile.php';
                        unset($_SESSION['redirect_url']); // Clear redirect URL from session
                        header("Location: $redirectUrl");
                        exit;
                    } else {
                        $error = "Invalid credentials.";
                    }
                } else {
                    $error = "User not found.";
                }

                $stmt->close();
            } else {
                $error = "Error preparing SQL statement.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../public/css/styles.css">
    <link rel="icon" type="image/x-icon" href="../public/images/favicon.ico">
    <title>Login</title>
</head>
<body>
<header>
    <h1>Login</h1>
</header>
<nav>
    <a href="/pages/gallery.php">Home</a>
    <a href="/api/search.php">Search</a>
    <a href="/pages/upload.php">Upload</a>
    <a href="/pages/profile.php">Profile</a>
    <a href="/pages/register.php">Sign Up</a>
    <a href="/assets/docs/Terms of Use.php">Terms of Use</a>
    <a href="/assets/docs/Privacy Policy.php">Privacy Policy</a>
    <a href="/assets/docs/news.php">Site News</a>
</nav>
<main>
    <form method="POST" action="login.php">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        
        <!-- Show errors -->
        <?php if (!empty($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Login</button>
    </form>
</main>
<footer>
    <p>&copy; 2025 Yiff-Fox. (Property of NIXTENSSERVER (nixten.ddns.net)) All Rights Reserved.</p>
</footer>
</body>
</html>
