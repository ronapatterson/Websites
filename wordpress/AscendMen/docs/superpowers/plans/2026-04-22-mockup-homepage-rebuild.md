# Mockup Homepage Rebuild + Unified Styling — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Rebuild the AscendMen homepage to match the mockup (ascend-men-builder-f8ifz88nh8zmxwba.hostingersite.com) and introduce unified brand tokens that cascade to all other pages via the Kadence child theme.

**Architecture:** Custom `front-page.php`, `header.php`, and `footer.php` overrides in the Kadence child theme, plus a shared `style.css` + `theme.json` that defines brand tokens (Oswald/Inter fonts, existing color palette, button classes) and applies them site-wide. A small vanilla-JS testimonial carousel is the only runtime JavaScript added. No plugin config changes.

**Tech Stack:** WordPress 6.x, Kadence parent theme, PHP 8.1, Kadence child theme, vanilla HTML/CSS/JS, Node `--test` for JS unit tests, `curl` + `php -l` for verification.

**Root paths used throughout this plan:**
- Project root: `/home/dev/repos/Websites/wordpress/AscendMen/`
- Child theme: `/home/dev/repos/Websites/wordpress/AscendMen/wp-content/themes/kadence-child/`
- Local site URL: `http://ascendmen.local/` (per `README.md`; assumes `sudo service apache2 start` and `sudo service mysql start`)

**Spec:** `docs/superpowers/specs/2026-04-22-mockup-homepage-rebuild-design.md`

---

## File Map

All paths below are relative to `wp-content/themes/kadence-child/` unless noted.

| Path | Responsibility |
|---|---|
| `functions.php` | Enqueue Google Fonts, child stylesheet, homepage-only carousel JS. |
| `style.css` | Brand tokens (CSS variables), typography, colors, button classes, homepage section styles, site-wide cascade rules. |
| `theme.json` | Kadence/Gutenberg palette + typography so block editor inherits brand. |
| `header.php` | Override: sticky navy header, logo left, short primary nav, JOIN/LOGIN button right, mobile hamburger. |
| `footer.php` | Override: 4-column footer with deeper-page links, decorative subscribe/socials/contact, copyright strip. |
| `front-page.php` | Hand-coded homepage: hero, mission strip, stats, three pillars, testimonial carousel. |
| `assets/images/hero-campfire.jpg` | Hero background (stock). |
| `assets/images/pillar-coaching.jpg` | Pillar 1 image (stock). |
| `assets/images/pillar-community.jpg` | Pillar 2 image (stock). |
| `assets/images/pillar-leadership.jpg` | Pillar 3 image (stock). |
| `assets/images/CREDITS.md` | Attributions for stock photos. |
| `assets/js/testimonial-carousel.js` | Vanilla-JS carousel module (init + auto-advance + controls). |
| `assets/js/testimonial-carousel.test.js` | Node `--test` unit tests for the carousel module. |

---

## Task 1: Foundation — Google Fonts, brand tokens, `theme.json`

**Files:**
- Modify: `wp-content/themes/kadence-child/functions.php`
- Modify: `wp-content/themes/kadence-child/style.css`
- Create: `wp-content/themes/kadence-child/theme.json`

- [ ] **Step 1.1: Write the failing smoke test**

Create a one-off verification script at the project root (do NOT commit it; it's a scratch test). Save as `/tmp/smoke-fonts.sh`:

```bash
#!/usr/bin/env bash
set -euo pipefail
URL="http://ascendmen.local/"
HTML="$(curl -sSL "$URL")"
echo "$HTML" | grep -q "fonts.googleapis.com/css2?family=Oswald" \
  || { echo "FAIL: Oswald Google Fonts link missing"; exit 1; }
echo "$HTML" | grep -q "family=Inter" \
  || { echo "FAIL: Inter Google Fonts link missing"; exit 1; }
echo "$HTML" | grep -q "\-\-am-flame-blue" \
  || { echo "FAIL: brand token CSS variable not present"; exit 1; }
echo "PASS"
```

Make executable: `chmod +x /tmp/smoke-fonts.sh`

- [ ] **Step 1.2: Run test — expect FAIL**

Run: `/tmp/smoke-fonts.sh`
Expected: `FAIL: Oswald Google Fonts link missing` (Google Fonts not yet enqueued).

- [ ] **Step 1.3: Extend `functions.php` to enqueue Google Fonts**

Replace the entire contents of `wp-content/themes/kadence-child/functions.php`:

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
        [ 'kadence-parent-style' ],
        '1.1.0'
    );
    wp_enqueue_style(
        'am-google-fonts',
        'https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Inter:wght@400;500;700&display=swap',
        [],
        null
    );
});

require_once get_stylesheet_directory() . '/includes/programs-cpt.php';
```

- [ ] **Step 1.4: Extend `style.css` with typography + color tokens**

Replace the contents of `wp-content/themes/kadence-child/style.css`:

```css
/*
 Theme Name:   Kadence Child — AscendMen
 Theme URI:    https://ascendmen.com
 Description:  AscendMen child theme for Kadence
 Author:       AscendMen
 Template:     kadence
 Version:      1.1.0
*/

/* ------------------------------------------------------------
   Brand tokens
   ------------------------------------------------------------ */
:root {
  --am-flame-blue:   #29ABE2;
  --am-navy:         #1B2A4A;
  --am-dark-nav:     #111d33;
  --am-steel-blue:   #4A7FC1;
  --am-white:        #FFFFFF;

  --am-font-heading: "Oswald", "Arial Narrow", sans-serif;
  --am-font-body:    "Inter", system-ui, -apple-system, sans-serif;

  --am-container:    1200px;
  --am-section-pad:  80px;
}

body {
  font-family: var(--am-font-body);
  font-size: 17px;
  line-height: 1.6;
  color: var(--am-navy);
}

h1, h2, h3, h4, h5, h6,
.entry-title, .site-title {
  font-family: var(--am-font-heading);
  font-weight: 700;
  letter-spacing: 0.01em;
  line-height: 1.15;
  color: inherit;
}

h1 { font-size: 64px; line-height: 1.1; }
h2 { font-size: 44px; }
h3 { font-size: 28px; }

a { color: var(--am-steel-blue); }
a:hover { color: var(--am-flame-blue); text-decoration: underline; }

/* Container / rhythm cascaded to Kadence content wrappers */
.content-container,
.entry-content {
  max-width: var(--am-container);
}

@media (max-width: 768px) {
  h1 { font-size: 42px; }
  h2 { font-size: 32px; }
  h3 { font-size: 22px; }
  :root { --am-section-pad: 48px; }
}
```

- [ ] **Step 1.5: Create `theme.json`**

Create `wp-content/themes/kadence-child/theme.json`:

```json
{
  "$schema": "https://schemas.wp.org/trunk/theme.json",
  "version": 2,
  "settings": {
    "color": {
      "palette": [
        { "slug": "flame-blue", "name": "Flame Blue", "color": "#29ABE2" },
        { "slug": "mountain-navy", "name": "Mountain Navy", "color": "#1B2A4A" },
        { "slug": "dark-nav", "name": "Dark Nav", "color": "#111d33" },
        { "slug": "steel-blue", "name": "Steel Blue", "color": "#4A7FC1" },
        { "slug": "summit-white", "name": "Summit White", "color": "#FFFFFF" }
      ]
    },
    "typography": {
      "fontFamilies": [
        { "slug": "oswald", "name": "Oswald", "fontFamily": "\"Oswald\", \"Arial Narrow\", sans-serif" },
        { "slug": "inter",  "name": "Inter",  "fontFamily": "\"Inter\", system-ui, sans-serif" }
      ]
    }
  },
  "styles": {
    "typography": {
      "fontFamily": "var(--wp--preset--font-family--inter)"
    },
    "elements": {
      "h1": { "typography": { "fontFamily": "var(--wp--preset--font-family--oswald)", "fontWeight": "700" } },
      "h2": { "typography": { "fontFamily": "var(--wp--preset--font-family--oswald)", "fontWeight": "700" } },
      "h3": { "typography": { "fontFamily": "var(--wp--preset--font-family--oswald)", "fontWeight": "700" } },
      "h4": { "typography": { "fontFamily": "var(--wp--preset--font-family--oswald)", "fontWeight": "500" } }
    }
  }
}
```

- [ ] **Step 1.6: PHP syntax check**

Run: `php -l wp-content/themes/kadence-child/functions.php`
Expected: `No syntax errors detected in ...`

- [ ] **Step 1.7: Re-run the smoke test — expect PASS**

Run: `/tmp/smoke-fonts.sh`
Expected: `PASS`

If it fails on a connection error, start Apache/MySQL first: `sudo service mysql start && sudo service apache2 start`.

- [ ] **Step 1.8: Commit**

```bash
git add wp-content/themes/kadence-child/functions.php \
        wp-content/themes/kadence-child/style.css \
        wp-content/themes/kadence-child/theme.json
git commit -m "feat(theme): enqueue Oswald+Inter and add brand tokens"
```

---

## Task 2: Shared button classes + site-wide cascade

**Files:**
- Modify: `wp-content/themes/kadence-child/style.css`

- [ ] **Step 2.1: Write the failing smoke test**

Append a second check to `/tmp/smoke-fonts.sh`, or create `/tmp/smoke-buttons.sh`:

```bash
#!/usr/bin/env bash
set -euo pipefail
URL="http://ascendmen.local/"
CSS_URL="$(curl -sSL "$URL" | grep -oE 'https?://[^"]+kadence-child/style\.css[^"]*' | head -1)"
[ -n "$CSS_URL" ] || { echo "FAIL: child style.css href not found on page"; exit 1; }
CSS="$(curl -sSL "$CSS_URL")"
echo "$CSS" | grep -q "\.am-btn--solid"   || { echo "FAIL: .am-btn--solid missing"; exit 1; }
echo "$CSS" | grep -q "\.am-btn--outline" || { echo "FAIL: .am-btn--outline missing"; exit 1; }
echo "$CSS" | grep -q "wp-block-button__link" || { echo "FAIL: Kadence button alias missing"; exit 1; }
echo "PASS"
```

Make executable: `chmod +x /tmp/smoke-buttons.sh`

- [ ] **Step 2.2: Run test — expect FAIL**

Run: `/tmp/smoke-buttons.sh`
Expected: `FAIL: .am-btn--solid missing`

- [ ] **Step 2.3: Extend `style.css` with buttons + site-wide rules**

Append to `wp-content/themes/kadence-child/style.css`:

```css
/* ------------------------------------------------------------
   Shared buttons (homepage + Kadence pages)
   ------------------------------------------------------------ */
.am-btn,
.am-btn--solid,
.am-btn--outline,
.wp-block-button__link {
  display: inline-block;
  font-family: var(--am-font-heading);
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  padding: 14px 28px;
  border-radius: 2px;
  border: 2px solid transparent;
  text-decoration: none;
  transition: background-color 0.18s ease, color 0.18s ease, border-color 0.18s ease;
  cursor: pointer;
}

.am-btn--solid,
.wp-block-button__link {
  background-color: var(--am-flame-blue);
  color: var(--am-white);
}
.am-btn--solid:hover,
.wp-block-button__link:hover {
  background-color: #1f91c3;
  color: var(--am-white);
  text-decoration: none;
}

.am-btn--outline {
  background-color: transparent;
  color: var(--am-white);
  border-color: var(--am-white);
}
.am-btn--outline:hover {
  background-color: var(--am-flame-blue);
  border-color: var(--am-flame-blue);
  color: var(--am-white);
  text-decoration: none;
}

/* ------------------------------------------------------------
   Site-wide section rhythm (picked up by Kadence page layouts)
   ------------------------------------------------------------ */
.am-section {
  padding-top: var(--am-section-pad);
  padding-bottom: var(--am-section-pad);
}
.am-section--navy {
  background-color: var(--am-navy);
  color: var(--am-white);
}
.am-section--dark {
  background-color: var(--am-dark-nav);
  color: var(--am-white);
}
.am-section--dark h1,
.am-section--dark h2,
.am-section--dark h3,
.am-section--navy h1,
.am-section--navy h2,
.am-section--navy h3 { color: var(--am-white); }

.am-container {
  max-width: var(--am-container);
  margin: 0 auto;
  padding: 0 24px;
}
```

- [ ] **Step 2.4: Re-run the smoke test — expect PASS**

Run: `/tmp/smoke-buttons.sh`
Expected: `PASS`

- [ ] **Step 2.5: Visual spot-check on an existing page**

Load `http://ascendmen.local/blog/` in a browser. Verify headings use Oswald, body uses Inter, and any existing default WP buttons (from Kadence-rendered posts) render with Flame Blue background. If `/blog/` has no Kadence buttons yet, load the admin block editor preview of any post to confirm button styling is applied.

- [ ] **Step 2.6: Commit**

```bash
git add wp-content/themes/kadence-child/style.css
git commit -m "feat(theme): add shared button + section classes"
```

---

## Task 3: `header.php` override — short nav + JOIN/LOGIN + mobile hamburger

**Files:**
- Create: `wp-content/themes/kadence-child/header.php`
- Modify: `wp-content/themes/kadence-child/style.css`

- [ ] **Step 3.1: Write the failing smoke test**

Create `/tmp/smoke-header.sh`:

```bash
#!/usr/bin/env bash
set -euo pipefail
URL="http://ascendmen.local/"
HTML="$(curl -sSL "$URL")"
echo "$HTML" | grep -q 'class="am-header"' || { echo "FAIL: am-header class missing"; exit 1; }
echo "$HTML" | grep -q '>Home<'       || { echo "FAIL: Home nav item missing"; exit 1; }
echo "$HTML" | grep -q '>About<'      || { echo "FAIL: About nav item missing"; exit 1; }
echo "$HTML" | grep -q 'href="/#purpose"' || { echo "FAIL: Purpose anchor missing"; exit 1; }
echo "$HTML" | grep -q 'href="/#greatness"' || { echo "FAIL: Greatness anchor missing"; exit 1; }
echo "$HTML" | grep -q 'am-header__cta' || { echo "FAIL: JOIN/LOGIN CTA missing"; exit 1; }
echo "$HTML" | grep -q 'id="am-nav-toggle"' || { echo "FAIL: mobile toggle missing"; exit 1; }
echo "PASS"
```

Make executable: `chmod +x /tmp/smoke-header.sh`

- [ ] **Step 3.2: Run test — expect FAIL**

Run: `/tmp/smoke-header.sh`
Expected: `FAIL: am-header class missing`

- [ ] **Step 3.3: Create `header.php`**

Create `wp-content/themes/kadence-child/header.php`:

```php
<?php
/**
 * AscendMen child header — short mockup-style nav.
 * Overrides Kadence parent header. Keeps required WP hooks.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$logo_path = ABSPATH . 'ASCEND MEN PNG TRANSPARENT.png';
$has_logo  = file_exists( $logo_path );

$is_logged_in = is_user_logged_in();
if ( $is_logged_in ) {
    $user      = wp_get_current_user();
    $cta_label = esc_html( $user->first_name ?: $user->display_name );
    $cta_href  = home_url( '/account/' );
} else {
    $cta_label = 'JOIN / LOGIN';
    $cta_href  = home_url( '/register/' );
}
?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#am-content"><?php esc_html_e( 'Skip to content', 'kadence' ); ?></a>

<header class="am-header" role="banner">
  <div class="am-header__inner am-container">
    <a class="am-header__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="AscendMen home">
      <?php if ( $has_logo ) : ?>
        <img src="<?php echo esc_url( home_url( '/ASCEND%20MEN%20PNG%20TRANSPARENT.png' ) ); ?>" alt="AscendMen" />
      <?php else : ?>
        <span><?php bloginfo( 'name' ); ?></span>
      <?php endif; ?>
    </a>

    <button id="am-nav-toggle" class="am-header__toggle" aria-label="Toggle menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>

    <nav class="am-header__nav" aria-label="Primary">
      <ul>
        <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
        <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a></li>
        <li><a href="<?php echo esc_url( home_url( '/#purpose' ) ); ?>">Purpose</a></li>
        <li><a href="<?php echo esc_url( home_url( '/#greatness' ) ); ?>">Greatness</a></li>
      </ul>
    </nav>

    <a class="am-btn am-btn--solid am-header__cta" href="<?php echo esc_url( $cta_href ); ?>">
      <?php echo $cta_label; ?>
    </a>
  </div>
</header>

<main id="am-content" class="am-main">
```

Notes:
- This override intentionally replaces Kadence's header action hooks. `wp_head()`, `wp_body_open()`, `wp_footer()` (in the footer) still fire so plugins keep working.
- The closing of `<main>` happens in `footer.php` (Task 4).

- [ ] **Step 3.4: Append header CSS to `style.css`**

Append to `wp-content/themes/kadence-child/style.css`:

```css
/* ------------------------------------------------------------
   Header
   ------------------------------------------------------------ */
.am-header {
  position: sticky;
  top: 0;
  z-index: 100;
  background-color: var(--am-navy);
  color: var(--am-white);
  box-shadow: 0 2px 0 rgba(0,0,0,0.08);
}
.am-header__inner {
  display: flex;
  align-items: center;
  gap: 24px;
  height: 72px;
}
.am-header__brand { display: inline-flex; align-items: center; color: var(--am-white); }
.am-header__brand img { height: 42px; width: auto; display: block; }
.am-header__brand span { font-family: var(--am-font-heading); font-weight: 700; font-size: 22px; letter-spacing: 0.05em; }

.am-header__nav { margin-left: auto; }
.am-header__nav ul {
  list-style: none;
  margin: 0; padding: 0;
  display: flex; gap: 28px;
}
.am-header__nav a {
  color: var(--am-white);
  text-decoration: none;
  font-family: var(--am-font-heading);
  font-weight: 500;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  font-size: 14px;
}
.am-header__nav a:hover { color: var(--am-flame-blue); }

.am-header__cta { padding: 10px 22px; font-size: 13px; }

.am-header__toggle {
  display: none;
  margin-left: auto;
  background: transparent;
  border: 0;
  width: 40px; height: 40px;
  cursor: pointer;
  flex-direction: column;
  justify-content: space-around;
  padding: 8px 6px;
}
.am-header__toggle span {
  display: block;
  height: 2px;
  background: var(--am-white);
  width: 100%;
}

@media (max-width: 900px) {
  .am-header__toggle { display: flex; }
  .am-header__nav {
    position: absolute;
    top: 72px; left: 0; right: 0;
    background: var(--am-navy);
    display: none;
    padding: 16px 24px;
    margin-left: 0;
  }
  .am-header__nav[data-open="true"] { display: block; }
  .am-header__nav ul { flex-direction: column; gap: 14px; }
  .am-header__cta { display: none; }
  .am-header__nav[data-open="true"]::after {
    content: "";
  }
}
```

- [ ] **Step 3.5: Inline the toggle script (small enough to inline in footer.php Task 4)**

Document: the hamburger JS will be added in Task 4 as an inline `<script>` in `footer.php` to avoid an extra enqueue for ~8 lines of code. For now, the toggle button renders but is non-functional. The smoke test only checks for the button's presence.

- [ ] **Step 3.6: PHP syntax check**

Run: `php -l wp-content/themes/kadence-child/header.php`
Expected: `No syntax errors detected in ...`

- [ ] **Step 3.7: Re-run the smoke test — expect PASS**

Run: `/tmp/smoke-header.sh`
Expected: `PASS`

- [ ] **Step 3.8: Commit**

```bash
git add wp-content/themes/kadence-child/header.php \
        wp-content/themes/kadence-child/style.css
git commit -m "feat(theme): add custom header with short nav and JOIN/LOGIN"
```

---

## Task 4: `footer.php` override — 4-column footer + mobile hamburger JS

**Files:**
- Create: `wp-content/themes/kadence-child/footer.php`
- Modify: `wp-content/themes/kadence-child/style.css`

- [ ] **Step 4.1: Write the failing smoke test**

Create `/tmp/smoke-footer.sh`:

```bash
#!/usr/bin/env bash
set -euo pipefail
URL="http://ascendmen.local/"
HTML="$(curl -sSL "$URL")"
echo "$HTML" | grep -q 'class="am-footer"'       || { echo "FAIL: am-footer class missing"; exit 1; }
echo "$HTML" | grep -q '>Blog<'                  || { echo "FAIL: Blog footer link missing"; exit 1; }
echo "$HTML" | grep -q '>Programs<'              || { echo "FAIL: Programs footer link missing"; exit 1; }
echo "$HTML" | grep -q '>Camps<'                 || { echo "FAIL: Camps footer link missing"; exit 1; }
echo "$HTML" | grep -q '>Outreach<'              || { echo "FAIL: Outreach footer link missing"; exit 1; }
echo "$HTML" | grep -q '>Community<'             || { echo "FAIL: Community footer link missing"; exit 1; }
echo "$HTML" | grep -q 'contact@ascendmen.org'   || { echo "FAIL: contact email missing"; exit 1; }
echo "$HTML" | grep -q 'am-footer__subscribe'    || { echo "FAIL: subscribe form missing"; exit 1; }
echo "$HTML" | grep -q 'am-footer__social'       || { echo "FAIL: socials missing"; exit 1; }
echo "$HTML" | grep -q 'am-nav-toggle' || true # presence verified in header smoke
echo "PASS"
```

Make executable: `chmod +x /tmp/smoke-footer.sh`

- [ ] **Step 4.2: Run test — expect FAIL**

Run: `/tmp/smoke-footer.sh`
Expected: `FAIL: am-footer class missing`

- [ ] **Step 4.3: Create `footer.php`**

Create `wp-content/themes/kadence-child/footer.php`:

```php
<?php
/**
 * AscendMen child footer — 4-column mockup-style layout.
 * Overrides Kadence parent footer. Keeps required WP hooks.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$logo_path = ABSPATH . 'ASCEND MEN PNG TRANSPARENT.png';
$has_logo  = file_exists( $logo_path );
?>
</main><!-- /#am-content -->

<footer class="am-footer" role="contentinfo">
  <div class="am-footer__inner am-container">

    <div class="am-footer__col">
      <?php if ( $has_logo ) : ?>
        <img class="am-footer__logo" src="<?php echo esc_url( home_url( '/ASCEND%20MEN%20PNG%20TRANSPARENT.png' ) ); ?>" alt="AscendMen" />
      <?php else : ?>
        <strong class="am-footer__logo-text"><?php bloginfo( 'name' ); ?></strong>
      <?php endif; ?>
      <p class="am-footer__tagline">Inspiring men to embrace their true potential.</p>
      <ul class="am-footer__social" aria-label="Social">
        <li><a href="#" aria-label="Facebook">FB</a></li>
        <li><a href="#" aria-label="Instagram">IG</a></li>
        <li><a href="#" aria-label="TikTok">TT</a></li>
        <li><a href="#" aria-label="X">X</a></li>
      </ul>
    </div>

    <div class="am-footer__col">
      <h4>Explore</h4>
      <ul>
        <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a></li>
        <li><a href="<?php echo esc_url( home_url( '/#purpose' ) ); ?>">Purpose</a></li>
        <li><a href="<?php echo esc_url( home_url( '/#greatness' ) ); ?>">Greatness</a></li>
        <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a></li>
      </ul>
    </div>

    <div class="am-footer__col">
      <h4>Community</h4>
      <ul>
        <li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a></li>
        <li><a href="<?php echo esc_url( home_url( '/programs/' ) ); ?>">Programs</a></li>
        <li><a href="<?php echo esc_url( home_url( '/camps/' ) ); ?>">Camps</a></li>
        <li><a href="<?php echo esc_url( home_url( '/outreach/' ) ); ?>">Outreach</a></li>
        <li><a href="<?php echo esc_url( home_url( '/community/' ) ); ?>">Community</a></li>
        <li><a href="<?php echo esc_url( home_url( '/events/' ) ); ?>">Events</a></li>
        <li><a href="<?php echo esc_url( home_url( '/register/' ) ); ?>">Register</a></li>
        <li><a href="<?php echo esc_url( home_url( '/login/' ) ); ?>">Login</a></li>
      </ul>
    </div>

    <div class="am-footer__col">
      <h4>Stay Connected</h4>
      <form class="am-footer__subscribe" onsubmit="return false;" aria-label="Subscribe to updates">
        <label class="screen-reader-text" for="am-subscribe-email">Email</label>
        <input id="am-subscribe-email" type="email" placeholder="you@example.com" autocomplete="off" />
        <button type="submit" class="am-btn am-btn--solid">Subscribe</button>
      </form>
      <p>contact@ascendmen.org</p>
      <p>123-456-7890</p>
    </div>

  </div>

  <div class="am-footer__bottom">
    <div class="am-container">
      <p>&copy; <?php echo esc_html( date( 'Y' ) ); ?> Ascend Men. All rights reserved.</p>
    </div>
  </div>
</footer>

<script>
(function(){
  var btn = document.getElementById('am-nav-toggle');
  var nav = document.querySelector('.am-header__nav');
  if (!btn || !nav) return;
  btn.addEventListener('click', function(){
    var open = nav.getAttribute('data-open') === 'true';
    nav.setAttribute('data-open', open ? 'false' : 'true');
    btn.setAttribute('aria-expanded', open ? 'false' : 'true');
  });
})();
</script>

<?php wp_footer(); ?>
</body>
</html>
```

- [ ] **Step 4.4: Append footer CSS to `style.css`**

Append to `wp-content/themes/kadence-child/style.css`:

```css
/* ------------------------------------------------------------
   Footer
   ------------------------------------------------------------ */
.am-footer {
  background-color: var(--am-navy);
  color: var(--am-white);
  padding-top: 64px;
  margin-top: 80px;
  border-top: 4px solid var(--am-flame-blue);
}
.am-footer__inner {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 48px;
  padding-bottom: 48px;
}
.am-footer__col h4 {
  font-family: var(--am-font-heading);
  text-transform: uppercase;
  letter-spacing: 0.08em;
  font-size: 16px;
  color: var(--am-flame-blue);
  margin-bottom: 14px;
}
.am-footer__col ul {
  list-style: none;
  margin: 0; padding: 0;
  display: flex; flex-direction: column; gap: 8px;
}
.am-footer__col a { color: var(--am-white); text-decoration: none; }
.am-footer__col a:hover { color: var(--am-flame-blue); }

.am-footer__logo { max-width: 140px; height: auto; margin-bottom: 12px; display: block; }
.am-footer__tagline { margin: 0 0 16px; opacity: 0.9; }

.am-footer__social { flex-direction: row !important; gap: 14px !important; }
.am-footer__social a {
  display: inline-flex;
  width: 36px; height: 36px;
  align-items: center; justify-content: center;
  border: 1px solid var(--am-white);
  border-radius: 50%;
  font-size: 12px; font-weight: 700;
}

.am-footer__subscribe {
  display: flex; gap: 8px; margin-bottom: 16px;
}
.am-footer__subscribe input {
  flex: 1 1 auto;
  padding: 10px 12px;
  border: 1px solid rgba(255,255,255,0.3);
  background: var(--am-dark-nav);
  color: var(--am-white);
  border-radius: 2px;
  font-family: var(--am-font-body);
}
.am-footer__subscribe button { padding: 10px 16px; font-size: 12px; }

.am-footer__bottom {
  background: var(--am-dark-nav);
  padding: 18px 0;
  font-size: 14px;
  text-align: center;
}

@media (max-width: 900px) {
  .am-footer__inner { grid-template-columns: 1fr 1fr; gap: 32px; }
}
@media (max-width: 560px) {
  .am-footer__inner { grid-template-columns: 1fr; }
}
```

- [ ] **Step 4.5: PHP syntax check**

Run: `php -l wp-content/themes/kadence-child/footer.php`
Expected: `No syntax errors detected in ...`

- [ ] **Step 4.6: Re-run the smoke test — expect PASS**

Run: `/tmp/smoke-footer.sh`
Expected: `PASS`

- [ ] **Step 4.7: Visually verify mobile hamburger**

Load `http://ascendmen.local/` in a browser, resize to 500px width. Click the hamburger — nav should open/close and `aria-expanded` should flip.

- [ ] **Step 4.8: Commit**

```bash
git add wp-content/themes/kadence-child/footer.php \
        wp-content/themes/kadence-child/style.css
git commit -m "feat(theme): add custom footer and mobile nav toggle"
```

---

## Task 5: Stock imagery + attributions

**Files:**
- Create: `wp-content/themes/kadence-child/assets/images/hero-campfire.jpg`
- Create: `wp-content/themes/kadence-child/assets/images/pillar-coaching.jpg`
- Create: `wp-content/themes/kadence-child/assets/images/pillar-community.jpg`
- Create: `wp-content/themes/kadence-child/assets/images/pillar-leadership.jpg`
- Create: `wp-content/themes/kadence-child/assets/images/CREDITS.md`

- [ ] **Step 5.1: Create the assets directory**

Run: `mkdir -p wp-content/themes/kadence-child/assets/images wp-content/themes/kadence-child/assets/js`

- [ ] **Step 5.2: Browse and pick four images from Unsplash**

Open each search in a browser and pick the first thematically appropriate image per the spec §6 criteria. Right-click → "Save image as…" into the paths below. Target dimensions: hero ≥ 2400×1400; pillars ≥ 1200×800.

| Search URL | Save as |
|---|---|
| https://unsplash.com/s/photos/campfire-night | `assets/images/hero-campfire.jpg` |
| https://unsplash.com/s/photos/mentor-conversation | `assets/images/pillar-coaching.jpg` |
| https://unsplash.com/s/photos/men-community | `assets/images/pillar-community.jpg` |
| https://unsplash.com/s/photos/men-leadership | `assets/images/pillar-leadership.jpg` |

Fallback if internet is unavailable: `curl -sSL "https://picsum.photos/2400/1400" -o assets/images/hero-campfire.jpg` (and similar for the pillars). Placeholder imagery is acceptable for now — real thematic selection can happen later in wp-admin.

- [ ] **Step 5.3: Write `CREDITS.md`**

Create `wp-content/themes/kadence-child/assets/images/CREDITS.md`:

```markdown
# Image Credits

All images below are used under the [Unsplash License](https://unsplash.com/license) (free for any use, no permission required, attribution appreciated).

| File | Photographer | Source |
|---|---|---|
| hero-campfire.jpg | _pending attribution_ | _pending URL_ |
| pillar-coaching.jpg | _pending attribution_ | _pending URL_ |
| pillar-community.jpg | _pending attribution_ | _pending URL_ |
| pillar-leadership.jpg | _pending attribution_ | _pending URL_ |

Update each row with the photographer name and photo URL after downloading.
```

- [ ] **Step 5.4: Verify all four images exist and are non-trivial in size**

Run:
```bash
ls -l wp-content/themes/kadence-child/assets/images/*.jpg
```
Expected: four files, each ≥ 50KB.

- [ ] **Step 5.5: Commit**

```bash
git add wp-content/themes/kadence-child/assets/images/
git commit -m "chore(assets): add homepage stock imagery and credits"
```

---

## Task 6: `front-page.php` — scaffold + Hero section

**Files:**
- Create: `wp-content/themes/kadence-child/front-page.php`
- Modify: `wp-content/themes/kadence-child/style.css`

- [ ] **Step 6.1: Write the failing smoke test**

Create `/tmp/smoke-hero.sh`:

```bash
#!/usr/bin/env bash
set -euo pipefail
URL="http://ascendmen.local/"
HTML="$(curl -sSL "$URL")"
echo "$HTML" | grep -q "Empowering Men to Embrace Their Greatness" \
  || { echo "FAIL: hero headline missing"; exit 1; }
echo "$HTML" | grep -q "discovering your God-given purpose" \
  || { echo "FAIL: hero subhead missing"; exit 1; }
echo "$HTML" | grep -q "am-hero__cta--learn" \
  || { echo "FAIL: Learn CTA missing"; exit 1; }
echo "$HTML" | grep -q "am-hero__cta--join" \
  || { echo "FAIL: Join CTA missing"; exit 1; }
echo "PASS"
```

Make executable: `chmod +x /tmp/smoke-hero.sh`

- [ ] **Step 6.2: Run test — expect FAIL**

Run: `/tmp/smoke-hero.sh`
Expected: `FAIL: hero headline missing`

(It may return the default WP front page, which doesn't contain the hero headline.)

- [ ] **Step 6.3: Create `front-page.php` with the hero section**

Create `wp-content/themes/kadence-child/front-page.php`:

```php
<?php
/**
 * AscendMen homepage — mockup-matched layout.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

$img = get_stylesheet_directory_uri() . '/assets/images';
?>

<section class="am-hero" style="background-image: url('<?php echo esc_url( $img . '/hero-campfire.jpg' ); ?>');">
  <div class="am-hero__overlay"></div>
  <div class="am-hero__content am-container">
    <h1 class="am-hero__headline">Empowering Men to Embrace Their Greatness</h1>
    <p class="am-hero__subhead">Join us in discovering your God-given purpose and unleashing your true potential.</p>
    <div class="am-hero__ctas">
      <a class="am-btn am-btn--outline am-hero__cta--learn" href="#purpose">Learn</a>
      <a class="am-btn am-btn--solid am-hero__cta--join" href="<?php echo esc_url( home_url( '/register/' ) ); ?>">Join</a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
```

- [ ] **Step 6.4: Append hero CSS to `style.css`**

Append to `wp-content/themes/kadence-child/style.css`:

```css
/* ------------------------------------------------------------
   Homepage — Hero
   ------------------------------------------------------------ */
.am-hero {
  position: relative;
  min-height: 100vh;
  background-size: cover;
  background-position: center;
  color: var(--am-white);
  display: flex;
  align-items: center;
}
.am-hero__overlay {
  position: absolute; inset: 0;
  background: linear-gradient(135deg, rgba(27, 42, 74, 0.55), rgba(17, 29, 51, 0.7));
}
.am-hero__content {
  position: relative;
  padding: 80px 24px;
  max-width: 960px;
  text-align: center;
  margin: 0 auto;
}
.am-hero__headline {
  font-size: 72px;
  line-height: 1.05;
  margin: 0 0 24px;
  color: var(--am-white);
  text-transform: uppercase;
  letter-spacing: 0.02em;
}
.am-hero__subhead {
  font-size: 20px;
  max-width: 640px;
  margin: 0 auto 36px;
  opacity: 0.95;
}
.am-hero__ctas { display: inline-flex; gap: 16px; flex-wrap: wrap; justify-content: center; }

@media (max-width: 768px) {
  .am-hero { min-height: 75vh; }
  .am-hero__headline { font-size: 42px; }
  .am-hero__subhead { font-size: 17px; }
}
```

- [ ] **Step 6.5: Verify WordPress is using a static front page**

Run:
```bash
wp option get show_on_front --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
```
If output is `posts`, switch to a static page (WP will then use `front-page.php`):
```bash
wp option update show_on_front page --path=/home/dev/repos/Websites/wordpress/AscendMen --allow-root
# The page_on_front ID can remain as-is or point to any existing page — front-page.php overrides regardless.
```

- [ ] **Step 6.6: PHP syntax check**

Run: `php -l wp-content/themes/kadence-child/front-page.php`
Expected: `No syntax errors detected in ...`

- [ ] **Step 6.7: Re-run the smoke test — expect PASS**

Run: `/tmp/smoke-hero.sh`
Expected: `PASS`

- [ ] **Step 6.8: Commit**

```bash
git add wp-content/themes/kadence-child/front-page.php \
        wp-content/themes/kadence-child/style.css
git commit -m "feat(theme): add front-page.php with hero section"
```

---

## Task 7: Mission strip + Stats sections

**Files:**
- Modify: `wp-content/themes/kadence-child/front-page.php`
- Modify: `wp-content/themes/kadence-child/style.css`

- [ ] **Step 7.1: Write the failing smoke test**

Create `/tmp/smoke-mission-stats.sh`:

```bash
#!/usr/bin/env bash
set -euo pipefail
URL="http://ascendmen.local/"
HTML="$(curl -sSL "$URL")"
echo "$HTML" | grep -q 'id="greatness"' || { echo "FAIL: #greatness anchor missing"; exit 1; }
echo "$HTML" | grep -q "At Ascend Men, we inspire and empower men" \
  || { echo "FAIL: mission copy missing"; exit 1; }
echo "$HTML" | grep -q '>150\+<' || { echo "FAIL: 150+ stat missing"; exit 1; }
echo "$HTML" | grep -q '>15<'    || { echo "FAIL: 15 stat missing"; exit 1; }
echo "$HTML" | grep -q '>Members<'  || { echo "FAIL: Members label missing"; exit 1; }
echo "$HTML" | grep -q '>Programs<' || { echo "FAIL: Programs label missing"; exit 1; }
echo "PASS"
```

Make executable: `chmod +x /tmp/smoke-mission-stats.sh`

- [ ] **Step 7.2: Run test — expect FAIL**

Run: `/tmp/smoke-mission-stats.sh`
Expected: `FAIL: #greatness anchor missing`

- [ ] **Step 7.3: Modify `front-page.php` — insert mission + stats before `get_footer()`**

Edit `wp-content/themes/kadence-child/front-page.php`. Insert these sections between the closing `</section>` of the hero and `<?php get_footer(); ?>`:

```php
<section id="greatness" class="am-mission am-section am-section--navy">
  <div class="am-container">
    <p class="am-mission__copy">
      At Ascend Men, we inspire and empower men to embrace their God-given gifts,
      unlocking their true potential and purpose in life.
    </p>
  </div>
</section>

<section class="am-stats am-section am-section--dark">
  <div class="am-container am-stats__grid">
    <div class="am-stats__item">
      <div class="am-stats__number">150+</div>
      <div class="am-stats__label">Members</div>
    </div>
    <div class="am-stats__item">
      <div class="am-stats__number">15</div>
      <div class="am-stats__label">Programs</div>
    </div>
  </div>
</section>
```

- [ ] **Step 7.4: Append CSS to `style.css`**

Append to `wp-content/themes/kadence-child/style.css`:

```css
/* ------------------------------------------------------------
   Homepage — Mission + Stats
   ------------------------------------------------------------ */
.am-mission { text-align: center; }
.am-mission__copy {
  max-width: 820px;
  margin: 0 auto;
  font-size: 22px;
  line-height: 1.55;
}

.am-stats__grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 48px;
  text-align: center;
}
.am-stats__number {
  font-family: var(--am-font-heading);
  font-weight: 700;
  font-size: 96px;
  color: var(--am-flame-blue);
  line-height: 1;
}
.am-stats__label {
  font-family: var(--am-font-body);
  font-size: 18px;
  margin-top: 8px;
  text-transform: uppercase;
  letter-spacing: 0.08em;
}

@media (max-width: 640px) {
  .am-stats__grid { grid-template-columns: 1fr; gap: 32px; }
  .am-stats__number { font-size: 64px; }
  .am-mission__copy { font-size: 19px; }
}
```

- [ ] **Step 7.5: Re-run the smoke test — expect PASS**

Run: `/tmp/smoke-mission-stats.sh`
Expected: `PASS`

- [ ] **Step 7.6: Commit**

```bash
git add wp-content/themes/kadence-child/front-page.php \
        wp-content/themes/kadence-child/style.css
git commit -m "feat(home): add mission strip and stats sections"
```

---

## Task 8: Three Pillars section

**Files:**
- Modify: `wp-content/themes/kadence-child/front-page.php`
- Modify: `wp-content/themes/kadence-child/style.css`

- [ ] **Step 8.1: Write the failing smoke test**

Create `/tmp/smoke-pillars.sh`:

```bash
#!/usr/bin/env bash
set -euo pipefail
URL="http://ascendmen.local/"
HTML="$(curl -sSL "$URL")"
echo "$HTML" | grep -q 'id="purpose"' || { echo "FAIL: #purpose anchor missing"; exit 1; }
echo "$HTML" | grep -q "Purposeful Life Coaching" \
  || { echo "FAIL: pillar 1 title missing"; exit 1; }
echo "$HTML" | grep -q "Community Support Network" \
  || { echo "FAIL: pillar 2 title missing"; exit 1; }
echo "$HTML" | grep -q "Leadership Development" \
  || { echo "FAIL: pillar 3 title missing"; exit 1; }
echo "$HTML" | grep -q "pillar-coaching.jpg" \
  || { echo "FAIL: pillar 1 image ref missing"; exit 1; }
echo "PASS"
```

Make executable: `chmod +x /tmp/smoke-pillars.sh`

- [ ] **Step 8.2: Run test — expect FAIL**

Run: `/tmp/smoke-pillars.sh`
Expected: `FAIL: #purpose anchor missing`

- [ ] **Step 8.3: Modify `front-page.php` — add pillars section**

Insert this section between the stats section and `<?php get_footer(); ?>`:

```php
<section id="purpose" class="am-pillars am-section">
  <div class="am-container">
    <h2 class="am-pillars__heading">Lead with Purpose</h2>
    <div class="am-pillars__grid">

      <article class="am-pillar">
        <img class="am-pillar__image" src="<?php echo esc_url( $img . '/pillar-coaching.jpg' ); ?>" alt="One-on-one coaching conversation" />
        <h3 class="am-pillar__title">Purposeful Life Coaching</h3>
        <p class="am-pillar__body">Developing confidence through identifying unique abilities.</p>
      </article>

      <article class="am-pillar">
        <img class="am-pillar__image" src="<?php echo esc_url( $img . '/pillar-community.jpg' ); ?>" alt="Men gathered in community" />
        <h3 class="am-pillar__title">Community Support Network</h3>
        <p class="am-pillar__body">Peer accountability and shared purpose.</p>
      </article>

      <article class="am-pillar">
        <img class="am-pillar__image" src="<?php echo esc_url( $img . '/pillar-leadership.jpg' ); ?>" alt="Men engaged in leadership activity" />
        <h3 class="am-pillar__title">Leadership Development &amp; Community Initiatives</h3>
        <p class="am-pillar__body">Programs fostering growth and shared experiences.</p>
      </article>

    </div>
  </div>
</section>
```

- [ ] **Step 8.4: Append pillars CSS to `style.css`**

Append to `wp-content/themes/kadence-child/style.css`:

```css
/* ------------------------------------------------------------
   Homepage — Three Pillars
   ------------------------------------------------------------ */
.am-pillars { background-color: var(--am-white); color: var(--am-navy); }
.am-pillars__heading {
  text-align: center;
  font-size: 40px;
  margin-bottom: 48px;
  text-transform: uppercase;
}
.am-pillars__grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 32px;
}
.am-pillar { text-align: center; }
.am-pillar__image {
  width: 100%;
  aspect-ratio: 4 / 3;
  object-fit: cover;
  border-radius: 4px;
  margin-bottom: 18px;
}
.am-pillar__title {
  font-size: 22px;
  margin: 0 0 10px;
  text-transform: uppercase;
}
.am-pillar__body { margin: 0; color: #3a4a66; }

@media (max-width: 900px) {
  .am-pillars__grid { grid-template-columns: 1fr; gap: 40px; }
}
```

- [ ] **Step 8.5: Re-run the smoke test — expect PASS**

Run: `/tmp/smoke-pillars.sh`
Expected: `PASS`

- [ ] **Step 8.6: Commit**

```bash
git add wp-content/themes/kadence-child/front-page.php \
        wp-content/themes/kadence-child/style.css
git commit -m "feat(home): add three pillars section"
```

---

## Task 9: Testimonial carousel — JS module with unit tests

**Files:**
- Create: `wp-content/themes/kadence-child/assets/js/testimonial-carousel.js`
- Create: `wp-content/themes/kadence-child/assets/js/testimonial-carousel.test.js`

- [ ] **Step 9.1: Write the failing unit test**

Create `wp-content/themes/kadence-child/assets/js/testimonial-carousel.test.js`:

```js
// Node built-in test runner. Run with:
//   node --test wp-content/themes/kadence-child/assets/js/testimonial-carousel.test.js
const { test } = require('node:test');
const assert   = require('node:assert');

// Minimal fake DOM element sufficient for the carousel's needs.
function makeEl() {
  const listeners = {};
  return {
    _attrs: {},
    _classes: new Set(),
    _children: [],
    addEventListener: (ev, fn) => { (listeners[ev] = listeners[ev] || []).push(fn); },
    removeEventListener: () => {},
    setAttribute: function(k, v){ this._attrs[k] = String(v); },
    getAttribute: function(k){ return this._attrs[k]; },
    classList: {
      add:    (c) => this._classes && this._classes.add(c),
      remove: (c) => this._classes && this._classes.delete(c),
      contains: (c) => this._classes && this._classes.has(c),
      toggle: (c, on) => { if (on) this._classes.add(c); else this._classes.delete(c); }
    },
    querySelectorAll: () => [],
    _trigger: (ev) => { (listeners[ev] || []).forEach(fn => fn({ preventDefault(){} })); }
  };
}

// Require the module under test. Expect it to export an object with { createCarousel }.
const { createCarousel } = require('./testimonial-carousel.js');

test('advance() cycles forward and wraps', () => {
  const c = createCarousel({ slideCount: 3, intervalMs: 0, autoplay: false });
  assert.equal(c.index(), 0);
  c.advance();
  assert.equal(c.index(), 1);
  c.advance();
  assert.equal(c.index(), 2);
  c.advance();
  assert.equal(c.index(), 0);
});

test('retreat() cycles backward and wraps', () => {
  const c = createCarousel({ slideCount: 3, intervalMs: 0, autoplay: false });
  c.retreat();
  assert.equal(c.index(), 2);
  c.retreat();
  assert.equal(c.index(), 1);
});

test('goTo() clamps via modulo', () => {
  const c = createCarousel({ slideCount: 5, intervalMs: 0, autoplay: false });
  c.goTo(7);
  assert.equal(c.index(), 2);
  c.goTo(-1);
  assert.equal(c.index(), 4);
});

test('reducedMotion disables autoplay', () => {
  const c = createCarousel({ slideCount: 3, intervalMs: 6000, autoplay: true, reducedMotion: true });
  assert.equal(c.isPlaying(), false);
});

test('pause()/play() toggle state', () => {
  const c = createCarousel({ slideCount: 3, intervalMs: 6000, autoplay: true });
  assert.equal(c.isPlaying(), true);
  c.pause();
  assert.equal(c.isPlaying(), false);
  c.play();
  assert.equal(c.isPlaying(), true);
});
```

- [ ] **Step 9.2: Run test — expect FAIL**

Run:
```bash
cd wp-content/themes/kadence-child
node --test assets/js/testimonial-carousel.test.js
```
Expected: failures because `./testimonial-carousel.js` does not exist yet.

- [ ] **Step 9.3: Implement the carousel module**

Create `wp-content/themes/kadence-child/assets/js/testimonial-carousel.js`:

```js
(function (root, factory) {
  if (typeof module === 'object' && module.exports) {
    module.exports = factory();
  } else {
    root.AMTestimonialCarousel = factory();
  }
}(typeof self !== 'undefined' ? self : this, function () {

  function createCarousel(opts) {
    const slideCount = opts.slideCount | 0;
    const intervalMs = opts.intervalMs || 6000;
    const reducedMotion = !!opts.reducedMotion;
    let autoplay = !!opts.autoplay && !reducedMotion;
    let i = 0;
    let timer = null;

    function mod(n, m) { return ((n % m) + m) % m; }

    function goTo(n) {
      if (slideCount <= 0) return;
      i = mod(n, slideCount);
      if (opts.onChange) opts.onChange(i);
    }
    function advance()  { goTo(i + 1); }
    function retreat()  { goTo(i - 1); }

    function play() {
      if (reducedMotion) return;
      autoplay = true;
      if (timer) clearInterval(timer);
      timer = setInterval(advance, intervalMs);
    }
    function pause() {
      autoplay = false;
      if (timer) { clearInterval(timer); timer = null; }
    }

    if (autoplay && typeof setInterval !== 'undefined' && intervalMs > 0) {
      play();
    }

    return {
      advance, retreat, goTo,
      play, pause,
      index: function () { return i; },
      isPlaying: function () { return autoplay; }
    };
  }

  function initFromDOM(root) {
    if (!root) return null;
    const slides = Array.prototype.slice.call(root.querySelectorAll('.am-carousel__slide'));
    const dots   = Array.prototype.slice.call(root.querySelectorAll('.am-carousel__dot'));
    const prev   = root.querySelector('.am-carousel__prev');
    const next   = root.querySelector('.am-carousel__next');

    const reducedMotion = (typeof matchMedia !== 'undefined')
      && matchMedia('(prefers-reduced-motion: reduce)').matches;

    function render(n) {
      slides.forEach(function (s, idx) {
        s.classList.toggle('is-active', idx === n);
        s.setAttribute('aria-hidden', idx === n ? 'false' : 'true');
      });
      dots.forEach(function (d, idx) {
        d.setAttribute('aria-current', idx === n ? 'true' : 'false');
      });
    }

    const c = createCarousel({
      slideCount: slides.length,
      intervalMs: 6000,
      autoplay: true,
      reducedMotion: reducedMotion,
      onChange: render
    });

    render(0);

    if (prev) prev.addEventListener('click', function(){ c.retreat(); });
    if (next) next.addEventListener('click', function(){ c.advance(); });
    dots.forEach(function (d, idx) {
      d.addEventListener('click', function(){ c.goTo(idx); });
    });

    root.addEventListener('mouseenter', function(){ c.pause(); });
    root.addEventListener('mouseleave', function(){ c.play();  });
    document.addEventListener('visibilitychange', function(){
      if (document.hidden) c.pause(); else c.play();
    });

    return c;
  }

  return { createCarousel: createCarousel, initFromDOM: initFromDOM };
}));
```

- [ ] **Step 9.4: Re-run the unit test — expect PASS**

Run:
```bash
cd wp-content/themes/kadence-child
node --test assets/js/testimonial-carousel.test.js
```
Expected: `# pass 5 # fail 0`

- [ ] **Step 9.5: Commit**

```bash
git add wp-content/themes/kadence-child/assets/js/testimonial-carousel.js \
        wp-content/themes/kadence-child/assets/js/testimonial-carousel.test.js
git commit -m "feat(home): testimonial carousel module with unit tests"
```

---

## Task 10: Testimonial section markup + enqueue carousel JS

**Files:**
- Modify: `wp-content/themes/kadence-child/front-page.php`
- Modify: `wp-content/themes/kadence-child/functions.php`
- Modify: `wp-content/themes/kadence-child/style.css`

- [ ] **Step 10.1: Write the failing smoke test**

Create `/tmp/smoke-testimonials.sh`:

```bash
#!/usr/bin/env bash
set -euo pipefail
URL="http://ascendmen.local/"
HTML="$(curl -sSL "$URL")"
echo "$HTML" | grep -q 'class="am-carousel"' || { echo "FAIL: carousel root missing"; exit 1; }
echo "$HTML" | grep -q 'am-carousel__slide' || { echo "FAIL: carousel slides missing"; exit 1; }
echo "$HTML" | grep -q "Ascend Men has truly empowered me" \
  || { echo "FAIL: quote 1 missing"; exit 1; }
echo "$HTML" | grep -q "The camps weren.t a retreat" \
  || { echo "FAIL: quote 5 missing"; exit 1; }
SLIDES=$(echo "$HTML" | grep -oE 'am-carousel__slide\b' | wc -l)
[ "$SLIDES" -ge 5 ] || { echo "FAIL: expected >=5 slides, got $SLIDES"; exit 1; }
echo "$HTML" | grep -q "testimonial-carousel.js" \
  || { echo "FAIL: carousel script not enqueued"; exit 1; }
echo "PASS"
```

Make executable: `chmod +x /tmp/smoke-testimonials.sh`

- [ ] **Step 10.2: Run test — expect FAIL**

Run: `/tmp/smoke-testimonials.sh`
Expected: `FAIL: carousel root missing`

- [ ] **Step 10.3: Enqueue carousel JS only on the homepage**

Replace the `wp_enqueue_scripts` action body in `wp-content/themes/kadence-child/functions.php`:

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
        [ 'kadence-parent-style' ],
        '1.2.0'
    );
    wp_enqueue_style(
        'am-google-fonts',
        'https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Inter:wght@400;500;700&display=swap',
        [],
        null
    );

    if ( is_front_page() ) {
        wp_enqueue_script(
            'am-testimonial-carousel',
            get_stylesheet_directory_uri() . '/assets/js/testimonial-carousel.js',
            [],
            '1.0.0',
            true
        );
        wp_add_inline_script(
            'am-testimonial-carousel',
            "document.addEventListener('DOMContentLoaded', function(){
                var root = document.querySelector('.am-carousel');
                if (root && window.AMTestimonialCarousel) {
                    window.AMTestimonialCarousel.initFromDOM(root);
                }
            });"
        );
    }
});

require_once get_stylesheet_directory() . '/includes/programs-cpt.php';
```

- [ ] **Step 10.4: Add testimonial markup to `front-page.php`**

Insert between the pillars section and `<?php get_footer(); ?>`:

```php
<?php
$am_testimonials = [
    'Ascend Men has truly empowered me to embrace my God-given gifts and pursue my purpose with confidence.',
    'The brotherhood I found at Ascend Men rekindled my drive to lead my family well.',
    'I came in looking for direction and left with a calling.',
    'Every man needs this in his life — it pulled me out of coasting and into purpose.',
    'The camps weren\'t a retreat — they were a reset.',
];
?>
<section class="am-testimonials am-section am-section--dark">
  <div class="am-container">
    <div class="am-testimonials__stars" aria-hidden="true">
      <?php for ( $s = 0; $s < 5; $s++ ) : ?>
        <svg width="22" height="22" viewBox="0 0 24 24" fill="#29ABE2" xmlns="http://www.w3.org/2000/svg"><path d="M12 2l2.9 6.9 7.4.6-5.6 4.9 1.7 7.2L12 17.8 5.6 21.6l1.7-7.2L1.7 9.5l7.4-.6L12 2z"/></svg>
      <?php endfor; ?>
    </div>

    <div class="am-carousel" aria-label="Member testimonials">
      <button class="am-carousel__prev" aria-label="Previous testimonial">&#8249;</button>
      <button class="am-carousel__next" aria-label="Next testimonial">&#8250;</button>

      <div class="am-carousel__track">
        <?php foreach ( $am_testimonials as $idx => $quote ) : ?>
          <blockquote class="am-carousel__slide<?php echo $idx === 0 ? ' is-active' : ''; ?>"
                      aria-hidden="<?php echo $idx === 0 ? 'false' : 'true'; ?>">
            <p>&ldquo;<?php echo esc_html( $quote ); ?>&rdquo;</p>
            <cite>&mdash; John Doe</cite>
          </blockquote>
        <?php endforeach; ?>
      </div>

      <div class="am-carousel__dots" role="tablist">
        <?php foreach ( $am_testimonials as $idx => $_q ) : ?>
          <button class="am-carousel__dot"
                  aria-label="Go to testimonial <?php echo esc_attr( $idx + 1 ); ?>"
                  aria-current="<?php echo $idx === 0 ? 'true' : 'false'; ?>"></button>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
```

- [ ] **Step 10.5: Append carousel CSS to `style.css`**

Append to `wp-content/themes/kadence-child/style.css`:

```css
/* ------------------------------------------------------------
   Homepage — Testimonial carousel
   ------------------------------------------------------------ */
.am-testimonials { text-align: center; }
.am-testimonials__stars {
  display: inline-flex; gap: 4px; margin-bottom: 24px;
}

.am-carousel {
  position: relative;
  max-width: 760px;
  margin: 0 auto;
  padding: 0 56px;
}
.am-carousel__track { position: relative; min-height: 160px; }
.am-carousel__slide {
  position: absolute; inset: 0;
  margin: 0;
  opacity: 0;
  transition: opacity 0.45s ease;
  pointer-events: none;
  font-family: var(--am-font-body);
  font-size: 22px;
  line-height: 1.5;
}
.am-carousel__slide.is-active { opacity: 1; pointer-events: auto; position: relative; }
.am-carousel__slide p { margin: 0 0 12px; }
.am-carousel__slide cite { font-style: normal; color: var(--am-flame-blue); font-weight: 700; }

.am-carousel__prev, .am-carousel__next {
  position: absolute;
  top: 50%; transform: translateY(-50%);
  background: transparent;
  border: 1px solid rgba(255,255,255,0.4);
  color: var(--am-white);
  width: 40px; height: 40px;
  border-radius: 50%;
  font-size: 24px;
  cursor: pointer;
}
.am-carousel__prev { left: 0; }
.am-carousel__next { right: 0; }
.am-carousel__prev:hover, .am-carousel__next:hover { border-color: var(--am-flame-blue); color: var(--am-flame-blue); }

.am-carousel__dots {
  display: flex; justify-content: center; gap: 8px;
  margin-top: 24px;
}
.am-carousel__dot {
  width: 10px; height: 10px; border-radius: 50%;
  background: rgba(255,255,255,0.3);
  border: 0;
  cursor: pointer;
  padding: 0;
}
.am-carousel__dot[aria-current="true"] { background: var(--am-flame-blue); }
```

- [ ] **Step 10.6: Re-run unit tests and smoke test**

Run:
```bash
cd wp-content/themes/kadence-child
node --test assets/js/testimonial-carousel.test.js
cd -
/tmp/smoke-testimonials.sh
```
Expected: both PASS.

- [ ] **Step 10.7: Manual browser verification**

Load `http://ascendmen.local/` in a browser.
- Confirm the carousel auto-advances every ~6s.
- Hover the carousel — advance should pause.
- Move mouse away — advance resumes.
- Click prev/next arrows and dots — index changes manually.
- In DevTools, apply `prefers-reduced-motion: reduce` and reload — auto-advance should NOT run.

- [ ] **Step 10.8: Commit**

```bash
git add wp-content/themes/kadence-child/front-page.php \
        wp-content/themes/kadence-child/functions.php \
        wp-content/themes/kadence-child/style.css
git commit -m "feat(home): testimonial carousel section with 5 slides"
```

---

## Task 11: End-to-end integration + cross-page styling verification

**Files:** (no new code — verification only)

- [ ] **Step 11.1: Run all smoke tests in sequence**

Run:
```bash
/tmp/smoke-fonts.sh
/tmp/smoke-buttons.sh
/tmp/smoke-header.sh
/tmp/smoke-footer.sh
/tmp/smoke-hero.sh
/tmp/smoke-mission-stats.sh
/tmp/smoke-pillars.sh
/tmp/smoke-testimonials.sh
```
Expected: every script prints `PASS`.

- [ ] **Step 11.2: Run the JS unit tests**

Run:
```bash
cd wp-content/themes/kadence-child
node --test assets/js/testimonial-carousel.test.js
```
Expected: all tests pass.

- [ ] **Step 11.3: Cross-page styling spot-check via HTTP**

Run:
```bash
for p in / /blog/ /about/ /contact/; do
  echo "== $p =="
  curl -sSL "http://ascendmen.local$p" | grep -oE 'fonts\.googleapis\.com/css2[^"]+' | head -1
  curl -sSL "http://ascendmen.local$p" | grep -oE '\-\-am-flame-blue[^;]*;' | head -1
done
```
Expected: every page returns both the Google Fonts URL and the brand token reference.

- [ ] **Step 11.4: Visual QA — compare against mockup**

Open side-by-side in a browser:
- Target: `http://ascendmen.local/`
- Mockup: `https://ascend-men-builder-f8ifz88nh8zmxwba.hostingersite.com/`

Check each section top-to-bottom:
- [ ] Header — logo left, 4-item short nav, JOIN/LOGIN pill right
- [ ] Hero — campfire bg, headline + subhead + Learn/Join CTAs
- [ ] Mission strip — navy bg, centered copy
- [ ] Stats — 150+ / Members and 15 / Programs
- [ ] Pillars — three-column grid with images + titles + body
- [ ] Testimonial — stars + rotating quote + John Doe attribution + dots/arrows
- [ ] Footer — 4 columns: mission/social, Explore, Community, Stay Connected
- [ ] Resize to 375px width — layout stacks cleanly, mobile hamburger works

- [ ] **Step 11.5: Verify deeper pages still work**

Open:
- `/blog/` — posts list with updated typography
- `/programs/` — programs archive renders
- `/camps/` — camps listing renders
- `/contact/` — contact page renders
- `/register/` — Ultimate Member registration form renders
- `/login/` — Ultimate Member login form renders

No 500 errors, no layout breakage; fonts and brand colors apply consistently.

- [ ] **Step 11.6: Run `php -l` on all touched PHP files**

Run:
```bash
for f in wp-content/themes/kadence-child/functions.php \
         wp-content/themes/kadence-child/header.php \
         wp-content/themes/kadence-child/footer.php \
         wp-content/themes/kadence-child/front-page.php; do
  php -l "$f"
done
```
Expected: `No syntax errors detected` for each.

- [ ] **Step 11.7: Clean up smoke test scripts (they live in `/tmp`, so just verify they are NOT in the repo)**

Run:
```bash
git status --porcelain
```
Expected: no `/tmp/*.sh` files staged; only theme changes in the clean working tree (should be empty after commits).

- [ ] **Step 11.8: Update top-level `README.md` if site URL structure changed**

If `show_on_front` was flipped from `posts` to `page` (Task 6 Step 6.5), document this in the README's "Site Pages" section. Otherwise skip.

- [ ] **Step 11.9: Final commit (if any cleanup needed)**

If steps above produced README or config tweaks, commit:
```bash
git add README.md
git commit -m "docs: note static front page requirement for homepage"
```

Otherwise no commit needed — the implementation is complete.

---

## Rollback

If anything goes wrong, every task was a separate commit. To undo:

```bash
# Revert the entire feature:
git log --oneline | head -20        # find the last commit before Task 1
git revert <task1-commit>..HEAD      # or:
git reset --hard <pre-task1-commit>  # destructive, only if safe
```

Individual tasks can be reverted independently since each is one commit.
