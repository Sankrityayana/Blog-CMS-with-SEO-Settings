<?php
require_once 'includes/functions.php';

header('Content-Type: text/plain; charset=utf-8');

echo getSiteSetting('robots_txt', "User-agent: *\nAllow: /\n\nSitemap: " . SITE_URL . "/sitemap.php");
?>
