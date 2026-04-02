# Ms. Isme WordPress Website — Design Spec

## Overview

A personal brand WordPress website for Ms. Isme, showcasing six services. The site follows a WellQor-inspired modern, warm, full-width design with the brand's copper/cream color palette. Focus is on services and landing pages — no blog at launch.

## Color Palette

| Token            | Hex       | Usage                              |
|------------------|-----------|------------------------------------|
| Primary copper   | `#A0622E` | Logo color, primary brand          |
| Dark copper      | `#7A4A1F` | Headings, dark accents             |
| Cream background | `#FFF8F0` | Page background                    |
| Deep burgundy    | `#8B2D4F` | CTA buttons, hover states          |
| Soft gold        | `#D4A853` | Highlights, borders, decorative    |
| Warm white       | `#FFFDF9` | Card backgrounds                   |
| Dark text        | `#2D1B0E` | Body copy                          |

## Typography

- **Headings:** Playfair Display (serif) — Google Fonts
- **Body:** Poppins (sans-serif) — Google Fonts
- **Tagline/accent text:** Playfair Display italic

## Site Structure

### Header (sticky)
- Logo image (left)
- Nav links (center): Home | About | Services | Contact
- CTA button (right): "Book a Consultation" — burgundy, rounded

### Footer
- Logo + tagline "Count It All Joy"
- Quick links: Home, About, Services, Contact
- Social media icons
- Contact info (email, phone)
- Copyright line

### Pages

#### 1. Home (front page)

**Hero Section**
- Full-width warm cream/copper gradient background
- Large headline: "Count It All Joy"
- Subheading: personal brand tagline (placeholder: "Bringing warmth, wisdom, and purpose to every moment")
- CTA button: "Explore Services" (burgundy, rounded)
- Subtle gold decorative flourishes

**Services Preview**
- Section title: "How I Can Help"
- 3x2 card grid
- Each card: icon, service name, 1-2 sentence description
- Soft gold border, copper accent on hover
- Cards link to Services page

**About Preview**
- Alternating image-text layout (WellQor style)
- Placeholder photo on one side, bio/mission text on other
- CTA: "Learn More" linking to About page

**Testimonials**
- Section title: "Kind Words"
- Carousel/slider with client quotes (placeholders)
- Warm cream background, copper decorative quote marks

**CTA Banner**
- Full-width burgundy/copper gradient background
- Headline: "Ready to Get Started?"
- Button: "Book a Consultation" (cream/white, rounded)

#### 2. About

- Hero banner with page title
- Large placeholder photo + bio text section
- "Count It All Joy" philosophy section
- CTA at bottom: "Let's Work Together" linking to Contact

#### 3. Services

- Hero banner with page title + intro line
- Six service blocks in alternating image-text rows:
  1. **Event Planning** — Crafting memorable events from concept to execution
  2. **Event MC** — Energizing and hosting events with warmth and professionalism
  3. **Speaking Engagement** — Inspiring audiences with purpose-driven messages
  4. **Marriage Pastor** — Officiating ceremonies with heart and meaning
  5. **Counseling** — Guiding individuals and couples through life's challenges
  6. **Social Media Marketing** — Building authentic online presence and engagement
- Each block: placeholder image, title, description, "Get in Touch" button

#### 4. Contact

- Hero banner with page title
- Two-column layout:
  - **Left:** Contact form (Name, Email, Phone, Service of Interest dropdown, Message, Submit)
  - **Right:** Direct contact info, social media icons, brief welcoming message
- Contact form powered by Contact Form 7 plugin

## Technical Architecture

### WordPress Setup
- Fresh WordPress install in `/home/dev/repos/Websites/wordpress/MsIsme/`
- MySQL database: `msisme` with dedicated DB user
- Symlink: `/var/www/html/MsIsme -> /home/dev/repos/Websites/wordpress/MsIsme/`

### Custom Theme: `msisme-theme`
Location: `wp-content/themes/msisme-theme/`

Theme files:
- `style.css` — theme metadata + all styles
- `functions.php` — theme setup, enqueue scripts/styles, nav menus, widget areas
- `header.php` — sticky header with logo, nav, CTA
- `footer.php` — footer with logo, links, social, contact info
- `index.php` — fallback template
- `front-page.php` — homepage template with all sections
- `page.php` — generic page template
- `page-about.php` — About page template
- `page-services.php` — Services page template
- `page-contact.php` — Contact page template
- `assets/css/` — additional stylesheets if needed
- `assets/js/` — JavaScript (testimonial carousel, mobile menu toggle)
- `assets/images/` — logo, placeholder images
- `screenshot.png` — theme screenshot

### Plugins
- **Contact Form 7** — contact form on Contact page

### Design Patterns (WellQor-inspired)
- Full-width sections with generous padding
- Alternating image-text rows for content sections
- Rounded buttons (border-radius: 50px)
- Card-based layouts with hover effects
- Smooth scroll and subtle transitions
- Mobile-responsive (hamburger menu on mobile)
- Testimonial carousel with auto-rotation
- Decorative flourishes/accents using CSS/SVG

### Placeholder Content
All images and text content will be placeholder. The site structure will be fully functional and ready for real content to be swapped in.

## Out of Scope
- Blog functionality (to be added later)
- E-commerce / payment processing
- User registration / login
- SEO plugin configuration
- Analytics integration
