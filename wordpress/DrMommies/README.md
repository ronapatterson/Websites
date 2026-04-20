# DrMommies

A WordPress site modelled after [drmommies.com](https://drmommies.com) -- a holistic nutrition platform featuring recipes, blog articles, and newsletter signups. Built with a fully custom theme on a LAMP stack.

## Architecture

```
                  Browser (http://drmommies.local)
                         |
                    Apache 2.4
                         |
              PHP 8.1 (mod_php)
                         |
                   WordPress 6.x
                    /         \
          Core CMS          Custom Theme (drmommies/)
                             /        |         \
                      CPT: recipe   Taxonomies   Newsletter
                      (meta fields)  (recipe_category,  (AJAX -> custom
                                      meal_type)         DB table)
                         |
                    Ratings, Reviews & FAQs
                    (AJAX -> custom DB tables)
                         |
                    MySQL 8.0
                  (drmommies_wp)
```

### Custom Theme

The theme lives at `wp-content/themes/drmommies/` and includes:

```
drmommies/
├── style.css              # Design system (CSS variables, full stylesheet)
├── functions.php          # CPTs, taxonomies, meta boxes, AJAX handlers, admin moderation
├── header.php / footer.php
├── front-page.php         # Homepage (hero, about, stats, testimonials, newsletter)
├── page-recipes.php       # Recipe grid with sort/filter by rating
├── page-blog.php          # Blog listing + FAQ
├── page-contact.php       # Contact page
├── single-recipe.php      # Recipe detail (ingredients, nutrition, ratings, reviews, FAQ)
├── single.php             # Blog post detail
├── archive-recipe.php     # Recipe category archive
├── page.php               # Generic page
├── js/main.js             # Newsletter AJAX, mobile menu, ratings, FAQ accordion
├── css/                   # Additional stylesheets
└── images/                # Theme images
```

### Custom Post Type: `recipe`

Registered in `functions.php` with these meta fields:

| Field | Key | Type |
|-------|-----|------|
| Prep Time | `_prep_time` | text |
| Cook Time | `_cook_time` | text |
| Servings | `_servings` | text |
| Difficulty | `_difficulty` | text |
| Ingredients | `_ingredients` | textarea |
| Calories | `_calories` | text |
| Protein | `_protein` | text |
| Carbs | `_carbs` | text |
| Fat | `_fat` | text |
| Fiber | `_fiber` | text |

### Taxonomies

- **`recipe_category`** (hierarchical) -- Breakfast, Lunch, Dinner, Snacks, Smoothies, Desserts
- **`meal_type`** (non-hierarchical)

### Ratings, Reviews & FAQs

Logged-in users can rate recipes (1-5 stars) with an optional written review. Each recipe also has a Q&A section where users can ask questions.

**Database tables** (created on theme activation):

| Table | Purpose |
|-------|---------|
| `wp_recipe_ratings` | Star ratings + optional review text. Unique constraint on `(recipe_id, user_id)` -- one rating per user per recipe. |
| `wp_recipe_faqs` | Recipe-specific questions and admin answers. |

**Cached post meta** per recipe (recalculated on each new rating):

| Key | Type | Description |
|-----|------|-------------|
| `_rating_average` | float | Average star rating (e.g. 4.3) |
| `_rating_count` | int | Total number of ratings |
| `_review_count` | int | Number of approved written reviews |

**Moderation:**

- Star-only ratings are auto-approved
- Ratings with review text require admin approval (stars still count immediately)
- FAQ questions always require admin approval
- Admin moderation page: Recipes > Moderation in wp-admin

**AJAX endpoints** (logged-in users only):

| Action | Description |
|--------|-------------|
| `rate_recipe` | Submit a 1-5 star rating with optional review text |
| `submit_faq` | Submit a question about a recipe |

**Frontend features:**

- Interactive star rating widget on single recipe pages (hover preview, click to rate)
- Reviews section showing approved reviews below recipe content
- FAQ accordion section with question submission form
- Star ratings displayed on recipe cards (recipes page, archive, related recipes)
- Sort recipes by: Newest, Highest Rated, Most Reviewed
- Filter recipes by: 4+ Stars, 3+ Stars

### Newsletter

AJAX form submissions are stored in a custom database table (`wp_newsletter_subscribers`), created on theme activation.

### Design System

- **Colors:** Dark purple `#2f1c6a`, primary `#673de6`, light accent `#ebe4ff`
- **Fonts:** Playfair Display (headings) + Inter (body) via Google Fonts
- **CSS variables** defined in `style.css` for consistency

## Prerequisites

Ubuntu/Debian (or WSL2):

```bash
sudo apt-get update
sudo apt-get install -y php php-mysql php-curl php-gd php-mbstring php-xml php-zip php-intl \
    mysql-server apache2 libapache2-mod-php
```

Optional -- WP-CLI:

```bash
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
chmod +x wp-cli.phar
sudo mv wp-cli.phar /usr/local/bin/wp
```

## Running Locally

### Start

```bash
sudo service mysql start
sudo service apache2 start
```

Then visit http://drmommies.local.

### Stop

```bash
sudo service mysql stop
sudo service apache2 stop
```

### WSL Users

Services don't persist after the terminal closes. Start them each session, or add to `~/.bashrc`:

```bash
sudo service mysql start > /dev/null 2>&1
sudo service apache2 start > /dev/null 2>&1
```

## Admin Access

| | |
|---|---|
| URL | http://drmommies.local/wp-admin |
| Username | `admin` |
| Password | `DrMommies2024!` |

## Database

| | |
|---|---|
| Name | `drmommies_wp` |
| User | `wp_user` |
| Password | `wp_secure_pass_2024` |
| Host | `127.0.0.1` |

Connect:

```bash
mysql -u wp_user -pwp_secure_pass_2024 -h 127.0.0.1 drmommies_wp
```

## Site Pages

| URL | Description |
|-----|-------------|
| `http://drmommies.local/` | Homepage -- hero, about, stats, recipes preview, testimonials, newsletter |
| `http://drmommies.local/recipes/` | Recipe grid with sort/filter by rating |
| `http://drmommies.local/recipes/<slug>/` | Individual recipe |
| `http://drmommies.local/blog/` | Blog listing + FAQ |
| `http://drmommies.local/<post-slug>/` | Blog post |
| `http://drmommies.local/about-us/` | About page |
| `http://drmommies.local/contact/` | Contact page |
| `http://drmommies.local/wp-admin/edit.php?post_type=recipe&page=recipe-moderation` | Reviews & FAQ moderation (admin) |

## Seeded Content

| Type | Count | Examples |
|------|-------|---------|
| Pages | 6 | Home, Blog, Recipes, About, Contact, Privacy |
| Recipes | 8 | Oat Banana Pancakes, Green Immunity Smoothie, Berry Chia Pudding, Rainbow Veggie Bowl, and more |
| Blog Posts | 5 | Holistic nutrition articles |
| Recipe Categories | 6 | Breakfast, Lunch, Dinner, Snacks, Smoothies, Desserts |

## Key File Locations

| Resource | Path |
|----------|------|
| WordPress root | `/home/dev/repos/Websites/wordpress/DrMommies` |
| Custom theme | `wp-content/themes/drmommies/` |
| WordPress config | `wp-config.php` |
| Apache vhost | `/etc/apache2/sites-available/drmommies.conf` |
| Apache error log | `/var/log/apache2/drmommies_error.log` |

## Troubleshooting

**"Error establishing a database connection"** -- MySQL stopped. Run `sudo service mysql start`.

**Pages return 404 (except homepage)** -- Flush permalinks:

```bash
sudo wp rewrite flush --allow-root --path=/home/dev/repos/Websites/wordpress/DrMommies
```

Or restart Apache after verifying `.htaccess` exists: `sudo service apache2 restart`.

**Apache won't start** -- Check config: `sudo apache2ctl configtest`.
