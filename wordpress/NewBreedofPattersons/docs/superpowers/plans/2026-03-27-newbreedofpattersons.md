# NewBreedofPattersons Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build a vibrant, playful WordPress blog theme called "New Breed of Pattersons" — a Christian family blog with articles on marriage, children, long distance relationships, and family finances, plus a movie pick of the week feature and photo gallery.

**Architecture:** Custom WordPress theme (`newbreedofpattersons`) installed in a fresh WordPress instance at `/home/dev/repos/Websites/wordpress/NewBreedofPattersons/`. The theme uses a custom post type for movie picks, native WordPress categories for article topics, and a custom page template with CSS/JS lightbox for the gallery. No external plugin dependencies.

**Tech Stack:** WordPress 6.9.4, PHP 8.1, MySQL 8.0, vanilla CSS3 (custom properties, grid, flexbox), vanilla JavaScript (no jQuery).

---

## File Structure

All theme files live in `wp-content/themes/newbreedofpattersons/` under the WordPress root at `/home/dev/repos/Websites/wordpress/NewBreedofPattersons/`.

| File | Responsibility |
|------|---------------|
| `style.css` | Theme metadata + all CSS (variables, layout, components, responsive) |
| `functions.php` | Theme setup, asset enqueue, CPT registration, menus, image sizes, excerpts |
| `header.php` | Sticky header with site branding and primary navigation |
| `footer.php` | Dark navy footer with 3 sections |
| `front-page.php` | Homepage: hero, movie pick, latest posts, scripture banner |
| `index.php` | Blog archive fallback with card grid and pagination |
| `archive.php` | Category archive pages (same grid layout, with category header) |
| `single.php` | Single post view with narrow content column |
| `page.php` | Default page template |
| `404.php` | Error page with friendly message |
| `templates/template-gallery.php` | Gallery page with photo grid |
| `templates/template-about.php` | Mission-focused about page |
| `assets/js/main.js` | Mobile menu toggle, sticky header shadow |
| `assets/js/lightbox.js` | Gallery lightbox open/close/navigate |
| `assets/css/lightbox.css` | Lightbox overlay and image styles |

The WordPress root also contains:
| File | Responsibility |
|------|---------------|
| `wp-config.php` | Database and site configuration for NewBreedofPattersons |

---

### Task 1: WordPress Installation Setup

**Files:**
- Create: `/home/dev/repos/Websites/wordpress/NewBreedofPattersons/wp-config.php`
- Copy: WordPress core files from PerfectLoveRestored

- [ ] **Step 1: Copy WordPress core files**

Copy all WordPress core files (wp-admin, wp-includes, root PHP files) from PerfectLoveRestored into NewBreedofPattersons. Exclude `wp-content/themes` (we'll create our own) and `wp-config.php` (we'll create a new one).

```bash
cd /home/dev/repos/Websites/wordpress
# Copy core files
cp PerfectLoveRestored/index.php NewBreedofPattersons/
cp PerfectLoveRestored/wp-activate.php NewBreedofPattersons/
cp PerfectLoveRestored/wp-blog-header.php NewBreedofPattersons/
cp PerfectLoveRestored/wp-comments-post.php NewBreedofPattersons/
cp PerfectLoveRestored/wp-cron.php NewBreedofPattersons/
cp PerfectLoveRestored/wp-links-opml.php NewBreedofPattersons/
cp PerfectLoveRestored/wp-load.php NewBreedofPattersons/
cp PerfectLoveRestored/wp-login.php NewBreedofPattersons/
cp PerfectLoveRestored/wp-mail.php NewBreedofPattersons/
cp PerfectLoveRestored/wp-settings.php NewBreedofPattersons/
cp PerfectLoveRestored/wp-signup.php NewBreedofPattersons/
cp PerfectLoveRestored/wp-trackback.php NewBreedofPattersons/
cp PerfectLoveRestored/xmlrpc.php NewBreedofPattersons/
cp PerfectLoveRestored/wp-config-sample.php NewBreedofPattersons/
cp -r PerfectLoveRestored/wp-admin NewBreedofPattersons/
cp -r PerfectLoveRestored/wp-includes NewBreedofPattersons/
# Copy wp-content structure but only default items
mkdir -p NewBreedofPattersons/wp-content/themes
mkdir -p NewBreedofPattersons/wp-content/plugins
mkdir -p NewBreedofPattersons/wp-content/uploads
cp PerfectLoveRestored/wp-content/index.php NewBreedofPattersons/wp-content/ 2>/dev/null || true
```

- [ ] **Step 2: Create the database**

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS newbreedofpattersons DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -e "CREATE USER IF NOT EXISTS 'nbop_user'@'127.0.0.1' IDENTIFIED BY 'NBOP_Str0ng_P@ss2026';"
mysql -u root -e "GRANT ALL PRIVILEGES ON newbreedofpattersons.* TO 'nbop_user'@'127.0.0.1'; FLUSH PRIVILEGES;"
```

- [ ] **Step 3: Create wp-config.php**

Write `/home/dev/repos/Websites/wordpress/NewBreedofPattersons/wp-config.php`:

```php
<?php
define( 'DB_NAME', 'newbreedofpattersons' );
define( 'DB_USER', 'nbop_user' );
define( 'DB_PASSWORD', 'NBOP_Str0ng_P@ss2026' );
define( 'DB_HOST', '127.0.0.1' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

define( 'AUTH_KEY',          'unique-phrase-1-change-me' );
define( 'SECURE_AUTH_KEY',   'unique-phrase-2-change-me' );
define( 'LOGGED_IN_KEY',     'unique-phrase-3-change-me' );
define( 'NONCE_KEY',         'unique-phrase-4-change-me' );
define( 'AUTH_SALT',         'unique-phrase-5-change-me' );
define( 'SECURE_AUTH_SALT',  'unique-phrase-6-change-me' );
define( 'LOGGED_IN_SALT',    'unique-phrase-7-change-me' );
define( 'NONCE_SALT',        'unique-phrase-8-change-me' );

$table_prefix = 'wp_';

define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );

if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}

require_once ABSPATH . 'wp-settings.php';
```

Generate real salts at runtime using the WordPress salt API. Replace the placeholder strings with unique random values.

- [ ] **Step 4: Create the theme directory**

```bash
mkdir -p /home/dev/repos/Websites/wordpress/NewBreedofPattersons/wp-content/themes/newbreedofpattersons/assets/js
mkdir -p /home/dev/repos/Websites/wordpress/NewBreedofPattersons/wp-content/themes/newbreedofpattersons/assets/css
mkdir -p /home/dev/repos/Websites/wordpress/NewBreedofPattersons/wp-content/themes/newbreedofpattersons/templates
```

- [ ] **Step 5: Commit**

```bash
git add NewBreedofPattersons/wp-config.php
git commit -m "chore: set up WordPress installation for NewBreedofPattersons"
```

Note: Do NOT commit WordPress core files (wp-admin, wp-includes, etc.) — only track custom theme files and wp-config.php. Add wp-admin/, wp-includes/, and wp-content/uploads/ to .gitignore if not already ignored.

---

### Task 2: Theme Foundation — style.css + functions.php

**Files:**
- Create: `wp-content/themes/newbreedofpattersons/style.css`
- Create: `wp-content/themes/newbreedofpattersons/functions.php`

All paths below are relative to `/home/dev/repos/Websites/wordpress/NewBreedofPattersons/`.

- [ ] **Step 1: Create style.css with theme metadata and CSS custom properties**

Write `wp-content/themes/newbreedofpattersons/style.css`:

```css
/*
Theme Name: New Breed of Pattersons
Theme URI: https://newbreedofpattersons.com
Author: New Breed of Pattersons
Author URI: https://newbreedofpattersons.com
Description: A vibrant, playful Christian family blog theme featuring articles on marriage, children, long distance relationships, and family finances.
Version: 1.0.0
License: GNU General Public License v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: newbreedofpattersons
*/

/* ============================================
   CSS CUSTOM PROPERTIES
   ============================================ */
:root {
    /* Colors */
    --nbop-primary: #FF6B35;
    --nbop-secondary: #F7C548;
    --nbop-tertiary: #3BCEAC;
    --nbop-dark: #2D3047;
    --nbop-bg: #FFF8F0;
    --nbop-white: #FFFFFF;
    --nbop-text: #2D3047;
    --nbop-text-light: #777777;
    --nbop-purple: #7B68EE;
    --nbop-border: #E8E4DD;

    /* Typography */
    --nbop-font-heading: 'Lora', Georgia, serif;
    --nbop-font-body: 'Nunito', system-ui, sans-serif;

    /* Spacing */
    --nbop-spacing-xs: 0.5rem;
    --nbop-spacing-sm: 1rem;
    --nbop-spacing-md: 2rem;
    --nbop-spacing-lg: 4rem;
    --nbop-spacing-xl: 6rem;

    /* Layout */
    --nbop-max-width: 1200px;
    --nbop-content-width: 800px;
    --nbop-radius: 12px;
    --nbop-radius-pill: 25px;
    --nbop-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    --nbop-shadow-hover: 0 8px 32px rgba(0, 0, 0, 0.12);
    --nbop-transition: 0.3s ease;
}

/* ============================================
   RESET & BASE
   ============================================ */
*,
*::before,
*::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

html {
    font-size: 16px;
    scroll-behavior: smooth;
}

body {
    font-family: var(--nbop-font-body);
    color: var(--nbop-text);
    background-color: var(--nbop-white);
    line-height: 1.7;
    -webkit-font-smoothing: antialiased;
}

img {
    max-width: 100%;
    height: auto;
    display: block;
}

a {
    color: var(--nbop-primary);
    text-decoration: none;
    transition: color var(--nbop-transition);
}

a:hover {
    color: var(--nbop-dark);
}

h1, h2, h3, h4, h5, h6 {
    font-family: var(--nbop-font-heading);
    font-weight: 700;
    line-height: 1.3;
    color: var(--nbop-dark);
}

h1 { font-size: 2.8rem; }
h2 { font-size: 2.2rem; }
h3 { font-size: 1.5rem; }
h4 { font-size: 1.25rem; }

.screen-reader-text {
    border: 0;
    clip: rect(1px, 1px, 1px, 1px);
    clip-path: inset(50%);
    height: 1px;
    margin: -1px;
    overflow: hidden;
    padding: 0;
    position: absolute;
    width: 1px;
    word-wrap: normal !important;
}

/* ============================================
   LAYOUT
   ============================================ */
.container {
    max-width: var(--nbop-max-width);
    margin: 0 auto;
    padding: 0 var(--nbop-spacing-md);
}

.content-narrow {
    max-width: var(--nbop-content-width);
    margin: 0 auto;
}

/* ============================================
   BUTTONS
   ============================================ */
.btn {
    display: inline-block;
    padding: 12px 28px;
    border-radius: var(--nbop-radius-pill);
    font-weight: 700;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    cursor: pointer;
    transition: all var(--nbop-transition);
    border: 2px solid transparent;
    text-decoration: none;
}

.btn-primary {
    background: var(--nbop-white);
    color: var(--nbop-primary);
    border-color: var(--nbop-white);
}

.btn-primary:hover {
    background: transparent;
    color: var(--nbop-white);
    border-color: var(--nbop-white);
}

.btn-outline {
    background: transparent;
    color: var(--nbop-white);
    border-color: var(--nbop-white);
}

.btn-outline:hover {
    background: var(--nbop-white);
    color: var(--nbop-primary);
}

.btn-solid {
    background: var(--nbop-primary);
    color: var(--nbop-white);
    border-color: var(--nbop-primary);
}

.btn-solid:hover {
    background: var(--nbop-dark);
    border-color: var(--nbop-dark);
    color: var(--nbop-white);
}

/* ============================================
   HEADER
   ============================================ */
.site-header {
    position: sticky;
    top: 0;
    z-index: 1000;
    background: var(--nbop-white);
    border-bottom: 3px solid var(--nbop-primary);
    transition: box-shadow var(--nbop-transition);
}

.site-header.scrolled {
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
}

.header-inner {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 32px;
    max-width: var(--nbop-max-width);
    margin: 0 auto;
}

.site-branding {
    font-family: var(--nbop-font-heading);
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--nbop-dark);
}

.site-branding span {
    color: var(--nbop-primary);
}

.site-branding a {
    color: inherit;
    text-decoration: none;
}

.site-branding a:hover {
    color: inherit;
}

.primary-nav ul {
    display: flex;
    gap: 24px;
    list-style: none;
}

.primary-nav a {
    font-size: 0.8125rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--nbop-dark);
    position: relative;
    padding-bottom: 4px;
}

.primary-nav a::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background: var(--nbop-primary);
    transition: width var(--nbop-transition);
}

.primary-nav a:hover::after,
.primary-nav .current-menu-item a::after {
    width: 100%;
}

.menu-toggle {
    display: none;
    background: none;
    border: none;
    cursor: pointer;
    padding: 8px;
    color: var(--nbop-dark);
}

.menu-toggle svg {
    width: 24px;
    height: 24px;
}

/* ============================================
   HERO
   ============================================ */
.hero {
    background: linear-gradient(135deg, var(--nbop-primary) 0%, var(--nbop-secondary) 50%, var(--nbop-tertiary) 100%);
    padding: 80px 48px;
    text-align: center;
    color: var(--nbop-white);
}

.hero-label {
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 3px;
    margin-bottom: 16px;
    opacity: 0.9;
}

.hero h1 {
    color: var(--nbop-white);
    font-size: 3rem;
    margin-bottom: 16px;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.hero-description {
    font-size: 1.125rem;
    max-width: 560px;
    margin: 0 auto 32px;
    opacity: 0.95;
    line-height: 1.6;
}

.hero-ctas {
    display: flex;
    gap: 16px;
    justify-content: center;
}

/* ============================================
   MOVIE PICK
   ============================================ */
.movie-pick {
    background: var(--nbop-dark);
    padding: 24px 48px;
    color: var(--nbop-white);
}

.movie-pick-inner {
    display: flex;
    align-items: center;
    gap: 24px;
    max-width: var(--nbop-max-width);
    margin: 0 auto;
}

.movie-pick-badge {
    background: var(--nbop-primary);
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    white-space: nowrap;
}

.movie-pick-info {
    flex: 1;
}

.movie-pick-label {
    font-size: 0.6875rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: var(--nbop-secondary);
    margin-bottom: 4px;
}

.movie-pick-title {
    font-family: var(--nbop-font-heading);
    font-size: 1.25rem;
    font-weight: 700;
}

.movie-pick-review {
    font-size: 0.875rem;
    color: #ccc;
    max-width: 350px;
    line-height: 1.5;
}

.movie-pick-rating {
    color: var(--nbop-secondary);
    font-size: 1.125rem;
    white-space: nowrap;
}

/* ============================================
   POST GRID
   ============================================ */
.posts-section {
    padding: var(--nbop-spacing-xl) 0;
    background: var(--nbop-bg);
}

.section-title {
    text-align: center;
    margin-bottom: var(--nbop-spacing-md);
}

.section-title h2 {
    margin-bottom: 12px;
}

.section-title-bar {
    width: 60px;
    height: 4px;
    background: linear-gradient(90deg, var(--nbop-primary), var(--nbop-secondary));
    margin: 0 auto;
    border-radius: 2px;
}

.posts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 24px;
    max-width: var(--nbop-max-width);
    margin: 0 auto;
    padding: 0 var(--nbop-spacing-md);
}

.post-card {
    background: var(--nbop-white);
    border-radius: var(--nbop-radius);
    overflow: hidden;
    box-shadow: var(--nbop-shadow);
    transition: transform var(--nbop-transition), box-shadow var(--nbop-transition);
}

.post-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--nbop-shadow-hover);
}

.post-card-image {
    height: 200px;
    overflow: hidden;
    background: var(--nbop-bg);
}

.post-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.post-card-body {
    padding: 20px;
}

.category-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 0.6875rem;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--nbop-white);
    margin-bottom: 8px;
}

.category-badge.cat-marriage { background: var(--nbop-primary); }
.category-badge.cat-children { background: var(--nbop-tertiary); }
.category-badge.cat-long-distance { background: var(--nbop-purple); }
.category-badge.cat-family-finances { background: var(--nbop-secondary); color: var(--nbop-dark); }

.post-card-title {
    font-family: var(--nbop-font-heading);
    font-size: 1.0625rem;
    font-weight: 700;
    margin-bottom: 8px;
    line-height: 1.4;
}

.post-card-title a {
    color: var(--nbop-dark);
}

.post-card-title a:hover {
    color: var(--nbop-primary);
}

.post-card-excerpt {
    font-size: 0.8125rem;
    color: var(--nbop-text-light);
    line-height: 1.5;
}

.post-card-meta {
    font-size: 0.75rem;
    color: var(--nbop-text-light);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 8px;
}

/* ============================================
   SCRIPTURE BANNER
   ============================================ */
.scripture-banner {
    background: linear-gradient(135deg, var(--nbop-tertiary), var(--nbop-dark));
    padding: var(--nbop-spacing-xl) var(--nbop-spacing-md);
    text-align: center;
    color: var(--nbop-white);
}

.scripture-text {
    font-family: var(--nbop-font-heading);
    font-size: 1.5rem;
    font-style: italic;
    max-width: 700px;
    margin: 0 auto 12px;
    line-height: 1.6;
}

.scripture-ref {
    font-size: 0.8125rem;
    text-transform: uppercase;
    letter-spacing: 3px;
    opacity: 0.8;
}

/* ============================================
   SINGLE POST
   ============================================ */
.post-header {
    background: var(--nbop-bg);
    padding: var(--nbop-spacing-lg) var(--nbop-spacing-md);
    text-align: center;
}

.post-header h1 {
    margin-bottom: 12px;
}

.post-meta {
    font-size: 0.875rem;
    color: var(--nbop-text-light);
}

.post-featured-image {
    max-width: var(--nbop-content-width);
    margin: var(--nbop-spacing-md) auto;
    border-radius: var(--nbop-radius);
    overflow: hidden;
}

.post-content {
    max-width: var(--nbop-content-width);
    margin: 0 auto;
    padding: var(--nbop-spacing-md);
    font-size: 1.1rem;
    line-height: 1.8;
}

.post-content p {
    margin-bottom: 1.5em;
}

.post-content h2 {
    margin-top: 2em;
    margin-bottom: 0.75em;
}

.post-content h3 {
    margin-top: 1.5em;
    margin-bottom: 0.5em;
}

.post-content img {
    border-radius: var(--nbop-radius);
    margin: 1.5em 0;
}

.post-content blockquote {
    border-left: 4px solid var(--nbop-primary);
    padding-left: 1.5em;
    margin: 1.5em 0;
    font-style: italic;
    color: var(--nbop-text-light);
}

.post-navigation {
    display: flex;
    justify-content: space-between;
    max-width: var(--nbop-content-width);
    margin: var(--nbop-spacing-lg) auto;
    padding: var(--nbop-spacing-md);
    border-top: 1px solid var(--nbop-border);
}

.post-navigation a {
    font-weight: 600;
    font-size: 0.875rem;
}

/* ============================================
   ARCHIVE / CATEGORY HEADER
   ============================================ */
.archive-header {
    background: linear-gradient(135deg, var(--nbop-primary) 0%, var(--nbop-secondary) 100%);
    padding: var(--nbop-spacing-lg) var(--nbop-spacing-md);
    text-align: center;
    color: var(--nbop-white);
}

.archive-header h1 {
    color: var(--nbop-white);
    margin-bottom: 8px;
}

.archive-header p {
    opacity: 0.9;
    max-width: 500px;
    margin: 0 auto;
}

/* ============================================
   PAGINATION
   ============================================ */
.pagination {
    display: flex;
    justify-content: center;
    gap: 8px;
    padding: var(--nbop-spacing-lg) var(--nbop-spacing-md);
}

.pagination a,
.pagination span {
    display: inline-block;
    padding: 10px 16px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
}

.pagination a {
    background: var(--nbop-white);
    color: var(--nbop-dark);
    box-shadow: var(--nbop-shadow);
    transition: all var(--nbop-transition);
}

.pagination a:hover {
    background: var(--nbop-primary);
    color: var(--nbop-white);
}

.pagination .current {
    background: var(--nbop-primary);
    color: var(--nbop-white);
}

/* ============================================
   PAGE TEMPLATES
   ============================================ */
.page-header {
    background: linear-gradient(135deg, var(--nbop-primary) 0%, var(--nbop-secondary) 50%, var(--nbop-tertiary) 100%);
    padding: var(--nbop-spacing-lg) var(--nbop-spacing-md);
    text-align: center;
    color: var(--nbop-white);
}

.page-header h1 {
    color: var(--nbop-white);
}

.page-content {
    max-width: var(--nbop-content-width);
    margin: 0 auto;
    padding: var(--nbop-spacing-lg) var(--nbop-spacing-md);
    font-size: 1.05rem;
    line-height: 1.8;
}

.page-content p {
    margin-bottom: 1.5em;
}

/* About page */
.about-mission {
    text-align: center;
    padding: var(--nbop-spacing-lg) var(--nbop-spacing-md);
    background: var(--nbop-bg);
}

.about-mission blockquote {
    font-family: var(--nbop-font-heading);
    font-size: 1.4rem;
    font-style: italic;
    max-width: 700px;
    margin: 0 auto;
    line-height: 1.6;
    color: var(--nbop-dark);
    border: none;
    padding: 0;
}

.about-sections {
    max-width: var(--nbop-content-width);
    margin: 0 auto;
    padding: var(--nbop-spacing-lg) var(--nbop-spacing-md);
}

.about-section {
    margin-bottom: var(--nbop-spacing-lg);
}

.about-section h2 {
    margin-bottom: var(--nbop-spacing-sm);
    color: var(--nbop-primary);
}

.about-section p {
    font-size: 1.05rem;
    line-height: 1.8;
    margin-bottom: 1em;
}

/* ============================================
   GALLERY
   ============================================ */
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 16px;
    max-width: var(--nbop-max-width);
    margin: 0 auto;
    padding: var(--nbop-spacing-lg) var(--nbop-spacing-md);
}

.gallery-item {
    border-radius: var(--nbop-radius);
    overflow: hidden;
    cursor: pointer;
    aspect-ratio: 4/3;
    box-shadow: var(--nbop-shadow);
    transition: transform var(--nbop-transition), box-shadow var(--nbop-transition);
}

.gallery-item:hover {
    transform: scale(1.02);
    box-shadow: var(--nbop-shadow-hover);
}

.gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* ============================================
   404
   ============================================ */
.error-404 {
    text-align: center;
    padding: var(--nbop-spacing-xl) var(--nbop-spacing-md);
}

.error-404-code {
    font-size: 8rem;
    font-weight: 700;
    font-family: var(--nbop-font-heading);
    color: var(--nbop-primary);
    line-height: 1;
    opacity: 0.3;
}

.error-404 h1 {
    margin: var(--nbop-spacing-sm) 0;
}

.error-404 p {
    color: var(--nbop-text-light);
    margin-bottom: var(--nbop-spacing-md);
    max-width: 480px;
    margin-left: auto;
    margin-right: auto;
}

/* ============================================
   FOOTER
   ============================================ */
.site-footer {
    background: var(--nbop-dark);
    color: #ccc;
    padding: var(--nbop-spacing-lg) var(--nbop-spacing-md) var(--nbop-spacing-md);
}

.footer-inner {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    gap: var(--nbop-spacing-lg);
    max-width: var(--nbop-max-width);
    margin: 0 auto;
}

.footer-about .footer-title {
    font-family: var(--nbop-font-heading);
    font-size: 1.25rem;
    color: var(--nbop-white);
    font-weight: 700;
    margin-bottom: 8px;
}

.footer-about .footer-title span {
    color: var(--nbop-primary);
}

.footer-about p {
    font-size: 0.875rem;
    line-height: 1.6;
}

.footer-nav h3,
.footer-connect h3 {
    color: var(--nbop-white);
    font-size: 1rem;
    margin-bottom: 12px;
}

.footer-nav ul {
    list-style: none;
}

.footer-nav a {
    color: #ccc;
    font-size: 0.875rem;
    line-height: 2;
}

.footer-nav a:hover {
    color: var(--nbop-primary);
}

.footer-connect p {
    font-size: 0.875rem;
    line-height: 1.6;
}

.footer-bottom {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    margin-top: var(--nbop-spacing-lg);
    padding-top: var(--nbop-spacing-md);
    text-align: center;
    font-size: 0.8125rem;
}

/* ============================================
   RESPONSIVE
   ============================================ */
@media (max-width: 968px) {
    .footer-inner {
        grid-template-columns: 1fr;
        text-align: center;
    }

    .posts-grid {
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    }
}

@media (max-width: 768px) {
    :root {
        --nbop-spacing-xl: 4rem;
    }

    h1 { font-size: 2.2rem; }
    h2 { font-size: 1.8rem; }

    .menu-toggle {
        display: block;
    }

    .primary-nav {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: var(--nbop-white);
        border-bottom: 3px solid var(--nbop-primary);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .primary-nav.is-open {
        display: block;
    }

    .primary-nav ul {
        flex-direction: column;
        padding: var(--nbop-spacing-sm) var(--nbop-spacing-md);
        gap: 0;
    }

    .primary-nav a {
        display: block;
        padding: 12px 0;
        border-bottom: 1px solid var(--nbop-border);
    }

    .hero {
        padding: 48px 24px;
    }

    .hero h1 {
        font-size: 2.2rem;
    }

    .hero-ctas {
        flex-direction: column;
        align-items: center;
    }

    .movie-pick {
        padding: 20px 24px;
    }

    .movie-pick-inner {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }

    .movie-pick-review {
        max-width: 100%;
    }

    .posts-grid {
        grid-template-columns: 1fr;
    }

    .post-navigation {
        flex-direction: column;
        gap: 16px;
    }

    .gallery-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    }
}

@media (max-width: 480px) {
    .hero h1 {
        font-size: 2rem;
    }

    .btn {
        display: block;
        width: 100%;
        text-align: center;
    }

    .header-inner {
        padding: 12px 16px;
    }
}

/* ============================================
   WORDPRESS ALIGNMENT CLASSES
   ============================================ */
.alignleft {
    float: left;
    margin-right: 1.5em;
    margin-bottom: 1em;
}

.alignright {
    float: right;
    margin-left: 1.5em;
    margin-bottom: 1em;
}

.aligncenter {
    display: block;
    margin-left: auto;
    margin-right: auto;
}

.alignwide {
    max-width: var(--nbop-max-width);
    margin-left: auto;
    margin-right: auto;
}

.alignfull {
    width: 100vw;
    margin-left: calc(-50vw + 50%);
}
```

- [ ] **Step 2: Create functions.php**

Write `wp-content/themes/newbreedofpattersons/functions.php`:

```php
<?php
/**
 * New Breed of Pattersons theme functions
 *
 * @package NewBreedofPattersons
 */

// ── Theme Setup ──────────────────────────────────────────────

function nbop_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', array(
        'height'      => 80,
        'width'       => 250,
        'flex-height' => true,
        'flex-width'  => true,
    ) );
    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
    ) );
    add_theme_support( 'automatic-feed-links' );

    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'newbreedofpattersons' ),
        'footer'  => __( 'Footer Menu', 'newbreedofpattersons' ),
    ) );
}
add_action( 'after_setup_theme', 'nbop_setup' );

// ── Custom Image Sizes ───────────────────────────────────────

add_image_size( 'nbop-card', 600, 400, true );
add_image_size( 'nbop-hero', 1600, 600, true );

// ── Enqueue Assets ───────────────────────────────────────────

function nbop_enqueue_assets() {
    // Google Fonts
    wp_enqueue_style(
        'nbop-google-fonts',
        'https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=Nunito:wght@300;400;600;700&display=swap',
        array(),
        null
    );

    // Main stylesheet
    wp_enqueue_style(
        'nbop-style',
        get_stylesheet_uri(),
        array( 'nbop-google-fonts' ),
        wp_get_theme()->get( 'Version' )
    );

    // Main JS
    wp_enqueue_script(
        'nbop-main',
        get_template_directory_uri() . '/assets/js/main.js',
        array(),
        wp_get_theme()->get( 'Version' ),
        true
    );

    // Lightbox (only on gallery template)
    if ( is_page_template( 'templates/template-gallery.php' ) ) {
        wp_enqueue_style(
            'nbop-lightbox',
            get_template_directory_uri() . '/assets/css/lightbox.css',
            array(),
            wp_get_theme()->get( 'Version' )
        );
        wp_enqueue_script(
            'nbop-lightbox',
            get_template_directory_uri() . '/assets/js/lightbox.js',
            array(),
            wp_get_theme()->get( 'Version' ),
            true
        );
    }
}
add_action( 'wp_enqueue_scripts', 'nbop_enqueue_assets' );

// ── Custom Post Type: Movie Pick ─────────────────────────────

function nbop_register_movie_pick() {
    register_post_type( 'movie_pick', array(
        'labels' => array(
            'name'               => 'Movie Picks',
            'singular_name'      => 'Movie Pick',
            'add_new_item'       => 'Add New Movie Pick',
            'edit_item'          => 'Edit Movie Pick',
            'new_item'           => 'New Movie Pick',
            'view_item'          => 'View Movie Pick',
            'search_items'       => 'Search Movie Picks',
            'not_found'          => 'No movie picks found',
        ),
        'public'       => false,
        'show_ui'      => true,
        'show_in_menu' => true,
        'menu_icon'    => 'dashicons-video-alt2',
        'supports'     => array( 'title' ),
        'has_archive'  => false,
    ) );
}
add_action( 'init', 'nbop_register_movie_pick' );

// ── Movie Pick Meta Box ──────────────────────────────────────

function nbop_movie_pick_meta_boxes() {
    add_meta_box(
        'nbop_movie_details',
        'Movie Details',
        'nbop_movie_details_callback',
        'movie_pick',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'nbop_movie_pick_meta_boxes' );

function nbop_movie_details_callback( $post ) {
    wp_nonce_field( 'nbop_movie_details', 'nbop_movie_nonce' );
    $review = get_post_meta( $post->ID, '_nbop_movie_review', true );
    $rating = get_post_meta( $post->ID, '_nbop_movie_rating', true );
    ?>
    <p>
        <label for="nbop_movie_review"><strong>Review / Description:</strong></label><br>
        <textarea id="nbop_movie_review" name="nbop_movie_review" rows="4" style="width:100%;"><?php echo esc_textarea( $review ); ?></textarea>
    </p>
    <p>
        <label for="nbop_movie_rating"><strong>Rating (1-5 stars):</strong></label><br>
        <select id="nbop_movie_rating" name="nbop_movie_rating">
            <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                <option value="<?php echo $i; ?>" <?php selected( $rating, $i ); ?>><?php echo str_repeat( '&#9733;', $i ); ?></option>
            <?php endfor; ?>
        </select>
    </p>
    <?php
}

function nbop_save_movie_details( $post_id ) {
    if ( ! isset( $_POST['nbop_movie_nonce'] ) || ! wp_verify_nonce( $_POST['nbop_movie_nonce'], 'nbop_movie_details' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['nbop_movie_review'] ) ) {
        update_post_meta( $post_id, '_nbop_movie_review', sanitize_textarea_field( $_POST['nbop_movie_review'] ) );
    }
    if ( isset( $_POST['nbop_movie_rating'] ) ) {
        $rating = intval( $_POST['nbop_movie_rating'] );
        $rating = max( 1, min( 5, $rating ) );
        update_post_meta( $post_id, '_nbop_movie_rating', $rating );
    }
}
add_action( 'save_post_movie_pick', 'nbop_save_movie_details' );

// ── Helper: Get Latest Movie Pick ────────────────────────────

function nbop_get_movie_pick() {
    $picks = get_posts( array(
        'post_type'      => 'movie_pick',
        'posts_per_page' => 1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ) );

    if ( empty( $picks ) ) {
        return null;
    }

    $pick = $picks[0];
    return array(
        'title'  => $pick->post_title,
        'review' => get_post_meta( $pick->ID, '_nbop_movie_review', true ),
        'rating' => (int) get_post_meta( $pick->ID, '_nbop_movie_rating', true ),
    );
}

// ── Helper: Category Badge Class ─────────────────────────────

function nbop_category_badge_class( $category_slug ) {
    $map = array(
        'marriage'                    => 'cat-marriage',
        'children'                    => 'cat-children',
        'long-distance-relationships' => 'cat-long-distance',
        'family-finances'             => 'cat-family-finances',
    );
    return isset( $map[ $category_slug ] ) ? $map[ $category_slug ] : '';
}

// ── Excerpt Customization ────────────────────────────────────

function nbop_excerpt_length( $length ) {
    return 25;
}
add_filter( 'excerpt_length', 'nbop_excerpt_length' );

function nbop_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'nbop_excerpt_more' );

// ── Widget Areas ─────────────────────────────────────────────

function nbop_widgets_init() {
    register_sidebar( array(
        'name'          => 'Footer Widget Area',
        'id'            => 'footer-widgets',
        'before_widget' => '<div class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'nbop_widgets_init' );
```

- [ ] **Step 3: Commit**

```bash
git add wp-content/themes/newbreedofpattersons/style.css wp-content/themes/newbreedofpattersons/functions.php
git commit -m "feat: add theme foundation — style.css with full CSS and functions.php with setup, CPT, and helpers"
```

---

### Task 3: Header + Footer Templates

**Files:**
- Create: `wp-content/themes/newbreedofpattersons/header.php`
- Create: `wp-content/themes/newbreedofpattersons/footer.php`

- [ ] **Step 1: Create header.php**

Write `wp-content/themes/newbreedofpattersons/header.php`:

```php
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <div class="header-inner">
        <div class="site-branding">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                <span>New</span>Breed<span>of</span>Pattersons
            </a>
        </div>

        <button class="menu-toggle" aria-label="Toggle menu" aria-expanded="false">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>

        <nav class="primary-nav" aria-label="Primary navigation">
            <?php
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'container'      => false,
                'fallback_cb'    => false,
            ) );
            ?>
        </nav>
    </div>
</header>
```

- [ ] **Step 2: Create footer.php**

Write `wp-content/themes/newbreedofpattersons/footer.php`:

```php
<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-about">
            <div class="footer-title"><span>New</span>Breed<span>of</span>Pattersons</div>
            <p>A Christ-centered family blog sharing real stories about faith, marriage, parenting, and building a home rooted in God's love.</p>
        </div>

        <div class="footer-nav">
            <h3>Navigate</h3>
            <?php
            wp_nav_menu( array(
                'theme_location' => 'footer',
                'container'      => false,
                'fallback_cb'    => false,
            ) );
            ?>
        </div>

        <div class="footer-connect">
            <h3>Connect</h3>
            <p>We'd love to hear from you.<br>Share your story with us.</p>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; <?php echo date( 'Y' ); ?> New Breed of Pattersons. All rights reserved.</p>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
```

- [ ] **Step 3: Commit**

```bash
git add wp-content/themes/newbreedofpattersons/header.php wp-content/themes/newbreedofpattersons/footer.php
git commit -m "feat: add header and footer templates"
```

---

### Task 4: Homepage (front-page.php)

**Files:**
- Create: `wp-content/themes/newbreedofpattersons/front-page.php`

- [ ] **Step 1: Create front-page.php**

Write `wp-content/themes/newbreedofpattersons/front-page.php`:

```php
<?php get_header(); ?>

<!-- Hero -->
<section class="hero">
    <div class="hero-label">Faith &bull; Family &bull; Love</div>
    <h1>New Breed of Pattersons</h1>
    <p class="hero-description">Real stories about faith, marriage, parenting, and building a Christ-centered home — one day at a time.</p>
    <div class="hero-ctas">
        <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="btn btn-primary">Read the Blog</a>
        <?php
        $about_page = get_page_by_path( 'about' );
        if ( $about_page ) : ?>
            <a href="<?php echo esc_url( get_permalink( $about_page ) ); ?>" class="btn btn-outline">About Us</a>
        <?php endif; ?>
    </div>
</section>

<!-- Movie Pick of the Week -->
<?php $movie = nbop_get_movie_pick(); ?>
<?php if ( $movie ) : ?>
<section class="movie-pick">
    <div class="movie-pick-inner">
        <div class="movie-pick-badge">&#127916; Movie Pick</div>
        <div class="movie-pick-info">
            <div class="movie-pick-label">This Week's Family Movie</div>
            <div class="movie-pick-title"><?php echo esc_html( $movie['title'] ); ?></div>
        </div>
        <?php if ( $movie['review'] ) : ?>
            <div class="movie-pick-review"><?php echo esc_html( $movie['review'] ); ?></div>
        <?php endif; ?>
        <?php if ( $movie['rating'] ) : ?>
            <div class="movie-pick-rating"><?php echo str_repeat( '&#9733;', $movie['rating'] ) . str_repeat( '&#9734;', 5 - $movie['rating'] ); ?></div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<!-- Latest Blog Posts -->
<section class="posts-section">
    <div class="section-title">
        <h2>Latest from the Blog</h2>
        <div class="section-title-bar"></div>
    </div>

    <div class="posts-grid">
        <?php
        $latest = new WP_Query( array(
            'posts_per_page' => 6,
            'post_status'    => 'publish',
        ) );

        if ( $latest->have_posts() ) :
            while ( $latest->have_posts() ) : $latest->the_post();
                $categories = get_the_category();
                $cat_slug   = ! empty( $categories ) ? $categories[0]->slug : '';
                $cat_name   = ! empty( $categories ) ? $categories[0]->name : '';
                $badge_class = nbop_category_badge_class( $cat_slug );
        ?>
            <article class="post-card">
                <div class="post-card-image">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'nbop-card' ); ?>
                    <?php endif; ?>
                </div>
                <div class="post-card-body">
                    <?php if ( $cat_name ) : ?>
                        <span class="category-badge <?php echo esc_attr( $badge_class ); ?>"><?php echo esc_html( $cat_name ); ?></span>
                    <?php endif; ?>
                    <h3 class="post-card-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                    <p class="post-card-excerpt"><?php echo get_the_excerpt(); ?></p>
                </div>
            </article>
        <?php
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </div>
</section>

<!-- Scripture Banner -->
<section class="scripture-banner">
    <p class="scripture-text">"Train up a child in the way he should go: and when he is old, he will not depart from it."</p>
    <p class="scripture-ref">Proverbs 22:6</p>
</section>

<?php get_footer(); ?>
```

- [ ] **Step 2: Commit**

```bash
git add wp-content/themes/newbreedofpattersons/front-page.php
git commit -m "feat: add homepage with hero, movie pick, blog posts, and scripture banner"
```

---

### Task 5: Blog Archive + Category Archive + Pagination

**Files:**
- Create: `wp-content/themes/newbreedofpattersons/index.php`
- Create: `wp-content/themes/newbreedofpattersons/archive.php`

- [ ] **Step 1: Create index.php (blog archive fallback)**

Write `wp-content/themes/newbreedofpattersons/index.php`:

```php
<?php get_header(); ?>

<section class="archive-header">
    <h1>The Blog</h1>
    <p>Stories of faith, family, and love from our home to yours.</p>
</section>

<section class="posts-section">
    <div class="posts-grid">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post();
                $categories = get_the_category();
                $cat_slug   = ! empty( $categories ) ? $categories[0]->slug : '';
                $cat_name   = ! empty( $categories ) ? $categories[0]->name : '';
                $badge_class = nbop_category_badge_class( $cat_slug );
            ?>
                <article class="post-card">
                    <div class="post-card-image">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'nbop-card' ); ?>
                        <?php endif; ?>
                    </div>
                    <div class="post-card-body">
                        <?php if ( $cat_name ) : ?>
                            <span class="category-badge <?php echo esc_attr( $badge_class ); ?>"><?php echo esc_html( $cat_name ); ?></span>
                        <?php endif; ?>
                        <div class="post-card-meta"><?php echo get_the_date(); ?></div>
                        <h3 class="post-card-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        <p class="post-card-excerpt"><?php echo get_the_excerpt(); ?></p>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php else : ?>
            <p style="text-align:center;grid-column:1/-1;padding:4rem 0;color:var(--nbop-text-light);">No posts found. Check back soon!</p>
        <?php endif; ?>
    </div>

    <?php if ( have_posts() ) : ?>
    <div class="pagination">
        <?php
        the_posts_pagination( array(
            'mid_size'  => 2,
            'prev_text' => '&larr; Prev',
            'next_text' => 'Next &rarr;',
        ) );
        ?>
    </div>
    <?php endif; ?>
</section>

<?php get_footer(); ?>
```

- [ ] **Step 2: Create archive.php (category archives)**

Write `wp-content/themes/newbreedofpattersons/archive.php`:

```php
<?php get_header(); ?>

<section class="archive-header">
    <?php the_archive_title( '<h1>', '</h1>' ); ?>
    <?php the_archive_description( '<p>', '</p>' ); ?>
</section>

<section class="posts-section">
    <div class="posts-grid">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post();
                $categories = get_the_category();
                $cat_slug   = ! empty( $categories ) ? $categories[0]->slug : '';
                $cat_name   = ! empty( $categories ) ? $categories[0]->name : '';
                $badge_class = nbop_category_badge_class( $cat_slug );
            ?>
                <article class="post-card">
                    <div class="post-card-image">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'nbop-card' ); ?>
                        <?php endif; ?>
                    </div>
                    <div class="post-card-body">
                        <?php if ( $cat_name ) : ?>
                            <span class="category-badge <?php echo esc_attr( $badge_class ); ?>"><?php echo esc_html( $cat_name ); ?></span>
                        <?php endif; ?>
                        <div class="post-card-meta"><?php echo get_the_date(); ?></div>
                        <h3 class="post-card-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        <p class="post-card-excerpt"><?php echo get_the_excerpt(); ?></p>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php else : ?>
            <p style="text-align:center;grid-column:1/-1;padding:4rem 0;color:var(--nbop-text-light);">No posts found in this category yet.</p>
        <?php endif; ?>
    </div>

    <?php if ( have_posts() ) : ?>
    <div class="pagination">
        <?php
        the_posts_pagination( array(
            'mid_size'  => 2,
            'prev_text' => '&larr; Prev',
            'next_text' => 'Next &rarr;',
        ) );
        ?>
    </div>
    <?php endif; ?>
</section>

<?php get_footer(); ?>
```

- [ ] **Step 3: Commit**

```bash
git add wp-content/themes/newbreedofpattersons/index.php wp-content/themes/newbreedofpattersons/archive.php
git commit -m "feat: add blog archive and category archive templates with pagination"
```

---

### Task 6: Single Post Template

**Files:**
- Create: `wp-content/themes/newbreedofpattersons/single.php`

- [ ] **Step 1: Create single.php**

Write `wp-content/themes/newbreedofpattersons/single.php`:

```php
<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<section class="post-header">
    <?php
    $categories = get_the_category();
    if ( ! empty( $categories ) ) :
        $cat_slug   = $categories[0]->slug;
        $badge_class = nbop_category_badge_class( $cat_slug );
    ?>
        <span class="category-badge <?php echo esc_attr( $badge_class ); ?>"><?php echo esc_html( $categories[0]->name ); ?></span>
    <?php endif; ?>
    <h1><?php the_title(); ?></h1>
    <p class="post-meta"><?php echo get_the_date(); ?></p>
</section>

<?php if ( has_post_thumbnail() ) : ?>
<div class="post-featured-image">
    <?php the_post_thumbnail( 'nbop-hero' ); ?>
</div>
<?php endif; ?>

<article class="post-content">
    <?php the_content(); ?>
</article>

<nav class="post-navigation">
    <div>
        <?php previous_post_link( '%link', '&larr; %title' ); ?>
    </div>
    <div>
        <?php next_post_link( '%link', '%title &rarr;' ); ?>
    </div>
</nav>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
```

- [ ] **Step 2: Commit**

```bash
git add wp-content/themes/newbreedofpattersons/single.php
git commit -m "feat: add single post template with featured image and post navigation"
```

---

### Task 7: Page Template + About Page + 404

**Files:**
- Create: `wp-content/themes/newbreedofpattersons/page.php`
- Create: `wp-content/themes/newbreedofpattersons/templates/template-about.php`
- Create: `wp-content/themes/newbreedofpattersons/404.php`

- [ ] **Step 1: Create page.php**

Write `wp-content/themes/newbreedofpattersons/page.php`:

```php
<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<section class="page-header">
    <h1><?php the_title(); ?></h1>
</section>

<div class="page-content">
    <?php the_content(); ?>
</div>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
```

- [ ] **Step 2: Create template-about.php**

Write `wp-content/themes/newbreedofpattersons/templates/template-about.php`:

```php
<?php
/**
 * Template Name: About
 */
get_header();
?>

<section class="page-header">
    <h1>About Us</h1>
</section>

<section class="about-mission">
    <blockquote>"Our mission is to encourage and inspire families to build their homes on the unshakeable foundation of Christ's love — through honest stories, practical wisdom, and unwavering faith."</blockquote>
</section>

<div class="about-sections">
    <div class="about-section">
        <h2>Why This Blog?</h2>
        <p>New Breed of Pattersons was born from a simple truth: family life is beautiful, messy, and deeply rewarding when rooted in faith. We created this space to share the lessons we're learning — about marriage, raising children, navigating distance, and managing finances — all through the lens of a Christ-centered household.</p>
    </div>

    <div class="about-section">
        <h2>What You'll Find Here</h2>
        <p>We write about the real stuff — the joys and the struggles. From practical tips on budgeting as a family to heartfelt reflections on keeping marriage strong, every article is written with one goal: to help your family grow closer to each other and closer to God.</p>
    </div>

    <div class="about-section">
        <h2>Our Faith Foundation</h2>
        <p>We believe that God designed the family as a reflection of His love. Every word on this blog flows from that belief. We don't claim to have it all figured out — but we trust the One who does. We hope our stories encourage you on your own journey.</p>
    </div>
</div>

<section class="scripture-banner">
    <p class="scripture-text">"As for me and my house, we will serve the Lord."</p>
    <p class="scripture-ref">Joshua 24:15</p>
</section>

<?php get_footer(); ?>
```

- [ ] **Step 3: Create 404.php**

Write `wp-content/themes/newbreedofpattersons/404.php`:

```php
<?php get_header(); ?>

<section class="error-404">
    <div class="error-404-code">404</div>
    <h1>Page Not Found</h1>
    <p>Oops! The page you're looking for doesn't exist. But don't worry — there's always a way back home.</p>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-solid">Back to Home</a>
</section>

<?php get_footer(); ?>
```

- [ ] **Step 4: Commit**

```bash
git add wp-content/themes/newbreedofpattersons/page.php wp-content/themes/newbreedofpattersons/templates/template-about.php wp-content/themes/newbreedofpattersons/404.php
git commit -m "feat: add page, about, and 404 templates"
```

---

### Task 8: Gallery Page Template + Lightbox

**Files:**
- Create: `wp-content/themes/newbreedofpattersons/templates/template-gallery.php`
- Create: `wp-content/themes/newbreedofpattersons/assets/css/lightbox.css`
- Create: `wp-content/themes/newbreedofpattersons/assets/js/lightbox.js`

- [ ] **Step 1: Create template-gallery.php**

Write `wp-content/themes/newbreedofpattersons/templates/template-gallery.php`:

```php
<?php
/**
 * Template Name: Gallery
 */
get_header();
?>

<section class="page-header">
    <h1>Gallery</h1>
</section>

<div class="gallery-grid">
    <?php
    $images = get_posts( array(
        'post_type'      => 'attachment',
        'post_mime_type' => 'image',
        'post_parent'    => get_the_ID(),
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ) );

    if ( ! empty( $images ) ) :
        foreach ( $images as $image ) :
            $full_url = wp_get_attachment_url( $image->ID );
            $thumb    = wp_get_attachment_image( $image->ID, 'nbop-card', false, array(
                'class' => 'gallery-img',
                'alt'   => get_post_meta( $image->ID, '_wp_attachment_image_alt', true ),
            ) );
    ?>
        <div class="gallery-item" data-full="<?php echo esc_url( $full_url ); ?>">
            <?php echo $thumb; ?>
        </div>
    <?php
        endforeach;
    else :
    ?>
        <p style="grid-column:1/-1;text-align:center;padding:4rem 0;color:var(--nbop-text-light);">No photos yet. Upload images to this page to build your gallery!</p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
```

- [ ] **Step 2: Create lightbox.css**

Write `wp-content/themes/newbreedofpattersons/assets/css/lightbox.css`:

```css
.nbop-lightbox {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.92);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.nbop-lightbox.is-open {
    display: flex;
}

.nbop-lightbox img {
    max-width: 90vw;
    max-height: 90vh;
    object-fit: contain;
    border-radius: 4px;
    cursor: default;
}

.nbop-lightbox-close {
    position: absolute;
    top: 20px;
    right: 24px;
    background: none;
    border: none;
    color: #fff;
    font-size: 2rem;
    cursor: pointer;
    line-height: 1;
    padding: 8px;
    opacity: 0.8;
    transition: opacity 0.2s;
}

.nbop-lightbox-close:hover {
    opacity: 1;
}
```

- [ ] **Step 3: Create lightbox.js**

Write `wp-content/themes/newbreedofpattersons/assets/js/lightbox.js`:

```javascript
(function () {
    'use strict';

    // Create lightbox DOM using safe DOM methods
    var overlay = document.createElement('div');
    overlay.className = 'nbop-lightbox';

    var closeBtn = document.createElement('button');
    closeBtn.className = 'nbop-lightbox-close';
    closeBtn.setAttribute('aria-label', 'Close');
    closeBtn.textContent = '\u00D7';

    var img = document.createElement('img');
    img.src = '';
    img.alt = '';

    overlay.appendChild(closeBtn);
    overlay.appendChild(img);
    document.body.appendChild(overlay);

    function openLightbox(src, alt) {
        img.src = src;
        img.alt = alt || '';
        overlay.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        overlay.classList.remove('is-open');
        document.body.style.overflow = '';
        img.src = '';
    }

    // Click gallery items to open
    document.querySelectorAll('.gallery-item').forEach(function (item) {
        item.addEventListener('click', function () {
            var fullSrc = this.getAttribute('data-full');
            var imgEl = this.querySelector('img');
            var altText = imgEl ? imgEl.alt : '';
            openLightbox(fullSrc, altText);
        });
    });

    // Close on overlay click (not on image)
    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) {
            closeLightbox();
        }
    });

    // Close button
    closeBtn.addEventListener('click', closeLightbox);

    // Close on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeLightbox();
        }
    });
})();
```

- [ ] **Step 4: Commit**

```bash
git add wp-content/themes/newbreedofpattersons/templates/template-gallery.php wp-content/themes/newbreedofpattersons/assets/css/lightbox.css wp-content/themes/newbreedofpattersons/assets/js/lightbox.js
git commit -m "feat: add gallery template with CSS/JS lightbox"
```

---

### Task 9: Main JavaScript (Mobile Menu + Scroll Effects)

**Files:**
- Create: `wp-content/themes/newbreedofpattersons/assets/js/main.js`

- [ ] **Step 1: Create main.js**

Write `wp-content/themes/newbreedofpattersons/assets/js/main.js`:

```javascript
(function () {
    'use strict';

    // ── Mobile Menu Toggle ──────────────────────────────────
    var toggle = document.querySelector('.menu-toggle');
    var nav = document.querySelector('.primary-nav');

    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            var expanded = toggle.getAttribute('aria-expanded') === 'true';
            toggle.setAttribute('aria-expanded', !expanded);
            nav.classList.toggle('is-open');
        });

        // Close nav when a link is clicked
        nav.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                nav.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
            });
        });
    }

    // ── Sticky Header Shadow on Scroll ──────────────────────
    var header = document.querySelector('.site-header');

    if (header) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 10) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }
})();
```

- [ ] **Step 2: Commit**

```bash
git add wp-content/themes/newbreedofpattersons/assets/js/main.js
git commit -m "feat: add mobile menu toggle and scroll effects"
```

---

### Task 10: WordPress Setup — Database, Config, and Activation

This task handles the runtime WordPress setup: creating the database, writing wp-config.php with real salts, and verifying the theme activates.

- [ ] **Step 1: Create the MySQL database and user**

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS newbreedofpattersons DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -e "CREATE USER IF NOT EXISTS 'nbop_user'@'127.0.0.1' IDENTIFIED BY 'NBOP_Str0ng_P@ss2026';"
mysql -u root -e "GRANT ALL PRIVILEGES ON newbreedofpattersons.* TO 'nbop_user'@'127.0.0.1'; FLUSH PRIVILEGES;"
```

Expected: no errors.

- [ ] **Step 2: Copy WordPress core files**

```bash
cd /home/dev/repos/Websites/wordpress
for f in index.php wp-activate.php wp-blog-header.php wp-comments-post.php wp-cron.php wp-links-opml.php wp-load.php wp-login.php wp-mail.php wp-settings.php wp-signup.php wp-trackback.php xmlrpc.php wp-config-sample.php license.txt readme.html; do
    cp "PerfectLoveRestored/$f" "NewBreedofPattersons/" 2>/dev/null
done
cp -r PerfectLoveRestored/wp-admin NewBreedofPattersons/
cp -r PerfectLoveRestored/wp-includes NewBreedofPattersons/
mkdir -p NewBreedofPattersons/wp-content/uploads
cp PerfectLoveRestored/wp-content/index.php NewBreedofPattersons/wp-content/ 2>/dev/null || true
```

- [ ] **Step 3: Write wp-config.php with real salts**

Fetch salts from the WordPress salt API and write the final wp-config.php to `/home/dev/repos/Websites/wordpress/NewBreedofPattersons/wp-config.php`. The file must contain:

- DB_NAME: `newbreedofpattersons`
- DB_USER: `nbop_user`
- DB_PASSWORD: `NBOP_Str0ng_P@ss2026`
- DB_HOST: `127.0.0.1`
- DB_CHARSET: `utf8mb4`
- Real unique salts (from API or generated)
- WP_DEBUG: `true`
- Table prefix: `wp_`

- [ ] **Step 4: Set up the web server symlink (if needed)**

If serving from `/var/www/html/NewBreedofPattersons`, create a symlink:

```bash
ln -sf /home/dev/repos/Websites/wordpress/NewBreedofPattersons /var/www/html/NewBreedofPattersons
```

- [ ] **Step 5: Run the WordPress install via WP-CLI or browser**

If WP-CLI is available:

```bash
cd /home/dev/repos/Websites/wordpress/NewBreedofPattersons
wp core install --url="http://localhost/NewBreedofPattersons" --title="New Breed of Pattersons" --admin_user=admin --admin_password=admin --admin_email=admin@newbreedofpattersons.com --skip-email
```

If WP-CLI is not available, navigate to `http://localhost/NewBreedofPattersons/wp-admin/install.php` in the browser and complete the 5-minute install.

- [ ] **Step 6: Activate the theme and create categories**

```bash
cd /home/dev/repos/Websites/wordpress/NewBreedofPattersons
wp theme activate newbreedofpattersons
wp term create category "Marriage" --slug=marriage
wp term create category "Children" --slug=children
wp term create category "Long Distance Relationships" --slug=long-distance-relationships
wp term create category "Family Finances" --slug=family-finances
```

If WP-CLI is not available, do these steps via the WordPress admin dashboard:
1. Appearance > Themes > Activate "New Breed of Pattersons"
2. Posts > Categories > Add each category

- [ ] **Step 7: Create required pages**

```bash
cd /home/dev/repos/Websites/wordpress/NewBreedofPattersons
wp post create --post_type=page --post_title="About" --post_status=publish --page_template="templates/template-about.php"
wp post create --post_type=page --post_title="Gallery" --post_status=publish --page_template="templates/template-gallery.php"
wp post create --post_type=page --post_title="Blog" --post_status=publish
```

Set the front page to display a static page:

```bash
wp option update show_on_front page
wp option update page_on_front $(wp post list --post_type=page --name="front-page" --field=ID 2>/dev/null || echo "0")
wp option update page_for_posts $(wp post list --post_type=page --title="Blog" --field=ID)
```

Note: Since we use `front-page.php`, WordPress will automatically use it for the homepage regardless of the static front page setting. But setting `page_for_posts` to the Blog page ensures the "Read the Blog" CTA link works.

- [ ] **Step 8: Create a sample movie pick**

```bash
cd /home/dev/repos/Websites/wordpress/NewBreedofPattersons
MOVIE_ID=$(wp post create --post_type=movie_pick --post_title="The War Room" --post_status=publish --porcelain)
wp post meta update $MOVIE_ID _nbop_movie_review "A powerful film about the importance of prayer in marriage and family life. Perfect for family movie night!"
wp post meta update $MOVIE_ID _nbop_movie_rating 5
```

- [ ] **Step 9: Commit wp-config.php**

```bash
cd /home/dev/repos/Websites/wordpress/NewBreedofPattersons
git add wp-config.php
git commit -m "chore: add wp-config.php for NewBreedofPattersons"
```

---

### Task 11: Verification

- [ ] **Step 1: Verify theme files exist**

```bash
ls -la /home/dev/repos/Websites/wordpress/NewBreedofPattersons/wp-content/themes/newbreedofpattersons/
```

Expected: all theme files listed (style.css, functions.php, header.php, footer.php, front-page.php, index.php, archive.php, single.php, page.php, 404.php, assets/, templates/).

- [ ] **Step 2: Verify PHP syntax**

```bash
find /home/dev/repos/Websites/wordpress/NewBreedofPattersons/wp-content/themes/newbreedofpattersons -name "*.php" -exec php -l {} \;
```

Expected: "No syntax errors detected" for each file.

- [ ] **Step 3: Verify the site loads in browser**

Open `http://localhost/NewBreedofPattersons/` in a browser. Check:
1. Homepage loads with hero, movie pick banner, blog posts section, scripture banner, and footer
2. Navigation links work
3. Mobile menu toggle works (resize browser to < 768px)
4. Gallery page shows grid (upload a test image first)
5. About page shows mission content
6. 404 page shows when visiting a non-existent URL

- [ ] **Step 4: Final commit (if any fixes needed)**

```bash
git add -A wp-content/themes/newbreedofpattersons/
git commit -m "fix: address any issues found during verification"
```

Only run this if Step 3 revealed issues that needed fixing.
