<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
$category = getCategoryBySlug($slug);

if (!$category) {
    header('Location: blog.php');
    exit;
}

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = POSTS_PER_PAGE;
$offset = ($page - 1) * $limit;

$posts = getPosts($limit, $offset, $category['id']);
$total_posts = countPosts($category['id']);
$total_pages = ceil($total_posts / $limit);

$page_title = $category['meta_title'] ?: $category['name'];
include 'includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1><?php echo $category['name']; ?></h1>
        <?php if ($category['description']): ?>
            <p><?php echo $category['description']; ?></p>
        <?php endif; ?>
    </div>

    <div class="posts-list">
        <?php if (empty($posts)): ?>
            <div class="empty-state">
                <p>No posts in this category yet.</p>
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <article class="post-card">
                    <?php if ($post['featured_image']): ?>
                        <div class="post-image">
                            <img src="<?php echo $post['featured_image']; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                        </div>
                    <?php endif; ?>
                    <div class="post-content">
                        <div class="post-meta">
                            <span class="date"><?php echo formatDate($post['publish_date']); ?></span>
                        </div>
                        <h2><a href="post.php?slug=<?php echo $post['slug']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h2>
                        <p><?php echo getExcerpt($post['excerpt'] ?: $post['content']); ?></p>
                        <div class="post-footer">
                            <span class="author">By <?php echo $post['author_full_name']; ?></span>
                            <span>•</span>
                            <span><?php echo $post['comment_count']; ?> comments</span>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>

            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?slug=<?php echo $slug; ?>&page=<?php echo $page - 1; ?>" class="btn">← Previous</a>
                    <?php endif; ?>
                    
                    <span class="page-info">Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>
                    
                    <?php if ($page < $total_pages): ?>
                        <a href="?slug=<?php echo $slug; ?>&page=<?php echo $page + 1; ?>" class="btn">Next →</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
