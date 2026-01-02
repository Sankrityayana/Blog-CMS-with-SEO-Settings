<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?><?php echo SITE_NAME; ?></title>
    
    <?php if (isset($meta_tags)): ?>
        <?php outputMetaTags($meta_tags); ?>
    <?php else: ?>
        <meta name="description" content="<?php echo SITE_TAGLINE; ?>">
    <?php endif; ?>
    
    <?php if (isset($structured_data)): ?>
        <?php echo $structured_data; ?>
    <?php endif; ?>
    
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="<?php echo SITE_URL; ?>/index.php"><?php echo SITE_NAME; ?></a>
                    <p class="tagline"><?php echo SITE_TAGLINE; ?></p>
                </div>
                <nav class="main-nav">
                    <a href="<?php echo SITE_URL; ?>/index.php">Home</a>
                    <a href="<?php echo SITE_URL; ?>/blog.php">Blog</a>
                    <a href="<?php echo SITE_URL; ?>/categories.php">Categories</a>
                    <?php if (isLoggedIn()): ?>
                        <a href="<?php echo SITE_URL; ?>/admin/index.php">Dashboard</a>
                        <a href="<?php echo SITE_URL; ?>/admin/logout.php">Logout</a>
                    <?php else: ?>
                        <a href="<?php echo SITE_URL; ?>/admin/login.php">Login</a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>
    <main class="site-main">
