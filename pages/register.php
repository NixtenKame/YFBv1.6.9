<?php
include_once('../includes/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        echo "Registration successful! <a href='login.php'>Login here</a>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../public/css/styles.css">
    <title>Register</title>
</head>
<body>
<header>
    <h1>Register</h1>
</header>
<nav>
        <a href="/pages/gallery.php">Home</a>
        <a href="/pages/upload.php">Upload</a>
        <a href="/pages/profile.php">Profile</a>
        <a href="/pages/login.php">login</a>
        <a href="/assets/docs/Terms of Use.php">Terms of Use</a>
        <a href="/assets/docs/Privacy Policy.php">Privacy Policy</a>
        <a href="/assets/docs/news.php">Site News</a>
        <a href="/assets/docs/About Server Owner.php">About Server Owner</a>
    </nav>
<main>
    <form method="POST" action="register.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Register</button>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<p>By clicking Register you agree to the <a href="/assets/docs/Terms of Use.php">Terms of Use</a> and <a href="/assets/docs/Privacy Policy.php">Privacy Policy</a></p>
    </form>
</main>
<footer>
    <p>&copy; 2025 Yiff-Fox. (Property of NIXTENSSERVER (nixten.ddns.net)) All Rights Reserved. Developer Beta v1.6.9</p>
</footer>
</body>
</html>
