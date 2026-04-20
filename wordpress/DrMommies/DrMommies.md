# Doctor Mommies — Local WordPress Setup

A WordPress site modelled after [drmommies.com](https://drmommies.com), featuring a custom purple theme, recipe management, and a holistic nutrition blog.

---

## Stack

| Component | Version |
|-----------|---------|
| WordPress | Latest (6.x) |
| PHP | 8.1 |
| MySQL | 8.0 |
| Apache | 2.4 |
| WP-CLI | 2.12 |

---

## Prerequisites

Install the required packages (Ubuntu/Debian):

```bash
sudo apt-get update
sudo apt-get install -y php php-mysql php-curl php-gd php-mbstring php-xml php-zip php-intl \
    mysql-server apache2 libapache2-mod-php
```

Install WP-CLI:

```bash
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
chmod +x wp-cli.phar
sudo mv wp-cli.phar /usr/local/bin/wp
```

---

## Starting the Site

Every time you start a new session (e.g. after a reboot or WSL restart), run these two commands:

```bash
sudo service mysql start
sudo service apache2 start
```

Then open your browser to:

```
http://drmommies.local
```

### Verify services are running

```bash
sudo service mysql status
sudo service apache2 status
```

---

## Stopping the Site

```bash
sudo service mysql stop
sudo service apache2 stop
```

---

## Admin Access

| Field | Value |
|-------|-------|
| Admin URL | http://drmommies.local/wp-admin |
| Username | `admin` |
| Password | `DrMommies2024!` |
| Admin Email | nourishments@doctormommies.com |

---

## Database

| Field | Value |
|-------|-------|
| Database name | `drmommies_wp` |
| Username | `wp_user` |
| Password | `wp_secure_pass_2024` |
| Host | `127.0.0.1` |

### Connect via MySQL CLI

```bash
mysql -u wp_user -pwp_secure_pass_2024 -h 127.0.0.1 drmommies_wp
```

### Connect as root (no password required locally)

```bash
sudo mysql -u root
```

### View content counts

```sql
SELECT post_type, COUNT(*) as count
FROM wp_posts
WHERE post_status = 'publish'
  AND post_type IN ('page', 'post', 'recipe')
GROUP BY post_type;
```

### View newsletter subscribers

```sql
SELECT * FROM wp_newsletter_subscribers;
```

---

## File Locations

| Resource | Path |
|----------|------|
| WordPress root | `/home/dev/repos/Websites/wordpress/DrMommies` |
| Custom theme | `/home/dev/repos/Websites/wordpress/DrMommies/wp-content/themes/drmommies` |
| Apache virtual host config | `/etc/apache2/sites-available/drmommies.conf` |
| WordPress config | `/home/dev/repos/Websites/wordpress/DrMommies/wp-config.php` |
| Apache rewrite rules | `/home/dev/repos/Websites/wordpress/DrMommies/.htaccess` |
| Apache error log | `/var/log/apache2/drmommies_error.log` |

---

## Site Pages

| URL | Description |
|-----|-------------|
| `http://drmommies.local/` | Homepage — hero, about, stats, recipes preview, testimonials, newsletter |
| `http://drmommies.local/recipes/` | All recipes grid |
| `http://drmommies.local/recipes/<slug>/` | Individual recipe detail |
| `http://drmommies.local/blog/` | Blog listing + FAQ |
| `http://drmommies.local/<post-slug>/` | Individual blog post |
| `http://drmommies.local/about-us/` | About page |
| `http://drmommies.local/contact/` | Contact page |
| `http://drmommies.local/wp-admin/` | WordPress admin dashboard |

---

## Theme

The custom theme lives at `/home/dev/repos/Websites/wordpress/DrMommies/wp-content/themes/drmommies/` and mirrors the DrMommies design:

- **Colors** — dark purple `#2f1c6a`, primary purple `#673de6`, light accents `#ebe4ff`
- **Fonts** — Playfair Display (headings) + Inter (body)
- **Custom Post Type** — `recipe` with meta fields: prep time, cook time, servings, difficulty, ingredients
- **Taxonomies** — `recipe_category` (Breakfast, Lunch, Dinner, Snacks, Smoothies, Desserts)
- **Newsletter** — AJAX signup that stores emails in `wp_newsletter_subscribers` table

### Key theme files

```
drmommies/
├── style.css              # Theme stylesheet + CSS variables
├── functions.php          # Theme setup, CPTs, meta boxes, AJAX handlers
├── index.php              # Homepage template
├── header.php             # Site header & navigation
├── footer.php             # Site footer
├── page-recipes.php       # Recipes archive page template
├── page-blog.php          # Blog listing + FAQ template
├── single-recipe.php      # Single recipe template
├── single.php             # Single blog post template
├── page.php               # Generic page template
├── archive-recipe.php     # Recipe taxonomy archive template
└── js/
    └── main.js            # Newsletter AJAX, mobile menu, scroll animations
```

---

## WP-CLI Cheatsheet

All WP-CLI commands must be run from the WordPress root with `--allow-root`.

```bash
WP_ROOT=/home/dev/repos/Websites/wordpress/DrMommies

# List all recipes
wp post list --post_type=recipe --allow-root --path=$WP_ROOT

# List all pages
wp post list --post_type=page --allow-root --path=$WP_ROOT

# Create a new recipe
wp post create --post_type=recipe --post_title="My Recipe" --post_status=publish --allow-root --path=$WP_ROOT

# Flush permalink cache (fix 404s)
wp rewrite flush --allow-root --path=$WP_ROOT

# Update a plugin or theme
wp theme update drmommies --allow-root --path=$WP_ROOT
```

---

## Troubleshooting

### Site shows "Error establishing a database connection"

MySQL has stopped. Start it:

```bash
sudo service mysql start
```

### Pages return 404 (except homepage)

The `.htaccess` file may be missing or Apache rewrite is off. Fix:

```bash
WP_ROOT=/home/dev/repos/Websites/wordpress/DrMommies

# Recreate .htaccess
sudo wp rewrite flush --allow-root --path=$WP_ROOT

# Or manually create it
sudo tee $WP_ROOT/.htaccess << 'EOF'
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
# END WordPress
EOF

sudo service apache2 restart
```

### Apache fails to start

Check for config errors:

```bash
sudo apache2ctl configtest
sudo tail -20 /var/log/apache2/error.log
```

### WSL-specific: services stop after terminal closes

WSL does not run background services automatically. You must start MySQL and Apache each time you open a new WSL session (see [Starting the Site](#starting-the-site) above).

To automate this, add to your `~/.bashrc` or `~/.zshrc`:

```bash
# Auto-start web services in WSL
sudo service mysql start > /dev/null 2>&1
sudo service apache2 start > /dev/null 2>&1
```

---

## Seeded Content

| Type | Count | Notes |
|------|-------|-------|
| Pages | 6 | Home, Blog, Recipes, About, Contact, Privacy |
| Recipes | 8 | Oat Banana Pancakes, Green Immunity Smoothie, Berry Chia Pudding, Rainbow Veggie Bowl, Hidden Veggie Pasta Sauce, Lentil & Sweet Potato Soup, Lemon Herb Salmon, Baked Sweet Potato Fries |
| Blog Posts | 5 | Holistic nutrition articles |
| Recipe Categories | 6 | Breakfast, Lunch, Dinner, Snacks, Smoothies, Desserts |
