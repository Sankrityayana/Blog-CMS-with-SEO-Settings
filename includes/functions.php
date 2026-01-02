<?php
require_once __DIR__ . '/config.php';

// Authentication Functions
function login($email, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        return true;
    }
    return false;
}

function logout() {
    session_destroy();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . SITE_URL . '/admin/login.php');
        exit;
    }
}

function requireAdmin() {
    requireLogin();
    if ($_SESSION['role'] !== 'admin') {
        header('Location: ' . SITE_URL . '/admin/index.php');
        exit;
    }
}

function getCurrentUser() {
    global $pdo;
    if (!isLoggedIn()) return null;
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

// Post Functions
function getPosts($limit = 10, $offset = 0, $category_id = null, $tag_id = null, $search = null, $status = 'published') {
    global $pdo;
    
    $sql = "SELECT p.*, u.username as author_name, u.full_name as author_full_name, c.name as category_name, c.slug as category_slug,
            (SELECT COUNT(*) FROM comments WHERE post_id = p.id AND status = 'approved') as comment_count
            FROM posts p
            LEFT JOIN users u ON p.author_id = u.id
            LEFT JOIN categories c ON p.category_id = c.id";
    
    $where = [];
    $params = [];
    
    if ($status) {
        $where[] = "p.status = ?";
        $params[] = $status;
        
        if ($status == 'published') {
            $where[] = "p.publish_date <= NOW()";
        }
    }
    
    if ($category_id) {
        $where[] = "p.category_id = ?";
        $params[] = $category_id;
    }
    
    if ($tag_id) {
        $sql .= " INNER JOIN post_tags pt ON p.id = pt.post_id";
        $where[] = "pt.tag_id = ?";
        $params[] = $tag_id;
    }
    
    if ($search) {
        $where[] = "(p.title LIKE ? OR p.content LIKE ? OR p.excerpt LIKE ?)";
        $searchTerm = "%$search%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }
    
    if (!empty($where)) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }
    
    $sql .= " ORDER BY p.publish_date DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getPostBySlug($slug) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT p.*, u.username as author_name, u.full_name as author_full_name, u.bio as author_bio,
                          c.name as category_name, c.slug as category_slug
                          FROM posts p
                          LEFT JOIN users u ON p.author_id = u.id
                          LEFT JOIN categories c ON p.category_id = c.id
                          WHERE p.slug = ? AND p.status = 'published' AND p.publish_date <= NOW()");
    $stmt->execute([$slug]);
    $post = $stmt->fetch();
    
    if ($post) {
        // Increment views
        $update = $pdo->prepare("UPDATE posts SET views = views + 1 WHERE id = ?");
        $update->execute([$post['id']]);
        
        // Get tags
        $post['tags'] = getPostTags($post['id']);
    }
    
    return $post;
}

function getPostById($id) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM posts p
                          LEFT JOIN categories c ON p.category_id = c.id
                          WHERE p.id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch();
    
    if ($post) {
        $post['tags'] = getPostTags($post['id']);
    }
    
    return $post;
}

function getPostTags($post_id) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT t.* FROM tags t
                          INNER JOIN post_tags pt ON t.id = pt.tag_id
                          WHERE pt.post_id = ?");
    $stmt->execute([$post_id]);
    return $stmt->fetchAll();
}

function countPosts($category_id = null, $tag_id = null, $search = null, $status = 'published') {
    global $pdo;
    
    $sql = "SELECT COUNT(DISTINCT p.id) FROM posts p";
    $where = [];
    $params = [];
    
    if ($tag_id) {
        $sql .= " INNER JOIN post_tags pt ON p.id = pt.post_id";
    }
    
    if ($status) {
        $where[] = "p.status = ?";
        $params[] = $status;
        
        if ($status == 'published') {
            $where[] = "p.publish_date <= NOW()";
        }
    }
    
    if ($category_id) {
        $where[] = "p.category_id = ?";
        $params[] = $category_id;
    }
    
    if ($tag_id) {
        $where[] = "pt.tag_id = ?";
        $params[] = $tag_id;
    }
    
    if ($search) {
        $where[] = "(p.title LIKE ? OR p.content LIKE ? OR p.excerpt LIKE ?)";
        $searchTerm = "%$search%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }
    
    if (!empty($where)) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchColumn();
}

// Category Functions
function getCategories() {
    global $pdo;
    
    $stmt = $pdo->query("SELECT c.*, COUNT(p.id) as post_count FROM categories c
                        LEFT JOIN posts p ON c.id = p.category_id AND p.status = 'published' AND p.publish_date <= NOW()
                        GROUP BY c.id
                        ORDER BY c.name");
    return $stmt->fetchAll();
}

function getCategoryBySlug($slug) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE slug = ?");
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

// Tag Functions
function getPopularTags($limit = 20) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT t.*, COUNT(pt.post_id) as post_count
                          FROM tags t
                          INNER JOIN post_tags pt ON t.id = pt.tag_id
                          INNER JOIN posts p ON pt.post_id = p.id
                          WHERE p.status = 'published' AND p.publish_date <= NOW()
                          GROUP BY t.id
                          ORDER BY post_count DESC
                          LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

function getTagBySlug($slug) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM tags WHERE slug = ?");
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

// Comment Functions
function getComments($post_id, $status = 'approved') {
    global $pdo;
    
    $sql = "SELECT c.*, u.username FROM comments c
            LEFT JOIN users u ON c.user_id = u.id
            WHERE c.post_id = ?";
    
    $params = [$post_id];
    
    if ($status) {
        $sql .= " AND c.status = ?";
        $params[] = $status;
    }
    
    $sql .= " ORDER BY c.created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function addComment($post_id, $author_name, $author_email, $content, $user_id = null, $parent_id = null) {
    global $pdo;
    
    $ip = $_SERVER['REMOTE_ADDR'];
    
    $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, parent_id, author_name, author_email, content, ip_address)
                          VALUES (?, ?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$post_id, $user_id, $parent_id, $author_name, $author_email, $content, $ip]);
}

// Helper Functions
function generateSlug($text) {
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    $text = trim($text, '-');
    return $text;
}

function formatDate($date) {
    return date('F j, Y', strtotime($date));
}

function getExcerpt($text, $length = 200) {
    $text = strip_tags($text);
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function getSiteSetting($key, $default = '') {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_key = ?");
    $stmt->execute([$key]);
    $result = $stmt->fetchColumn();
    
    return $result !== false ? $result : $default;
}

function updateSiteSetting($key, $value) {
    global $pdo;
    
    $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?)
                          ON DUPLICATE KEY UPDATE setting_value = ?");
    return $stmt->execute([$key, $value, $value]);
}

// SEO Functions
function generateMetaTags($post) {
    $meta = [];
    
    // Basic meta tags
    $meta['title'] = $post['meta_title'] ?: $post['title'];
    $meta['description'] = $post['meta_description'] ?: getExcerpt($post['excerpt'] ?: $post['content'], 160);
    $meta['keywords'] = $post['meta_keywords'] ?: '';
    
    // Open Graph tags
    $meta['og_title'] = $post['og_title'] ?: $meta['title'];
    $meta['og_description'] = $post['og_description'] ?: $meta['description'];
    $meta['og_image'] = $post['og_image'] ?: $post['featured_image'];
    $meta['og_url'] = $post['canonical_url'] ?: (SITE_URL . '/post.php?slug=' . $post['slug']);
    
    // Twitter Card
    $meta['twitter_card'] = $post['twitter_card'] ?: 'summary_large_image';
    
    return $meta;
}

function outputMetaTags($meta) {
    echo '<meta name="description" content="' . htmlspecialchars($meta['description']) . '">' . "\n";
    
    if ($meta['keywords']) {
        echo '<meta name="keywords" content="' . htmlspecialchars($meta['keywords']) . '">' . "\n";
    }
    
    echo '<meta property="og:title" content="' . htmlspecialchars($meta['og_title']) . '">' . "\n";
    echo '<meta property="og:description" content="' . htmlspecialchars($meta['og_description']) . '">' . "\n";
    echo '<meta property="og:type" content="article">' . "\n";
    echo '<meta property="og:url" content="' . htmlspecialchars($meta['og_url']) . '">' . "\n";
    
    if ($meta['og_image']) {
        echo '<meta property="og:image" content="' . htmlspecialchars($meta['og_image']) . '">' . "\n";
    }
    
    echo '<meta name="twitter:card" content="' . htmlspecialchars($meta['twitter_card']) . '">' . "\n";
    echo '<meta name="twitter:title" content="' . htmlspecialchars($meta['og_title']) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . htmlspecialchars($meta['og_description']) . '">' . "\n";
    
    if ($meta['og_image']) {
        echo '<meta name="twitter:image" content="' . htmlspecialchars($meta['og_image']) . '">' . "\n";
    }
}

function generateStructuredData($post) {
    $data = [
        "@context" => "https://schema.org",
        "@type" => $post['schema_type'] ?: "BlogPosting",
        "headline" => $post['title'],
        "description" => getExcerpt($post['excerpt'] ?: $post['content'], 160),
        "datePublished" => date('c', strtotime($post['publish_date'])),
        "dateModified" => date('c', strtotime($post['updated_at'])),
        "author" => [
            "@type" => "Person",
            "name" => $post['author_full_name'] ?: $post['author_name']
        ]
    ];
    
    if ($post['featured_image']) {
        $data["image"] = $post['featured_image'];
    }
    
    return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
}
?>
