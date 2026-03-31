# AscendMen - Local Development Setup

A faith-based WordPress site for men to discover their God-given identity as Sons of God. Blog, community, programs, and camp registration platform.

## Prerequisites

- **PHP** 8.1+
- **MySQL** 8.0+
- **Apache** 2.4+ with `mod_rewrite` enabled
- **WP-CLI** 2.12+ ([install guide](https://wp-cli.org/#installing))

On Ubuntu/Debian:

```bash
sudo apt update
sudo apt install apache2 mysql-server php php-mysql php-xml php-mbstring php-curl php-zip php-gd php-intl
sudo a2enmod rewrite
```

## Quick Start

### 1. Start services

```bash
sudo service mysql start
sudo service apache2 start
```

### 2. Create the database

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS ascendmen_wp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -e "CREATE USER IF NOT EXISTS 'wp_user'@'localhost' IDENTIFIED BY 'wp_secure_pass_2024';"
mysql -u root -e "GRANT ALL PRIVILEGES ON ascendmen_wp.* TO 'wp_user'@'localhost'; FLUSH PRIVILEGES;"
```

> If your MySQL root user requires a password, add `-p` to the commands above.

### 3. Configure Apache VirtualHost

Create `/etc/apache2/sites-available/ascendmen.conf`:

```apacheconf
<VirtualHost *:80>
    ServerName ascendmen.local
    DocumentRoot /home/dev/repos/Websites/wordpress/AscendMen

    <Directory /home/dev/repos/Websites/wordpress/AscendMen>
        Options FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/ascendmen_error.log
    CustomLog ${APACHE_LOG_DIR}/ascendmen_access.log combined
</VirtualHost>
```

Enable the site and add the hostname:

```bash
sudo a2ensite ascendmen.conf
echo "127.0.0.1  ascendmen.local" | sudo tee -a /etc/hosts
sudo service apache2 reload
```

### 4. Install WordPress (first time only)

```bash
cd /home/dev/repos/Websites/wordpress/AscendMen

wp core download --allow-root

wp config create \
  --dbname=ascendmen_wp \
  --dbuser=wp_user \
  --dbpass=wp_secure_pass_2024 \
  --dbhost=127.0.0.1 \
  --dbprefix=am_ \
  --allow-root

wp core install \
  --url=http://ascendmen.local \
  --title="AscendMen" \
  --admin_user=ascendadmin \
  --admin_password=AscendAdmin2024! \
  --admin_email=admin@ascendmen.com \
  --allow-root

wp rewrite structure '/%postname%/' --allow-root
```

### 5. Install theme and plugins

```bash
# Kadence theme
wp theme install kadence --allow-root

# The child theme is already in wp-content/themes/kadence-child/
wp theme activate kadence-child --allow-root

# Plugins
wp plugin install ultimate-member woocommerce the-events-calendar \
  kadence-blocks wordpress-seo wp-mail-smtp updraftplus \
  --activate --allow-root

# Custom Eventbrite plugin is already in wp-content/plugins/ascendmen-eventbrite/
wp plugin activate ascendmen-eventbrite --allow-root
```

### 6. Verify

Open **http://ascendmen.local/** in your browser.

Admin dashboard: **http://ascendmen.local/wp-admin/**
- Username: `ascendadmin`
- Password: `AscendAdmin2024!`

## Daily Development

Each time you start working:

```bash
sudo service mysql start
sudo service apache2 start
```

Then visit http://ascendmen.local/

## Project Structure

```
AscendMen/
├── wp-config.php                    # DB credentials, debug settings
├── wp-content/
│   ├── themes/
│   │   ├── kadence/                 # Parent theme (do not edit)
│   │   └── kadence-child/           # Child theme (edit this)
│   │       ├── style.css            # Brand CSS variables
│   │       ├── functions.php        # Enqueues + includes
│   │       └── includes/
│   │           └── programs-cpt.php # Programs custom post type
│   ├── plugins/
│   │   ├── ascendmen-eventbrite/    # Custom Eventbrite integration
│   │   │   ├── ascendmen-eventbrite.php
│   │   │   ├── includes/
│   │   │   │   ├── class-eventbrite-api.php
│   │   │   │   └── class-registration-form.php
│   │   │   └── tests/
│   │   │       └── test-eventbrite-api.php
│   │   └── ... (installed plugins)
│   └── uploads/                     # Media files
├── ASCEND MEN PNG TRANSPARENT.png   # Logo source file
├── Ascend men JPEG.jpg              # Logo alternate
└── docs/
    └── superpowers/
        ├── specs/                   # Design spec
        └── plans/                   # Implementation plan
```

## Site Pages

| Page | URL | Description |
|------|-----|-------------|
| Home | `/` | Hero with logo, tagline, CTAs |
| About | `/about/` | Vision, mission, team |
| Blog | `/blog/` | Posts with category filtering |
| Programs | `/programs/` | Courses, workshops, events |
| Camps | `/camps/` | Camp info and registration |
| Outreach | `/outreach/` | Community service activities |
| Community | `/community/` | Social links, member directory |
| Contact | `/contact/` | Contact form |
| Events | `/events/` | Events Calendar listing |
| Register | `/register/` | Member registration |
| Login | `/login/` | Member login |

## Blog Categories

Identity, Leadership, Fatherhood, Marriage, Faith & Scripture, Camp Stories, Outreach

## Brand Colors

| Name | Hex | Usage |
|------|-----|-------|
| Flame Blue | `#29ABE2` | Primary accent, CTAs |
| Mountain Navy | `#1B2A4A` | Backgrounds, header |
| Steel Blue | `#4A7FC1` | Secondary accents, links |
| Summit White | `#FFFFFF` | Text, content areas |

## Plugins

| Plugin | Version | Purpose |
|--------|---------|---------|
| Kadence Blocks | 3.6.6 | Page builder blocks |
| Ultimate Member | 2.11.2 | Membership & user profiles |
| WooCommerce | 10.6.1 | Paid program checkout |
| The Events Calendar | 6.15.17.1 | Camp/event listings |
| Yoast SEO | 27.2 | Search optimization |
| WP Mail SMTP | 4.7.1 | Email delivery |
| UpdraftPlus | 1.26.2 | Backups & migration |
| AscendMen Eventbrite | 1.0.0 | Camp registration via Eventbrite API |

## Custom Features

### Programs (Custom Post Type)

Programs are managed as a custom post type (`am_program`) with a taxonomy for type (Course, Workshop, Recurring Meeting, One-Time Event). Supports free and paid programs via WooCommerce integration.

### Eventbrite Camp Registration

Add the shortcode to any page or camp event:

```
[ascendmen_camp_register event_id="YOUR_EVENTBRITE_EVENT_ID"]
```

Configure your Eventbrite API key at **Settings > Eventbrite** in the admin dashboard.

### Membership

Three access levels:
- **Guest** -- Blog, About, Contact, Camps info, Programs browse
- **Member** -- Profile, member directory, members-only content, program signup
- **Admin** -- Full WordPress dashboard

## Deploying to Hostinger

1. Install UpdraftPlus on both local and Hostinger WordPress
2. In local admin: **Settings > UpdraftPlus > Backup Now** (database + files)
3. Download the backup zip files
4. Upload and restore on Hostinger via UpdraftPlus
5. Run search-replace for the new domain:

```bash
wp search-replace 'http://ascendmen.local' 'https://yourdomain.com' --allow-root
```

6. Update in Hostinger admin:
   - **Settings > Eventbrite** -- enter production API key
   - **WP Mail SMTP** -- configure production SMTP credentials
   - Set `WP_DEBUG` to `false` in `wp-config.php`

## Troubleshooting

**Pages return 404:** Flush rewrite rules:
```bash
wp rewrite flush --hard --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```

**"Unable to connect to database":** Make sure MySQL is running:
```bash
sudo service mysql start
```

**Permission errors on uploads:** Fix ownership:
```bash
sudo chown -R www-data:www-data /home/dev/repos/Websites/wordpress/AscendMen/wp-content/uploads
```

**Ultimate Member fatal error (FTP):** Ensure `wp-config.php` has:
```php
define('FS_METHOD', 'direct');
```
