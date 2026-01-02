<?php
require_once '../includes/functions.php';
requireLogin();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitizeInput($_POST['title']);
    $slug = generateSlug($_POST['slug'] ?: $title);
    $content = $_POST['content'];
    $excerpt = sanitizeInput($_POST['excerpt']);
    $category_id = intval($_POST['category_id']);
    $status = $_POST['status'];
    $publish_date = $_POST['publish_date'] ?: date('Y-m-d H:i:s');
    
    // SEO fields
    $meta_title = sanitizeInput($_POST['meta_title']);
    $meta_description = sanitizeInput($_POST['meta_description']);
    $meta_keywords = sanitizeInput($_POST['meta_keywords']);
    $og_title = sanitizeInput($_POST['og_title']);
    $og_description = sanitizeInput($_POST['og_description']);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO posts (author_id, category_id, title, slug, excerpt, content, status, publish_date,
                              meta_title, meta_description, meta_keywords, og_title, og_description)
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $category_id, $title, $slug, $excerpt, $content, $status, $publish_date,
                       $meta_title, $meta_description, $meta_keywords, $og_title, $og_description]);
        
        $post_id = $pdo->lastInsertId();
        
        // Handle tags
        if (!empty($_POST['tags'])) {
            $tags = explode(',', $_POST['tags']);
            foreach ($tags as $tag_name) {
                $tag_name = trim($tag_name);
                $tag_slug = generateSlug($tag_name);
                
                // Insert or get tag
                $stmt = $pdo->prepare("INSERT IGNORE INTO tags (name, slug) VALUES (?, ?)");
                $stmt->execute([$tag_name, $tag_slug]);
                
                $tag_id = $pdo->lastInsertId() ?: $pdo->prepare("SELECT id FROM tags WHERE slug = ?")->execute([$tag_slug]);
                if (!$tag_id) {
                    $tag_id = $pdo->query("SELECT id FROM tags WHERE slug = '$tag_slug'")->fetchColumn();
                }
                
                $pdo->prepare("INSERT IGNORE INTO post_tags (post_id, tag_id) VALUES (?, ?)")->execute([$post_id, $tag_id]);
            }
        }
        
        $success = 'Post created successfully!';
    } catch (PDOException $e) {
        $error = 'Error creating post: ' . $e->getMessage();
    }
}

$categories = getCategories();
$page_title = 'New Post';
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
                <a href="new_post.php" class="active">New Post</a>
                <a href="categories.php">Categories</a>
                <a href="comments.php">Comments</a>
                <a href="settings.php">SEO Settings</a>
                <a href="logout.php">Logout</a>
            </nav>
        </aside>

        <main class="admin-main">
            <div class="admin-header">
                <h1>Create New Post</h1>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" class="post-form">
                <div class="form-row">
                    <div class="form-col-main">
                        <div class="form-group">
                            <label>Title *</label>
                            <input type="text" name="title" required>
                        </div>

                        <div class="form-group">
                            <label>Content *</label>
                            <textarea name="content" rows="15" required></textarea>
                        </div>

                        <div class="form-group">
                            <label>Excerpt</label>
                            <textarea name="excerpt" rows="3"></textarea>
                        </div>

                        <details class="seo-section">
                            <summary><strong>SEO Settings</strong></summary>
                            
                            <div class="form-group">
                                <label>Meta Title (for search engines)</label>
                                <input type="text" name="meta_title" maxlength="60">
                                <small>Leave empty to use post title. Recommended: 50-60 characters</small>
                            </div>

                            <div class="form-group">
                                <label>Meta Description</label>
                                <textarea name="meta_description" rows="2" maxlength="160"></textarea>
                                <small>Recommended: 150-160 characters</small>
                            </div>

                            <div class="form-group">
                                <label>Meta Keywords</label>
                                <input type="text" name="meta_keywords">
                                <small>Comma-separated keywords</small>
                            </div>

                            <div class="form-group">
                                <label>Open Graph Title</label>
                                <input type="text" name="og_title">
                                <small>For social media sharing (Facebook, LinkedIn)</small>
                            </div>

                            <div class="form-group">
                                <label>Open Graph Description</label>
                                <textarea name="og_description" rows="2"></textarea>
                            </div>
                        </details>
                    </div>

                    <div class="form-col-sidebar">
                        <div class="form-group">
                            <label>Category *</label>
                            <select name="category_id" required>
                                <option value="">Select category</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Tags</label>
                            <input type="text" name="tags" placeholder="Comma-separated tags">
                        </div>

                        <div class="form-group">
                            <label>Status *</label>
                            <select name="status" required>
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Publish Date</label>
                            <input type="datetime-local" name="publish_date" value="<?php echo date('Y-m-d\TH:i'); ?>">
                        </div>

                        <div class="form-group">
                            <label>Slug (URL)</label>
                            <input type="text" name="slug" placeholder="Auto-generated from title">
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Publish Post</button>
                    </div>
                </div>
            </form>
        </main>
    </div>
</body>
</html>
