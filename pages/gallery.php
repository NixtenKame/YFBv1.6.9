<?php
include_once('../includes/config.php'); // Include database connection

// Define items per page
$itemsPerPage = 50;

// Get the current page from the URL, default is 1
$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
if ($currentPage < 1) {
    $currentPage = 1;
}

// Get filter type from URL (default to both SFW and NSFW)
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'both';

// Calculate the offset
$offset = ($currentPage - 1) * $itemsPerPage;

// Build query based on filter
$whereClause = "";
if ($filter == 'sfw') {
    $whereClause = "WHERE category = 'SFW'";
} elseif ($filter == 'nsfw') {
    $whereClause = "WHERE category = 'NSFW'";
}

// Fetch total number of uploads to calculate total pages
$totalQuery = "SELECT COUNT(*) AS total FROM uploads $whereClause";
$totalResult = $db->query($totalQuery);
$totalRow = $totalResult->fetch_assoc();
$totalItems = $totalRow['total'];
$totalPages = ceil($totalItems / $itemsPerPage);

// Fetch uploads for the current page
$query = "SELECT * FROM uploads $whereClause ORDER BY upload_date DESC LIMIT $itemsPerPage OFFSET $offset";
$result = $db->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../public/images/favicon.ico">
    <title>Gallery - yiff-fox</title>
    <link rel="stylesheet" href="../public/css/styles.css"> <!-- Link to your styles.css -->
</head>
<body>
    <header>
        <h1>Art Gallery</h1>
    </header>

    <nav>
        <div class="filter-options">
        <a href="gallery.php?filter=sfw" <?= $filter === 'sfw' ? 'style="font-weight:bold;color:green;"' : ''; ?>>SFW</a> |
    <a href="gallery.php?filter=nsfw" <?= $filter === 'nsfw' ? 'style="font-weight:bold;color:red;"' : ''; ?>>NSFW</a> |
    <a href="gallery.php?filter=Both" <?= $filter === 'both' ? 'style="font-weight:bold;color:blue;"' : ''; ?>>Both</a>
        </div>
        <br>
    <a href="/pages/gallery.php">Home</a>
    <a href="/pages/upload.php">Upload</a>
    <?php if (isset($_SESSION['user_id'])): ?>
        <!-- User is logged in -->
        <a href="/pages/profile.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
        <a href="/pages/logout.php">Logout</a>
        <a href="/users/Profile_Request.php">Request a Profile page?</a>
    <?php else: ?>
        <!-- User is logged out -->
        <a href="/pages/login.php">Login</a>
        <a href="/pages/register.php">Signup</a>
    <?php endif; ?>
    <a href="/assets/docs/Terms of Use.php">Terms of Use</a>
    <a href="/assets/docs/Privacy Policy.php">Privacy Policy</a>
    <a href="/assets/docs/news.php">Site News</a>
    <a href="/assets/docs/About Server Owner.php">About Server Owner</a>
    <a href="/public/users/Nixten%20Leo%20Kame/Nixten_Leo_Kame.htm">Owners Profile</a>
    <form method="GET" action="/api/search.php" style="display:inline;">
</form>
</nav>

    <main>
        <h2>Recent Uploads</h2>

        <?php if ($result->num_rows > 0): ?>
            <div class="gallery">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="gallery-item">
                        <!-- Display file name -->
                        <h3><?php echo htmlspecialchars(pathinfo($row['file_name'], PATHINFO_FILENAME)); ?></h3>

                        <!-- Display category and tags -->
                        <p>Category: <?php echo htmlspecialchars($row['category']); ?></p>
                        <p>Tags: <?php echo htmlspecialchars($row['tags']); ?></p>
            
                        <!-- Make the image clickable, linking to the uploaded file -->
                        <a href="../public/uploads/<?php echo htmlspecialchars($row['file_name']); ?>" target="_blank">
                            <img src="../public/uploads/<?php echo htmlspecialchars($row['file_name']); ?>" 
                         
                                 alt="<?php echo htmlspecialchars(pathinfo($row['file_name'], PATHINFO_FILENAME)); ?>">
                         
                                </a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No uploads available.</p>
        <?php endif; ?>
<!-- Pagination -->
<div class="pagination">
    <?php if ($currentPage > 1): ?>
        <a href="?page=<?php echo $currentPage - 1; ?>&filter=<?php echo $filter; ?>">&laquo; Previous</a>
    <?php endif; ?>

    <?php 
    // Always show the first page
    if ($currentPage > 3): ?>
        <a href="?page=1&filter=<?php echo $filter; ?>">1</a>
        <?php if ($currentPage > 4): ?>
            <span class="dots">...</span>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Show range around current page -->
    <?php
    $start = max(1, $currentPage - 2);
    $end = min($totalPages, $currentPage + 2);
    for ($i = $start; $i <= $end; $i++): ?>
        <a href="?page=<?php echo $i; ?>&filter=<?php echo $filter; ?>" class="<?php echo ($i === $currentPage) ? 'active' : ''; ?>">
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>

    <!-- Always show the last page -->
    <?php if ($currentPage < $totalPages - 2): ?>
        <?php if ($currentPage < $totalPages - 3): ?>
            <span class="dots">...</span>
        <?php endif; ?>
        <a href="?page=<?php echo $totalPages; ?>&filter=<?php echo $filter; ?>"><?php echo $totalPages; ?></a>
    <?php endif; ?>

    <?php if ($currentPage < $totalPages): ?>
        <a href="?page=<?php echo $currentPage + 1; ?>&filter=<?php echo $filter; ?>">Next &raquo;</a>
    <?php endif; ?>
</div>
    </main>
<br>
<br>
<br>
<br>
    <footer>
        <p>&copy; 2025 Yiff-Fox. (Property of NIXTENSSERVER (nixten.ddns.net)) All Rights Reserved. Developer Beta v1.6.9</p>
    </footer>
</body>
</html>
