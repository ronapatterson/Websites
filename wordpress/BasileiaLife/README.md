# Basileia Life Transformation Ministries — Local WordPress Site

A local WordPress replica of [Basileia Life Transformation Ministries](https://basileia-life-transformation-ministries-agb2bbvxgncpdglg.builder-preview.com/), built with PHP, MySQL, and the Astra theme.

---

## Prerequisites

Ensure the following are installed on your system:

- **PHP 8.1+** — `php --version`
- **MySQL 8.0+** — `mysql --version`
- **WP-CLI** — `wp --version` (install from [wp-cli.org](https://wp-cli.org))

---

## First-Time Setup

These steps only need to be run once.

### 1. Start MySQL

```bash
sudo service mysql start
```

### 2. Create the database and user

```bash
sudo mysql -e "
CREATE DATABASE IF NOT EXISTS basileia_wp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'basileia'@'localhost' IDENTIFIED BY 'basileia_pass_2024';
GRANT ALL PRIVILEGES ON basileia_wp.* TO 'basileia'@'localhost';
FLUSH PRIVILEGES;
"
```

### 3. Install WordPress core (if not already present)

```bash
cd /path/to/wordpress/BasileiaLife
wp core download --allow-root
```

### 4. Generate wp-config.php

```bash
wp core config \
  --dbname=basileia_wp \
  --dbuser=basileia \
  --dbpass=basileia_pass_2024 \
  --dbhost=localhost \
  --allow-root
```

### 5. Install WordPress

```bash
wp core install \
  --url="http://localhost:8080" \
  --title="Basileia Life Transformation Ministries" \
  --admin_user="admin" \
  --admin_password="admin123" \
  --admin_email="contact@basileialife.org" \
  --allow-root
```

### 6. Run the site setup script

This populates all pages, images, navigation, and custom CSS:

```bash
php -r "
\$_SERVER['HTTP_HOST'] = 'localhost:8080';
\$_SERVER['REQUEST_URI'] = '/';
require 'wp-load.php';
require 'setup-basileia.php';
" 2>&1
```

---

## Running the Site

Every time you want to start the site locally:

```bash
# 1. Start MySQL
sudo service mysql start

# 2. Navigate to the BasileiaLife folder
cd /path/to/wordpress/BasileiaLife

# 3. Start the WordPress development server
wp server --host=0.0.0.0 --port=8080 --allow-root
```

> **Important:** The server must be started from inside the `BasileiaLife/` directory, otherwise WordPress files won't be found and you'll get a 404.

Then open your browser at:

| URL | Description |
|-----|-------------|
| [http://localhost:8080](http://localhost:8080) | Home page |
| [http://localhost:8080/about/](http://localhost:8080/about/) | About page |
| [http://localhost:8080/events/](http://localhost:8080/events/) | Events |
| [http://localhost:8080/lessons/](http://localhost:8080/lessons/) | Lessons |
| [http://localhost:8080/live/](http://localhost:8080/live/) | Live stream |
| [http://localhost:8080/on-demand/](http://localhost:8080/on-demand/) | On Demand |
| [http://localhost:8080/contact/](http://localhost:8080/contact/) | Contact |
| [http://localhost:8080/wp-admin](http://localhost:8080/wp-admin) | Admin dashboard |

### Admin credentials

| Field | Value |
|-------|-------|
| Username | `admin` |
| Password | `admin123` |

---

## Project Structure

```
wordpress/
├── DrMommies.md                 # Unrelated file (left at root)
└── BasileiaLife/                # ← All site files live here
    ├── wp-content/
    │   ├── themes/
    │   │   └── astra/           # Active theme
    │   ├── plugins/
    │   │   ├── elementor/       # Page builder (installed)
    │   │   └── classic-editor/  # Classic editor
    │   └── uploads/
    │       └── basileia/        # Downloaded site images
    │           ├── logo.png
    │           ├── pastor-garth.jpg
    │           └── ...
    ├── setup-basileia.php       # Site content setup script
    ├── wp-config.php            # WordPress config (DB credentials)
    └── README.md                # This file
```

---

## Pages & Content

| Page | Content |
|------|---------|
| **Home** | Hero with Pastor Garth photo, "What to Expect?" section, Empower & Transform cards, testimonials, footer |
| **About** | Hero with stats (150+ community, 15 faith-driven), Empowerment Projects, customer reviews, footer |
| **Events** | Placeholder page |
| **Lessons** | Placeholder page (parent of Live and On Demand) |
| **Live** | Placeholder page |
| **On Demand** | Placeholder page |
| **Contact** | Contact info + message form |

---

## Database Details

| Setting | Value |
|---------|-------|
| Database name | `basileia_wp` |
| Username | `basileia` |
| Password | `basileia_pass_2024` |
| Host | `localhost` |
| Table prefix | `wp_` |

To connect directly:

```bash
mysql -u basileia -pbasileia_pass_2024 basileia_wp
```

---

## Stopping the Server

Press `Ctrl+C` in the terminal running `wp server` to stop it.

To also stop MySQL:

```bash
sudo service mysql stop
```

---

## Re-running the Setup Script

If you need to reset all page content to match the original site:

```bash
cd /path/to/wordpress/BasileiaLife

php -r "
\$_SERVER['HTTP_HOST'] = 'localhost:8080';
\$_SERVER['REQUEST_URI'] = '/';
require 'wp-load.php';
require 'setup-basileia.php';
" 2>&1
```

This is safe to re-run — it updates existing pages rather than creating duplicates.
