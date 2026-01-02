    </main>
    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3><?php echo SITE_NAME; ?></h3>
                    <p><?php echo getSiteSetting('site_description', SITE_TAGLINE); ?></p>
                </div>
                <div class="footer-section">
                    <h4>Categories</h4>
                    <ul>
                        <?php
                        $footer_categories = getCategories();
                        foreach (array_slice($footer_categories, 0, 5) as $cat):
                        ?>
                            <li><a href="<?php echo SITE_URL; ?>/category.php?slug=<?php echo $cat['slug']; ?>"><?php echo $cat['name']; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="<?php echo SITE_URL; ?>/index.php">Home</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/blog.php">Blog</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/sitemap.php">Sitemap</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>
    <script src="<?php echo SITE_URL; ?>/js/main.js"></script>
</body>
</html>
