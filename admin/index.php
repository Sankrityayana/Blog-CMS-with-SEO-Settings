<?php
require_once '../includes/functions.php';
requireLogin();

$stats = [
    'total_posts' => $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn(),
    'published_posts' => $pdo->query("SELECT COUNT(*) FROM posts WHERE status = 'published'")->fetchColumn(),
    'draft_posts' => $pdo->query("SELECT COUNT(*) FROM posts WHERE status = 'draft'")->fetchColumn(),
    'total_comments' => $pdo->query("SELECT COUNT(*) FROM comments")->fetchColumn(),
    'pending_comments' => $pdo->query("SELECT COUNT(*) FROM comments WHERE status = 'pending'")->fetchColumn(),
    'total_categories' => $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn(),
];

$recent_posts = getPosts(5, 0, null, null, null, null);

$page_title = 'Dashboard';
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
                <p>Admin Panel</p>
            </div>
            <nav class="admin-nav">
                <a href="index.php" class="active">Dashboard</a>
                <a href="posts.php">Posts</a>
                <a href="new_post.php">New Post</a>
                <a href="categories.php">Categories</a>
                <a href="comments.php">Comments</a>
                <a href="settings.php">SEO Settings</a>
                <a href="<?php echo SITE_URL; ?>/index.php" target="_blank">View Site</a>
                <a href="logout.php">Logout</a>
            </nav>
        </aside>

        <main class="admin-main">
            <div class="admin-header">
                <h1>Dashboard</h1>
                <p>Welcome back, <?php echo $_SESSION['username']; ?>!</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card stat-blue">
                    <h3>Total Posts</h3>
                    <p class="stat-number"><?php echo $stats['total_posts']; ?></p>
                </div>
                <div class="stat-card stat-green">
                    <h3>Published</h3>
                    <p class="stat-number"><?php echo $stats['published_posts']; ?></p>
                </div>
                <div class="stat-card stat-orange">
                    <h3>Drafts</h3>
                    <p class="stat-number"><?php echo $stats['draft_posts']; ?></p>
                </div>
                <div class="stat-card stat-purple">
                    <h3>Comments</h3>
                    <p class="stat-number"><?php echo $stats['total_comments']; ?></p>
                    <?php if ($stats['pending_comments'] > 0): ?>
                        <span class="badge"><?php echo $stats['pending_comments']; ?> pending</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="admin-section">
                <h2>Recent Posts</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Views</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_posts as $post): ?>
                            <tr>
                                <td><a href="<?php echo SITE_URL; ?>/post.php?slug=<?php echo $post['slug']; ?>" target="_blank"><?php echo htmlspecialchars($post['title']); ?></a></td>
                                <td><?php echo $post['category_name']; ?></td>
                                <td><span class="status-badge status-<?php echo $post['status']; ?>"><?php echo ucfirst($post['status']); ?></span></td>
                                <td><?php echo formatDate($post['publish_date'] ?: $post['created_at']); ?></td>
                                <td><?php echo $post['views']; ?></td>
                                <td>
                                    <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm">Edit</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
