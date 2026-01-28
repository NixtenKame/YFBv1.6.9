<?php
session_start();
include_once('../includes/config.php'); // Ensure correct config path

// Check if a username is provided in the URL
if (!isset($_GET['user']) || empty($_GET['user'])) {
    die("Invalid profile request.");
}

$username = $_GET['user']; // Get username from URL parameter

// Fetch user details from the database
$query = $db->prepare("SELECT id, username, email, profile_picture, bio FROM users WHERE username = ?");
if ($query) {
    $query->bind_param('s', $username);
    $query->execute();
    $result = $query->get_result();

    if ($result && $result->num_rows > 0) {
        $profileUser = $result->fetch_assoc();
    } else {
        die("User not found.");
    }
    $query->close();
} else {
    die("Database query error.");
}

// Determine profile picture path
$profilePicture = $profileUser['profile_picture'] 
    ? "../public/uploads/" . htmlspecialchars($profileUser['profile_picture']) 
    : "../public/images/default-profile.png"; // Default profile picture
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/public/images/favicon.ico">
    <title><?php echo htmlspecialchars($profileUser['username']); ?>'s Profile</title>
    <link rel="stylesheet" href="../public/css/styles.css">
</head>
<body>
    <header>
        <h1><?php echo htmlspecialchars($profileUser['username']); ?>'s Profile</h1>
        <nav>
            <a href="/pages/gallery.php">Home</a>
            <a href="/api/search.php">Search</a>
            <a href="/pages/upload.php">Upload</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/pages/profile.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
                <a href="/pages/logout.php">Logout</a>
            <?php else: ?>
                <a href="/pages/login.php">Login</a>
                <a href="/pages/register.php">Signup</a>
            <?php endif; ?>
            <a href="/assets/docs/Terms of Use.php">Terms of Use</a>
            <a href="/assets/docs/Privacy Policy.php">Privacy Policy</a>
            <a href="/assets/docs/news.php">Site News</a>
        </nav>
    </header>
    <main>
        <div class="profile-container">
            <div class="profile-picture">
                <img src="<?php echo $profilePicture; ?>" alt="Profile Picture" style="width: 150px; height: 150px; border-radius: 50%;">
            </div>
            <div class="profile-details">
                <p>Email: <?php echo htmlspecialchars($profileUser['email']); ?></p>
                <p>Bio: <?php echo nl2br(htmlspecialchars($profileUser['bio'])); ?></p>
            </div>
        </div>
    </main>
    <footer>
        <p>&copy; 2025 Yiff-Fox. (Property of NIXTENSSERVER (nixten.ddns.net)) All Rights Reserved.</p>
    </footer>
</body>
</html>
