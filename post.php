<?php
require_once 'includes/functions.php';

$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
$post = getPostBySlug($slug);

if (!$post) {
    header('Location: blog.php');
    exit;
}

$comments = getComments($post['id']);
$comment_added = false;
$comment_error = '';

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $post['allow_comments']) {
    $author_name = sanitizeInput($_POST['author_name']);
    $author_email = sanitizeInput($_POST['author_email']);
    $content = sanitizeInput($_POST['content']);
    
    if ($author_name && $author_email && $content) {
        $user_id = isLoggedIn() ? $_SESSION['user_id'] : null;
        if (addComment($post['id'], $author_name, $author_email, $content, $user_id)) {
            $comment_added = true;
            $comments = getComments($post['id']);
        }
    } else {
        $comment_error = 'All fields are required';
    }
}

$page_title = $post['meta_title'] ?: $post['title'];
$meta_tags = generateMetaTags($post);
$structured_data = generateStructuredData($post);

include 'includes/header.php';
?>

<div class="container">
    <article class="single-post">
        <header class="post-header">
            <div class="post-meta">
                <a href="category.php?slug=<?php echo $post['category_slug']; ?>" class="category category-<?php echo $post['category_slug']; ?>">
                    <?php echo $post['category_name']; ?>
                </a>
                <span class="date"><?php echo formatDate($post['publish_date']); ?></span>
            </div>
            <h1><?php echo htmlspecialchars($post['title']); ?></h1>
            <div class="author-info">
                <span class="author">By <?php echo $post['author_full_name']; ?></span>
                <span>•</span>
                <span><?php echo $post['views']; ?> views</span>
                <span>•</span>
                <span><?php echo count($comments); ?> comments</span>
            </div>
        </header>

        <?php if ($post['featured_image']): ?>
            <div class="post-featured-image">
                <img src="<?php echo $post['featured_image']; ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
            </div>
        <?php endif; ?>

        <div class="post-body">
            <?php echo $post['content']; ?>
        </div>

        <?php if (!empty($post['tags'])): ?>
            <div class="post-tags">
                <strong>Tags:</strong>
                <?php foreach ($post['tags'] as $tag): ?>
                    <a href="tag.php?slug=<?php echo $tag['slug']; ?>" class="tag"><?php echo $tag['name']; ?></a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($post['author_bio']): ?>
            <div class="author-box">
                <h3>About the Author</h3>
                <p><strong><?php echo $post['author_full_name']; ?></strong></p>
                <p><?php echo $post['author_bio']; ?></p>
            </div>
        <?php endif; ?>
    </article>

    <section class="comments-section">
        <h2>Comments (<?php echo count($comments); ?>)</h2>

        <?php if ($post['allow_comments']): ?>
            <?php if ($comment_added): ?>
                <div class="alert alert-success">Your comment has been submitted and is awaiting moderation.</div>
            <?php endif; ?>

            <?php if ($comment_error): ?>
                <div class="alert alert-error"><?php echo $comment_error; ?></div>
            <?php endif; ?>

            <form method="POST" class="comment-form">
                <div class="form-group">
                    <label>Name *</label>
                    <input type="text" name="author_name" required value="<?php echo isLoggedIn() ? getCurrentUser()['full_name'] : ''; ?>">
                </div>

                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="author_email" required value="<?php echo isLoggedIn() ? getCurrentUser()['email'] : ''; ?>">
                </div>

                <div class="form-group">
                    <label>Comment *</label>
                    <textarea name="content" rows="5" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Submit Comment</button>
            </form>
        <?php else: ?>
            <p>Comments are closed for this post.</p>
        <?php endif; ?>

        <div class="comments-list">
            <?php if (empty($comments)): ?>
                <p>No comments yet. Be the first to comment!</p>
            <?php else: ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <div class="comment-author">
                            <strong><?php echo htmlspecialchars($comment['author_name']); ?></strong>
                            <span class="comment-date"><?php echo formatDate($comment['created_at']); ?></span>
                        </div>
                        <div class="comment-content">
                            <?php echo nl2br(htmlspecialchars($comment['content'])); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>
