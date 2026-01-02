<?php
require_once 'includes/functions.php';

header('Content-Type: application/xml; charset=utf-8');

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

// Homepage
echo '<url>';
echo '<loc>' . SITE_URL . '/</loc>';
echo '<changefreq>daily</changefreq>';
echo '<priority>1.0</priority>';
echo '</url>';

// Blog page
echo '<url>';
echo '<loc>' . SITE_URL . '/blog.php</loc>';
echo '<changefreq>daily</changefreq>';
echo '<priority>0.9</priority>';
echo '</url>';

// Categories page
echo '<url>';
echo '<loc>' . SITE_URL . '/categories.php</loc>';
echo '<changefreq>weekly</changefreq>';
echo '<priority>0.8</priority>';
echo '</url>';

// All posts
$posts = getPosts(1000);
foreach ($posts as $post) {
    echo '<url>';
    echo '<loc>' . SITE_URL . '/post.php?slug=' . htmlspecialchars($post['slug']) . '</loc>';
    echo '<lastmod>' . date('Y-m-d', strtotime($post['updated_at'])) . '</lastmod>';
    echo '<changefreq>weekly</changefreq>';
    echo '<priority>0.7</priority>';
    echo '</url>';
}

// All categories
$categories = getCategories();
foreach ($categories as $category) {
    if ($category['post_count'] > 0) {
        echo '<url>';
        echo '<loc>' . SITE_URL . '/category.php?slug=' . htmlspecialchars($category['slug']) . '</loc>';
        echo '<changefreq>weekly</changefreq>';
        echo '<priority>0.6</priority>';
        echo '</url>';
    }
}

echo '</urlset>';
?>
