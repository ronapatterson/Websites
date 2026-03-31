# AscendMen WordPress Site — Design Spec
**Date:** 2026-03-25
**Status:** Approved

---

## Overview

AscendMen is a faith-based blog and community website for men. Its mission is to reveal men's God-given identity as Sons of God — calling them to rise above cultural and personal definitions of who they are and become the leaders, fathers, and husbands God has called them to be. The organization is camp-focused with community outreach activities and structured programs men can sign up for.

---

## Technical Stack

| Layer | Choice |
|---|---|
| CMS | WordPress 6.x (installed via WP-CLI) |
| Theme | Kadence Theme (free) |
| Page Builder | Kadence Blocks (free + Pro) |
| PHP | 8.1 |
| Database | MySQL 8.0 |
| Web Server | Apache 2.4 |
| Local Root | `/home/dev/repos/Websites/wordpress/AscendMen/` |
| Apache Doc Root | `/var/www/html/` (symlinked or configured to AscendMen dir) |
| Migration Target | Hostinger (via UpdraftPlus export) |

### Key Plugins

| Plugin | Purpose |
|---|---|
| Ultimate Member | User accounts, member profiles, role-based access |
| WooCommerce | Paid program registration and checkout |
| The Events Calendar | Camp and event listings |
| Yoast SEO | Search engine optimization |
| WP Mail SMTP | Reliable email delivery |
| UpdraftPlus | Backups and Hostinger migration |
| Custom Eventbrite Integration | Camp registration via Eventbrite API |

---

## Brand & Visual Identity

**Direction:** Bold & Rugged

| Token | Value | Usage |
|---|---|---|
| Flame Blue | `#29ABE2` | Primary accent, CTAs, highlights |
| Mountain Navy | `#1B2A4A` | Backgrounds, header, footer |
| Steel Blue | `#4A7FC1` | Secondary accents, links, icons |
| Summit White | `#FFFFFF` | Body text, content backgrounds |

**Logo:** `ASCEND MEN PNG TRANSPARENT.png` — flame surrounding a mountain with a cross at the peak. Used in the navigation header and site identity.

---

## Site Structure

### Pages

#### Home
- Full-width hero with logo and tagline
- Mission statement strip
- Featured blog posts (3-up card grid)
- Upcoming camps and programs preview
- Join the community CTA

#### About
- Vision and mission statement
- Our story
- Team / leadership section
- Core values

#### Blog
- Category filter bar
- Card grid layout with featured image, title, excerpt
- Search bar
- **Categories:** Identity, Leadership, Fatherhood, Marriage, Faith & Scripture, Camp Stories, Outreach

#### Programs
- Browse by type: Course / Workshop / Recurring Meeting / One-Time Event
- Free and paid badges on program cards
- Members-only programs gated behind login
- Program signup via WooCommerce checkout (free and paid)

#### Camps
- Upcoming camps listing powered by The Events Calendar
- Individual camp detail pages
- Custom branded registration form
- Eventbrite API integration for ticketing and payment processing

#### Outreach
- Current and upcoming community service activities
- How to get involved section
- Photo gallery from past outreach events
- Volunteer signup form

#### Community
- Social platform links (Facebook Group, WhatsApp, etc.)
- Member directory (visible to logged-in members only)
- Members-only content area
- Ultimate Member profile pages

#### Contact
- Contact form
- Social media links
- Location / address (if applicable)

---

## Navigation Header

```
[Logo]  About  Blog  Programs  Camps  Outreach  Community  [JOIN / LOGIN]
```

- Logo left-aligned (PNG with transparent background)
- Nav links center/right
- JOIN / LOGIN button in Flame Blue (`#29ABE2`)
- Sticky header on scroll
- Mobile: hamburger menu

---

## Membership & Access Control

Three user roles managed by Ultimate Member:

| Role | Access |
|---|---|
| **Guest** (not logged in) | Blog, About, Contact, Camps info, Programs browse |
| **Member** (registered) | + Profile page, member directory, members-only content, program signup |
| **Admin** | Full WordPress dashboard, manage all content and users |

Registration flow:
1. User clicks JOIN
2. Ultimate Member registration form
3. Email verification
4. Redirected to member dashboard

---

## Programs Feature

Programs support multiple formats:
- **Recurring meetings** — weekly/monthly, ongoing enrollment
- **Multi-week courses** — structured curriculum with start/end dates
- **Workshops** — single intensive sessions
- **One-time events** — standalone events

Both free and paid programs supported. Paid programs use WooCommerce checkout. Members-only programs require login before signup is shown.

---

## Camps & Eventbrite Integration

Camps are listed via The Events Calendar plugin. Each camp has a detail page with:
- Description, dates, location
- Custom branded registration form (built with Kadence Blocks form)
- Form submits to Eventbrite API to create/retrieve ticket orders
- Payment handled entirely by Eventbrite
- Confirmation email sent via Eventbrite + WP Mail SMTP

---

## Local Development & Deployment

**Local setup:**
- WordPress files stored in `/home/dev/repos/Websites/wordpress/AscendMen/`
- Apache configured to serve from this directory
- MySQL database: `ascendmen_db`
- WP site URL: `http://localhost/`

**Migration to Hostinger:**
- UpdraftPlus used to export full backup (files + database)
- Import to Hostinger via UpdraftPlus restore
- Update `siteurl` and `home` in wp-options after migration
- Update Eventbrite API keys in production environment

---

## File Structure (WordPress Root)

```
AscendMen/
├── wp-content/
│   ├── themes/
│   │   └── kadence/
│   ├── plugins/
│   │   ├── ultimate-member/
│   │   ├── woocommerce/
│   │   ├── the-events-calendar/
│   │   ├── kadence-blocks/
│   │   └── ...
│   └── uploads/
├── wp-config.php
├── wp-admin/
├── wp-includes/
├── ASCEND MEN PNG TRANSPARENT.png  (logo source)
└── docs/
    └── superpowers/
        └── specs/
            └── 2026-03-25-ascendmen-design.md
```
