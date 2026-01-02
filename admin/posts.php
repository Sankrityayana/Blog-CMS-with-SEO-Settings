<?php
require_once '../includes/functions.php';
requireLogin();

$filter_status = isset($_GET['status']) ? $_GET['status'] : null;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

$posts = getPosts($limit, $offset, null, null, null, $filter_status);
$total_posts = countPosts(null, null, null, $filter_status);
$total_pages = ceil($total_posts / $limit);

// Handle delete
if (isset($_GET['delete']) && $_SESSION['role'] == 'admin') {
    $id = intval($_GET['delete']);
    $pdo->prepare("DELETE FROM posts WHERE id = ?")->execute([$id]);
    header('Location: posts.php');
    exit;
}

$page_title = 'Manage Posts';
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
                <a href="posts.php" class="active">Posts</a>
                <a href="new_post.php">New Post</a>
                <a href="categories.php">Categories</a>
                <a href="comments.php">Comments</a>
                <a href="settings.php">SEO Settings</a>
                <a href="logout.php">Logout</a>
            </nav>
        </aside>

        <main class="admin-main">
            <div class="admin-header">
                <h1>All Posts</h1>
                <a href="new_post.php" class="btn btn-primary">Add New Post</a>
            </div>

            <div class="filter-tabs">
                <a href="posts.php" class="<?php echo !$filter_status ? 'active' : ''; ?>">All</a>
                <a href="posts.php?status=published" class="<?php echo $filter_status == 'published' ? 'active' : ''; ?>">Published</a>
                <a href="posts.php?status=draft" class="<?php echo $filter_status == 'draft' ? 'active' : ''; ?>">Drafts</a>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Views</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($post['title']); ?></td>
                            <td><?php echo $post['author_full_name']; ?></td>
                            <td><?php echo $post['category_name']; ?></td>
                            <td><span class="status-badge status-<?php echo $post['status']; ?>"><?php echo ucfirst($post['status']); ?></span></td>
                            <td><?php echo formatDate($post['publish_date'] ?: $post['created_at']); ?></td>
                            <td><?php echo $post['views']; ?></td>
                            <td class="actions">
                                <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm">Edit</a>
                                <a href="<?php echo SITE_URL; ?>/post.php?slug=<?php echo $post['slug']; ?>" class="btn btn-sm" target="_blank">View</a>
                                <?php if ($_SESSION['role'] == 'admin'): ?>
                                    <a href="posts.php?delete=<?php echo $post['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this post?')">Delete</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?><?php echo $filter_status ? '&status=' . $filter_status : ''; ?>" class="btn">← Previous</a>
                    <?php endif; ?>
                    
                    <span>Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>
                    
                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?php echo $page + 1; ?><?php echo $filter_status ? '&status=' . $filter_status : ''; ?>" class="btn">Next →</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
