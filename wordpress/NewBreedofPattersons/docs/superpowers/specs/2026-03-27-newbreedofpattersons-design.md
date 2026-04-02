# NewBreedofPattersons — WordPress Blog Design Spec

## Overview

A vibrant, playful WordPress blog called **New Breed of Pattersons** about Christian family life. The site serves articles on marriage, children, long distance relationships, and family finances. It includes a weekly family movie pick feature and a photo gallery.

The site lives in `/home/dev/repos/Websites/wordpress/NewBreedofPattersons/` as a standalone WordPress installation (separate from PerfectLoveRestored).

## Design Direction: Vibrant & Playful

### Color Palette

| Token         | Hex       | Usage                          |
|---------------|-----------|--------------------------------|
| Primary       | `#FF6B35` | Orange — main accent, CTAs     |
| Secondary     | `#F7C548` | Yellow — highlights, badges    |
| Tertiary      | `#3BCEAC` | Teal — secondary accent        |
| Dark          | `#2D3047` | Navy — text, dark sections     |
| Background    | `#FFF8F0` | Warm cream — section backgrounds |
| White         | `#FFFFFF` | Card backgrounds               |
| Text          | `#2D3047` | Primary body text              |
| Text Light    | `#777777` | Secondary/meta text            |

### Typography

- **Headings:** Georgia or similar warm serif font (Google Fonts: "Playfair Display" or "Lora")
- **Body:** Clean sans-serif (Google Fonts: "Nunito" or "Inter")
- **Accents:** Uppercase, letter-spaced labels for categories and meta

### Spacing & Layout

- Max content width: 1200px
- Content column: 800px
- Border radius: 12px (cards), 25px (buttons/badges)
- Shadows: soft (`0 4px 16px rgba(0,0,0,0.06)`)
- Generous whitespace throughout

## Site Structure

### Pages

1. **Home (front-page.php)**
   - Sticky header with logo and navigation
   - Hero section with vibrant gradient (orange -> yellow -> teal), tagline, two CTA buttons
   - Movie Pick of the Week banner (dark strip below hero)
   - Latest Blog Posts section — 3-column card grid
   - Scripture banner with featured Bible verse
   - Footer

2. **Blog Archive (index.php / archive.php)**
   - Traditional blog feed, latest posts in card grid
   - All categories mixed together
   - Pagination at bottom

3. **Category Archives**
   - Same layout as blog archive, filtered by category
   - Categories: Marriage, Children, Long Distance Relationships, Family Finances

4. **Single Post (single.php)**
   - Post header with title, date, category badge
   - Featured image
   - Post content in narrow column (800px)
   - Post navigation (previous/next)

5. **Gallery (template-gallery.php)**
   - Simple photo grid layout
   - Click-to-enlarge lightbox (CSS/JS, no plugin dependency)
   - Photos uploaded via WordPress media library

6. **About (template-about.php)**
   - Mission-focused page
   - Blog purpose and vision statement
   - What readers can expect
   - Faith foundation

7. **404 Page (404.php)**
   - Friendly error message
   - Link back to home

### Navigation

Primary menu items: Home | Marriage | Children | Long Distance | Finances | Gallery | About

Marriage, Children, Long Distance, and Finances link to their respective category archive pages.

### Movie Pick of the Week

- Displayed as a dark banner strip below the hero on the homepage
- Shows: movie title, short family review/description, star rating
- Managed via a custom WordPress widget or a custom post type with a single "current pick" post
- Implementation: Custom post type `movie_pick` with custom fields (title, review, rating)
- Only the most recent movie pick displays on the homepage
- Simple — no archive, no voting, no history page

### Gallery

- Dedicated page template with CSS grid layout
- Responsive grid (auto-fill, minmax ~280px)
- Lightbox: pure CSS/JS overlay when clicking a photo
- Photos managed via a WordPress gallery page using the native gallery block or attached media
- No albums, no categories — single flat grid

### Footer

- Dark navy background (`#2D3047`)
- Three sections: site name + description, navigation links, copyright
- Responsive — stacks on mobile

## Theme Structure

```
newbreedofpattersons/          (theme directory)
├── style.css                  (theme info + all styles)
├── functions.php              (theme setup, enqueues, CPT registration)
├── header.php                 (sticky header + nav)
├── footer.php                 (dark footer)
├── front-page.php             (homepage)
├── index.php                  (blog archive fallback)
├── archive.php                (category archives)
├── single.php                 (single post)
├── page.php                   (default page)
├── 404.php                    (error page)
├── assets/
│   ├── css/
│   │   └── lightbox.css       (gallery lightbox styles)
│   └── js/
│       ├── main.js            (mobile menu, scroll effects)
│       └── lightbox.js        (gallery lightbox functionality)
└── templates/
    ├── template-gallery.php   (gallery page)
    └── template-about.php     (about page)
```

## WordPress Configuration

- WordPress core files copied from PerfectLoveRestored (same WP version) into `NewBreedofPattersons/`
- New `wp-config.php` with its own database credentials (separate DB from PerfectLoveRestored)
- Custom post type: `movie_pick` with fields: title, review, rating (1-5)
- Custom categories pre-created: Marriage, Children, Long Distance Relationships, Family Finances
- Two menus registered: Primary, Footer
- Custom image sizes: card thumbnail (600x400), hero (1600x600)
- Widget area: footer

## Key Design Details

### Hero Section
- Full-width gradient: `linear-gradient(135deg, #FF6B35 0%, #F7C548 50%, #3BCEAC 100%)`
- Centered content: subtitle label, main title, description, two buttons
- Primary button: white bg, orange text, rounded
- Outline button: white border, white text, rounded

### Blog Post Cards
- White background, rounded corners (12px), soft shadow
- Featured image at top (or emoji/color placeholder)
- Category badge: pill-shaped, color-coded per category
  - Marriage: `#FF6B35` (orange)
  - Children: `#3BCEAC` (teal)
  - Long Distance: `#7B68EE` (purple)
  - Family Finances: `#F7C548` (yellow)
- Post title in serif font
- Excerpt text
- Hover: card lifts with enhanced shadow

### Scripture Banner
- Gradient background (teal -> navy)
- Centered italic serif quote
- Scripture reference in uppercase small text

### Movie Pick Banner
- Dark navy background (`#2D3047`)
- Horizontal layout: badge, movie info, review snippet, star rating
- Movie title in serif bold
- Compact — single row on desktop, stacks on mobile

## Responsive Breakpoints

- Desktop: 968px+ (full layout)
- Tablet: 769px–968px (2-column grid, visible nav)
- Mobile: 768px and below (hamburger menu, single column, stacked elements)
- Small mobile: 480px and below (full-width buttons, reduced font sizes)

## No External Plugin Dependencies

The theme is self-contained:
- Lightbox: custom CSS/JS (no plugin)
- Movie pick: custom post type in functions.php (no plugin)
- Gallery: native WordPress media + custom template (no plugin)
- All styles in theme stylesheet (no CSS framework)
- Vanilla JavaScript (no jQuery dependency)
