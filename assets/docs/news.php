<?php

// Set a page title dynamically
$pageTitle = 'Site News';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/public/images/favicon.ico">
    <title><?php echo htmlspecialchars($pageTitle); ?> - yiff-fox</title>
    <link rel="stylesheet" href="/public/css/styles.css">
</head>
<body>
    <header>
        <h1>Site News</h1>
    </header>
    <nav>
        <a onclick="window.history.back()">Continue</a>
        <a href="/assets/docs/Terms of Use.php">Terms of Use</a>
        <a href="/assets/docs/Privacy Policy.php">Privacy Policy</a>
        <a href="/assets/docs/About Server Owner.php">About Server Owner</a>
    </nav>
    <main>
        <h1>Site News!</h1>
        <p>Check back soon for updates and announcements!</p>
    </main>
    <footer>
        <p>&copy; 2025 Yiff-Fox. (Property of NIXTENSSERVER (nixten.ddns.net)) All Rights Reserved. Developer Beta v1.6.9</p>
    </footer>
    <script src="/js/scripts.js"></script>
</body>
</html>
