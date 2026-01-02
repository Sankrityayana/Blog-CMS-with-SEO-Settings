# Blog CMS with SEO Settings

A comprehensive Blog Content Management System built with PHP and MySQL, featuring advanced SEO capabilities including meta tags, Open Graph, structured data, and sitemap generation.

## Features

- **Full Blog Management**: Create, edit, and publish blog posts with rich content
- **Advanced SEO Features**:
  - Custom meta titles and descriptions per post
  - Meta keywords support
  - Open Graph tags for social media sharing
  - Twitter Card integration
  - Canonical URLs
  - Structured data (JSON-LD) support
  - XML sitemap generation
  - Robots.txt configuration
- **Content Organization**:
  - Categories with hierarchical support
  - Tag system for flexible content classification
  - Post search functionality
- **Comment System**: Built-in commenting with moderation
- **Admin Dashboard**:
  - Intuitive admin interface
  - Post statistics and analytics
  - Category management
  - Comment moderation
  - SEO settings panel
- **User Roles**: Admin, author, and contributor roles
- **Responsive Design**: Light multicolor theme with no gradients

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache Web Server (XAMPP recommended)
- Modern web browser

## Installation

1. **Clone or download the repository**
   ```bash
   git clone https://github.com/Sankrityayana/Blog-CMS-with-SEO-Settings.git
   ```

2. **Configure XAMPP**
   - Ensure Apache and MySQL are running
   - MySQL should be configured to use port 3307

3. **Import Database**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database named `blog_cms_seo`
   - Import the `database/database.sql` file

4. **Configure Database Connection**
   - Open `includes/config.php`
   - Update database credentials if needed:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_PORT', '3307');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     define('DB_NAME', 'blog_cms_seo');
     ```

5. **Set Site URL**
   - Update `SITE_URL` in `includes/config.php` to match your local environment

6. **Access the Application**
   - Frontend: `http://localhost/Blog-CMS-with-SEO-Settings`
   - Admin Panel: `http://localhost/Blog-CMS-with-SEO-Settings/admin`

## Default Login Credentials

**Admin Account:**
- Email: admin@blog.com
- Password: password

**Author Accounts:**
- Email: john@blog.com or jane@blog.com
- Password: password

## Database Schema

The system uses 8 main tables:

- **users**: User accounts and authentication
- **categories**: Blog categories with SEO support
- **posts**: Blog posts with full SEO metadata
- **tags**: Tag system for content classification
- **post_tags**: Junction table for posts and tags
- **comments**: Comment system with moderation
- **site_settings**: Global site configuration

## SEO Features

### Per-Post SEO Settings

Each blog post supports:
- Custom meta title (50-60 characters recommended)
- Meta description (150-160 characters recommended)
- Meta keywords
- Open Graph title and description
- Open Graph image
- Twitter Card type
- Canonical URL
- Schema type (BlogPosting, Article, etc.)

### Structured Data

Automatic JSON-LD structured data generation for:
- Blog posts
- Authors
- Publication dates
- Featured images

### Sitemap

- Automatic XML sitemap generation
- Includes all published posts
- Category pages
- Organized with priorities and change frequencies
- Access at: `/sitemap.php`

### Robots.txt

- Configurable robots.txt via admin panel
- Custom directives for search engine crawlers
- Access at: `/robots.php`

## Project Structure

```
Blog-CMS-with-SEO-Settings/
├── admin/
│   ├── index.php          # Admin dashboard
│   ├── login.php          # Admin login
│   ├── posts.php          # Manage posts
│   ├── new_post.php       # Create new post
│   ├── settings.php       # SEO settings
│   └── logout.php         # Logout
├── css/
│   └── style.css          # Main stylesheet
├── database/
│   └── database.sql       # Database schema
├── includes/
│   ├── config.php         # Configuration
│   ├── functions.php      # Core functions
│   ├── header.php         # Page header
│   └── footer.php         # Page footer
├── js/
│   └── main.js            # JavaScript
├── uploads/               # Upload directory
├── index.php              # Homepage
├── blog.php               # Blog listing
├── post.php               # Single post
├── category.php           # Category view
├── categories.php         # All categories
├── tag.php                # Tag view
├── sitemap.php            # XML sitemap
├── robots.php             # Robots.txt
└── README.md
```

## Usage

### Creating a Blog Post

1. Log in to the admin panel
2. Click "New Post" in the sidebar
3. Enter post title and content
4. Select category and add tags
5. Expand "SEO Settings" section for optimization:
   - Add custom meta title and description
   - Configure Open Graph tags
   - Set meta keywords
6. Choose status (Draft or Published)
7. Click "Publish Post"

### Optimizing for SEO

**Meta Tags:**
- Keep titles under 60 characters
- Keep descriptions between 150-160 characters
- Use relevant keywords naturally

**Open Graph:**
- Set custom OG titles for social sharing
- Add compelling OG descriptions
- Upload featured images (OG image)

**Structured Data:**
- Automatically generated for all posts
- Helps search engines understand content

### Managing Categories

Categories support their own SEO settings:
- Custom meta titles
- Meta descriptions
- Category descriptions

### Site-Wide SEO Settings

Access via Admin Panel → SEO Settings:
- Configure site name and tagline
- Set default meta author
- Add Google Analytics ID
- Configure social media handles
- Customize robots.txt

## Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL (Port 3307)
- **Frontend**: HTML5, CSS3, JavaScript
- **Server**: Apache (XAMPP)

## License

MIT License - Copyright (c) 2026 Sankrityayana

## Support

For issues or questions, please open an issue on the GitHub repository.
