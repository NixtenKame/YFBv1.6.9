<?php
include_once('../includes/config.php'); // Ensure database connection is included

if (isset($_POST['upload'])) {
    try {
        // Debugging: Check if the form is submitting
        echo "Form submitted.<br>";

        // Get form data
        $category = $_POST['category'] ?? '';
        $tags = $_POST['tags'] ?? '';
        $file = $_FILES['file'] ?? null;

        // Validate form inputs
        if (!$file || empty($category) || empty($tags)) {
            throw new Exception("Form data is incomplete.");
        }

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = $file['type'] ?? '';
        if (!in_array($fileType, $allowedTypes)) {
            throw new Exception("Invalid file type. Only JPEG, PNG, and GIF are allowed.");
        }

        // Ensure upload directory exists
        $baseUploadDir = '../public/uploads/';
        $uploadDir = $baseUploadDir . $category . '/';
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
            throw new Exception("Failed to create upload directory: $uploadDir");
        }

        // Generate unique file name
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $uniqueId = uniqid() . bin2hex(random_bytes(5));
        $fileName = $uniqueId . '.' . $fileExtension;
        $uploadFilePath = $uploadDir . $fileName;

        // Debugging: Check file paths
        echo "Upload file path: $uploadFilePath<br>";

        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
            throw new Exception("Failed to move uploaded file.");
        }

        // Save metadata to the database
        $userId = 1; // Replace with actual logged-in user ID
        $uploadDate = date('Y-m-d H:i:s');
        $relativeFilePath = $category . '/' . $fileName;

        $query = "INSERT INTO uploads (file_name, category, tags, uploaded_by, upload_date)
                  VALUES ('$relativeFilePath', '$category', '$tags', '$userId', '$uploadDate')";

        if (!$db->query($query)) {
            throw new Exception("Database error: " . $db->error);
        }

        echo "File uploaded successfully!";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../public/images/favicon.ico">
    <title>Upload - yiff-fox</title>
    <link rel="stylesheet" href="/public/css/styles.css"> <!-- Link to your styles.css -->
</head>
<body>
    <header>
        <h1>Upload Your Art</h1>
    </header>

    <nav>
        <a href="gallery.php">Home</a>
        <a href="/api/search.php">Search</a>
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
    </nav>

    <h3>Please provide the file name, category, tags, and your artist name (or any name you prefer) in the "Tags (comma separated):" section. This information helps us keep our servers organized and makes it easier for others to find your work. While we plan to introduce an automatic feature for this in the future, we kindly ask for your input in the meantime. Thank you for your cooperation!</h3>
    <main>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <label for="file">Select File:</label>
            <input type="file" name="file" id="file" required><br><br>

            <label for="category">Category:</label>
            <select name="category" id="category" required>
                <option value="sfw">SFW</option>
                <option value="nsfw">NSFW</option>
            </select><br><br>

            <label for="tags">Tags (comma separated):</label>
            <input type="text" name="tags" id="tags" placeholder="e.g., art, digital" required><br><br>

            <input type="submit" name="upload" value="Upload">
        </form>
    </main>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
    <footer>
        <p>&copy; 2025 Yiff-Fox. (Property of NIXTENSSERVER (nixten.ddns.net)) All Rights Reserved. Developer Beta v1.6.9</p>
    </footer>
</body>
</html>