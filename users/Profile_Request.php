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
    <title>Proflle Page Request</title>
    <ling rel="icon" type="image/x-icon" href="../public/images/favicon.ico">
</head>
<body>
<header>
    <h1>Profile Page Request</h1>
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
        <a href="https://discord.gg/yQ3bCRMcKB">Discord</a>
    </nav>
<main>
    <form method="POST" action="register.php">
    <p>If you would like to have a profile page and show it off please send me a email containing your username, e-mail, and desired display name and import art you want on your profile page and anything else you want</p>
    <h3>NOTICE: Requesting a Profile Page if free of charge for the first time requesting a edit to the profile page will cost $2.00</h3>
    <br>
    <nav>
    <a href="mailto:nixtenkame@gmail.com">Continue</a>
</nav>
    <br>
	<br>
	<br>
	<br>
	<br>
	<br>
    </form>
</main>
<footer>
    <p>&copy; 2025 Yiff-Fox. (Property of NIXTENSSERVER (nixten.ddns.net)) All Rights Reserved. Developer Beta v1.6.9</p>
    <p> <a href="/assets/docs/Terms of Use.php">Terms of Use</a> <a href="/assets/docs/Privacy Policy.php">Privacy Policy</a> <a href="/assets/docs/Code of Conduct.php">Code of Conduct</a> <a href="/public/users/Nixten Leo Kame/Nixten_Leo_Kame.htm">Nixten Kame</a></p>
</footer>
</body>
</html>
