<?php
require_once 'includes/functions.php';

$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
$tag = getTagBySlug($slug);

if (!$tag) {
    header('Location: blog.php');
    exit;
}

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = POSTS_PER_PAGE;
$offset = ($page - 1) * $limit;

$posts = getPosts($limit, $offset, null, $tag['id']);
$total_posts = countPosts(null, $tag['id']);
$total_pages = ceil($total_posts / $limit);

$page_title = 'Tag: ' . $tag['name'];
include 'includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1>Tag: <?php echo $tag['name']; ?></h1>
        <p><?php echo $total_posts; ?> post<?php echo $total_posts != 1 ? 's' : ''; ?> tagged with "<?php echo $tag['name']; ?>"</p>
    </div>

    <div class="posts-list">
        <?php foreach ($posts as $post): ?>
            <article class="post-card">
                <?php if ($post['featured_image']): ?>
                    <div class="post-image">
                        <img src="<?php echo $post['featured_image']; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                    </div>
                <?php endif; ?>
                <div class="post-content">
                    <div class="post-meta">
                        <a href="category.php?slug=<?php echo $post['category_slug']; ?>" class="category category-<?php echo $post['category_slug']; ?>">
                            <?php echo $post['category_name']; ?>
                        </a>
                        <span class="date"><?php echo formatDate($post['publish_date']); ?></span>
                    </div>
                    <h2><a href="post.php?slug=<?php echo $post['slug']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h2>
                    <p><?php echo getExcerpt($post['excerpt'] ?: $post['content']); ?></p>
                </div>
            </article>
        <?php endforeach; ?>
    </div>

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
</div>

<?php include 'includes/footer.php'; ?>
