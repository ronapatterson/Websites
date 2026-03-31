# AscendMen WordPress Site — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build a fully functional WordPress site for AscendMen — a faith-based men's blog, community, and program platform — served locally at `http://ascendmen.local` and exportable to Hostinger.

**Architecture:** WordPress 6.x installed via WP-CLI in `/home/dev/repos/Websites/wordpress/AscendMen/`, served by Apache with a dedicated VirtualHost at `ascendmen.local`. Kadence theme provides the design foundation. Custom Eventbrite integration lives in a dedicated plugin `wp-content/plugins/ascendmen-eventbrite/`.

**Tech Stack:** WordPress 6.x, PHP 8.1, MySQL 8.0, Apache 2.4, WP-CLI 2.12, Kadence Theme, Ultimate Member, WooCommerce, The Events Calendar, Yoast SEO, UpdraftPlus

---

## File Map

| File/Directory | Responsibility |
|---|---|
| `/home/dev/repos/Websites/wordpress/AscendMen/` | WordPress root |
| `wp-config.php` | DB credentials, salts, debug settings |
| `wp-content/themes/kadence/` | Base theme (do not edit directly) |
| `wp-content/themes/kadence-child/` | Child theme for custom CSS overrides |
| `wp-content/plugins/ascendmen-eventbrite/` | Custom Eventbrite API integration plugin |
| `wp-content/plugins/ascendmen-eventbrite/ascendmen-eventbrite.php` | Plugin bootstrap, shortcode registration |
| `wp-content/plugins/ascendmen-eventbrite/includes/class-eventbrite-api.php` | Eventbrite REST API client |
| `wp-content/plugins/ascendmen-eventbrite/includes/class-registration-form.php` | Camp registration form handler |
| `wp-content/plugins/ascendmen-eventbrite/tests/test-eventbrite-api.php` | PHPUnit tests for API client |
| `/etc/apache2/sites-available/ascendmen.conf` | Apache VirtualHost config |

---

## Task 1: Start Services

**Files:** none

- [ ] **Step 1: Start MySQL**
```bash
service mysql start
```
Expected: `* Starting MySQL database server mysqld`

- [ ] **Step 2: Verify MySQL is running**
```bash
service mysql status
```
Expected: `* /usr/bin/mysqladmin  Ver 8.0... Uptime: ...`

- [ ] **Step 3: Start Apache**
```bash
service apache2 start
```
Expected: `* Starting Apache httpd web server apache2`

- [ ] **Step 4: Verify MySQL user and create database**
```bash
mysql -u wp_user -pwp_secure_pass_2024 -h 127.0.0.1 -e "CREATE DATABASE IF NOT EXISTS ascendmen_wp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```
Expected: no error output

- [ ] **Step 5: Verify database created**
```bash
mysql -u wp_user -pwp_secure_pass_2024 -h 127.0.0.1 -e "SHOW DATABASES LIKE 'ascendmen_wp';"
```
Expected: row showing `ascendmen_wp`

---

## Task 2: Install WordPress

**Files:**
- Create: `/home/dev/repos/Websites/wordpress/AscendMen/wp-config.php` (via WP-CLI)

- [ ] **Step 1: Download WordPress core**
```bash
wp core download --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: `Downloading WordPress 6.x.x (en_US)... Success: WordPress downloaded.`

- [ ] **Step 2: Create wp-config.php**
```bash
wp config create \
  --path=/home/dev/repos/Websites/wordpress/AscendMen \
  --dbname=ascendmen_wp \
  --dbuser=wp_user \
  --dbpass=wp_secure_pass_2024 \
  --dbhost=127.0.0.1 \
  --dbprefix=am_ \
  --allow-root
```
Expected: `Success: Generated 'wp-config.php' file.`

- [ ] **Step 3: Install WordPress**
```bash
wp core install \
  --path=/home/dev/repos/Websites/wordpress/AscendMen \
  --url=http://ascendmen.local \
  --title="AscendMen" \
  --admin_user=ascendadmin \
  --admin_password=AscendAdmin2024! \
  --admin_email=admin@ascendmen.com \
  --allow-root
```
Expected: `Success: WordPress installed successfully.`

- [ ] **Step 4: Verify install**
```bash
wp core is-installed --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: exit code 0 (no output = success)

- [ ] **Step 5: Set permalink structure**
```bash
wp rewrite structure '/%postname%/' \
  --path=/home/dev/repos/Websites/wordpress/AscendMen \
  --allow-root
```
Expected: `Success: Rewrite structure set.`

---

## Task 3: Configure Apache VirtualHost

**Files:**
- Create: `/etc/apache2/sites-available/ascendmen.conf`
- Modify: `/etc/hosts`

- [ ] **Step 1: Create VirtualHost config**

Write this file to `/etc/apache2/sites-available/ascendmen.conf`:
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

- [ ] **Step 2: Enable the site**
```bash
a2ensite ascendmen.conf
```
Expected: `Enabling site ascendmen.`

- [ ] **Step 3: Ensure mod_rewrite is enabled**
```bash
a2enmod rewrite
```
Expected: `Module rewrite already enabled` or `Enabling module rewrite.`

- [ ] **Step 4: Add ascendmen.local to /etc/hosts**
```bash
echo "127.0.0.1  ascendmen.local" >> /etc/hosts
```

- [ ] **Step 5: Reload Apache**
```bash
service apache2 reload
```
Expected: `* Reloading Apache httpd web server apache2`

- [ ] **Step 6: Verify site is reachable**
```bash
curl -s -o /dev/null -w "%{http_code}" http://ascendmen.local/
```
Expected: `200` or `301`

---

## Task 4: Install Kadence Theme & Child Theme

**Files:**
- Create: `wp-content/themes/kadence-child/style.css`
- Create: `wp-content/themes/kadence-child/functions.php`

- [ ] **Step 1: Install Kadence theme**
```bash
wp theme install kadence \
  --path=/home/dev/repos/Websites/wordpress/AscendMen \
  --activate \
  --allow-root
```
Expected: `Installing Kadence... Success: Installed 1 of 1 themes.`

- [ ] **Step 2: Create child theme directory**
```bash
mkdir -p /home/dev/repos/Websites/wordpress/AscendMen/wp-content/themes/kadence-child
```

- [ ] **Step 3: Create child theme style.css**

Write to `wp-content/themes/kadence-child/style.css`:
```css
/*
 Theme Name:   Kadence Child — AscendMen
 Theme URI:    https://ascendmen.com
 Description:  AscendMen child theme for Kadence
 Author:       AscendMen
 Template:     kadence
 Version:      1.0.0
*/

/* Brand Variables */
:root {
  --am-flame-blue:   #29ABE2;
  --am-navy:         #1B2A4A;
  --am-steel-blue:   #4A7FC1;
  --am-white:        #FFFFFF;
  --am-dark-nav:     #111d33;
}
```

- [ ] **Step 4: Create child theme functions.php**

Write to `wp-content/themes/kadence-child/functions.php`:
```php
<?php
add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'kadence-parent-style',
        get_template_directory_uri() . '/style.css'
    );
    wp_enqueue_style(
        'kadence-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        [ 'kadence-parent-style' ]
    );
});
```

- [ ] **Step 5: Activate child theme**
```bash
wp theme activate kadence-child \
  --path=/home/dev/repos/Websites/wordpress/AscendMen \
  --allow-root
```
Expected: `Success: Switched to 'Kadence Child — AscendMen' theme.`

- [ ] **Step 6: Verify child theme is active**
```bash
wp theme status kadence-child \
  --path=/home/dev/repos/Websites/wordpress/AscendMen \
  --allow-root
```
Expected: `Active` status shown

---

## Task 5: Configure Kadence Brand Colors & Logo

**Files:**
- Modify: WordPress options (via WP-CLI)

- [ ] **Step 1: Set Kadence global palette via theme_mods**
```bash
wp eval '
$mods = [
  "kadence_global_palette" => json_encode([
    "palette" => [
      ["color" => "#29ABE2", "slug" => "palette1", "name" => "Flame Blue"],
      ["color" => "#1B2A4A", "slug" => "palette2", "name" => "Mountain Navy"],
      ["color" => "#4A7FC1", "slug" => "palette3", "name" => "Steel Blue"],
      ["color" => "#FFFFFF", "slug" => "palette4", "name" => "Summit White"],
      ["color" => "#111d33", "slug" => "palette5", "name" => "Dark Nav"],
      ["color" => "#8aa4c8", "slug" => "palette6", "name" => "Muted Blue"],
      ["color" => "#607090", "slug" => "palette7", "name" => "Slate"],
      ["color" => "#0d1827", "slug" => "palette8", "name" => "Deep Night"],
    ]
  ]),
  "header_color_setting" => "#1B2A4A",
  "footer_color_setting" => "#111d33",
];
foreach ($mods as $key => $val) {
    set_theme_mod($key, $val);
}
echo "Brand colors set.\n";
' --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: `Brand colors set.`

- [ ] **Step 2: Upload logo and capture attachment ID**

```bash
LOGO_ID=$(wp media import "/home/dev/repos/Websites/wordpress/AscendMen/ASCEND MEN PNG TRANSPARENT.png" \
  --title="AscendMen Logo" \
  --path=/home/dev/repos/Websites/wordpress/AscendMen \
  --allow-root \
  --porcelain)
echo "LOGO_ID=$LOGO_ID"
```
Expected: prints `LOGO_ID=<number>` (e.g., `LOGO_ID=5`)

- [ ] **Step 3: Set logo as site logo and capture its URL**

```bash
LOGO_URL=$(wp eval "echo wp_get_attachment_url($LOGO_ID);" \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root)

wp eval "set_theme_mod('custom_logo', (int) '$LOGO_ID'); echo 'Logo set: $LOGO_URL\n';" \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: `Logo set: http://ascendmen.local/wp-content/uploads/....png`

> Keep `$LOGO_ID` and `$LOGO_URL` in your shell — they are used in Task 14.

- [ ] **Step 4: Set site title and tagline**
```bash
wp option update blogname "AscendMen" \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
wp option update blogdescription "Rise Into Who God Made You" \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: `Success: Updated 'blogname' option.` (x2)

---

## Task 6: Install Core Plugins

**Files:** none (plugin installs via WP-CLI)

- [ ] **Step 1: Install Ultimate Member**
```bash
wp plugin install ultimate-member \
  --activate \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: `Success: Installed 1 of 1 plugins.`

- [ ] **Step 2: Install WooCommerce**
```bash
wp plugin install woocommerce \
  --activate \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: `Success: Installed 1 of 1 plugins.`

- [ ] **Step 3: Install The Events Calendar**
```bash
wp plugin install the-events-calendar \
  --activate \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: `Success: Installed 1 of 1 plugins.`

- [ ] **Step 4: Install Kadence Blocks**
```bash
wp plugin install kadence-blocks \
  --activate \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: `Success: Installed 1 of 1 plugins.`

- [ ] **Step 5: Install Yoast SEO**
```bash
wp plugin install wordpress-seo \
  --activate \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: `Success: Installed 1 of 1 plugins.`

- [ ] **Step 6: Install WP Mail SMTP**
```bash
wp plugin install wp-mail-smtp \
  --activate \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: `Success: Installed 1 of 1 plugins.`

- [ ] **Step 7: Install UpdraftPlus**
```bash
wp plugin install updraftplus \
  --activate \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: `Success: Installed 1 of 1 plugins.`

- [ ] **Step 8: Verify all plugins active**
```bash
wp plugin list \
  --status=active \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: table showing all 7 plugins as active

---

## Task 7: Create Blog Categories

**Files:** none (wp-cli term creation)

- [ ] **Step 1: Delete default "Uncategorized" rename to Identity**
```bash
wp term update category 1 --name="Identity" --slug="identity" \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: `Success: Term updated.`

- [ ] **Step 2: Create remaining categories**
```bash
wp term create category "Leadership" --slug="leadership" \
  --description="Leading at home, work, and church" \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root

wp term create category "Fatherhood" --slug="fatherhood" \
  --description="Being the dad God called you to be" \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root

wp term create category "Marriage" --slug="marriage" \
  --description="Husbands, relationships, and covenant" \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root

wp term create category "Faith & Scripture" --slug="faith-scripture" \
  --description="Biblical grounding and devotionals" \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root

wp term create category "Camp Stories" --slug="camp-stories" \
  --description="Testimonies and recaps from camps" \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root

wp term create category "Outreach" --slug="outreach" \
  --description="Community service highlights" \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: `Success: Created term.` for each

- [ ] **Step 3: Verify categories**
```bash
wp term list category \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: 7 categories listed (Identity, Leadership, Fatherhood, Marriage, Faith & Scripture, Camp Stories, Outreach)

---

## Task 8: Create All Pages

**Files:** none (WP-CLI page creation)

- [ ] **Step 1: Create static front page (capture ID)**
```bash
HOME_ID=$(wp post create \
  --post_type=page \
  --post_title="Home" \
  --post_status=publish \
  --post_content="<!-- wp:paragraph --><p>Welcome to AscendMen</p><!-- /wp:paragraph -->" \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root \
  --porcelain)
echo "HOME_ID=$HOME_ID"
```
Expected: prints `HOME_ID=<number>` (e.g., `HOME_ID=2`). The `$HOME_ID` shell variable is used in Steps 3 and later in Task 14.

- [ ] **Step 2: Create Blog page (capture ID)**
```bash
BLOG_ID=$(wp post create \
  --post_type=page \
  --post_title="Blog" \
  --post_name="blog" \
  --post_status=publish \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root \
  --porcelain)
echo "BLOG_ID=$BLOG_ID"
```
Expected: prints `BLOG_ID=<number>`. The `$BLOG_ID` shell variable is used in Step 4.

- [ ] **Step 3: Create remaining pages**
```bash
for page in "About" "Programs" "Camps" "Outreach" "Community" "Contact"; do
  wp post create \
    --post_type=page \
    --post_title="$page" \
    --post_name="$(echo $page | tr '[:upper:]' '[:lower:]')" \
    --post_status=publish \
    --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root \
    --porcelain
done
```
Expected: ID printed for each page (6 IDs)

- [ ] **Step 4: Set Home as static front page (uses $HOME_ID and $BLOG_ID from Steps 1-2)**
```bash
wp option update show_on_front page \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
wp option update page_on_front $HOME_ID \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
wp option update page_for_posts $BLOG_ID \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: `Success: Updated 'show_on_front' option.` (x3)

- [ ] **Step 5: Verify pages**
```bash
wp post list --post_type=page --post_status=publish \
  --fields=ID,post_title,post_name \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: 8 pages listed (Home, Blog, About, Programs, Camps, Outreach, Community, Contact)

---

## Task 9: Build Navigation Menu

**Files:** none (WP-CLI menu)

- [ ] **Step 1: Create primary menu**
```bash
wp menu create "Primary Navigation" \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: `Success: Created menu 1.`

- [ ] **Step 2: Add pages to menu (use page IDs from Task 8)**
```bash
# Get page IDs
wp post list --post_type=page --post_status=publish \
  --fields=ID,post_title \
  --format=csv \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Then add each page to the menu:
```bash
for title in "About" "Blog" "Programs" "Camps" "Outreach" "Community"; do
  PAGE_ID=$(wp post list --post_type=page --post_status=publish \
    --name="$(echo $title | tr '[:upper:]' '[:lower:]')" \
    --field=ID \
    --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root)
  wp menu item add-post primary-navigation $PAGE_ID \
    --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
done
```
Expected: `Success: Menu item added.` (x6)

- [ ] **Step 3: Assign menu to primary location**
```bash
wp menu location assign primary-navigation primary \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: `Success: Assigned location to menu.`

- [ ] **Step 4: Verify menu**
```bash
wp menu item list primary-navigation \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: 6 menu items listed

---

## Task 10: Configure Ultimate Member (Membership)

**Files:** none (WP options + WP-CLI eval)

- [ ] **Step 1: Query UM's actual form IDs**

UM auto-creates forms as `um_form` post type on activation, but IDs are not guaranteed to be 1/2/3 — they depend on DB auto-increment state. Query them first:

```bash
wp post list --post_type=um_form \
  --fields=ID,post_title \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected output (IDs will vary):
```
+----+----------------+
| ID | post_title     |
+----+----------------+
| 12 | Login          |
| 13 | Register       |
| 14 | Password Reset |
+----+----------------+
```
Note the IDs for Login, Register, and Password Reset forms. Use them in Step 2.

- [ ] **Step 2: Create UM pages with correct form IDs**

Replace `LOGIN_FORM_ID`, `REGISTER_FORM_ID`, and `RESET_FORM_ID` with the actual IDs from Step 1:
```bash
UM_LOGIN_ID=$(wp post list --post_type=um_form --name=login --field=ID \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root)
UM_REG_ID=$(wp post list --post_type=um_form --name=register --field=ID \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root)
UM_RESET_ID=$(wp post list --post_type=um_form --name=password-reset --field=ID \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root)

echo "Login form: $UM_LOGIN_ID | Register form: $UM_REG_ID | Reset form: $UM_RESET_ID"
```
Expected: three non-empty IDs printed.

Then create pages using those IDs:
```bash
wp eval "
\$pages = [
  'User'           => ['slug' => 'user',           'content' => '[ultimatemember]'],
  'Login'          => ['slug' => 'login',          'content' => '[ultimatemember form_id=\"$UM_LOGIN_ID\"]'],
  'Register'       => ['slug' => 'register',       'content' => '[ultimatemember form_id=\"$UM_REG_ID\"]'],
  'Members'        => ['slug' => 'members',        'content' => '[ultimatemember_directory]'],
  'Password Reset' => ['slug' => 'password-reset', 'content' => '[ultimatemember form_id=\"$UM_RESET_ID\"]'],
  'Account'        => ['slug' => 'account',        'content' => '[ultimatemember_account]'],
  'Logout'         => ['slug' => 'logout',         'content' => '[ultimatemember_logout]'],
];
foreach (\$pages as \$title => \$cfg) {
  \$existing = get_page_by_path(\$cfg['slug']);
  if (!\$existing) {
    \$id = wp_insert_post([
      'post_title'   => \$title,
      'post_name'    => \$cfg['slug'],
      'post_status'  => 'publish',
      'post_type'    => 'page',
      'post_content' => \$cfg['content'],
    ]);
    echo 'Created page: ' . \$title . ' (ID ' . \$id . \")\n\";
  } else {
    echo 'Page exists: ' . \$title . \"\n\";
  }
}
" --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: `Created page:` or `Page exists:` for each of the 7 pages

- [ ] **Step 2: Configure UM core settings**
```bash
wp eval '
$settings = [
  "um_login_page"          => get_page_by_path("login")->ID,
  "um_register_page"       => get_page_by_path("register")->ID,
  "um_members_page"        => get_page_by_path("members")->ID,
  "um_account_page"        => get_page_by_path("account")->ID,
  "um_logout_page"         => get_page_by_path("logout")->ID,
  "um_password_reset_page" => get_page_by_path("password-reset")->ID,
  "um_user_page"           => get_page_by_path("user")->ID,
  "um_require_emailactivation_registration" => 1,
  "um_members_directory_default_role" => "subscriber",
];
foreach ($settings as $key => $val) {
  update_option($key, $val);
}
echo "UM settings configured.\n";
' --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: `UM settings configured.`

- [ ] **Step 3: Add JOIN/LOGIN link to navigation**
```bash
wp menu item add-custom primary-navigation "Join / Login" http://ascendmen.local/register/ \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: `Success: Menu item added.`

- [ ] **Step 4: Verify registration page works**
```bash
curl -s -o /dev/null -w "%{http_code}" http://ascendmen.local/register/
```
Expected: `200`

---

## Task 11: Configure WooCommerce & Programs Custom Post Type

**Files:**
- Create: `wp-content/themes/kadence-child/includes/programs-cpt.php`
- Modify: `wp-content/themes/kadence-child/functions.php`

- [ ] **Step 1: Run WooCommerce initial setup**
```bash
wp eval '
update_option("woocommerce_store_address", "");
update_option("woocommerce_default_country", "US");
update_option("woocommerce_currency", "USD");
update_option("woocommerce_sell_in_person", "no");
// Disable shipping + tax for digital programs
update_option("woocommerce_ship_to_countries", "disabled");
update_option("woocommerce_calc_taxes", "no");
echo "WooCommerce configured.\n";
' --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: `WooCommerce configured.`

- [ ] **Step 2: Create Programs CPT file**

Write to `wp-content/themes/kadence-child/includes/programs-cpt.php`:
```php
<?php
/**
 * AscendMen Programs Custom Post Type
 */

function ascendmen_register_programs_cpt() {
    register_post_type( 'am_program', [
        'labels' => [
            'name'               => 'Programs',
            'singular_name'      => 'Program',
            'add_new_item'       => 'Add New Program',
            'edit_item'          => 'Edit Program',
            'view_item'          => 'View Program',
            'search_items'       => 'Search Programs',
            'not_found'          => 'No programs found.',
            'menu_name'          => 'Programs',
        ],
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => [ 'slug' => 'programs' ],
        'supports'     => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
        'menu_icon'    => 'dashicons-groups',
        'show_in_rest' => true,
    ]);

    register_taxonomy( 'am_program_type', 'am_program', [
        'labels' => [
            'name'          => 'Program Types',
            'singular_name' => 'Program Type',
            'menu_name'     => 'Program Types',
        ],
        'hierarchical'  => true,
        'public'        => true,
        'rewrite'       => [ 'slug' => 'program-type' ],
        'show_in_rest'  => true,
    ]);
}
add_action( 'init', 'ascendmen_register_programs_cpt' );

/**
 * Add program meta: price_type (free|paid), members_only (yes|no)
 */
function ascendmen_register_program_meta() {
    foreach ( ['am_price_type', 'am_members_only', 'am_woo_product_id'] as $key ) {
        register_post_meta( 'am_program', $key, [
            'show_in_rest'  => true,
            'single'        => true,
            'type'          => 'string',
            'auth_callback' => function() { return current_user_can('edit_posts'); },
        ]);
    }
}
add_action( 'init', 'ascendmen_register_program_meta' );
```

- [ ] **Step 3: Add include to child theme functions.php**

Append to `wp-content/themes/kadence-child/functions.php`:
```php
require_once get_stylesheet_directory() . '/includes/programs-cpt.php';
```

- [ ] **Step 4: Flush rewrite rules**
```bash
wp rewrite flush \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: `Success: Rewrite rules flushed.`

- [ ] **Step 5: Create Program Type terms**
```bash
wp eval '
$types = ["Course", "Workshop", "Recurring Meeting", "One-Time Event"];
foreach ($types as $type) {
  wp_insert_term($type, "am_program_type");
  echo "Created type: $type\n";
}
' --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: `Created type:` for each

- [ ] **Step 6: Verify CPT registered**
```bash
wp post-type get am_program \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: post type details shown

---

## Task 12: Build Eventbrite Integration Plugin

**Files:**
- Create: `wp-content/plugins/ascendmen-eventbrite/ascendmen-eventbrite.php`
- Create: `wp-content/plugins/ascendmen-eventbrite/includes/class-eventbrite-api.php`
- Create: `wp-content/plugins/ascendmen-eventbrite/includes/class-registration-form.php`
- Create: `wp-content/plugins/ascendmen-eventbrite/tests/test-eventbrite-api.php`

- [ ] **Step 1: Create plugin directory**
```bash
mkdir -p /home/dev/repos/Websites/wordpress/AscendMen/wp-content/plugins/ascendmen-eventbrite/includes
mkdir -p /home/dev/repos/Websites/wordpress/AscendMen/wp-content/plugins/ascendmen-eventbrite/tests
```

- [ ] **Step 2: Write failing test for Eventbrite API client**

Write to `wp-content/plugins/ascendmen-eventbrite/tests/test-eventbrite-api.php`:
```php
<?php
/**
 * Tests for AscendMen_Eventbrite_API
 * Run: cd /home/dev/repos/Websites/wordpress/AscendMen && ./vendor/bin/phpunit wp-content/plugins/ascendmen-eventbrite/tests/
 */

class Test_Eventbrite_API extends WP_UnitTestCase {

    public function test_get_event_returns_null_without_api_key() {
        $api = new AscendMen_Eventbrite_API( '' );
        $result = $api->get_event( '12345' );
        $this->assertNull( $result );
    }

    public function test_build_attendee_payload_includes_required_fields() {
        $api = new AscendMen_Eventbrite_API( 'fake-key' );
        $payload = $api->build_attendee_payload([
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'email'      => 'john@example.com',
        ]);
        $this->assertArrayHasKey( 'attendees', $payload );
        $this->assertEquals( 'John', $payload['attendees'][0]['profile']['first_name'] );
        $this->assertEquals( 'Doe',  $payload['attendees'][0]['profile']['last_name'] );
        $this->assertEquals( 'john@example.com', $payload['attendees'][0]['profile']['email'] );
    }

    public function test_build_attendee_payload_rejects_missing_email() {
        $api = new AscendMen_Eventbrite_API( 'fake-key' );
        $this->expectException( InvalidArgumentException::class );
        $api->build_attendee_payload([
            'first_name' => 'John',
            'last_name'  => 'Doe',
        ]);
    }
}
```

- [ ] **Step 3: Write the Eventbrite API client**

Write to `wp-content/plugins/ascendmen-eventbrite/includes/class-eventbrite-api.php`:
```php
<?php
/**
 * Eventbrite REST API client for AscendMen
 */
class AscendMen_Eventbrite_API {

    private string $api_key;
    private string $base_url = 'https://www.eventbriteapi.com/v3';

    public function __construct( string $api_key ) {
        $this->api_key = $api_key;
    }

    /**
     * Fetch event details from Eventbrite.
     * Returns associative array on success, null if no API key or request fails.
     */
    public function get_event( string $event_id ): ?array {
        if ( empty( $this->api_key ) ) {
            return null;
        }
        $response = wp_remote_get(
            "{$this->base_url}/events/{$event_id}/",
            [ 'headers' => [ 'Authorization' => "Bearer {$this->api_key}" ] ]
        );
        if ( is_wp_error( $response ) ) {
            return null;
        }
        return json_decode( wp_remote_retrieve_body( $response ), true );
    }

    /**
     * Build Eventbrite attendee registration payload.
     *
     * @throws InvalidArgumentException if required fields are missing.
     */
    public function build_attendee_payload( array $data ): array {
        foreach ( ['first_name', 'last_name', 'email'] as $field ) {
            if ( empty( $data[ $field ] ) ) {
                throw new InvalidArgumentException( "Missing required field: {$field}" );
            }
        }
        return [
            'attendees' => [[
                'profile' => [
                    'first_name' => sanitize_text_field( $data['first_name'] ),
                    'last_name'  => sanitize_text_field( $data['last_name'] ),
                    'email'      => sanitize_email( $data['email'] ),
                ],
            ]],
        ];
    }

    /**
     * Register an attendee for an event.
     * Returns Eventbrite order array on success, WP_Error on failure.
     */
    public function register_attendee( string $event_id, array $attendee_data ): array|WP_Error {
        if ( empty( $this->api_key ) ) {
            return new WP_Error( 'no_api_key', 'Eventbrite API key not configured.' );
        }
        $payload = $this->build_attendee_payload( $attendee_data );
        $response = wp_remote_post(
            "{$this->base_url}/events/{$event_id}/attendees/",
            [
                'headers' => [
                    'Authorization' => "Bearer {$this->api_key}",
                    'Content-Type'  => 'application/json',
                ],
                'body' => wp_json_encode( $payload ),
            ]
        );
        if ( is_wp_error( $response ) ) {
            return $response;
        }
        $body = json_decode( wp_remote_retrieve_body( $response ), true );
        $code = wp_remote_retrieve_response_code( $response );
        if ( $code >= 400 ) {
            return new WP_Error(
                'eventbrite_error',
                $body['error_description'] ?? 'Unknown Eventbrite error',
                [ 'status' => $code ]
            );
        }
        return $body;
    }
}
```

- [ ] **Step 4: Write registration form handler**

Write to `wp-content/plugins/ascendmen-eventbrite/includes/class-registration-form.php`:
```php
<?php
/**
 * Camp registration form shortcode and form processing
 * Usage: [ascendmen_camp_register event_id="12345"]
 */
class AscendMen_Registration_Form {

    private AscendMen_Eventbrite_API $api;

    public function __construct( AscendMen_Eventbrite_API $api ) {
        $this->api = $api;
    }

    public function init(): void {
        add_shortcode( 'ascendmen_camp_register', [ $this, 'render_form' ] );
        add_action( 'wp_ajax_am_camp_register',        [ $this, 'handle_submission' ] );
        add_action( 'wp_ajax_nopriv_am_camp_register', [ $this, 'handle_submission' ] );
    }

    public function render_form( array $atts ): string {
        $atts    = shortcode_atts( [ 'event_id' => '' ], $atts );
        $nonce   = wp_create_nonce( 'am_camp_register' );
        $ajax    = admin_url( 'admin-ajax.php' );
        ob_start();
        ?>
        <div class="am-camp-register-wrap">
          <form id="am-camp-register-form" data-event-id="<?php echo esc_attr( $atts['event_id'] ); ?>">
            <div class="am-form-group">
              <label>First Name</label>
              <input type="text" name="first_name" required class="am-input">
            </div>
            <div class="am-form-group">
              <label>Last Name</label>
              <input type="text" name="last_name" required class="am-input">
            </div>
            <div class="am-form-group">
              <label>Email Address</label>
              <input type="email" name="email" required class="am-input">
            </div>
            <input type="hidden" name="action"   value="am_camp_register">
            <input type="hidden" name="event_id" value="<?php echo esc_attr( $atts['event_id'] ); ?>">
            <input type="hidden" name="_nonce"   value="<?php echo esc_attr( $nonce ); ?>">
            <button type="submit" class="am-btn-primary">Register for Camp</button>
            <div class="am-form-message" style="display:none;"></div>
          </form>
          <script>
          document.getElementById('am-camp-register-form').addEventListener('submit', function(e) {
            e.preventDefault();
            var form = e.target;
            var msg  = form.querySelector('.am-form-message');
            var data = new FormData(form);
            fetch('<?php echo esc_url($ajax); ?>', { method:'POST', body: data })
              .then(r => r.json())
              .then(function(res) {
                msg.style.display = 'block';
                msg.textContent = res.data.message;
                msg.style.color = res.success ? '#29ABE2' : '#e94560';
                if (res.success) form.reset();
              });
          });
          </script>
        </div>
        <?php
        return ob_get_clean();
    }

    public function handle_submission(): void {
        if ( ! wp_verify_nonce( $_POST['_nonce'] ?? '', 'am_camp_register' ) ) {
            wp_send_json_error( [ 'message' => 'Security check failed.' ] );
        }
        $data = [
            'first_name' => sanitize_text_field( $_POST['first_name'] ?? '' ),
            'last_name'  => sanitize_text_field( $_POST['last_name']  ?? '' ),
            'email'      => sanitize_email(      $_POST['email']       ?? '' ),
        ];
        $event_id = sanitize_text_field( $_POST['event_id'] ?? '' );
        if ( empty( $event_id ) ) {
            wp_send_json_error( [ 'message' => 'Event ID missing.' ] );
        }
        $result = $this->api->register_attendee( $event_id, $data );
        if ( is_wp_error( $result ) ) {
            wp_send_json_error( [ 'message' => $result->get_error_message() ] );
        }
        wp_send_json_success( [ 'message' => 'You\'re registered! Check your email for confirmation.' ] );
    }
}
```

- [ ] **Step 5: Write plugin bootstrap**

Write to `wp-content/plugins/ascendmen-eventbrite/ascendmen-eventbrite.php`:
```php
<?php
/**
 * Plugin Name:  AscendMen Eventbrite Integration
 * Description:  Camp registration via Eventbrite API with custom branded form.
 * Version:      1.0.0
 * Author:       AscendMen
 * Text Domain:  ascendmen-eventbrite
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'AM_EB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once AM_EB_PLUGIN_DIR . 'includes/class-eventbrite-api.php';
require_once AM_EB_PLUGIN_DIR . 'includes/class-registration-form.php';

add_action( 'plugins_loaded', function() {
    $api_key = get_option( 'am_eventbrite_api_key', '' );
    $api     = new AscendMen_Eventbrite_API( $api_key );
    $form    = new AscendMen_Registration_Form( $api );
    $form->init();
});

// Admin setting for API key
add_action( 'admin_menu', function() {
    add_options_page(
        'Eventbrite Settings',
        'Eventbrite',
        'manage_options',
        'am-eventbrite',
        function() {
            if ( isset( $_POST['am_eb_nonce'] ) && wp_verify_nonce( $_POST['am_eb_nonce'], 'am_eb_save' ) ) {
                update_option( 'am_eventbrite_api_key', sanitize_text_field( $_POST['am_eventbrite_api_key'] ) );
                echo '<div class="notice notice-success"><p>Settings saved.</p></div>';
            }
            $key = get_option( 'am_eventbrite_api_key', '' );
            ?>
            <div class="wrap">
              <h1>Eventbrite Integration</h1>
              <form method="post">
                <?php wp_nonce_field( 'am_eb_save', 'am_eb_nonce' ); ?>
                <table class="form-table">
                  <tr>
                    <th>Eventbrite API Key</th>
                    <td><input type="text" name="am_eventbrite_api_key" value="<?php echo esc_attr($key); ?>" class="regular-text"></td>
                  </tr>
                </table>
                <?php submit_button(); ?>
              </form>
            </div>
            <?php
        }
    );
});
```

- [ ] **Step 6: Activate the plugin**
```bash
wp plugin activate ascendmen-eventbrite \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: `Plugin 'ascendmen-eventbrite' activated.`

- [ ] **Step 7: Verify plugin active**
```bash
wp plugin status ascendmen-eventbrite \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: Status shows `Active`

---

## Task 13: Configure The Events Calendar for Camps

**Files:** none (WP options)

- [ ] **Step 1: Configure Events Calendar settings**
```bash
wp eval '
update_option("tribe_events_calendar_options", array_merge(
  get_option("tribe_events_calendar_options", []),
  [
    "postExceptionBeforeHooks" => 0,
    "defaultCurrencySymbol"    => "\$",
    "defaultCurrencyCode"      => "USD",
  ]
));
echo "Events Calendar configured.\n";
' --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: `Events Calendar configured.`

- [ ] **Step 2: Create a sample camp event for testing**
```bash
wp eval '
$event_id = wp_insert_post([
  "post_title"   => "Spring Ascend Camp 2026",
  "post_type"    => "tribe_events",
  "post_status"  => "publish",
  "post_content" => "Join us for a transformative weekend in the wilderness. As Sons of God, we will worship, be challenged, and go home changed.",
]);
update_post_meta($event_id, "_EventStartDate",    "2026-05-15 08:00:00");
update_post_meta($event_id, "_EventEndDate",      "2026-05-17 17:00:00");
update_post_meta($event_id, "_EventVenueID",      0);
update_post_meta($event_id, "_EventCost",         "150");
update_post_meta($event_id, "_EventURL",          "");
echo "Sample camp created: ID $event_id\n";
' --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: `Sample camp created: ID X`

- [ ] **Step 3: Verify events endpoint**
```bash
curl -s -o /dev/null -w "%{http_code}" http://ascendmen.local/events/
```
Expected: `200`

---

## Task 14: Build Home Page Content

**Files:**
- Modify: Home page post content (via WP-CLI)

- [ ] **Step 1: Recover shell variables if in a new session**

If the shell session was restarted since Task 8/Task 5, re-query the IDs:
```bash
HOME_ID=$(wp post list --post_type=page --name=home --field=ID \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root)
LOGO_ID=$(wp post list --post_type=attachment --post_mime_type=image \
  --search="AscendMen Logo" --field=ID \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root | head -1)
LOGO_URL=$(wp eval "echo wp_get_attachment_url($LOGO_ID);" \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root)
echo "HOME_ID=$HOME_ID | LOGO_URL=$LOGO_URL"
```
Expected: both values non-empty.

- [ ] **Step 2: Update Home page with block content (automated logo URL substitution)**

The `$LOGO_URL` variable is expanded by the shell into the heredoc, so the actual URL is embedded automatically:

```bash
HOME_CONTENT=$(cat <<BLOCK
<!-- wp:kadence/rowlayout {"uniqueID":"home-hero","columns":1,"colLayout":"equal","backgroundType":"normal","background":"#1B2A4A","minHeight":600,"verticalAlignment":"center","padding":{"top":80,"bottom":80,"left":40,"right":40}} -->
<!-- wp:kadence/column {"id":1} -->
<!-- wp:image {"align":"center","sizeSlug":"medium","className":"am-hero-logo"} -->
<figure class="wp-block-image aligncenter size-medium am-hero-logo"><img src="${LOGO_URL}" alt="AscendMen Logo" /></figure>
<!-- /wp:image -->
<!-- wp:heading {"textAlign":"center","level":1,"style":{"color":{"text":"#FFFFFF"},"typography":{"letterSpacing":"4px","textTransform":"uppercase"}}} -->
<h1 class="has-text-align-center" style="color:#FFFFFF;letter-spacing:4px;text-transform:uppercase;">Rise Into Who God Made You</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","style":{"color":{"text":"#8aa4c8"}}} -->
<p class="has-text-align-center" style="color:#8aa4c8;">A brotherhood for men ready to leave behind the world&#8217;s definition and step into their God-given identity as Sons of God.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<!-- wp:button {"backgroundColor":"#29ABE2","textColor":"#ffffff"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-background has-text-color" style="background-color:#29ABE2;color:#ffffff;" href="/register/">Join the Brotherhood</a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-outline","style":{"color":{"text":"#4A7FC1","background":"transparent"},"border":{"color":"#4A7FC1"}}} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-text-color" style="color:#4A7FC1;border-color:#4A7FC1;" href="/blog/">Read the Blog</a></div>
<!-- /wp:button -->
<!-- /wp:buttons -->
<!-- /wp:kadence/column -->
<!-- /wp:kadence/rowlayout -->
BLOCK
)

wp post update $HOME_ID \
  --post_content="$HOME_CONTENT" \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: `Success: Updated post X.`

- [ ] **Step 3: Verify home page loads**
```bash
curl -s -o /dev/null -w "%{http_code}" http://ascendmen.local/
```
Expected: `200`

---

## Task 15: Final Configuration

**Files:** none

- [ ] **Step 1: Set default timezone**
```bash
wp option update timezone_string "America/New_York" \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: `Success: Updated 'timezone_string' option.`

- [ ] **Step 2: Set uploads directory permissions**
```bash
chown -R www-data:www-data /home/dev/repos/Websites/wordpress/AscendMen/wp-content/uploads
chmod -R 755 /home/dev/repos/Websites/wordpress/AscendMen/wp-content/uploads
```

- [ ] **Step 3: Disable file editing in wp-config for security**
```bash
wp config set DISALLOW_FILE_EDIT true --raw \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: `Success: Updated the constant 'DISALLOW_FILE_EDIT' in the 'wp-config.php' file.`

- [ ] **Step 4: Enable debug logging (dev only)**
```bash
wp config set WP_DEBUG true --raw \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
wp config set WP_DEBUG_LOG true --raw \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
wp config set WP_DEBUG_DISPLAY false --raw \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```

- [ ] **Step 5: Final rewrite flush**
```bash
wp rewrite flush --hard \
  --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
Expected: `Success: Rewrite rules flushed.`

- [ ] **Step 6: Full site smoke test**
```bash
for path in "/" "/about/" "/blog/" "/programs/" "/camps/" "/outreach/" "/community/" "/contact/" "/register/" "/login/" "/events/"; do
  code=$(curl -s -o /dev/null -w "%{http_code}" "http://ascendmen.local$path")
  echo "$path → $code"
done
```
Expected: all paths return `200` or `301`

- [ ] **Step 7: Verify admin dashboard accessible**
```bash
curl -s -o /dev/null -w "%{http_code}" http://ascendmen.local/wp-admin/
```
Expected: `302` (redirect to login) or `200`

---

## Hostinger Migration Checklist (for later)

When ready to go live:
1. In UpdraftPlus: **Backup Now** (include database + files)
2. Download the backup zip files
3. On Hostinger: install WordPress, then install UpdraftPlus
4. Upload backup files and restore
5. Run: `wp search-replace 'http://ascendmen.local' 'https://yourdomain.com' --allow-root`
6. Update Eventbrite API key in **Settings → Eventbrite**
7. Update WP Mail SMTP with production SMTP credentials
8. Update `siteurl` and `home` if needed
9. Set `WP_DEBUG` to `false` in wp-config.php
