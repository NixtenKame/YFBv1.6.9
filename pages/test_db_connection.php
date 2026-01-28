<?php
include_once('../includes/config.php');

// Simple query to check if the database is connected
$result = $db->query("SELECT * FROM uploads");

if ($result) {
    echo "Connected to the database and query executed successfully!";
} else {
    echo "Error executing query: " . $db->error;
}
?>
