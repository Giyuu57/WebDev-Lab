<?php
// 1. Start Output Buffering (Fixes "Headers Already Sent" error)
ob_start();

// 2. Include Configuration
require_once 'config/db.php';

// 3. Include Header
require_once 'Includes/header.php';

// 4. Routing Logic
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

$allowed_pages = [
    'home', 'catalog', 'product', 'cart', 'checkout', 
    'login', 'register', 'dashboard', 'admin'
];

$page_file = "Pages/{$page}.php";

if (in_array($page, $allowed_pages) && file_exists($page_file)) {
    include $page_file;
} else {
    echo "<div class='container' style='text-align:center; padding: 50px;'>";
    echo "<h1>404 - Page Not Found</h1>";
    echo "<p>The game level you are looking for does not exist.</p>";
    echo "<a href='index.php?page=home' class='btn-register'>Go Back Home</a>";
    echo "</div>";
}

// 5. Include Footer
require_once 'Includes/footer.php';

// 6. Flush Output Buffer (Send HTML to browser)
ob_end_flush();
?>