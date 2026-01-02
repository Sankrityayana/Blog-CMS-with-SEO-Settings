<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_PORT', '3307');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'blog_cms_seo');

// Site Configuration
define('SITE_NAME', 'My Blog');
define('SITE_TAGLINE', 'Insights, Tutorials, and Inspiration');
define('SITE_URL', 'http://localhost/Blog-CMS-with-SEO-Settings');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');
define('POSTS_PER_PAGE', 10);

// Session Configuration
session_start();

// Database Connection
try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
