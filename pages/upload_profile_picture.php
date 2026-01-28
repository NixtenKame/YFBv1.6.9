<?php
session_start();
include_once('../includes/config.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
    $userId = $_SESSION['user_id'];
    $uploadDir = UPLOAD_DIR . '/profile_pictures/';
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxFileSize = 2 * 1024 * 1024; // 2 MB

    // Ensure the upload directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $file = $_FILES['profile_picture'];
    $fileType = mime_content_type($file['tmp_name']);
    $fileSize = $file['size'];
    $fileName = uniqid('profile_', true) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);

    // Validate file type and size
    if (!in_array($fileType, $allowedTypes)) {
        die("Invalid file type. Please upload a JPEG, PNG, or GIF.");
    }
    if ($fileSize > $maxFileSize) {
        die("File size exceeds the maximum limit of 2 MB.");
    }

    // Move the uploaded file
    if (move_uploaded_file($file['tmp_name'], $uploadDir . $fileName)) {
        // Update the user's profile picture in the database
        $stmt = $db->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
        $filePath = 'profile_pictures/' . $fileName;
        $stmt->bind_param("si", $filePath, $userId);
        if ($stmt->execute()) {
            header("Location: profile.php?success=1");
        } else {
            echo "Failed to update profile picture in the database.";
        }
        $stmt->close();
    } else {
        echo "Failed to upload file.";
    }
} else {
    echo "No file uploaded.";
}
?>
