<?php
require_once 'includes/functions.php';

$featured_posts = getPosts(3);
$recent_posts = getPosts(6, 3);

$page_title = 'Home';
include 'includes/header.php';
?>

<div class="hero-section">
    <div class="container">
        <h1>Welcome to <?php echo SITE_NAME; ?></h1>
        <p class="hero-subtitle"><?php echo getSiteSetting('site_description', SITE_TAGLINE); ?></p>
        <a href="blog.php" class="btn btn-primary">Explore Articles</a>
    </div>
</div>

<div class="container">
    <section class="featured-posts">
        <h2>Featured Posts</h2>
        <div class="posts-grid">
            <?php foreach ($featured_posts as $post): ?>
                <article class="post-card featured">
                    <?php if ($post['featured_image']): ?>
                        <div class="post-image">
                            <img src="<?php echo $post['featured_image']; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                        </div>
                    <?php endif; ?>
                    <div class="post-content">
                        <div class="post-meta">
                            <span class="category category-<?php echo $post['category_slug']; ?>">
                                <?php echo $post['category_name']; ?>
                            </span>
                            <span class="date"><?php echo formatDate($post['publish_date']); ?></span>
                        </div>
                        <h3><a href="post.php?slug=<?php echo $post['slug']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h3>
                        <p><?php echo getExcerpt($post['excerpt'] ?: $post['content']); ?></p>
                        <div class="post-footer">
                            <span class="author">By <?php echo $post['author_full_name']; ?></span>
                            <span class="views"><?php echo $post['views']; ?> views</span>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="recent-posts">
        <h2>Recent Posts</h2>
        <div class="posts-list">
            <?php foreach ($recent_posts as $post): ?>
                <article class="post-list-item">
                    <?php if ($post['featured_image']): ?>
                        <div class="post-thumbnail">
                            <img src="<?php echo $post['featured_image']; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                        </div>
                    <?php endif; ?>
                    <div class="post-info">
                        <span class="category category-<?php echo $post['category_slug']; ?>">
                            <?php echo $post['category_name']; ?>
                        </span>
                        <h3><a href="post.php?slug=<?php echo $post['slug']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h3>
                        <div class="post-meta">
                            <span><?php echo formatDate($post['publish_date']); ?></span>
                            <span>•</span>
                            <span><?php echo $post['comment_count']; ?> comments</span>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        <div class="text-center">
            <a href="blog.php" class="btn btn-secondary">View All Posts</a>
        </div>
    </section>

    <section class="categories-section">
        <h2>Browse by Category</h2>
        <div class="categories-grid">
            <?php
            $categories = getCategories();
            foreach ($categories as $category):
                if ($category['post_count'] > 0):
            ?>
                <a href="category.php?slug=<?php echo $category['slug']; ?>" class="category-box category-<?php echo $category['slug']; ?>">
                    <h3><?php echo $category['name']; ?></h3>
                    <p><?php echo $category['post_count']; ?> articles</p>
                </a>
            <?php
                endif;
            endforeach;
            ?>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
