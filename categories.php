<?php
require_once 'includes/functions.php';

$categories = getCategories();

$page_title = 'Categories';
include 'includes/header.php';
?>

<div class="container">
    <div class="page-header">
        <h1>All Categories</h1>
        <p>Browse articles by category</p>
    </div>

    <div class="categories-grid-page">
        <?php foreach ($categories as $category): ?>
            <?php if ($category['post_count'] > 0): ?>
                <div class="category-card category-<?php echo $category['slug']; ?>">
                    <h2><a href="category.php?slug=<?php echo $category['slug']; ?>"><?php echo $category['name']; ?></a></h2>
                    <p><?php echo $category['description']; ?></p>
                    <div class="category-footer">
                        <span><?php echo $category['post_count']; ?> articles</span>
                        <a href="category.php?slug=<?php echo $category['slug']; ?>" class="btn btn-small">View →</a>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
