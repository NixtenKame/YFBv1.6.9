<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) 
{
    session_start();
}

// Database configuration
$host = ''; // Change if your database is hosted elsewhere
$username = ''; // Replace with your MySQL username
$password = ''; // Replace with your MySQL password
$database = ''; // Replace with your database name

// Create a new database connection
$db = new mysqli($host, $username, $password, $database);

// Check for connection errors
if ($db->connect_error) {
    die("Database connection failed: " . $db->connect_error);
}

// Set character encoding (optional but recommended)
$db->set_charset('utf8mb4');

// Define base directory paths (adjust as needed)
define('BASE_DIR', realpath(__DIR__ . '/..'));
define('UPLOAD_DIR', BASE_DIR . '../public/uploads');

// Define site URL (update this based on your hosting)
define('SITE_URL', 'http://yourdomain.com');

// Error reporting (set this appropriately for production)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Use 1 for development, 0 for production
?>
