# Ms. Isme — Count It All Joy

A personal brand WordPress website showcasing services: Event Planning, Event MC, Speaking Engagement, Marriage Pastor, Counseling, and Social Media Marketing.

## Local Development Setup

### Prerequisites

- Apache with `mod_rewrite` enabled
- PHP 8.x
- MySQL
- [WP-CLI](https://wp-cli.org/) (optional but recommended)

### 1. Database

The site uses a MySQL database called `msisme` with user `msisme_user`. If you need to recreate it:

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS msisme CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -e "CREATE USER IF NOT EXISTS 'msisme_user'@'127.0.0.1' IDENTIFIED BY 'MsIsme_Str0ng_P@ss2026';"
mysql -u root -e "GRANT ALL PRIVILEGES ON msisme.* TO 'msisme_user'@'127.0.0.1';"
mysql -u root -e "FLUSH PRIVILEGES;"
```

### 2. Apache Virtual Host

An Apache vhost config is at `/etc/apache2/sites-available/msisme.conf`:

```apache
<VirtualHost *:80>
    ServerName msisme.local
    DocumentRoot /home/dev/repos/Websites/wordpress/MsIsme

    <Directory /home/dev/repos/Websites/wordpress/MsIsme>
        Options FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Enable and reload:

```bash
a2ensite msisme.conf
service apache2 reload
```

### 3. Hosts File

Add to your hosts file so the browser can resolve `msisme.local`:

**Linux/Mac:** `/etc/hosts`
**Windows (WSL):** `C:\Windows\System32\drivers\etc\hosts`

```
127.0.0.1 msisme.local
```

### 4. WordPress Config

`wp-config.php` is gitignored. If missing, copy from the sample and update:

```bash
cp wp-config-sample.php wp-config.php
```

Set these values:

- `DB_NAME` = `msisme`
- `DB_USER` = `msisme_user`
- `DB_PASSWORD` = `MsIsme_Str0ng_P@ss2026`
- `DB_HOST` = `127.0.0.1`
- Table prefix: `$table_prefix = 'mi_';`
- Generate fresh salts at https://api.wordpress.org/secret-key/1.1/salt/

### 5. Access the Site

- **Site:** http://msisme.local
- **Admin:** http://msisme.local/wp-admin (user: `admin`, pass: `admin123`)

## Project Structure

```
MsIsme/
├── wp-content/
│   ├── themes/
│   │   └── msisme-theme/          # Custom theme
│   │       ├── style.css           # All styles + theme metadata
│   │       ├── functions.php       # Theme setup, enqueue, menus
│   │       ├── header.php          # Sticky header with nav + CTA
│   │       ├── footer.php          # Footer with links + social
│   │       ├── front-page.php      # Homepage template
│   │       ├── page.php            # Generic page template
│   │       ├── page-about.php      # About page
│   │       ├── page-services.php   # Services page
│   │       ├── page-contact.php    # Contact page (CF7 + fallback)
│   │       ├── index.php           # Fallback template
│   │       └── assets/
│   │           ├── css/
│   │           ├── js/main.js      # Carousel, mobile menu, scroll
│   │           └── images/         # Logo + placeholder SVGs
│   └── plugins/
│       └── contact-form-7/         # Contact form plugin
├── docs/
│   └── superpowers/
│       ├── specs/                  # Design spec
│       └── plans/                  # Implementation plan
└── wp-config.php                   # (gitignored)
```

## Pages

| Page     | URL                            | Template             |
|----------|--------------------------------|----------------------|
| Home     | http://msisme.local/           | `front-page.php`     |
| About    | http://msisme.local/about/     | `page-about.php`     |
| Services | http://msisme.local/services/  | `page-services.php`  |
| Contact  | http://msisme.local/contact/   | `page-contact.php`   |

## Color Palette

| Color          | Hex       | Usage                    |
|----------------|-----------|--------------------------|
| Copper         | `#A0622E` | Primary brand color      |
| Dark Copper    | `#7A4A1F` | Headings, dark accents   |
| Cream          | `#FFF8F0` | Page background          |
| Burgundy       | `#8B2D4F` | CTA buttons              |
| Gold           | `#D4A853` | Highlights, decorative   |
| Warm White     | `#FFFDF9` | Card backgrounds         |
| Dark Text      | `#2D1B0E` | Body copy                |

## Fonts

- **Headings:** Playfair Display (serif)
- **Body:** Poppins (sans-serif)

Both loaded via Google Fonts.

## Next Steps

### Content

- [ ] Replace placeholder images with real photos (in `wp-content/themes/msisme-theme/assets/images/`)
- [ ] Upload the Ms. Isme logo as `logo.png` in the theme images folder
- [ ] Update placeholder contact info (email, phone) in `footer.php` and `page-contact.php`
- [ ] Update social media links (currently `#`) in `footer.php` and `page-contact.php`
- [ ] Edit testimonials in `front-page.php` with real client quotes
- [ ] Customize service descriptions in `page-services.php` and `front-page.php`
- [ ] Update the About bio in `page-about.php`

### Contact Form 7

- [ ] Go to WP Admin > Contact > Contact Forms
- [ ] Edit the default form or create a new one with fields: Name, Email, Phone, Service dropdown, Message
- [ ] Copy the shortcode and update `page-contact.php` if needed
- [ ] Set the recipient email address in the form's Mail tab

### Optional Enhancements

- [ ] Add a blog (create a Posts page, assign in Settings > Reading)
- [ ] Install an SEO plugin (Yoast or Rank Math)
- [ ] Add Google Analytics
- [ ] Set up a custom logo via Appearance > Customize > Site Identity
- [ ] Add SSL certificate for HTTPS
