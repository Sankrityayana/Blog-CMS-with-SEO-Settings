-- Blog CMS with SEO Settings Database

CREATE DATABASE IF NOT EXISTS blog_cms_seo;
USE blog_cms_seo;

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    bio TEXT,
    avatar VARCHAR(255),
    role ENUM('admin', 'author', 'contributor') DEFAULT 'author',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT 1,
    INDEX idx_username (username),
    INDEX idx_email (email)
);

-- Categories table
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) UNIQUE NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    parent_id INT DEFAULT NULL,
    meta_title VARCHAR(255),
    meta_description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_slug (slug)
);

-- Posts table
CREATE TABLE posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    author_id INT NOT NULL,
    category_id INT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    excerpt TEXT,
    content LONGTEXT NOT NULL,
    featured_image VARCHAR(255),
    status ENUM('draft', 'published', 'scheduled') DEFAULT 'draft',
    publish_date DATETIME,
    views INT DEFAULT 0,
    allow_comments BOOLEAN DEFAULT 1,
    meta_title VARCHAR(255),
    meta_description TEXT,
    meta_keywords TEXT,
    og_title VARCHAR(255),
    og_description TEXT,
    og_image VARCHAR(255),
    twitter_card VARCHAR(50) DEFAULT 'summary_large_image',
    canonical_url VARCHAR(255),
    schema_type VARCHAR(50) DEFAULT 'BlogPosting',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_status (status),
    INDEX idx_publish_date (publish_date),
    INDEX idx_author (author_id),
    INDEX idx_category (category_id)
);

-- Tags table
CREATE TABLE tags (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) UNIQUE NOT NULL,
    slug VARCHAR(50) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_slug (slug)
);

-- Post Tags junction table
CREATE TABLE post_tags (
    post_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (post_id, tag_id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

-- Comments table
CREATE TABLE comments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    post_id INT NOT NULL,
    user_id INT,
    parent_id INT DEFAULT NULL,
    author_name VARCHAR(100) NOT NULL,
    author_email VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    status ENUM('pending', 'approved', 'spam') DEFAULT 'pending',
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (parent_id) REFERENCES comments(id) ON DELETE CASCADE,
    INDEX idx_post (post_id),
    INDEX idx_status (status)
);

-- Site Settings table
CREATE TABLE site_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample data

-- Sample users
INSERT INTO users (username, email, password, full_name, role) VALUES
('admin', 'admin@blog.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', 'admin'),
('john_author', 'john@blog.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Doe', 'author'),
('jane_author', 'jane@blog.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jane Smith', 'author');

-- Sample categories
INSERT INTO categories (name, slug, description, meta_title, meta_description) VALUES
('Technology', 'technology', 'All about technology trends and innovations', 'Technology Blog Posts', 'Read the latest technology articles and insights'),
('Web Development', 'web-development', 'Web development tutorials and tips', 'Web Development Articles', 'Learn web development with our comprehensive guides'),
('SEO', 'seo', 'Search Engine Optimization guides', 'SEO Tips and Tricks', 'Master SEO with our expert guides'),
('Digital Marketing', 'digital-marketing', 'Digital marketing strategies', 'Digital Marketing Blog', 'Explore digital marketing strategies and tactics'),
('Design', 'design', 'UI/UX and graphic design', 'Design Inspiration', 'Get inspired by the latest design trends');

-- Sample tags
INSERT INTO tags (name, slug) VALUES
('PHP', 'php'),
('MySQL', 'mysql'),
('JavaScript', 'javascript'),
('SEO', 'seo'),
('Tutorial', 'tutorial'),
('Best Practices', 'best-practices'),
('CSS', 'css'),
('HTML', 'html');

-- Sample posts
INSERT INTO posts (author_id, category_id, title, slug, excerpt, content, status, publish_date, meta_title, meta_description, meta_keywords, og_title, og_description) VALUES
(1, 1, 'Getting Started with Modern Web Development', 'getting-started-modern-web-development', 
'Learn the fundamentals of modern web development including HTML5, CSS3, and JavaScript.', 
'<p>Modern web development has evolved significantly over the years. In this comprehensive guide, we will explore the essential technologies and best practices that every web developer should know.</p><h2>HTML5 Basics</h2><p>HTML5 is the latest version of the markup language used to structure web content. It introduces new semantic elements, improved form controls, and powerful APIs.</p><h2>CSS3 Styling</h2><p>CSS3 brings advanced styling capabilities including flexbox, grid layouts, animations, and responsive design features.</p><h2>JavaScript Fundamentals</h2><p>JavaScript is the programming language of the web, enabling interactive and dynamic user experiences.</p>', 
'published', NOW(), 
'Modern Web Development Guide 2026', 
'Complete guide to modern web development covering HTML5, CSS3, JavaScript, and best practices for building responsive websites.', 
'web development, HTML5, CSS3, JavaScript, responsive design', 
'Getting Started with Modern Web Development', 
'Learn modern web development fundamentals'),

(2, 2, 'Building a PHP MySQL Blog from Scratch', 'building-php-mysql-blog-scratch', 
'Step-by-step tutorial on creating a blog using PHP and MySQL.', 
'<p>Creating a blog from scratch is a great way to learn PHP and MySQL. In this tutorial, we will build a fully functional blog system.</p><h2>Database Design</h2><p>We will start by designing our database schema with tables for posts, users, categories, and comments.</p><h2>Backend Development</h2><p>Learn how to create a secure PHP backend with proper authentication and CRUD operations.</p><h2>Frontend Integration</h2><p>Build a responsive frontend that displays your blog posts beautifully.</p>', 
'published', NOW() - INTERVAL 2 DAY, 
'Build a PHP MySQL Blog - Complete Tutorial', 
'Learn how to build a complete blog system using PHP and MySQL with this step-by-step tutorial.', 
'PHP, MySQL, blog tutorial, web development', 
'Building a PHP MySQL Blog from Scratch', 
'Complete PHP MySQL blog tutorial'),

(1, 3, 'Advanced SEO Techniques for 2026', 'advanced-seo-techniques-2026', 
'Master the latest SEO strategies to boost your website ranking.', 
'<p>Search Engine Optimization continues to evolve. Here are the most effective SEO techniques for 2026.</p><h2>Technical SEO</h2><p>Optimize your website structure, page speed, mobile responsiveness, and crawlability.</p><h2>Content Optimization</h2><p>Create high-quality, relevant content that matches user intent and includes proper keyword optimization.</p><h2>Link Building</h2><p>Build authoritative backlinks through content marketing and outreach strategies.</p>', 
'published', NOW() - INTERVAL 5 DAY, 
'Advanced SEO Techniques 2026 - Complete Guide', 
'Discover advanced SEO strategies including technical optimization, content marketing, and link building for 2026.', 
'SEO, search engine optimization, SEO 2026, ranking factors', 
'Advanced SEO Techniques for 2026', 
'Master SEO with advanced techniques'),

(3, 4, 'Social Media Marketing Best Practices', 'social-media-marketing-best-practices', 
'Effective strategies for social media marketing success.', 
'<p>Social media marketing is essential for modern businesses. Learn the best practices for each major platform.</p><h2>Platform Strategy</h2><p>Understand the unique characteristics of Facebook, Instagram, Twitter, LinkedIn, and TikTok.</p><h2>Content Creation</h2><p>Create engaging content that resonates with your audience and encourages sharing.</p><h2>Analytics and Optimization</h2><p>Track your performance metrics and continuously optimize your strategy.</p>', 
'published', NOW() - INTERVAL 1 DAY, 
'Social Media Marketing Best Practices 2026', 
'Learn social media marketing best practices for Facebook, Instagram, Twitter, LinkedIn and more platforms.', 
'social media, marketing, digital marketing, social strategy', 
'Social Media Marketing Best Practices', 
'Master social media marketing'),

(2, 5, 'Modern UI/UX Design Principles', 'modern-ui-ux-design-principles', 
'Essential design principles for creating beautiful user interfaces.', 
'<p>Good design is invisible. Learn the fundamental principles of UI/UX design.</p><h2>User-Centered Design</h2><p>Always put your users first and design for their needs, not your assumptions.</p><h2>Visual Hierarchy</h2><p>Guide users through your interface with proper use of size, color, and spacing.</p><h2>Accessibility</h2><p>Ensure your designs are accessible to all users, including those with disabilities.</p>', 
'draft', NOW() + INTERVAL 2 DAY, 
'Modern UI/UX Design Principles', 
'Learn essential UI/UX design principles for creating beautiful and functional user interfaces.', 
'UI design, UX design, user interface, user experience', 
'Modern UI/UX Design Principles', 
'Essential UI/UX design guide');

-- Link posts to tags
INSERT INTO post_tags (post_id, tag_id) VALUES
(1, 3), (1, 7), (1, 8), (1, 5),
(2, 1), (2, 2), (2, 5),
(3, 4), (3, 6),
(4, 6),
(5, 7);

-- Sample comments
INSERT INTO comments (post_id, author_name, author_email, content, status) VALUES
(1, 'Mike Johnson', 'mike@example.com', 'Great tutorial! Very helpful for beginners.', 'approved'),
(1, 'Sarah Williams', 'sarah@example.com', 'Thanks for sharing. Looking forward to more content.', 'approved'),
(2, 'David Brown', 'david@example.com', 'Excellent guide on PHP and MySQL!', 'approved'),
(3, 'Emma Davis', 'emma@example.com', 'Very informative SEO article.', 'pending');

-- Site settings
INSERT INTO site_settings (setting_key, setting_value) VALUES
('site_name', 'My Blog'),
('site_tagline', 'Insights, Tutorials, and Inspiration'),
('site_description', 'A modern blog covering technology, web development, SEO, and digital marketing'),
('site_url', 'http://localhost'),
('posts_per_page', '10'),
('comment_moderation', '1'),
('meta_author', 'Blog Admin'),
('google_analytics', ''),
('twitter_handle', '@myblog'),
('facebook_page', ''),
('robots_txt', 'User-agent: *\nAllow: /'),
('default_og_image', ''),
('sitemap_enabled', '1');
