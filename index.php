<?php
// Include reusable scripts
include_once 'includes/config.php';
include_once 'includes/utils.php';

// Set a page title dynamically
$pageTitle = 'Home';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../public/images/favicon.ico">
    <title><?php echo htmlspecialchars($pageTitle); ?> - yiff-fox</title>
    <link rel="stylesheet" href="/public/css/styles.css">
</head>
<body>
    <header>
        <h1>Yiff-Fox</h1>
    </header>
    <nav>
        <a href="/">Home</a>
        <a href="/assets/docs/Terms of Use.php">Terms of Use</a>
        <a href="/assets/docs/Privacy Policy.php">Privacy Policy</a>
        <a href="../assets/docs/About Server Owner.php">About Server Owner</a>

    </nav>
    <main>
        <p>By uploading and accessing this Web Stie you agree to the <a href="/assets/docs/Terms of Use.php">Terms of Use</a> and <a href="/assets/docs/Privacy Policy.php">Privacy Policy</a> and that I am 18+. if you agree to these terms and conditions click the I Agree button</p>
        <nav>
	<a href="/pages/gallery.php">I Agree and I am 18+</a>
	</nav>
    </main>
    <footer>
        <p>&copy; 2025 Yiff-Fox. (Property of NIXTENSSERVER (nixten.ddns.net)) All Rights Reserved.</p>
    </footer>
    <script src="/js/scripts.js"></script>
</body>
</html>
