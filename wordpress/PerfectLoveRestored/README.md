# Perfect Love Restored

A Christian WordPress website centered around knowing and experiencing the Love of God through relationship and His finished completed work on the cross.

**Local URL:** http://perfectloverestored.local

## Tech Stack

| Component  | Version |
|------------|---------|
| WordPress  | 6.9.4   |
| PHP        | 8.1.2   |
| MySQL      | 8.0.45  |
| Apache     | 2.4.52  |
| WP-CLI     | 2.12.0  |

## Architecture

### Apache Virtual Host

The site runs on its own Apache virtual host rather than a subfolder of another WordPress install. The config lives at:

```
/etc/apache2/sites-available/perfectloverestored.conf
```

It maps `perfectloverestored.local` to the project directory and is resolved via a `/etc/hosts` entry pointing to `127.0.0.1`.

**Important:** The database host in `wp-config.php` is set to `127.0.0.1` (TCP) instead of `localhost` (socket) because the `www-data` Apache user does not have socket-level access to MySQL in this environment.

### Database

- **Database:** `perfectloverestored`
- **User:** `plr_user`
- **Host:** `127.0.0.1`

Credentials are stored in `wp-config.php`.

### Custom Theme

The site uses a custom theme at `wp-content/themes/perfectloverestored/` with a clean, modern minimal design.

**Design tokens:** warm golds (`#C9A84C`), cream backgrounds (`#FDF8F0`), muted blues (`#7BA7BC`).

**Typography:** Playfair Display (headings), Inter (body), Cormorant Garamond (scripture/accents) via Google Fonts.

```
wp-content/themes/perfectloverestored/
├── assets/
│   └── js/
│       └── main.js              # Mobile menu toggle, sticky header shadow
├── templates/
│   ├── template-about.php       # About page (mission, beliefs, heart)
│   ├── template-blog.php        # PerfectLove blog listing page
│   ├── template-donate.php      # Donate page with giving info
│   └── template-store.php       # Store page (coming soon / WooCommerce-ready)
├── 404.php
├── footer.php
├── front-page.php               # Homepage with hero, scripture banner, latest posts
├── functions.php                 # Theme setup, enqueues, nav menus, widget areas
├── header.php                    # Sticky header with responsive nav
├── index.php                     # Default blog archive
├── page.php                      # Generic page template
├── single.php                    # Single post template
└── style.css                     # Full theme stylesheet with CSS custom properties
```

### Pages

| Page         | Slug           | Template              |
|--------------|----------------|-----------------------|
| Home         | `home`         | `front-page.php`      |
| About        | `about`        | `template-about.php`  |
| PerfectLove  | `perfectlove`  | `template-blog.php`   |
| Store        | `store`        | `template-store.php`  |
| Donate       | `donate`       | `template-donate.php` |

### Navigation Menus

- **Primary Menu** (header): Home, About, PerfectLove, Store, Donate (Donate gets a gold button style automatically)
- **Footer Menu**: About, Blog, Store, Donate

### WordPress Settings

- **Front page:** Static page (`Home`)
- **Posts page:** `PerfectLove`
- **Permalinks:** `/%postname%/`

## Running Locally

### Prerequisites

- Apache 2.4+
- PHP 8.1+
- MySQL 8.0+
- WP-CLI

### 1. Start Services

```bash
service mysql start
service apache2 start
```

### 2. Create the Database (first time only)

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS perfectloverestored;"
mysql -u root -e "CREATE USER IF NOT EXISTS 'plr_user'@'localhost' IDENTIFIED BY '<password>';"
mysql -u root -e "GRANT ALL PRIVILEGES ON perfectloverestored.* TO 'plr_user'@'localhost'; FLUSH PRIVILEGES;"
```

### 3. Add Hosts Entry (first time only)

```bash
echo "127.0.0.1 perfectloverestored.local" >> /etc/hosts
```

### 4. Enable the Apache Virtual Host (first time only)

```bash
a2ensite perfectloverestored.conf
a2enmod rewrite
service apache2 restart
```

### 5. Verify

Open http://perfectloverestored.local in a browser.

**Admin panel:** http://perfectloverestored.local/wp-admin/

### Troubleshooting

- **"Error establishing a database connection"** -- Make sure MySQL is running (`service mysql start`) and that `wp-config.php` has `DB_HOST` set to `127.0.0.1`, not `localhost`.
- **404 on all pages** -- Flush rewrite rules: `wp rewrite flush --allow-root` from the project directory.
- **Site not resolving** -- Confirm `/etc/hosts` has the `perfectloverestored.local` entry and Apache is running.

## Deploying to Hostinger

When ready to move to production:

1. **All-in-One WP Migration plugin** (recommended) -- Install the plugin, export the site, then import on Hostinger.
2. **Hostinger migration tool** -- Use the built-in WordPress migration in hPanel.
3. **Manual** -- Upload files via FTP, import the database, then update `siteurl` and `home` in the `wp_options` table to your production domain.

After migration, update `wp-config.php` with the Hostinger database credentials and remove any local-only settings.
