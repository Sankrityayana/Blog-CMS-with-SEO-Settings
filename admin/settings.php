<?php
require_once '../includes/functions.php';
requireAdmin();

$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    updateSiteSetting('site_name', sanitizeInput($_POST['site_name']));
    updateSiteSetting('site_tagline', sanitizeInput($_POST['site_tagline']));
    updateSiteSetting('site_description', sanitizeInput($_POST['site_description']));
    updateSiteSetting('meta_author', sanitizeInput($_POST['meta_author']));
    updateSiteSetting('google_analytics', sanitizeInput($_POST['google_analytics']));
    updateSiteSetting('twitter_handle', sanitizeInput($_POST['twitter_handle']));
    updateSiteSetting('facebook_page', sanitizeInput($_POST['facebook_page']));
    updateSiteSetting('robots_txt', $_POST['robots_txt']);
    
    $success = 'Settings saved successfully!';
}

$page_title = 'SEO Settings';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/css/style.css">
</head>
<body class="admin-page">
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="admin-logo">
                <h2><?php echo SITE_NAME; ?></h2>
            </div>
            <nav class="admin-nav">
                <a href="index.php">Dashboard</a>
                <a href="posts.php">Posts</a>
                <a href="new_post.php">New Post</a>
                <a href="categories.php">Categories</a>
                <a href="comments.php">Comments</a>
                <a href="settings.php" class="active">SEO Settings</a>
                <a href="logout.php">Logout</a>
            </nav>
        </aside>

        <main class="admin-main">
            <div class="admin-header">
                <h1>SEO & Site Settings</h1>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST" class="settings-form">
                <h2>General Settings</h2>
                
                <div class="form-group">
                    <label>Site Name *</label>
                    <input type="text" name="site_name" value="<?php echo getSiteSetting('site_name', SITE_NAME); ?>" required>
                </div>

                <div class="form-group">
                    <label>Site Tagline</label>
                    <input type="text" name="site_tagline" value="<?php echo getSiteSetting('site_tagline', SITE_TAGLINE); ?>">
                </div>

                <div class="form-group">
                    <label>Site Description</label>
                    <textarea name="site_description" rows="3"><?php echo getSiteSetting('site_description'); ?></textarea>
                    <small>Used for meta description on homepage</small>
                </div>

                <h2>SEO Settings</h2>

                <div class="form-group">
                    <label>Meta Author</label>
                    <input type="text" name="meta_author" value="<?php echo getSiteSetting('meta_author'); ?>">
                    <small>Default author name for meta tags</small>
                </div>

                <div class="form-group">
                    <label>Google Analytics ID</label>
                    <input type="text" name="google_analytics" value="<?php echo getSiteSetting('google_analytics'); ?>" placeholder="G-XXXXXXXXXX">
                </div>

                <h2>Social Media</h2>

                <div class="form-group">
                    <label>Twitter Handle</label>
                    <input type="text" name="twitter_handle" value="<?php echo getSiteSetting('twitter_handle'); ?>" placeholder="@yourusername">
                </div>

                <div class="form-group">
                    <label>Facebook Page URL</label>
                    <input type="text" name="facebook_page" value="<?php echo getSiteSetting('facebook_page'); ?>">
                </div>

                <h2>Robots.txt Configuration</h2>

                <div class="form-group">
                    <label>Robots.txt Content</label>
                    <textarea name="robots_txt" rows="8"><?php echo getSiteSetting('robots_txt', "User-agent: *\nAllow: /"); ?></textarea>
                    <small>Control search engine crawling behavior</small>
                </div>

                <button type="submit" class="btn btn-primary">Save Settings</button>
            </form>

            <div class="admin-section">
                <h2>SEO Tools</h2>
                <div class="seo-tools">
                    <a href="<?php echo SITE_URL; ?>/sitemap.php" target="_blank" class="btn">View Sitemap</a>
                    <a href="<?php echo SITE_URL; ?>/robots.php" target="_blank" class="btn">View Robots.txt</a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
