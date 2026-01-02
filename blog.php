<?php
require_once 'includes/functions.php';

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$search = isset($_GET['search']) ? $_GET['search'] : null;
$limit = POSTS_PER_PAGE;
$offset = ($page - 1) * $limit;

$posts = getPosts($limit, $offset, null, null, $search);
$total_posts = countPosts(null, null, $search);
$total_pages = ceil($total_posts / $limit);

$page_title = $search ? 'Search Results: ' . $search : 'Blog';
include 'includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1><?php echo $search ? 'Search Results' : 'All Blog Posts'; ?></h1>
        <?php if ($search): ?>
            <p>Showing results for: <strong><?php echo htmlspecialchars($search); ?></strong></p>
        <?php endif; ?>
    </div>

    <div class="blog-layout">
        <div class="blog-main">
            <?php if (empty($posts)): ?>
                <div class="empty-state">
                    <p>No posts found.</p>
                </div>
            <?php else: ?>
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
                                <div class="post-footer">
                                    <span class="author">By <?php echo $post['author_full_name']; ?></span>
                                    <span>•</span>
                                    <span><?php echo $post['views']; ?> views</span>
                                    <span>•</span>
                                    <span><?php echo $post['comment_count']; ?> comments</span>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?php echo $page - 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" class="btn">← Previous</a>
                        <?php endif; ?>
                        
                        <span class="page-info">Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>
                        
                        <?php if ($page < $total_pages): ?>
                            <a href="?page=<?php echo $page + 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" class="btn">Next →</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <aside class="blog-sidebar">
            <div class="sidebar-widget">
                <h3>Search</h3>
                <form action="blog.php" method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Search posts..." value="<?php echo htmlspecialchars($search ?: ''); ?>">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>

            <div class="sidebar-widget">
                <h3>Categories</h3>
                <ul class="category-list">
                    <?php
                    $sidebar_categories = getCategories();
                    foreach ($sidebar_categories as $cat):
                        if ($cat['post_count'] > 0):
                    ?>
                        <li>
                            <a href="category.php?slug=<?php echo $cat['slug']; ?>">
                                <?php echo $cat['name']; ?>
                                <span class="count">(<?php echo $cat['post_count']; ?>)</span>
                            </a>
                        </li>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </ul>
            </div>

            <div class="sidebar-widget">
                <h3>Popular Tags</h3>
                <div class="tag-cloud">
                    <?php
                    $tags = getPopularTags(15);
                    foreach ($tags as $tag):
                    ?>
                        <a href="tag.php?slug=<?php echo $tag['slug']; ?>" class="tag">
                            <?php echo $tag['name']; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </aside>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
