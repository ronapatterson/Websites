# AscendMen Homepage Rebuild + Unified Styling — Design Spec

**Date:** 2026-04-22
**Status:** Draft — pending user review
**Related:** Builds on `2026-03-25-ascendmen-design.md` (the original site spec)

---

## Overview

Rebuild the AscendMen WordPress homepage to visually match the mockup at
`https://ascend-men-builder-f8ifz88nh8zmxwba.hostingersite.com/` and introduce
unified brand styling tokens (fonts, colors, buttons) that cascade to every
other page on the site via Kadence's global styles and the child theme's
stylesheet.

The mockup is a simpler marketing-focused landing page than the existing
AscendMen spec. Rather than scope the full site down, this project:

- Rebuilds the homepage section-by-section to match the mockup.
- Introduces a short marketing-style primary nav (`Home / About / Purpose /
  Greatness`), moving the deeper pages (Blog, Programs, Camps, Outreach,
  Community, Events, Register, Login) into the footer so they remain reachable.
- Applies unified typography, color, and component styling across the whole
  site so pages outside the homepage inherit the new look without per-template
  rework.

---

## Goals

1. Homepage pixel-matches the mockup structure, sections, and visual
   hierarchy.
2. All existing pages (Blog, Programs, Camps, Outreach, Community, Contact,
   About) remain functional and accessible, now with unified styling.
3. No plugin reconfiguration and no changes to existing custom functionality
   (Ultimate Member, WooCommerce, Events Calendar, Eventbrite plugin, CPTs).
4. Deeper pages and auth flows (Register / Login) continue to work exactly as
   they do today.

---

## Non-Goals (Scope Fence)

- No content changes to Blog / Programs / Camps / Outreach / Community /
  Contact — they inherit new styling only.
- No plugin reconfiguration or new plugins (Ultimate Member, WooCommerce,
  Events Calendar, Yoast, AscendMen Eventbrite, WP Mail SMTP, UpdraftPlus).
- No new pages — `Purpose` and `Greatness` are homepage anchor links, not
  standalone pages.
- Subscribe form, social icons, contact email, and phone number in the
  footer are decorative placeholders; wiring them to real services / values
  is out of scope.
- Testimonial carousel uses placeholder copy; no testimonial CPT / CMS
  integration yet.
- No mobile app, no i18n / multilingual support, no new custom post types.

---

## Technical Approach

**Hybrid:** custom `front-page.php` template for pixel fidelity on the
homepage, plus brand tokens pushed through Kadence's global styles
(`theme.json`) and the child theme's `style.css` so all other pages pick up
the unified look automatically.

### Files Touched

Paths are relative to [wp-content/themes/kadence-child/](wp-content/themes/kadence-child/).

| File | Change | Purpose |
|---|---|---|
| `front-page.php` | NEW | Hand-coded homepage matching the mockup section-by-section. |
| `header.php` | NEW (override) | Short primary nav + JOIN/LOGIN button + logo; sticky. |
| `footer.php` | NEW (override) | Four-column footer with deeper-page links, socials, subscribe, contact. |
| `style.css` | EXTEND | Brand tokens (fonts, colors, buttons), homepage-specific styles, site-wide cascade rules. |
| `theme.json` | NEW | Kadence global color palette + typography so Gutenberg/Kadence Blocks inherit. |
| `functions.php` | EXTEND | Enqueue Google Fonts (Oswald + Inter); register nav menu locations if needed. |
| `assets/images/hero-campfire.jpg` | NEW | Free-licensed (CC) stock campfire hero image. |
| `assets/images/pillar-coaching.jpg` | NEW | Pillar 1 image (stock CC). |
| `assets/images/pillar-community.jpg` | NEW | Pillar 2 image (stock CC). |
| `assets/images/pillar-leadership.jpg` | NEW | Pillar 3 image (stock CC). |
| `assets/images/CREDITS.md` | NEW | Stock image attribution. |
| `assets/js/testimonial-carousel.js` | NEW | Vanilla JS slider (~80 LOC). |

### Untouched

- WooCommerce, Ultimate Member, The Events Calendar, Kadence Blocks, Yoast
  SEO, WP Mail SMTP, UpdraftPlus.
- `wp-content/plugins/ascendmen-eventbrite/` (custom plugin).
- `includes/programs-cpt.php` and the `am_program` CPT.
- All WordPress core files, `wp-config.php`, DB schema.

---

## Homepage Structure (`front-page.php`)

Top-to-bottom sections that match the mockup:

### 1. Header (shared via `header.php`)
See the Header / Navigation section below.

### 2. Hero
- Full-bleed background image: `assets/images/hero-campfire.jpg` with a dark
  navy gradient overlay (`rgba(27, 42, 74, 0.55)` → `rgba(17, 29, 51, 0.7)`).
- Headline: **"Empowering Men to Embrace Their Greatness"** (Oswald, heavy).
- Subhead: "Join us in discovering your God-given purpose and unleashing
  your true potential." (Inter, regular).
- Two CTAs side-by-side:
  - `Learn` — outline button (transparent bg, white border/text) — anchors
    to `#purpose`.
  - `Join` — filled button (Flame Blue `#29ABE2` bg, white text) — links to
    the Ultimate Member registration URL (`/register/`).
- Minimum height: `100vh` on desktop, `75vh` on mobile.

### 3. Mission Strip — `#greatness` anchor top
- Navy background (`#1B2A4A`), centered single paragraph, white text.
- Copy: "At Ascend Men, we inspire and empower men to embrace their
  God-given gifts, unlocking their true potential and purpose in life."
- Vertical padding: ~80px.

### 4. Stats
- Part of the `#greatness` anchor region (continues the mission block).
- Two numerals side-by-side, centered:
  - **150+** — label "Members"
  - **15** — label "Programs"
- Numerals in Oswald bold at ~96px; labels in Inter at ~18px.

### 5. Three Pillars — `#purpose` anchor
- Three-column grid (equal widths desktop; stacks on mobile).
- Each column has an image on top, title, and short body paragraph:

| # | Title | Body |
|---|---|---|
| 1 | **Purposeful Life Coaching** | Developing confidence through identifying unique abilities. |
| 2 | **Community Support Network** | Peer accountability and shared purpose. |
| 3 | **Leadership Development & Community Initiatives** | Programs fostering growth and shared experiences. |

Image files above; heading in Oswald 700.

### 6. Testimonial Carousel
- Section header: 5-star row rendered as filled star SVGs (Flame Blue).
- Carousel: **5 slides**, one visible at a time, auto-advances every 6s.
  - Arrows (prev / next) on desktop.
  - Dot pagination beneath.
  - Pauses on hover.
  - Pauses when tab is not visible (`document.hidden`).
  - Vanilla JS, no external library. Lives in
    `assets/js/testimonial-carousel.js`, enqueued on homepage only.
- Each slide: a quote + "— John Doe" (placeholder varied quotes all
  attributed to John Doe per user direction).

**Placeholder quotes (5):**
1. "Ascend Men has truly empowered me to embrace my God-given gifts and
   pursue my purpose with confidence."
2. "The brotherhood I found at Ascend Men rekindled my drive to lead my
   family well."
3. "I came in looking for direction and left with a calling."
4. "Every man needs this in his life — it pulled me out of coasting and
   into purpose."
5. "The camps weren't a retreat — they were a reset."

### 7. Footer (shared via `footer.php`)
See the Footer section below.

---

## Header / Navigation

Sticky header, Mountain Navy background (`#1B2A4A`), summit-white text.

**Desktop layout:**
```
[LOGO]   Home   About   Purpose   Greatness          [ JOIN / LOGIN ]
```

- Logo: `ASCEND MEN PNG TRANSPARENT.png` (existing), ~48px tall.
- Primary nav items:
  - `Home` → `/`
  - `About` → `/about/`
  - `Purpose` → `/#purpose` (on homepage resolves to an on-page anchor; on
    other pages navigates home first then scrolls)
  - `Greatness` → `/#greatness`
- `JOIN / LOGIN` pill button on the right:
  - Flame Blue (`#29ABE2`) background, white text.
  - Links to the Ultimate Member registration page (`/register/`) when
    logged out, and `/account/` (or equivalent Ultimate Member dashboard
    URL) when logged in. Label swaps to the logged-in user's first name
    when available.
- Sticky: `position: sticky; top: 0;` with a subtle shadow on scroll.

**Mobile (≤768px):**
- Logo left, hamburger icon right.
- Hamburger toggles a slide-down panel containing the four nav items plus
  the JOIN/LOGIN button.

**Nav menu source:** hard-coded in `header.php` for simplicity — this is a
small, static nav and tying it to a WP menu location adds indirection
without benefit for four items. If the user later wants admin-editable
items, we can register a menu location.

---

## Footer (`footer.php`)

Four columns on desktop (25% each), stacks on mobile. Mountain Navy
background with slight top border in Flame Blue.

### Column 1 — Mission
- AscendMen logo (white/transparent variant).
- One-line tagline: "Inspiring men to embrace their true potential."
- Social icons row (SVG): Facebook, Instagram, TikTok, X — all `href="#"`.

### Column 2 — Explore
Heading: **Explore**
- About → `/about/`
- Purpose → `/#purpose`
- Greatness → `/#greatness`
- Contact → `/contact/`

### Column 3 — Community *(deeper-page home)*
Heading: **Community**
- Blog → `/blog/`
- Programs → `/programs/`
- Camps → `/camps/`
- Outreach → `/outreach/`
- Community → `/community/`
- Events → `/events/`
- Register → `/register/`
- Login → `/login/`

### Column 4 — Stay Connected
Heading: **Stay Connected**
- Email subscribe form (single email input + submit). Decorative — `<form>`
  has no action/no JS handler. Submit button does nothing.
- Email: `contact@ascendmen.org`
- Phone: `123-456-7890`

### Bottom strip
Centered text: `© 2026 Ascend Men. All rights reserved.`

---

## Unified Site-Wide Styling

Brand tokens in `style.css` and mirrored in `theme.json` so Gutenberg,
Kadence Blocks, and native theme templates all resolve to the same values.

### Typography

Google Fonts (loaded via `wp_enqueue_style` in `functions.php` with
`display=swap`):

- **Headings:** Oswald — weights 500 and 700.
- **Body:** Inter — weights 400, 500, 700.

CSS:
```css
:root {
  --am-font-heading: "Oswald", "Arial Narrow", sans-serif;
  --am-font-body: "Inter", system-ui, sans-serif;
}
body { font-family: var(--am-font-body); }
h1, h2, h3, h4, h5, h6 { font-family: var(--am-font-heading); letter-spacing: 0.01em; }
```

Type scale (desktop):
- `h1` 64px / 1.1
- `h2` 44px / 1.15
- `h3` 28px / 1.2
- body 17px / 1.6

### Colors

Existing tokens preserved. Added as Kadence palette entries so they appear
in the block editor color picker.

| Token | Hex | Usage |
|---|---|---|
| Flame Blue | `#29ABE2` | Primary CTA, active links, highlights |
| Mountain Navy | `#1B2A4A` | Header / footer / dark sections |
| Dark Nav | `#111d33` | Deeper navy for contrast regions |
| Steel Blue | `#4A7FC1` | Links, secondary icons |
| Summit White | `#FFFFFF` | Body text on dark, content backgrounds |

### Buttons

Two shared classes used on the homepage and across Kadence-rendered pages:

- `.am-btn--solid` — Flame Blue background, white text, bold, uppercase,
  1.25rem padding, 2px radius.
- `.am-btn--outline` — transparent background, white border and text, same
  padding/radius, darkens to Flame Blue background on hover.

Both map Kadence's default `.wp-block-button__link` classes so native
buttons throughout the site inherit them.

### Links

`a` in content areas: Steel Blue; `a:hover`: Flame Blue; underline on
hover only.

### Containers and rhythm

- Max content width: 1200px.
- Section vertical padding: 80px desktop, 48px mobile.
- Grid gap on card layouts: 32px.

These rules target the Kadence content wrappers (`.content-container`,
`.entry-content`) so Blog, Programs, Camps, etc. inherit consistent
spacing without template edits.

---

## Asset Sourcing

All hero and pillar images are free-licensed stock from Unsplash or Pexels.
On build, download, rename, and commit them under
`assets/images/`. Add attributions to `assets/images/CREDITS.md` with photo
URL, photographer, and license name.

Criteria:
- Hero: campfire at night or dusk, warm flame light, moody; landscape;
  min 2400x1400.
- Pillar 1 (Coaching): one-on-one conversation, mentoring, outdoor/rugged.
- Pillar 2 (Community): group of men, camaraderie, outdoors.
- Pillar 3 (Leadership): leading / speaking / group activity.

---

## Accessibility

- All images have descriptive `alt` attributes.
- Carousel arrows have `aria-label`s; dot pagination uses `aria-current`.
- Carousel auto-advance respects `prefers-reduced-motion: reduce` — pauses
  when the user has requested reduced motion.
- Contrast: all white-on-navy and flame-blue-on-navy pairings meet WCAG AA.
- Nav anchors keyboard-focusable; `skip-to-content` link retained from
  Kadence.

---

## Testing Strategy

- **Visual:** load `/` in a browser at desktop (1440), tablet (768), and
  mobile (375) widths. Compare side-by-side to the mockup URL.
- **Functional:**
  - Header anchors scroll to `#purpose` and `#greatness` on the homepage.
  - From `/blog/`, clicking `Purpose` navigates to `/#purpose`.
  - JOIN/LOGIN button routes to the correct Ultimate Member URL.
  - Footer links to Blog / Programs / Camps / Outreach / Community /
    Events / Register / Login all resolve with 200 responses.
  - Testimonial carousel auto-advances, pauses on hover, stops auto-advance
    under `prefers-reduced-motion: reduce`.
- **Cross-page styling spot-check:** verify Blog index, a single blog
  post, Programs archive, and Contact page all render with Oswald
  headings, Inter body, and updated button styles.

---

## Open Questions / Deferred

- Hero-overlay exact gradient values may need tuning after the real image
  is picked — treat the values in §2 as starting points.
- If Kadence's default blog card layout looks off under the new typography,
  a narrow set of CSS overrides may be added to `style.css`; treat as
  follow-up during implementation rather than upfront design.

---

## Decisions Log

| # | Question | Choice |
|---|---|---|
| 1 | Scope of change | A — homepage visual rebuild; existing pages stay intact |
| 2 | Nav handling | C→B — mimic mockup short nav, anchor links for Purpose/Greatness |
| 3 | Where do deeper pages live | B — footer + JOIN/LOGIN stays in header |
| 4 | Content fidelity | A — mockup content verbatim as placeholder |
| 5 | Technical approach | C — hybrid custom `front-page.php` + Kadence theme tokens |
| 6 | Purpose/Greatness targets | A — both are homepage anchor sections |
| 7 | Hero image | A — I source free-licensed stock and commit it |
| 8 | Fonts | A — Oswald (heading) + Inter (body) |
| 9 | Footer form/social/contact | A — decorative placeholders |
| 10 | Testimonial format | 5-slide carousel, auto-advance 6s + arrows + dots |
