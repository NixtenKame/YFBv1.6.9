<?php
include_once('../includes/config.php'); // Include database connection

$searchTerm = ''; // Default search term
$results = []; // Default empty results array
$itemsPerPage = 50; // Limit to 50 items per page
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Default to page 1
$offset = ($currentPage - 1) * $itemsPerPage; // Calculate offset for SQL

$totalResults = 0; // Initialize total result count

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['tags'])) {
    $searchTerm = $_GET['tags'];

    // Count total results for pagination
    $countQuery = "SELECT COUNT(*) AS total FROM uploads 
                   WHERE file_name LIKE ? 
                   OR category LIKE ? 
                   OR tags LIKE ?";
    $stmt = $db->prepare($countQuery);
    $searchTermWithWildcards = '%' . $searchTerm . '%';
    $stmt->bind_param('sss', $searchTermWithWildcards, $searchTermWithWildcards, $searchTermWithWildcards);
    $stmt->execute();
    $countResult = $stmt->get_result();
    $totalResults = $countResult->fetch_assoc()['total'];
    $stmt->close();

    // Fetch paginated results
    $query = "SELECT * FROM uploads 
              WHERE file_name LIKE ? 
              OR category LIKE ? 
              OR tags LIKE ? 
              ORDER BY upload_date DESC 
              LIMIT ? OFFSET ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('sssii', $searchTermWithWildcards, $searchTermWithWildcards, $searchTermWithWildcards, $itemsPerPage, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $results = $result->fetch_all(MYSQLI_ASSOC);
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../public/images/favicon.ico">
    <title>Search - yiff-fox</title>
    <link rel="stylesheet" href="../public/css/styles.css">
</head>
<body>
    <header>
        <h1>Search Art</h1>
    </header>

    <nav>
        <a href="/pages/gallery.php">Home</a>
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
    </nav>

    <main>
        <form method="GET" action="search.php">
            <label for="search">Search:</label>
            <input type="text" id="tags" name="tags" value="<?php echo htmlspecialchars($searchTerm); ?>" placeholder="Enter file name, category, or tags" required>
            <button type="submit">Search</button>
        </form>

        <?php if (!empty($results)): ?>
            <h2>Search Results</h2>
            <div class="gallery">
                <?php foreach ($results as $row): ?>
                    <div class="gallery-item">
                        <h3><?php echo htmlspecialchars(pathinfo($row['file_name'], PATHINFO_FILENAME)); ?></h3>
                        <p>Category: <?php echo htmlspecialchars($row['category']); ?></p>
                        <p>Tags: <?php echo htmlspecialchars($row['tags']); ?></p>
                        <a href="../public/uploads/<?php echo htmlspecialchars($row['file_name']); ?>" target="_blank">
                            <img src="../public/uploads/<?php echo htmlspecialchars($row['file_name']); ?>" 
                                 alt="<?php echo htmlspecialchars(pathinfo($row['file_name'], PATHINFO_FILENAME)); ?>">
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Pagination -->
        <?php
$totalPages = ceil($totalResults / $itemsPerPage);

if ($totalPages > 1): ?>
    <div class="pagination">
    <?php if ($currentPage > 1): ?>
        <a href="?page=<?php echo $currentPage - 1; ?>">&laquo; Previous</a>
    <?php endif; ?>
        <?php 
        // Always show the first page
        if ($currentPage > 3): ?>
            <a href="?search=<?php echo urlencode($searchTerm); ?>&page=1">1</a>
            <?php if ($currentPage > 4): ?>
                <span class="dots">...</span>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Show range around current page -->
        <?php
        $start = max(1, $currentPage - 2);
        $end = min($totalPages, $currentPage + 2);
        for ($i = $start; $i <= $end; $i++): ?>
            <a href="?search=<?php echo urlencode($searchTerm); ?>&page=<?php echo $i; ?>" 
               class="<?php echo ($i === $currentPage) ? 'active' : ''; ?>">
               <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <!-- Always show the last page -->
        <?php if ($currentPage < $totalPages - 2): ?>
            <?php if ($currentPage < $totalPages - 3): ?>
                <span class="dots">...</span>
            <?php endif; ?>
            <a href="?search=<?php echo urlencode($searchTerm); ?>&page=<?php echo $totalPages; ?>"><?php echo $totalPages; ?></a>
        <?php endif; ?>
        
        <?php if ($currentPage < $totalPages): ?>
        <a href="?page=<?php echo $currentPage + 1; ?>">Next &raquo;</a>
    <?php endif; ?>
    </div>
<?php endif; ?>


        <?php if ($_SERVER['REQUEST_METHOD'] == 'GET' && empty($results)): ?>
            <p>No results found for "<?php echo htmlspecialchars($searchTerm); ?>".</p>
        <?php endif; ?>
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
