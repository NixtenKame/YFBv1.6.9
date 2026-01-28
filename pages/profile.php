<?php
session_start();
include_once('../includes/config.php'); // Ensure the path to config.php is correct

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Retrieve user information from the database
$user_id = $_SESSION['user_id'];
$query = $db->prepare("SELECT username, email, profile_picture FROM users WHERE id = ?");
if ($query) {
    $query->bind_param('i', $user_id);
    $query->execute();
    $result = $query->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        die('User not found.');
    }

    $query->close();
} else {
    die('Database query error.');
}

// Determine the profile picture path
$profilePicture = $user['profile_picture'] 
    ? "../public/uploads/" . htmlspecialchars($user['profile_picture']) 
    : "../public/images/default-profile.png"; // Default profile picture
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/public/images/favicon.ico">
    <title>Profile</title>
    <link rel="stylesheet" href="../public/css/styles.css">
</head>
<body>
    <header>
        <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?></h1>
        <nav>
            <a href="/pages/gallery.php">Home</a>
            <a href="/api/search.php">Search</a>
            <a href="/pages/upload.php">Upload</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- User is logged in -->
                <a href="/pages/profile.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
                <a href="/pages/logout.php">Logout</a>
            <?php else: ?>
                <!-- User is logged out -->
                <a href="/pages/login.php">Login</a>
                <a href="/pages/register.php">Signup</a>
            <?php endif; ?>
            <a href="/assets/docs/Terms of Use.php">Terms of Use</a>
            <a href="/assets/docs/Privacy Policy.php">Privacy Policy</a>
            <a href="/assets/docs/news.php">Site News</a>
            <a href="/assets/docs/About Server Owner.php">About Server Owner</a>
            <a href="mailto:nixtenkame@gmail.com">Request a Profile page?</a>
        </nav>
    </header>
    <main>
        <div class="profile-container">
            <div class="profile-picture">
                <img src="<?php echo $profilePicture; ?>" alt="Profile Picture" style="width: 150px; height: 150px; border-radius: 50%;">
            </div>
            <div class="profile-details">
                <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
            </div>
        </div>

        <form method="POST" action="upload_profile_picture.php" enctype="multipart/form-data">
            <label for="profile_picture">Upload Profile Picture:</label>
            <input type="file" id="profile_picture" name="profile_picture" accept="image/*" required>
            <button type="submit">Upload</button>
        </form>

    </main>
    <footer>
        <p>&copy; 2025 Yiff-Fox. (Property of NIXTENSSERVER (nixten.ddns.net)) All Rights Reserved. Developer Beta v1.6.9</p>
    </footer>
</body>
</html>
