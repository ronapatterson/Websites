# Ms. Isme WordPress Website Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build a personal brand WordPress website for Ms. Isme with a custom theme inspired by WellQor's design, showcasing six services across four pages.

**Architecture:** Fresh WordPress install with a custom theme (`msisme-theme`). All content is hardcoded into page templates with placeholder text/images — no dynamic WordPress content editing needed at launch. Contact Form 7 plugin handles the contact form. Site served locally via symlink from `/var/www/html/MsIsme`.

**Tech Stack:** WordPress 6.x, PHP 8.x, MySQL, HTML5, CSS3, vanilla JavaScript, Google Fonts (Playfair Display + Poppins), Contact Form 7 plugin.

**Spec:** `docs/superpowers/specs/2026-03-28-msisme-website-design.md`

---

### Task 1: WordPress Installation & Database Setup

**Files:**
- Create: `/home/dev/repos/Websites/wordpress/MsIsme/wp-config.php`
- Create: `/home/dev/repos/Websites/wordpress/MsIsme/.htaccess`

- [ ] **Step 1: Create MySQL database and user**

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS msisme CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -e "CREATE USER IF NOT EXISTS 'msisme_user'@'127.0.0.1' IDENTIFIED BY 'MsIsme_Str0ng_P@ss2026';"
mysql -u root -e "GRANT ALL PRIVILEGES ON msisme.* TO 'msisme_user'@'127.0.0.1';"
mysql -u root -e "FLUSH PRIVILEGES;"
```

- [ ] **Step 2: Download and extract WordPress**

```bash
cd /home/dev/repos/Websites/wordpress/MsIsme
curl -O https://wordpress.org/latest.tar.gz
tar -xzf latest.tar.gz --strip-components=1
rm latest.tar.gz
```

- [ ] **Step 3: Create wp-config.php**

Copy `wp-config-sample.php` to `wp-config.php` and set these values:

```php
define( 'DB_NAME', 'msisme' );
define( 'DB_USER', 'msisme_user' );
define( 'DB_PASSWORD', 'MsIsme_Str0ng_P@ss2026' );
define( 'DB_HOST', '127.0.0.1' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );
```

Generate fresh salts from `https://api.wordpress.org/secret-key/1.1/salt/` and paste them in.

Set `$table_prefix = 'mi_';`

Add before "stop editing" line:
```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
```

- [ ] **Step 4: Create .htaccess**

```apache
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /MsIsme/
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /MsIsme/index.php [L]
</IfModule>
# END WordPress
```

- [ ] **Step 5: Create symlink for local serving**

```bash
ln -s /home/dev/repos/Websites/wordpress/MsIsme /var/www/html/MsIsme
```

- [ ] **Step 6: Run WordPress install via WP-CLI or browser**

```bash
wp core install --url="http://localhost/MsIsme" --title="Ms. Isme" --admin_user="admin" --admin_password="admin123" --admin_email="admin@msisme.local" --path="/home/dev/repos/Websites/wordpress/MsIsme" --skip-email
```

If WP-CLI is not available, navigate to `http://localhost/MsIsme/` in a browser and complete the install wizard with:
- Site Title: Ms. Isme
- Username: admin
- Password: admin123
- Email: admin@msisme.local

- [ ] **Step 7: Verify WordPress loads**

Visit `http://localhost/MsIsme/` — should see the default WordPress site.

- [ ] **Step 8: Commit**

```bash
git init /home/dev/repos/Websites/wordpress/MsIsme
cd /home/dev/repos/Websites/wordpress/MsIsme
echo "*.tar.gz" >> .gitignore
echo "wp-config.php" >> .gitignore
git add .gitignore docs/
git commit -m "feat: initialize MsIsme project with specs and plans"
```

---

### Task 2: Theme Scaffolding — style.css & functions.php

**Files:**
- Create: `wp-content/themes/msisme-theme/style.css`
- Create: `wp-content/themes/msisme-theme/functions.php`
- Create: `wp-content/themes/msisme-theme/index.php`

- [ ] **Step 1: Create theme directory**

```bash
mkdir -p /home/dev/repos/Websites/wordpress/MsIsme/wp-content/themes/msisme-theme/assets/{css,js,images}
```

- [ ] **Step 2: Create style.css with theme metadata and full styles**

Create `wp-content/themes/msisme-theme/style.css` with:

```css
/*
Theme Name: Ms. Isme
Theme URI: http://localhost/MsIsme
Author: Ms. Isme
Description: A personal brand theme for Ms. Isme — Count It All Joy
Version: 1.0
License: GNU General Public License v2 or later
Text Domain: msisme-theme
*/

/* ========== CSS RESET & BASE ========== */
*,
*::before,
*::after {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

:root {
    --color-copper: #A0622E;
    --color-dark-copper: #7A4A1F;
    --color-cream: #FFF8F0;
    --color-burgundy: #8B2D4F;
    --color-gold: #D4A853;
    --color-warm-white: #FFFDF9;
    --color-dark-text: #2D1B0E;
    --color-burgundy-hover: #6E2340;
    --color-copper-light: rgba(160, 98, 46, 0.1);
    --color-gold-light: rgba(212, 168, 83, 0.3);
    --font-heading: 'Playfair Display', Georgia, serif;
    --font-body: 'Poppins', Arial, sans-serif;
    --max-width: 1200px;
    --section-padding: 100px 0;
    --border-radius: 50px;
}

html {
    scroll-behavior: smooth;
}

body {
    font-family: var(--font-body);
    font-size: 16px;
    line-height: 1.7;
    color: var(--color-dark-text);
    background-color: var(--color-cream);
    -webkit-font-smoothing: antialiased;
}

h1, h2, h3, h4, h5, h6 {
    font-family: var(--font-heading);
    color: var(--color-dark-copper);
    line-height: 1.3;
}

h1 { font-size: 3.5rem; }
h2 { font-size: 2.5rem; }
h3 { font-size: 1.5rem; }

p { margin-bottom: 1rem; }

a {
    color: var(--color-copper);
    text-decoration: none;
    transition: color 0.3s ease;
}

a:hover {
    color: var(--color-burgundy);
}

img {
    max-width: 100%;
    height: auto;
    display: block;
}

.container {
    max-width: var(--max-width);
    margin: 0 auto;
    padding: 0 20px;
}

/* ========== BUTTONS ========== */
.btn {
    display: inline-block;
    padding: 14px 36px;
    border-radius: var(--border-radius);
    font-family: var(--font-body);
    font-size: 1rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    letter-spacing: 0.5px;
}

.btn-primary {
    background-color: var(--color-burgundy);
    color: #fff;
}

.btn-primary:hover {
    background-color: var(--color-burgundy-hover);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(139, 45, 79, 0.3);
}

.btn-secondary {
    background-color: transparent;
    color: var(--color-burgundy);
    border: 2px solid var(--color-burgundy);
}

.btn-secondary:hover {
    background-color: var(--color-burgundy);
    color: #fff;
    transform: translateY(-2px);
}

.btn-light {
    background-color: var(--color-cream);
    color: var(--color-burgundy);
}

.btn-light:hover {
    background-color: #fff;
    color: var(--color-burgundy);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

/* ========== HEADER ========== */
.site-header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
    background-color: var(--color-warm-white);
    box-shadow: 0 2px 20px rgba(45, 27, 14, 0.08);
    transition: all 0.3s ease;
}

.header-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px 20px;
    max-width: var(--max-width);
    margin: 0 auto;
}

.site-logo img {
    height: 50px;
    width: auto;
}

.main-nav ul {
    display: flex;
    list-style: none;
    gap: 35px;
}

.main-nav a {
    font-family: var(--font-body);
    font-size: 0.95rem;
    font-weight: 500;
    color: var(--color-dark-text);
    text-decoration: none;
    position: relative;
    padding: 5px 0;
    transition: color 0.3s ease;
}

.main-nav a::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background-color: var(--color-copper);
    transition: width 0.3s ease;
}

.main-nav a:hover,
.main-nav .current-menu-item a {
    color: var(--color-copper);
}

.main-nav a:hover::after,
.main-nav .current-menu-item a::after {
    width: 100%;
}

.header-cta .btn {
    padding: 10px 28px;
    font-size: 0.9rem;
}

/* Mobile menu toggle */
.menu-toggle {
    display: none;
    background: none;
    border: none;
    cursor: pointer;
    padding: 5px;
}

.menu-toggle span {
    display: block;
    width: 25px;
    height: 3px;
    background-color: var(--color-dark-text);
    margin: 5px 0;
    transition: all 0.3s ease;
    border-radius: 2px;
}

.menu-toggle.active span:nth-child(1) {
    transform: rotate(45deg) translate(5px, 6px);
}

.menu-toggle.active span:nth-child(2) {
    opacity: 0;
}

.menu-toggle.active span:nth-child(3) {
    transform: rotate(-45deg) translate(5px, -6px);
}

/* ========== HERO SECTION ========== */
.hero {
    padding: 180px 0 120px;
    background: linear-gradient(135deg, var(--color-cream) 0%, rgba(160, 98, 46, 0.08) 50%, rgba(212, 168, 83, 0.12) 100%);
    text-align: center;
    position: relative;
    overflow: hidden;
}

.hero::before,
.hero::after {
    content: '';
    position: absolute;
    width: 300px;
    height: 300px;
    border-radius: 50%;
    background: var(--color-gold-light);
    opacity: 0.3;
}

.hero::before {
    top: -100px;
    right: -100px;
}

.hero::after {
    bottom: -100px;
    left: -100px;
}

.hero h1 {
    font-size: 4.5rem;
    color: var(--color-dark-copper);
    margin-bottom: 20px;
    font-style: italic;
}

.hero .subtitle {
    font-size: 1.25rem;
    color: var(--color-copper);
    max-width: 600px;
    margin: 0 auto 40px;
    font-weight: 300;
}

.hero .decorative-line {
    width: 80px;
    height: 3px;
    background: var(--color-gold);
    margin: 0 auto 30px;
    border-radius: 2px;
}

/* ========== SECTION STYLES ========== */
.section {
    padding: var(--section-padding);
}

.section-light {
    background-color: var(--color-warm-white);
}

.section-cream {
    background-color: var(--color-cream);
}

.section-title {
    text-align: center;
    margin-bottom: 60px;
}

.section-title h2 {
    margin-bottom: 15px;
}

.section-title .decorative-line {
    width: 60px;
    height: 3px;
    background: var(--color-gold);
    margin: 0 auto;
    border-radius: 2px;
}

/* ========== SERVICES GRID ========== */
.services-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

.service-card {
    background: var(--color-warm-white);
    border: 1px solid var(--color-gold-light);
    border-radius: 16px;
    padding: 40px 30px;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.service-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, var(--color-copper), var(--color-gold));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(160, 98, 46, 0.12);
    border-color: var(--color-gold);
}

.service-card:hover::before {
    opacity: 1;
}

.service-card .icon {
    font-size: 2.5rem;
    margin-bottom: 20px;
    display: block;
}

.service-card h3 {
    margin-bottom: 12px;
    font-size: 1.3rem;
}

.service-card p {
    font-size: 0.95rem;
    color: #666;
    line-height: 1.6;
}

/* ========== ABOUT PREVIEW ========== */
.about-preview {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 80px;
    align-items: center;
}

.about-preview .image-side {
    position: relative;
}

.about-preview .image-side img {
    border-radius: 16px;
    width: 100%;
    height: 500px;
    object-fit: cover;
}

.about-preview .image-side::after {
    content: '';
    position: absolute;
    bottom: -15px;
    right: -15px;
    width: 100%;
    height: 100%;
    border: 3px solid var(--color-gold);
    border-radius: 16px;
    z-index: -1;
}

.about-preview .text-side h2 {
    margin-bottom: 20px;
}

.about-preview .text-side p {
    margin-bottom: 15px;
    color: #555;
}

.about-preview .text-side .btn {
    margin-top: 15px;
}

/* ========== TESTIMONIALS ========== */
.testimonials {
    text-align: center;
}

.testimonial-carousel {
    position: relative;
    max-width: 800px;
    margin: 0 auto;
    overflow: hidden;
}

.testimonial-track {
    display: flex;
    transition: transform 0.5s ease;
}

.testimonial-slide {
    min-width: 100%;
    padding: 40px;
}

.testimonial-slide .quote {
    font-family: var(--font-heading);
    font-size: 1.3rem;
    font-style: italic;
    color: var(--color-dark-copper);
    line-height: 1.8;
    margin-bottom: 25px;
    position: relative;
}

.testimonial-slide .quote::before {
    content: '\201C';
    font-size: 4rem;
    color: var(--color-gold);
    position: absolute;
    top: -20px;
    left: -10px;
    font-family: var(--font-heading);
    line-height: 1;
}

.testimonial-slide .author {
    font-weight: 600;
    color: var(--color-copper);
}

.testimonial-slide .role {
    font-size: 0.9rem;
    color: #888;
}

.carousel-dots {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 30px;
}

.carousel-dots .dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--color-gold-light);
    border: none;
    cursor: pointer;
    transition: background 0.3s ease;
}

.carousel-dots .dot.active {
    background: var(--color-copper);
}

/* ========== CTA BANNER ========== */
.cta-banner {
    background: linear-gradient(135deg, var(--color-burgundy) 0%, var(--color-dark-copper) 100%);
    padding: 80px 0;
    text-align: center;
}

.cta-banner h2 {
    color: #fff;
    margin-bottom: 15px;
}

.cta-banner p {
    color: rgba(255, 255, 255, 0.85);
    font-size: 1.1rem;
    margin-bottom: 30px;
}

/* ========== PAGE HERO ========== */
.page-hero {
    padding: 160px 0 80px;
    background: linear-gradient(135deg, var(--color-cream) 0%, rgba(160, 98, 46, 0.1) 100%);
    text-align: center;
}

.page-hero h1 {
    font-size: 3.5rem;
    margin-bottom: 15px;
}

.page-hero p {
    font-size: 1.15rem;
    color: var(--color-copper);
    max-width: 600px;
    margin: 0 auto;
}

/* ========== SERVICES PAGE — ALTERNATING ROWS ========== */
.service-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 80px;
    align-items: center;
    padding: 80px 0;
    border-bottom: 1px solid rgba(212, 168, 83, 0.2);
}

.service-row:last-child {
    border-bottom: none;
}

.service-row.reverse .service-image {
    order: 2;
}

.service-row.reverse .service-text {
    order: 1;
}

.service-image img {
    border-radius: 16px;
    width: 100%;
    height: 380px;
    object-fit: cover;
}

.service-text h3 {
    font-size: 1.8rem;
    margin-bottom: 15px;
}

.service-text p {
    color: #555;
    margin-bottom: 25px;
    font-size: 1.05rem;
}

/* ========== ABOUT PAGE ========== */
.about-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 80px;
    align-items: center;
    padding: 80px 0;
}

.about-photo img {
    border-radius: 16px;
    width: 100%;
    height: 550px;
    object-fit: cover;
}

.about-bio h2 {
    margin-bottom: 20px;
}

.about-bio p {
    color: #555;
    margin-bottom: 15px;
}

.philosophy-section {
    text-align: center;
    padding: 80px 0;
    max-width: 800px;
    margin: 0 auto;
}

.philosophy-section h2 {
    font-style: italic;
    margin-bottom: 25px;
}

.philosophy-section p {
    font-size: 1.1rem;
    color: #555;
    line-height: 1.8;
}

/* ========== CONTACT PAGE ========== */
.contact-content {
    display: grid;
    grid-template-columns: 1.2fr 0.8fr;
    gap: 80px;
    padding: 80px 0;
}

.contact-form-wrapper h2 {
    margin-bottom: 30px;
}

/* Contact Form 7 Overrides */
.contact-form-wrapper .wpcf7-form p {
    margin-bottom: 20px;
}

.contact-form-wrapper .wpcf7-form label {
    display: block;
    font-weight: 500;
    margin-bottom: 8px;
    color: var(--color-dark-copper);
    font-size: 0.95rem;
}

.contact-form-wrapper .wpcf7-form input[type="text"],
.contact-form-wrapper .wpcf7-form input[type="email"],
.contact-form-wrapper .wpcf7-form input[type="tel"],
.contact-form-wrapper .wpcf7-form textarea,
.contact-form-wrapper .wpcf7-form select {
    width: 100%;
    padding: 14px 18px;
    border: 1px solid rgba(212, 168, 83, 0.4);
    border-radius: 10px;
    font-family: var(--font-body);
    font-size: 1rem;
    background: var(--color-warm-white);
    color: var(--color-dark-text);
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.contact-form-wrapper .wpcf7-form input:focus,
.contact-form-wrapper .wpcf7-form textarea:focus,
.contact-form-wrapper .wpcf7-form select:focus {
    outline: none;
    border-color: var(--color-copper);
    box-shadow: 0 0 0 3px var(--color-copper-light);
}

.contact-form-wrapper .wpcf7-form textarea {
    min-height: 150px;
    resize: vertical;
}

.contact-form-wrapper .wpcf7-form input[type="submit"] {
    background-color: var(--color-burgundy);
    color: #fff;
    padding: 14px 40px;
    border: none;
    border-radius: var(--border-radius);
    font-family: var(--font-body);
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.contact-form-wrapper .wpcf7-form input[type="submit"]:hover {
    background-color: var(--color-burgundy-hover);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(139, 45, 79, 0.3);
}

/* Fallback form styles (when CF7 not active) */
.fallback-form label {
    display: block;
    font-weight: 500;
    margin-bottom: 8px;
    color: var(--color-dark-copper);
    font-size: 0.95rem;
}

.fallback-form .form-group {
    margin-bottom: 20px;
}

.fallback-form input[type="text"],
.fallback-form input[type="email"],
.fallback-form input[type="tel"],
.fallback-form textarea,
.fallback-form select {
    width: 100%;
    padding: 14px 18px;
    border: 1px solid rgba(212, 168, 83, 0.4);
    border-radius: 10px;
    font-family: var(--font-body);
    font-size: 1rem;
    background: var(--color-warm-white);
    color: var(--color-dark-text);
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.fallback-form input:focus,
.fallback-form textarea:focus,
.fallback-form select:focus {
    outline: none;
    border-color: var(--color-copper);
    box-shadow: 0 0 0 3px var(--color-copper-light);
}

.fallback-form textarea {
    min-height: 150px;
    resize: vertical;
}

.fallback-form button[type="submit"] {
    background-color: var(--color-burgundy);
    color: #fff;
    padding: 14px 40px;
    border: none;
    border-radius: var(--border-radius);
    font-family: var(--font-body);
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.fallback-form button[type="submit"]:hover {
    background-color: var(--color-burgundy-hover);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(139, 45, 79, 0.3);
}

.contact-info-side h3 {
    margin-bottom: 20px;
    font-size: 1.5rem;
}

.contact-info-side p {
    color: #555;
    margin-bottom: 30px;
    font-size: 1.05rem;
}

.contact-details {
    list-style: none;
    margin-bottom: 30px;
}

.contact-details li {
    padding: 12px 0;
    border-bottom: 1px solid rgba(212, 168, 83, 0.2);
    display: flex;
    align-items: center;
    gap: 12px;
}

.contact-details li:last-child {
    border-bottom: none;
}

.contact-details .detail-icon {
    font-size: 1.2rem;
}

.social-links {
    display: flex;
    gap: 15px;
}

.social-links a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: var(--color-copper-light);
    color: var(--color-copper);
    transition: all 0.3s ease;
    font-size: 1.1rem;
}

.social-links a:hover {
    background: var(--color-copper);
    color: #fff;
    transform: translateY(-3px);
}

/* ========== FOOTER ========== */
.site-footer {
    background-color: var(--color-dark-copper);
    color: rgba(255, 255, 255, 0.8);
    padding: 60px 0 30px;
}

.footer-inner {
    display: grid;
    grid-template-columns: 1.5fr 1fr 1fr;
    gap: 60px;
    margin-bottom: 40px;
}

.footer-brand .footer-logo img {
    height: 60px;
    margin-bottom: 15px;
    filter: brightness(0) invert(1);
}

.footer-brand .tagline {
    font-family: var(--font-heading);
    font-style: italic;
    color: var(--color-gold);
    font-size: 1.1rem;
    margin-bottom: 15px;
}

.footer-brand p {
    font-size: 0.9rem;
    line-height: 1.7;
}

.footer-links h4,
.footer-contact h4 {
    color: #fff;
    font-family: var(--font-body);
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 20px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.footer-links ul {
    list-style: none;
}

.footer-links li {
    margin-bottom: 10px;
}

.footer-links a {
    color: rgba(255, 255, 255, 0.7);
    transition: color 0.3s ease;
    font-size: 0.95rem;
}

.footer-links a:hover {
    color: var(--color-gold);
}

.footer-contact p {
    margin-bottom: 10px;
    font-size: 0.95rem;
}

.footer-contact .social-links a {
    background: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.8);
}

.footer-contact .social-links a:hover {
    background: var(--color-gold);
    color: var(--color-dark-copper);
}

.footer-bottom {
    border-top: 1px solid rgba(255, 255, 255, 0.15);
    padding-top: 25px;
    text-align: center;
    font-size: 0.85rem;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 968px) {
    h1 { font-size: 2.5rem; }
    h2 { font-size: 2rem; }

    .hero { padding: 140px 0 80px; }
    .hero h1 { font-size: 3rem; }

    .services-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .about-preview,
    .about-content,
    .service-row,
    .contact-content {
        grid-template-columns: 1fr;
        gap: 40px;
    }

    .service-row.reverse .service-image,
    .service-row.reverse .service-text {
        order: unset;
    }

    .about-preview .image-side::after {
        display: none;
    }

    .footer-inner {
        grid-template-columns: 1fr;
        gap: 30px;
    }
}

@media (max-width: 768px) {
    .header-inner {
        flex-wrap: wrap;
    }

    .main-nav {
        display: none;
        width: 100%;
        order: 3;
        padding: 20px 0;
    }

    .main-nav.active {
        display: block;
    }

    .main-nav ul {
        flex-direction: column;
        gap: 0;
    }

    .main-nav li {
        border-bottom: 1px solid rgba(212, 168, 83, 0.2);
    }

    .main-nav a {
        display: block;
        padding: 12px 0;
    }

    .menu-toggle {
        display: block;
    }

    .header-cta {
        display: none;
    }

    .hero h1 { font-size: 2.5rem; }
    .page-hero h1 { font-size: 2.5rem; }

    .services-grid {
        grid-template-columns: 1fr;
    }

    .section { padding: 60px 0; }
}
```

- [ ] **Step 3: Create functions.php**

Create `wp-content/themes/msisme-theme/functions.php`:

```php
<?php
/**
 * Ms. Isme Theme Functions
 */

// Theme Setup
function msisme_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    register_nav_menus(array(
        'primary'   => __('Primary Menu', 'msisme-theme'),
        'footer'    => __('Footer Menu', 'msisme-theme'),
    ));
}
add_action('after_setup_theme', 'msisme_theme_setup');

// Enqueue Styles & Scripts
function msisme_enqueue_assets() {
    // Google Fonts
    wp_enqueue_style(
        'msisme-google-fonts',
        'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Poppins:wght@300;400;500;600;700&display=swap',
        array(),
        null
    );

    // Theme stylesheet
    wp_enqueue_style('msisme-style', get_stylesheet_uri(), array('msisme-google-fonts'), '1.0');

    // Theme JavaScript
    wp_enqueue_script('msisme-script', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0', true);
}
add_action('wp_enqueue_scripts', 'msisme_enqueue_assets');

// Add body class for page templates
function msisme_body_classes($classes) {
    if (is_front_page()) {
        $classes[] = 'home-page';
    }
    if (is_page('about')) {
        $classes[] = 'about-page';
    }
    if (is_page('services')) {
        $classes[] = 'services-page';
    }
    if (is_page('contact')) {
        $classes[] = 'contact-page';
    }
    return $classes;
}
add_filter('body_class', 'msisme_body_classes');
```

- [ ] **Step 4: Create index.php (fallback template)**

Create `wp-content/themes/msisme-theme/index.php`:

```php
<?php get_header(); ?>

<main>
    <section class="page-hero">
        <div class="container">
            <h1><?php the_title(); ?></h1>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <?php
            while (have_posts()) :
                the_post();
                the_content();
            endwhile;
            ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
```

- [ ] **Step 5: Activate theme and verify**

```bash
wp theme activate msisme-theme --path="/home/dev/repos/Websites/wordpress/MsIsme"
```

Visit `http://localhost/MsIsme/` — should see a blank page with no errors (no header/footer yet).

- [ ] **Step 6: Commit**

```bash
cd /home/dev/repos/Websites/wordpress/MsIsme
git add wp-content/themes/msisme-theme/
git commit -m "feat: add msisme-theme scaffolding with styles, functions, and index"
```

---

### Task 3: Header Template

**Files:**
- Create: `wp-content/themes/msisme-theme/header.php`

- [ ] **Step 1: Create header.php**

Create `wp-content/themes/msisme-theme/header.php`:

```php
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <div class="header-inner">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo">
            <?php if (has_custom_logo()) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/logo.png" alt="<?php bloginfo('name'); ?>">
            <?php endif; ?>
        </a>

        <button class="menu-toggle" aria-label="Toggle Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <nav class="main-nav">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'container'      => false,
                'fallback_cb'    => 'msisme_fallback_menu',
            ));
            ?>
        </nav>

        <div class="header-cta">
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-primary">Book a Consultation</a>
        </div>
    </div>
</header>
```

- [ ] **Step 2: Add fallback menu function to functions.php**

Append to `wp-content/themes/msisme-theme/functions.php`:

```php

// Fallback menu if no menu is assigned
function msisme_fallback_menu() {
    echo '<ul>';
    echo '<li><a href="' . esc_url(home_url('/')) . '">Home</a></li>';
    echo '<li><a href="' . esc_url(get_permalink(get_page_by_path('about'))) . '">About</a></li>';
    echo '<li><a href="' . esc_url(get_permalink(get_page_by_path('services'))) . '">Services</a></li>';
    echo '<li><a href="' . esc_url(get_permalink(get_page_by_path('contact'))) . '">Contact</a></li>';
    echo '</ul>';
}
```

- [ ] **Step 3: Verify header renders**

Visit `http://localhost/MsIsme/` — should see the sticky header with logo area, navigation links, and CTA button.

- [ ] **Step 4: Commit**

```bash
cd /home/dev/repos/Websites/wordpress/MsIsme
git add wp-content/themes/msisme-theme/header.php wp-content/themes/msisme-theme/functions.php
git commit -m "feat: add header template with sticky nav and mobile toggle"
```

---

### Task 4: Footer Template

**Files:**
- Create: `wp-content/themes/msisme-theme/footer.php`

- [ ] **Step 1: Create footer.php**

Create `wp-content/themes/msisme-theme/footer.php`:

```php
<footer class="site-footer">
    <div class="container">
        <div class="footer-inner">
            <div class="footer-brand">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-logo">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/logo.png" alt="<?php bloginfo('name'); ?>">
                </a>
                <p class="tagline">Count It All Joy</p>
                <p>Bringing warmth, wisdom, and purpose to every moment. Your journey matters, and I'm here to walk alongside you.</p>
            </div>

            <div class="footer-links">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
                    <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('about'))); ?>">About</a></li>
                    <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>">Services</a></li>
                    <li><a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>">Contact</a></li>
                </ul>
            </div>

            <div class="footer-contact">
                <h4>Get In Touch</h4>
                <p>Email: hello@msisme.com</p>
                <p>Phone: (555) 123-4567</p>
                <div class="social-links" style="margin-top: 15px;">
                    <a href="#" aria-label="Facebook">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                    </a>
                    <a href="#" aria-label="Instagram">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                    </a>
                    <a href="#" aria-label="Twitter">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <a href="#" aria-label="LinkedIn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> Ms. Isme. All rights reserved.</p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
```

- [ ] **Step 2: Verify footer renders**

Visit `http://localhost/MsIsme/` — should see the full footer with brand, quick links, and contact info.

- [ ] **Step 3: Commit**

```bash
cd /home/dev/repos/Websites/wordpress/MsIsme
git add wp-content/themes/msisme-theme/footer.php
git commit -m "feat: add footer template with brand, links, and social icons"
```

---

### Task 5: Front Page Template (Homepage)

**Files:**
- Create: `wp-content/themes/msisme-theme/front-page.php`

- [ ] **Step 1: Create front-page.php**

Create `wp-content/themes/msisme-theme/front-page.php`:

```php
<?php get_header(); ?>

<main>
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="decorative-line"></div>
            <h1>Count It All Joy</h1>
            <p class="subtitle">Bringing warmth, wisdom, and purpose to every moment</p>
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('services'))); ?>" class="btn btn-primary">Explore Services</a>
        </div>
    </section>

    <!-- Services Preview -->
    <section class="section section-light">
        <div class="container">
            <div class="section-title">
                <h2>How I Can Help</h2>
                <div class="decorative-line"></div>
            </div>
            <div class="services-grid">
                <div class="service-card">
                    <span class="icon">&#127881;</span>
                    <h3>Event Planning</h3>
                    <p>Crafting memorable events from concept to execution, ensuring every detail reflects your vision.</p>
                </div>
                <div class="service-card">
                    <span class="icon">&#127908;</span>
                    <h3>Event MC</h3>
                    <p>Energizing and hosting events with warmth, humor, and professionalism that keeps your guests engaged.</p>
                </div>
                <div class="service-card">
                    <span class="icon">&#127897;</span>
                    <h3>Speaking Engagement</h3>
                    <p>Inspiring audiences with purpose-driven messages that resonate and create lasting impact.</p>
                </div>
                <div class="service-card">
                    <span class="icon">&#128141;</span>
                    <h3>Marriage Pastor</h3>
                    <p>Officiating ceremonies with heart, meaning, and a personal touch that celebrates your love story.</p>
                </div>
                <div class="service-card">
                    <span class="icon">&#128156;</span>
                    <h3>Counseling</h3>
                    <p>Guiding individuals and couples through life's challenges with compassion and practical wisdom.</p>
                </div>
                <div class="service-card">
                    <span class="icon">&#128241;</span>
                    <h3>Social Media Marketing</h3>
                    <p>Building authentic online presence and engagement that connects with your audience.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Preview -->
    <section class="section section-cream">
        <div class="container">
            <div class="about-preview">
                <div class="image-side">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/about-placeholder.jpg" alt="Ms. Isme">
                </div>
                <div class="text-side">
                    <h2>Meet Ms. Isme</h2>
                    <p>With a heart for people and a passion for purpose, Ms. Isme brings joy, wisdom, and authenticity to everything she does. Whether planning your dream event, speaking to inspire, or walking alongside you through life's journey — she's here for you.</p>
                    <p>Her philosophy is simple: count it all joy. Every challenge, every celebration, every moment of growth is an opportunity to find meaning and beauty.</p>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('about'))); ?>" class="btn btn-secondary">Learn More</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="section section-light testimonials">
        <div class="container">
            <div class="section-title">
                <h2>Kind Words</h2>
                <div class="decorative-line"></div>
            </div>
            <div class="testimonial-carousel">
                <div class="testimonial-track">
                    <div class="testimonial-slide">
                        <p class="quote">Ms. Isme brought such warmth and joy to our wedding ceremony. She made it personal, meaningful, and unforgettable. We couldn't have asked for a better pastor.</p>
                        <p class="author">Sarah & James</p>
                        <p class="role">Wedding Ceremony</p>
                    </div>
                    <div class="testimonial-slide">
                        <p class="quote">Her energy as an MC is unmatched. She kept our entire corporate event flowing smoothly while making everyone feel welcome and engaged. Truly a professional.</p>
                        <p class="author">Michael T.</p>
                        <p class="role">Corporate Event</p>
                    </div>
                    <div class="testimonial-slide">
                        <p class="quote">The counseling sessions with Ms. Isme changed my perspective on so many things. Her wisdom and compassion created a safe space for real growth and healing.</p>
                        <p class="author">Ari W.</p>
                        <p class="role">Counseling Client</p>
                    </div>
                </div>
                <div class="carousel-dots">
                    <button class="dot active" data-index="0" aria-label="Testimonial 1"></button>
                    <button class="dot" data-index="1" aria-label="Testimonial 2"></button>
                    <button class="dot" data-index="2" aria-label="Testimonial 3"></button>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Banner -->
    <section class="cta-banner">
        <div class="container">
            <h2>Ready to Get Started?</h2>
            <p>Let's create something beautiful together. Reach out today and let's talk about how I can serve you.</p>
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-light">Book a Consultation</a>
        </div>
    </section>
</main>

<?php get_footer(); ?>
```

- [ ] **Step 2: Create placeholder image for about section**

Generate a placeholder image using a solid color block:

```bash
convert -size 600x500 xc:'#D4A853' /home/dev/repos/Websites/wordpress/MsIsme/wp-content/themes/msisme-theme/assets/images/about-placeholder.jpg 2>/dev/null || python3 -c "
from PIL import Image
img = Image.new('RGB', (600, 500), (212, 168, 83))
img.save('/home/dev/repos/Websites/wordpress/MsIsme/wp-content/themes/msisme-theme/assets/images/about-placeholder.jpg')
" 2>/dev/null || echo "Placeholder images will need to be added manually"
```

If neither tool is available, create a simple SVG placeholder instead:

```bash
cat > /home/dev/repos/Websites/wordpress/MsIsme/wp-content/themes/msisme-theme/assets/images/about-placeholder.svg << 'SVGEOF'
<svg width="600" height="500" xmlns="http://www.w3.org/2000/svg">
  <rect width="600" height="500" fill="#D4A853" rx="16"/>
  <text x="300" y="250" text-anchor="middle" fill="#7A4A1F" font-family="sans-serif" font-size="24">Photo Placeholder</text>
</svg>
SVGEOF
```

Update the `front-page.php` image reference to use `.svg` if `.jpg` generation failed.

- [ ] **Step 3: Verify homepage renders**

Visit `http://localhost/MsIsme/` — should see the full homepage with hero, services grid, about preview, testimonials, and CTA banner.

- [ ] **Step 4: Commit**

```bash
cd /home/dev/repos/Websites/wordpress/MsIsme
git add wp-content/themes/msisme-theme/front-page.php wp-content/themes/msisme-theme/assets/
git commit -m "feat: add front page template with hero, services, about, testimonials, CTA"
```

---

### Task 6: Page Templates — About, Services, Contact

**Files:**
- Create: `wp-content/themes/msisme-theme/page.php`
- Create: `wp-content/themes/msisme-theme/page-about.php`
- Create: `wp-content/themes/msisme-theme/page-services.php`
- Create: `wp-content/themes/msisme-theme/page-contact.php`

- [ ] **Step 1: Create page.php (generic page template)**

Create `wp-content/themes/msisme-theme/page.php`:

```php
<?php get_header(); ?>

<main>
    <section class="page-hero">
        <div class="container">
            <h1><?php the_title(); ?></h1>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <?php
            while (have_posts()) :
                the_post();
                the_content();
            endwhile;
            ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
```

- [ ] **Step 2: Create page-about.php**

Create `wp-content/themes/msisme-theme/page-about.php`:

```php
<?php get_header(); ?>

<main>
    <section class="page-hero">
        <div class="container">
            <h1>About Ms. Isme</h1>
            <p>The heart behind the mission</p>
        </div>
    </section>

    <section class="section section-light">
        <div class="container">
            <div class="about-content">
                <div class="about-photo">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/about-placeholder.jpg" alt="Ms. Isme">
                </div>
                <div class="about-bio">
                    <h2>Hello, I'm Ms. Isme</h2>
                    <p>With years of experience serving communities through events, counseling, and ministry, I have made it my life's work to uplift, inspire, and bring joy to those around me.</p>
                    <p>My journey has taken me from planning intimate gatherings to hosting large-scale events, from one-on-one counseling sessions to speaking before hundreds. Through it all, one truth has remained constant: joy is a choice, and it is available to each of us.</p>
                    <p>I believe in the power of connection, the beauty of celebration, and the strength that comes from walking through life's challenges with purpose and faith.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section section-cream">
        <div class="container">
            <div class="philosophy-section">
                <h2>"Count It All Joy"</h2>
                <div class="decorative-line" style="margin: 0 auto 30px;"></div>
                <p>This isn't just a tagline — it's a way of life. Inspired by the timeless wisdom of James 1:2, "Count it all joy" reminds us that every season has purpose. Whether you're celebrating a milestone, navigating a transition, or building something new, there is joy to be found in the journey.</p>
                <p>This philosophy is the foundation of everything I do. It shapes how I plan events, how I counsel, how I speak, and how I show up for the people I serve. My goal is to help you find that joy in your own story.</p>
            </div>
        </div>
    </section>

    <section class="cta-banner">
        <div class="container">
            <h2>Let's Work Together</h2>
            <p>Ready to start your journey? I'd love to hear from you.</p>
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-light">Get In Touch</a>
        </div>
    </section>
</main>

<?php get_footer(); ?>
```

- [ ] **Step 3: Create page-services.php**

Create `wp-content/themes/msisme-theme/page-services.php`:

```php
<?php get_header(); ?>

<main>
    <section class="page-hero">
        <div class="container">
            <h1>Services</h1>
            <p>Bringing joy, purpose, and professionalism to every engagement</p>
        </div>
    </section>

    <section class="section section-light">
        <div class="container">
            <!-- Event Planning -->
            <div class="service-row">
                <div class="service-image">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/service-placeholder.svg" alt="Event Planning">
                </div>
                <div class="service-text">
                    <h3>Event Planning</h3>
                    <p>From intimate gatherings to grand celebrations, I bring your vision to life with meticulous attention to detail and a personal touch. Every event tells a story, and I'm here to make sure yours is unforgettable.</p>
                    <p>Services include venue selection, vendor coordination, timeline management, decor planning, and day-of coordination. Whether it's a birthday, anniversary, church event, or community gathering — I've got you covered.</p>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-secondary">Get in Touch</a>
                </div>
            </div>

            <!-- Event MC -->
            <div class="service-row reverse">
                <div class="service-image">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/service-placeholder.svg" alt="Event MC">
                </div>
                <div class="service-text">
                    <h3>Event MC</h3>
                    <p>A great MC sets the tone for the entire event. I bring energy, warmth, and professionalism to every stage, keeping your guests engaged and your program flowing seamlessly from start to finish.</p>
                    <p>Whether it's a corporate gala, wedding reception, community event, or celebration of life — I tailor my approach to match your event's unique vibe and audience.</p>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-secondary">Get in Touch</a>
                </div>
            </div>

            <!-- Speaking Engagement -->
            <div class="service-row">
                <div class="service-image">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/service-placeholder.svg" alt="Speaking Engagement">
                </div>
                <div class="service-text">
                    <h3>Speaking Engagement</h3>
                    <p>Words have power, and I use mine to inspire, motivate, and uplift. Whether it's a keynote address, workshop, panel discussion, or conference breakout session, I deliver messages that resonate long after the event ends.</p>
                    <p>Topics include personal growth, joy and resilience, women's empowerment, faith and purpose, leadership, and community building.</p>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-secondary">Get in Touch</a>
                </div>
            </div>

            <!-- Marriage Pastor -->
            <div class="service-row reverse">
                <div class="service-image">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/service-placeholder.svg" alt="Marriage Pastor">
                </div>
                <div class="service-text">
                    <h3>Marriage Pastor</h3>
                    <p>Your wedding day is one of the most important days of your life. I officiate ceremonies with heart, meaning, and a personal touch that celebrates your unique love story and honors your commitment to each other.</p>
                    <p>I work closely with each couple to craft a ceremony that reflects your values, traditions, and personality — whether traditional, contemporary, or a beautiful blend of both.</p>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-secondary">Get in Touch</a>
                </div>
            </div>

            <!-- Counseling -->
            <div class="service-row">
                <div class="service-image">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/service-placeholder.svg" alt="Counseling">
                </div>
                <div class="service-text">
                    <h3>Counseling</h3>
                    <p>Life brings seasons of challenge, transition, and growth. I provide compassionate, faith-informed counseling for individuals and couples navigating difficult times, offering a safe space for honest conversation and practical guidance.</p>
                    <p>Areas of focus include relationship challenges, life transitions, grief and loss, personal growth, pre-marital counseling, and family dynamics.</p>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-secondary">Get in Touch</a>
                </div>
            </div>

            <!-- Social Media Marketing -->
            <div class="service-row reverse">
                <div class="service-image">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/service-placeholder.svg" alt="Social Media Marketing">
                </div>
                <div class="service-text">
                    <h3>Social Media Marketing</h3>
                    <p>In today's digital world, your online presence matters. I help individuals, ministries, and small businesses build authentic social media strategies that connect with their audience and amplify their message.</p>
                    <p>Services include content strategy, platform management, brand voice development, community engagement, and analytics review. Let's tell your story online with purpose and authenticity.</p>
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-secondary">Get in Touch</a>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-banner">
        <div class="container">
            <h2>Ready to Get Started?</h2>
            <p>Let's create something beautiful together. Reach out today.</p>
            <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-light">Book a Consultation</a>
        </div>
    </section>
</main>

<?php get_footer(); ?>
```

- [ ] **Step 4: Create page-contact.php**

Create `wp-content/themes/msisme-theme/page-contact.php`:

```php
<?php get_header(); ?>

<main>
    <section class="page-hero">
        <div class="container">
            <h1>Contact</h1>
            <p>I'd love to hear from you</p>
        </div>
    </section>

    <section class="section section-light">
        <div class="container">
            <div class="contact-content">
                <div class="contact-form-wrapper">
                    <h2>Send a Message</h2>
                    <?php
                    // Use Contact Form 7 if available
                    if (function_exists('wpcf7_contact_form_tag_func')) {
                        echo do_shortcode('[contact-form-7 id="contact-form" title="Contact Form"]');
                    } else {
                        // Fallback form
                    ?>
                    <form class="fallback-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                        <div class="form-group">
                            <label for="name">Your Name</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone">
                        </div>
                        <div class="form-group">
                            <label for="service">Service of Interest</label>
                            <select id="service" name="service">
                                <option value="">Select a service...</option>
                                <option value="event-planning">Event Planning</option>
                                <option value="event-mc">Event MC</option>
                                <option value="speaking">Speaking Engagement</option>
                                <option value="marriage-pastor">Marriage Pastor</option>
                                <option value="counseling">Counseling</option>
                                <option value="social-media">Social Media Marketing</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message">Your Message</label>
                            <textarea id="message" name="message" rows="6" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                    <?php } ?>
                </div>

                <div class="contact-info-side">
                    <h3>Let's Connect</h3>
                    <p>Have a question or ready to book? Reach out through any of these channels and I'll get back to you as soon as possible.</p>

                    <ul class="contact-details">
                        <li>
                            <span class="detail-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            </span>
                            <span>hello@msisme.com</span>
                        </li>
                        <li>
                            <span class="detail-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            </span>
                            <span>(555) 123-4567</span>
                        </li>
                        <li>
                            <span class="detail-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </span>
                            <span>Available for events nationwide</span>
                        </li>
                    </ul>

                    <h4 style="margin-bottom: 15px; font-family: var(--font-body); font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1px;">Follow Along</h4>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                        </a>
                        <a href="#" aria-label="Instagram">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                        </a>
                        <a href="#" aria-label="Twitter">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="#" aria-label="LinkedIn">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
```

- [ ] **Step 5: Create service placeholder SVG**

```bash
cat > /home/dev/repos/Websites/wordpress/MsIsme/wp-content/themes/msisme-theme/assets/images/service-placeholder.svg << 'SVGEOF'
<svg width="600" height="380" xmlns="http://www.w3.org/2000/svg">
  <rect width="600" height="380" fill="#D4A853" rx="16"/>
  <text x="300" y="190" text-anchor="middle" fill="#7A4A1F" font-family="sans-serif" font-size="20">Service Photo</text>
</svg>
SVGEOF
```

- [ ] **Step 6: Verify all pages render**

Visit each page and confirm layout:
- `http://localhost/MsIsme/` — homepage
- `http://localhost/MsIsme/about/` — about page
- `http://localhost/MsIsme/services/` — services page
- `http://localhost/MsIsme/contact/` — contact page

- [ ] **Step 7: Commit**

```bash
cd /home/dev/repos/Websites/wordpress/MsIsme
git add wp-content/themes/msisme-theme/page.php wp-content/themes/msisme-theme/page-about.php wp-content/themes/msisme-theme/page-services.php wp-content/themes/msisme-theme/page-contact.php wp-content/themes/msisme-theme/assets/images/service-placeholder.svg
git commit -m "feat: add about, services, and contact page templates"
```

---

### Task 7: JavaScript — Mobile Menu & Testimonial Carousel

**Files:**
- Create: `wp-content/themes/msisme-theme/assets/js/main.js`

- [ ] **Step 1: Create main.js**

Create `wp-content/themes/msisme-theme/assets/js/main.js`:

```javascript
document.addEventListener('DOMContentLoaded', function () {

    // ========== Mobile Menu Toggle ==========
    var menuToggle = document.querySelector('.menu-toggle');
    var mainNav = document.querySelector('.main-nav');

    if (menuToggle && mainNav) {
        menuToggle.addEventListener('click', function () {
            menuToggle.classList.toggle('active');
            mainNav.classList.toggle('active');
        });

        // Close menu when a link is clicked
        var navLinks = mainNav.querySelectorAll('a');
        navLinks.forEach(function (link) {
            link.addEventListener('click', function () {
                menuToggle.classList.remove('active');
                mainNav.classList.remove('active');
            });
        });
    }

    // ========== Testimonial Carousel ==========
    var track = document.querySelector('.testimonial-track');
    var dots = document.querySelectorAll('.carousel-dots .dot');

    if (track && dots.length > 0) {
        var currentSlide = 0;
        var totalSlides = dots.length;
        var autoplayInterval = null;

        function goToSlide(index) {
            currentSlide = index;
            track.style.transform = 'translateX(-' + (currentSlide * 100) + '%)';
            dots.forEach(function (dot, i) {
                dot.classList.toggle('active', i === currentSlide);
            });
        }

        dots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                var index = parseInt(dot.getAttribute('data-index'));
                goToSlide(index);
                resetAutoplay();
            });
        });

        function nextSlide() {
            goToSlide((currentSlide + 1) % totalSlides);
        }

        function resetAutoplay() {
            clearInterval(autoplayInterval);
            autoplayInterval = setInterval(nextSlide, 5000);
        }

        // Start autoplay
        autoplayInterval = setInterval(nextSlide, 5000);
    }

    // ========== Header Scroll Effect ==========
    var header = document.querySelector('.site-header');

    if (header) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 50) {
                header.style.boxShadow = '0 4px 30px rgba(45, 27, 14, 0.12)';
            } else {
                header.style.boxShadow = '0 2px 20px rgba(45, 27, 14, 0.08)';
            }
        });
    }

    // ========== Smooth Scroll for Anchor Links ==========
    var anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(function (link) {
        link.addEventListener('click', function (e) {
            var targetId = link.getAttribute('href');
            if (targetId === '#') return;
            var target = document.querySelector(targetId);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
});
```

- [ ] **Step 2: Verify carousel and mobile menu work**

Visit `http://localhost/MsIsme/`:
- Testimonial carousel should auto-rotate every 5 seconds; clicking dots should switch slides.
- Resize browser to mobile width — hamburger menu should appear and toggle the nav on click.

- [ ] **Step 3: Commit**

```bash
cd /home/dev/repos/Websites/wordpress/MsIsme
git add wp-content/themes/msisme-theme/assets/js/main.js
git commit -m "feat: add mobile menu toggle, testimonial carousel, and scroll effects"
```

---

### Task 8: WordPress Configuration — Pages, Menus, Settings

**Files:**
- No new files — WordPress database configuration via WP-CLI or admin panel.

- [ ] **Step 1: Create WordPress pages**

```bash
WP_PATH="/home/dev/repos/Websites/wordpress/MsIsme"
wp post create --post_type=page --post_title="Home" --post_status=publish --path="$WP_PATH"
wp post create --post_type=page --post_title="About" --post_name="about" --post_status=publish --path="$WP_PATH"
wp post create --post_type=page --post_title="Services" --post_name="services" --post_status=publish --path="$WP_PATH"
wp post create --post_type=page --post_title="Contact" --post_name="contact" --post_status=publish --path="$WP_PATH"
```

- [ ] **Step 2: Set front page to static "Home" page**

```bash
WP_PATH="/home/dev/repos/Websites/wordpress/MsIsme"
HOME_ID=$(wp post list --post_type=page --title="Home" --field=ID --path="$WP_PATH")
wp option update show_on_front page --path="$WP_PATH"
wp option update page_on_front "$HOME_ID" --path="$WP_PATH"
```

- [ ] **Step 3: Create primary navigation menu**

```bash
WP_PATH="/home/dev/repos/Websites/wordpress/MsIsme"
wp menu create "Primary Menu" --path="$WP_PATH"
wp menu item add-post "Primary Menu" $(wp post list --post_type=page --title="Home" --field=ID --path="$WP_PATH") --title="Home" --path="$WP_PATH"
wp menu item add-post "Primary Menu" $(wp post list --post_type=page --title="About" --field=ID --path="$WP_PATH") --title="About" --path="$WP_PATH"
wp menu item add-post "Primary Menu" $(wp post list --post_type=page --title="Services" --field=ID --path="$WP_PATH") --title="Services" --path="$WP_PATH"
wp menu item add-post "Primary Menu" $(wp post list --post_type=page --title="Contact" --field=ID --path="$WP_PATH") --title="Contact" --path="$WP_PATH"
wp menu location assign "Primary Menu" primary --path="$WP_PATH"
```

- [ ] **Step 4: Set permalink structure**

```bash
WP_PATH="/home/dev/repos/Websites/wordpress/MsIsme"
wp rewrite structure '/%postname%/' --path="$WP_PATH"
wp rewrite flush --path="$WP_PATH"
```

- [ ] **Step 5: Install and activate Contact Form 7**

```bash
WP_PATH="/home/dev/repos/Websites/wordpress/MsIsme"
wp plugin install contact-form-7 --activate --path="$WP_PATH"
```

- [ ] **Step 6: Copy logo to theme assets**

Copy the Ms. Isme logo into the theme:

```bash
cp /home/dev/repos/Websites/wordpress/MsIsme/assets/images/logo.png /home/dev/repos/Websites/wordpress/MsIsme/wp-content/themes/msisme-theme/assets/images/logo.png 2>/dev/null || echo "Logo file not found at expected location — user will need to place logo.png in wp-content/themes/msisme-theme/assets/images/"
```

- [ ] **Step 7: Verify full site works end-to-end**

Visit each URL and confirm:
- `http://localhost/MsIsme/` — homepage with all sections
- `http://localhost/MsIsme/about/` — about page with bio and philosophy
- `http://localhost/MsIsme/services/` — all 6 services with alternating layout
- `http://localhost/MsIsme/contact/` — contact form and info
- Navigation links work between all pages
- Header CTA "Book a Consultation" links to Contact page
- Mobile menu works at narrow widths
- Testimonial carousel auto-rotates

- [ ] **Step 8: Commit**

```bash
cd /home/dev/repos/Websites/wordpress/MsIsme
git add -A
git commit -m "feat: configure WordPress pages, menus, permalinks, and Contact Form 7"
```
