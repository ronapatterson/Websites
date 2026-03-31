<?php
/**
 * Basileia Life Transformation Ministries - WordPress Setup Script
 * Run with: wp eval-file setup-basileia.php --allow-root
 */

$BASE = 'http://localhost:8080/wp-content/uploads/2026/03';
$LOGO = $BASE . '/logo.png';

// ============================================================
// FOOTER HTML
// ============================================================
$footer_html = <<<HTML
<div class="basileia-footer">
  <div class="basileia-footer-inner">
    <div class="basileia-footer-grid">
      <div>
        <div class="basileia-footer-logo"><img src="{$LOGO}" alt="Basileia Life Transformation Ministries Logo"></div>
        <p class="basileia-footer-tagline">Transforming lives through faith and community support.</p>
        <div class="basileia-footer-contact">
          <p>📧 <a href="mailto:contact@basileialife.org">contact@basileialife.org</a></p>
          <p>📞 <a href="tel:+1234567890">+1234567890</a></p>
        </div>
      </div>
      <div>
        <h4>Quick Links</h4>
        <ul class="basileia-footer-links">
          <li><a href="/">Home</a></li>
          <li><a href="/about">About</a></li>
          <li><a href="/events">Events</a></li>
          <li><a href="/contact">Contact</a></li>
        </ul>
      </div>
      <div>
        <h4>Stay Connected</h4>
        <ul class="basileia-footer-links">
          <li><a href="#">Community</a></li>
          <li><a href="#">Restoration</a></li>
        </ul>
        <p style="font-size:0.9rem;opacity:0.8;margin-top:16px;">Subscribe to our newsletter:</p>
        <form class="basileia-newsletter-form" onsubmit="return false;">
          <input type="email" placeholder="Enter your email address">
          <button type="submit">Join our community today</button>
        </form>
      </div>
    </div>
    <div class="basileia-footer-bottom">
      <p>&copy; 2025. All rights reserved.</p>
      <div class="basileia-social-links">
        <a href="https://www.facebook.com/" target="_blank" rel="noopener">f</a>
        <a href="https://www.instagram.com/" target="_blank" rel="noopener">ig</a>
        <a href="https://tiktok.com/" target="_blank" rel="noopener">tt</a>
        <a href="https://x.com/" target="_blank" rel="noopener">x</a>
      </div>
    </div>
  </div>
</div>
HTML;

// ============================================================
// HOME PAGE
// ============================================================
$home_content = <<<HTML
<div class="basileia-hero">
  <div class="basileia-hero-content">
    <div class="basileia-badge-row">
      <span class="basileia-badge">Transformative</span>
      <span class="basileia-badge">Supportive</span>
      <span class="basileia-badge">Uplifting</span>
      <span class="basileia-badge">Faithful</span>
    </div>
    <h1>Empower Your Transformation</h1>
    <p>Join us in reflecting Christ's life and restoring families through faith and community.</p>
    <div class="basileia-stars">★★★★★</div>
    <a href="#" class="basileia-btn-primary">Learn More</a>
  </div>
  <div class="basileia-hero-image">
    <img src="{$BASE}/pastor-garth.jpg" alt="Pastor Garth Allison">
  </div>
</div>

<section class="basileia-section">
  <div class="basileia-section-header">
    <h2>What to Expect?</h2>
    <p>At Basileia Life Transformation Ministries, we inspire individuals to embody the teachings of Jesus Christ, creating a supportive community that heals families and promotes the establishment of God's kingdom on earth.</p>
  </div>
  <div class="basileia-expect-grid">
    <div class="basileia-expect-item">
      <img src="{$BASE}/community-icon.png" alt="Community">
      <h3>Community</h3>
      <p>At Basileia Life Transformation Ministries, we inspire individuals to embody the teachings of Jesus Christ, creating a supportive community that heals families and promotes the establishment of God's kingdom on earth.</p>
    </div>
    <div class="basileia-expect-item">
      <img src="{$BASE}/dresscode-icon.png" alt="Dress Code">
      <h3>Dress Code</h3>
      <p>At Basileia Life Transformation Ministries, we inspire individuals to embody the teachings of Jesus Christ, creating a supportive community that heals families and promotes the establishment of God's kingdom on earth.</p>
    </div>
    <div class="basileia-expect-item">
      <img src="{$BASE}/service-times-icon.png" alt="Service Times">
      <h3>Service Times</h3>
      <p>At Basileia Life Transformation Ministries, we inspire individuals to embody the teachings of Jesus Christ, creating a supportive community that heals families and promotes the establishment of God's kingdom on earth.</p>
    </div>
  </div>
</section>

<div style="background: var(--color-gray-light); padding: 80px 40px;">
<div style="max-width: 1200px; margin: 0 auto;">
  <div class="basileia-section-header">
    <h2>Empower and Transform</h2>
    <p>Join us in reflecting Jesus Christ's life and power, restoring families and building His kingdom.</p>
  </div>
  <div class="basileia-cards-grid">
    <div class="basileia-card">
      <img src="{$BASE}/family-restoration.jpg" alt="Family Restoration">
      <div class="basileia-card-body">
        <h4>Family Restoration</h4>
        <p>Our ministry focuses on healing families and strengthening relationships through Christ's love and teachings.</p>
      </div>
    </div>
    <div class="basileia-card">
      <img src="{$BASE}/community-growth.jpg" alt="Community Growth">
      <div class="basileia-card-body">
        <h4>Community Growth</h4>
        <p>We foster a supportive community that encourages personal growth and discipleship in Jesus Christ. Discover your purpose and connect with others who share the same mission of transformation.</p>
      </div>
    </div>
    <div class="basileia-card">
      <img src="{$BASE}/transformational.jpg" alt="Transformational Journey">
      <div class="basileia-card-body">
        <h4>Transformational Journey</h4>
        <p>Begin your journey of transformation with us. Discover how faith can renew every aspect of your life and relationships.</p>
      </div>
    </div>
  </div>
</div>
</div>

<div class="basileia-testimonials-section">
  <div class="basileia-testimonials-inner">
    <div class="basileia-section-header">
      <h2>Life Transformations</h2>
      <p>Empowering individuals to reflect Jesus Christ's life and power.</p>
    </div>
    <div class="basileia-testimonials-grid">
      <div class="basileia-testimonial">
        <div class="basileia-testimonial-header">
          <img src="{$BASE}/testimonial-john.jpg" alt="John Smith">
          <div>
            <div class="basileia-testimonial-name">John Smith</div>
            <div class="basileia-testimonial-location">Springfield</div>
          </div>
        </div>
        <div class="basileia-testimonial-stars">★★★★★</div>
        <blockquote>"Basileia has truly transformed my life and strengthened my family's faith journey."</blockquote>
      </div>
      <div class="basileia-testimonial">
        <div class="basileia-testimonial-header">
          <img src="{$BASE}/testimonial-emily.jpg" alt="Emily Davis">
          <div>
            <div class="basileia-testimonial-name">Emily Davis</div>
            <div class="basileia-testimonial-location">Hometown</div>
          </div>
        </div>
        <div class="basileia-testimonial-stars">★★★★★</div>
        <blockquote>"The community at Basileia Life Transformation Ministries has been a blessing, helping us restore our family bonds and deepen our relationship with God."</blockquote>
      </div>
    </div>
  </div>
</div>

{$footer_html}
HTML;

// ============================================================
// ABOUT PAGE
// ============================================================
$about_content = <<<HTML
<div class="basileia-about-hero">
  <div class="basileia-about-hero-content">
    <h2>Empowering Lives Through Christ</h2>
    <p>Join us in transforming lives and restoring families through the teachings of Jesus Christ.</p>
    <div class="basileia-stats-bar">
      <div class="basileia-stat">
        <div class="basileia-stat-number">150+</div>
        <div class="basileia-stat-label">Community Support</div>
      </div>
      <div class="basileia-stat">
        <div class="basileia-stat-number">15</div>
        <div class="basileia-stat-label">Faith Driven</div>
      </div>
      <div class="basileia-stat">
        <div class="basileia-stat-number"><a href="#" style="color:var(--color-warning);text-decoration:none;">Join</a></div>
        <div class="basileia-stat-label">Our Community</div>
      </div>
    </div>
  </div>
  <div class="basileia-about-hero-image">
    <img src="{$BASE}/about-hero.jpg" alt="Basileia Ministry Community">
  </div>
</div>

<section class="basileia-section">
  <div class="basileia-section-header">
    <h2>Empowerment Projects</h2>
    <p>We foster community restoration and establish God's kingdom on earth.</p>
  </div>
  <div class="basileia-about-card">
    <img src="{$BASE}/family-restoration-about.jpg" alt="Family Restoration">
    <div class="basileia-about-card-body">
      <h3>Family Restoration</h3>
      <p>Our family restoration initiatives aim to strengthen relationships and promote healing, reflecting the love and power of Jesus Christ in every home and community we serve.</p>
    </div>
  </div>
  <div class="basileia-about-card">
    <img src="{$BASE}/community-engagement.jpg" alt="Community Engagement">
    <div class="basileia-about-card-body">
      <h3>Community Engagement</h3>
      <p>Through various outreach programs, we engage individuals and families, creating a supportive environment that nurtures spiritual growth and fosters a sense of belonging within the body of Christ.</p>
    </div>
  </div>
</section>

<div class="basileia-testimonials-section">
  <div class="basileia-testimonials-inner">
    <div class="basileia-section-header">
      <h2>Customer Reviews</h2>
      <p>Read how we empower lives through transforming faith and community.</p>
    </div>
    <div class="basileia-testimonials-grid">
      <div class="basileia-testimonial">
        <div class="basileia-testimonial-header">
          <img src="{$BASE}/testimonial-john-about.jpg" alt="John Doe">
          <div>
            <div class="basileia-testimonial-name">John Doe</div>
            <div class="basileia-testimonial-location">Newark, NJ</div>
          </div>
        </div>
        <div class="basileia-testimonial-stars">★★★★★</div>
        <blockquote>"Basileia has transformed my family's relationship, reflecting Jesus's love in our lives."</blockquote>
      </div>
      <div class="basileia-testimonial">
        <div class="basileia-testimonial-header">
          <img src="{$BASE}/testimonial-jane-about.jpg" alt="Jane Smith">
          <div>
            <div class="basileia-testimonial-name">Jane Smith</div>
            <div class="basileia-testimonial-location">Boston, MA</div>
          </div>
        </div>
        <div class="basileia-testimonial-stars">★★★★★</div>
        <blockquote>"The supportive community at Basileia helped rebuild our family and strengthen our faith."</blockquote>
      </div>
    </div>
  </div>
</div>

{$footer_html}
HTML;

// ============================================================
// PLACEHOLDER PAGES
// ============================================================
function make_placeholder($title, $desc, $footer) {
    return <<<HTML
<div class="basileia-placeholder">
  <div>
    <h1>{$title}</h1>
    <p>{$desc}</p>
  </div>
</div>
{$footer}
HTML;
}

$events_content   = make_placeholder('Events', 'Stay tuned for upcoming events. We are preparing something amazing for our community.', $footer_html);
$lessons_content  = make_placeholder('Lessons', 'Explore our library of faith-based lessons and teachings. Coming soon.', $footer_html);
$live_content     = make_placeholder('Live Stream', 'Join us live for worship, teachings, and community gatherings. Coming soon.', $footer_html);
$ondemand_content = make_placeholder('On Demand', 'Watch past teachings and services on demand. Coming soon.', $footer_html);
$contact_content  = make_placeholder('Contact Us', "We'd love to hear from you. Reach us at contact@basileialife.org or call +1234567890.", $footer_html) . <<<HTML

<section class="basileia-section" style="max-width:600px; margin: 0 auto;">
  <h2 style="text-align:center;margin-bottom:30px;">Send Us a Message</h2>
  <form style="display:grid;gap:16px;" onsubmit="return false;">
    <input type="text" placeholder="Your Name" style="padding:12px 16px;border:1px solid #dadce0;border-radius:8px;font-size:1rem;">
    <input type="email" placeholder="Your Email" style="padding:12px 16px;border:1px solid #dadce0;border-radius:8px;font-size:1rem;">
    <input type="text" placeholder="Subject" style="padding:12px 16px;border:1px solid #dadce0;border-radius:8px;font-size:1rem;">
    <textarea placeholder="Your Message" rows="5" style="padding:12px 16px;border:1px solid #dadce0;border-radius:8px;font-size:1rem;resize:vertical;"></textarea>
    <button class="basileia-btn-primary" type="submit" style="text-align:center;border:none;cursor:pointer;width:100%;">Send Message</button>
  </form>
</section>
HTML;

// ============================================================
// CREATE / UPDATE PAGES IN DB
// ============================================================
$pages_data = [
  ['title' => 'Home',      'name' => 'home',      'content' => $home_content,    'seo_title' => 'Empower Your Life with Basileia Ministries | Basileia Life Transformation Ministries'],
  ['title' => 'About',     'name' => 'about',     'content' => $about_content,   'seo_title' => 'Basileia Life Transformation Ministries - Empowering Lives'],
  ['title' => 'Events',    'name' => 'events',    'content' => $events_content,  'seo_title' => 'Events | Basileia Life Transformation Ministries'],
  ['title' => 'Lessons',   'name' => 'lessons',   'content' => $lessons_content, 'seo_title' => 'Lessons | Basileia Life Transformation Ministries'],
  ['title' => 'Live',      'name' => 'live',      'content' => $live_content,    'seo_title' => 'Live | Basileia Life Transformation Ministries'],
  ['title' => 'On Demand', 'name' => 'on-demand', 'content' => $ondemand_content,'seo_title' => 'On Demand | Basileia Life Transformation Ministries'],
  ['title' => 'Contact',   'name' => 'contact',   'content' => $contact_content, 'seo_title' => 'Contact | Basileia Life Transformation Ministries'],
];

$page_ids = [];
foreach ($pages_data as $p) {
    $existing = get_page_by_path($p['name'], OBJECT, 'page');
    $post_data = [
        'post_title'   => $p['title'],
        'post_name'    => $p['name'],
        'post_content' => $p['content'],
        'post_status'  => 'publish',
        'post_type'    => 'page',
    ];
    if ($existing) {
        $post_data['ID'] = $existing->ID;
        $id = wp_update_post($post_data);
    } else {
        $id = wp_insert_post($post_data);
    }
    $page_ids[$p['name']] = $id;
    fwrite(STDOUT, "Page '{$p['title']}' -> ID {$id}\n");
}

// Set front page
update_option('show_on_front', 'page');
update_option('page_on_front', $page_ids['home']);
fwrite(STDOUT, "Front page set to Home (ID: {$page_ids['home']})\n");

// ============================================================
// NAVIGATION MENU
// ============================================================
$menu_name = 'Primary Navigation';
$existing_menu = wp_get_nav_menu_object($menu_name);
if ($existing_menu) wp_delete_nav_menu($existing_menu->term_id);
$menu_id = wp_create_nav_menu($menu_name);
fwrite(STDOUT, "Menu created: ID {$menu_id}\n");

$nav_items = [
  ['Home',      home_url('/'),                              1, 0],
  ['About',     get_permalink($page_ids['about']),          2, 0],
  ['Events',    get_permalink($page_ids['events']),         3, 0],
  ['Lessons',   get_permalink($page_ids['lessons']),        4, 0],
  ['Contact',   get_permalink($page_ids['contact']),        5, 0],
];

$item_ids = [];
foreach ($nav_items as [$title, $url, $order, $parent]) {
    $iid = wp_update_nav_menu_item($menu_id, 0, [
        'menu-item-title'     => $title,
        'menu-item-url'       => $url,
        'menu-item-status'    => 'publish',
        'menu-item-position'  => $order,
        'menu-item-parent-id' => $parent,
    ]);
    $item_ids[$title] = $iid;
    fwrite(STDOUT, "Nav item: {$title}\n");
}

// Lessons sub-items
$lessons_item_id = $item_ids['Lessons'];
foreach ([['Live', get_permalink($page_ids['live'])], ['On Demand', get_permalink($page_ids['on-demand'])]] as [$t, $u]) {
    wp_update_nav_menu_item($menu_id, 0, [
        'menu-item-title'     => $t,
        'menu-item-url'       => $u,
        'menu-item-status'    => 'publish',
        'menu-item-parent-id' => $lessons_item_id,
    ]);
    fwrite(STDOUT, "Sub-nav: {$t}\n");
}

// Give button
wp_update_nav_menu_item($menu_id, 0, [
    'menu-item-title'    => 'Give',
    'menu-item-url'      => 'https://donorbox.org/giving-803483',
    'menu-item-status'   => 'publish',
    'menu-item-position' => 6,
    'menu-item-target'   => '_blank',
]);
fwrite(STDOUT, "Nav item: Give\n");

// Assign to primary location
$locations = get_theme_mod('nav_menu_locations', []);
$locations['primary'] = $menu_id;
set_theme_mod('nav_menu_locations', $locations);
fwrite(STDOUT, "Menu assigned to primary\n");

// ============================================================
// CUSTOM CSS
// ============================================================
$custom_css = '
:root {
  --color-primary-dark: #5025d1;
  --color-primary: #673de6;
  --color-primary-light: #ebe4ff;
  --color-meteorite-dark: #2f1c6a;
  --color-meteorite-dark2: #1F1346;
  --color-meteorite: #8c85ff;
  --color-warning: #ffcd35;
  --color-gray: #727586;
  --color-gray-border: #dadce0;
  --color-gray-light: #f2f3f6;
  --color-dark: #1d1e20;
  --color-white: #ffffff;
}
body { font-family: "Inter","Helvetica Neue",Arial,sans-serif; color: var(--color-dark); }
.ast-primary-header-bar { background-color: var(--color-meteorite-dark2) !important; }
.ast-primary-header-bar a, .main-header-menu .menu-item a { color: #ffffff !important; }
.ast-primary-header-bar .main-header-menu .menu-item a:hover { color: var(--color-warning) !important; }
.entry-content, .post-content, .page-content { padding: 0 !important; }
.site-content .entry-content { margin: 0; max-width: 100%; }
.ast-container { max-width: 100% !important; padding: 0 !important; }
.entry-header { display: none; }
.ast-page-builder-template .site-content { padding: 0; }

/* Hero */
.basileia-hero { background: linear-gradient(135deg,#1F1346 0%,#2f1c6a 50%,#5025d1 100%); color:#fff; padding:80px 40px; display:flex; align-items:center; justify-content:space-between; gap:40px; flex-wrap:wrap; }
.basileia-hero-content { flex:1; min-width:280px; }
.basileia-hero h1 { font-size:3rem; font-weight:800; margin-bottom:20px; line-height:1.1; color:#fff; }
.basileia-hero p { font-size:1.2rem; margin-bottom:30px; opacity:0.9; }
.basileia-hero-image { flex:1; min-width:280px; text-align:center; }
.basileia-hero-image img { max-width:400px; width:100%; border-radius:16px; }
.basileia-badge-row { display:flex; flex-wrap:wrap; gap:10px; margin-bottom:20px; }
.basileia-badge { background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.3); color:#fff; padding:6px 16px; border-radius:20px; font-size:0.85rem; font-weight:600; }
.basileia-btn-primary { display:inline-block; background:var(--color-warning); color:var(--color-dark)!important; padding:14px 32px; border-radius:8px; font-weight:700; font-size:1rem; text-decoration:none!important; transition:background 0.2s; }
.basileia-btn-primary:hover { background:#fea419; }
.basileia-stars { color:var(--color-warning); font-size:1.3rem; margin-bottom:10px; }

/* Section */
.basileia-section { padding:80px 40px; max-width:1200px; margin:0 auto; }
.basileia-section-header { text-align:center; margin-bottom:50px; }
.basileia-section-header h2 { font-size:2.2rem; font-weight:800; color:var(--color-meteorite-dark); margin-bottom:16px; }
.basileia-section-header p { font-size:1.1rem; color:var(--color-gray); max-width:600px; margin:0 auto; }

/* Expect */
.basileia-expect-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:40px; }
.basileia-expect-item { text-align:center; padding:30px 20px; border-radius:12px; background:var(--color-gray-light); }
.basileia-expect-item img { width:80px; height:80px; object-fit:contain; margin-bottom:16px; }
.basileia-expect-item h3 { font-size:1.3rem; font-weight:700; color:var(--color-meteorite-dark); margin-bottom:12px; }
.basileia-expect-item p { color:var(--color-gray); font-size:0.95rem; line-height:1.6; }

/* Cards */
.basileia-cards-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:30px; }
.basileia-card { border-radius:12px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.08); background:#fff; transition:transform 0.2s,box-shadow 0.2s; }
.basileia-card:hover { transform:translateY(-4px); box-shadow:0 8px 30px rgba(0,0,0,0.12); }
.basileia-card img { width:100%; height:220px; object-fit:cover; }
.basileia-card-body { padding:24px; }
.basileia-card-body h4 { font-size:1.2rem; font-weight:700; color:var(--color-meteorite-dark); margin-bottom:10px; }
.basileia-card-body p { color:var(--color-gray); font-size:0.95rem; line-height:1.6; }

/* Testimonials */
.basileia-testimonials-section { background:linear-gradient(135deg,#1F1346 0%,#2f1c6a 100%); padding:80px 40px; color:#fff; }
.basileia-testimonials-inner { max-width:1200px; margin:0 auto; }
.basileia-testimonials-section .basileia-section-header h2 { color:#fff; }
.basileia-testimonials-section .basileia-section-header p { color:rgba(255,255,255,0.7); }
.basileia-testimonials-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:30px; }
.basileia-testimonial { background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.15); border-radius:12px; padding:28px; }
.basileia-testimonial-header { display:flex; align-items:center; gap:14px; margin-bottom:16px; }
.basileia-testimonial-header img { width:48px; height:48px; border-radius:50%; object-fit:cover; }
.basileia-testimonial-name { font-weight:700; font-size:1rem; }
.basileia-testimonial-location { font-size:0.85rem; opacity:0.7; }
.basileia-testimonial-stars { color:var(--color-warning); margin-bottom:10px; }
.basileia-testimonial blockquote { font-size:0.97rem; line-height:1.6; font-style:italic; opacity:0.9; margin:0; border:none; padding:0; background:none; }

/* About Hero */
.basileia-about-hero { background:linear-gradient(135deg,#1F1346 0%,#2f1c6a 100%); color:#fff; padding:80px 40px; display:flex; align-items:center; justify-content:space-between; gap:40px; flex-wrap:wrap; }
.basileia-about-hero-content { flex:1; min-width:280px; }
.basileia-about-hero h2 { font-size:2.8rem; font-weight:800; margin-bottom:20px; color:#fff; }
.basileia-about-hero p { font-size:1.1rem; opacity:0.9; margin-bottom:30px; }
.basileia-about-hero-image { flex:1; min-width:280px; text-align:center; }
.basileia-about-hero-image img { max-width:450px; width:100%; border-radius:16px; }
.basileia-stats-bar { display:flex; gap:40px; margin-top:30px; flex-wrap:wrap; }
.basileia-stat { text-align:center; }
.basileia-stat-number { font-size:2rem; font-weight:800; color:var(--color-warning); }
.basileia-stat-label { font-size:0.9rem; opacity:0.8; }

/* About Cards */
.basileia-about-card { display:flex; align-items:center; gap:0; margin-bottom:30px; background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.08); flex-wrap:wrap; }
.basileia-about-card:nth-child(even) { flex-direction:row-reverse; }
.basileia-about-card > img { width:300px; min-width:200px; height:240px; object-fit:cover; flex-shrink:0; }
.basileia-about-card-body { padding:30px; flex:1; }
.basileia-about-card-body h3 { font-size:1.5rem; font-weight:700; color:var(--color-meteorite-dark); margin-bottom:12px; }
.basileia-about-card-body p { color:var(--color-gray); line-height:1.7; }

/* Footer */
.basileia-footer { background:var(--color-meteorite-dark2); color:#fff; padding:60px 40px 30px; }
.basileia-footer-inner { max-width:1200px; margin:0 auto; }
.basileia-footer-grid { display:grid; grid-template-columns:2fr 1fr 1fr; gap:40px; margin-bottom:40px; }
.basileia-footer-logo img { max-width:180px; margin-bottom:16px; }
.basileia-footer-tagline { opacity:0.7; font-size:0.95rem; margin-bottom:16px; line-height:1.6; }
.basileia-footer-contact { font-size:0.9rem; opacity:0.8; }
.basileia-footer-contact a { color:#fff; text-decoration:none; }
.basileia-footer h4 { font-size:1rem; font-weight:700; margin-bottom:16px; color:#fff; }
.basileia-footer-links { list-style:none; margin:0; padding:0; }
.basileia-footer-links li { margin-bottom:8px; }
.basileia-footer-links a { color:rgba(255,255,255,0.7); text-decoration:none; font-size:0.9rem; }
.basileia-footer-links a:hover { color:#fff; }
.basileia-newsletter-form { display:flex; gap:10px; margin-top:12px; flex-wrap:wrap; }
.basileia-newsletter-form input { flex:1; min-width:180px; padding:10px 16px; border-radius:6px; border:1px solid rgba(255,255,255,0.2); background:rgba(255,255,255,0.1); color:#fff; font-size:0.9rem; }
.basileia-newsletter-form input::placeholder { color:rgba(255,255,255,0.5); }
.basileia-newsletter-form button { padding:10px 20px; background:var(--color-primary); color:#fff; border:none; border-radius:6px; font-weight:600; cursor:pointer; font-size:0.9rem; }
.basileia-footer-bottom { border-top:1px solid rgba(255,255,255,0.1); padding-top:24px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px; }
.basileia-footer-bottom p { opacity:0.6; font-size:0.85rem; margin:0; }
.basileia-social-links { display:flex; gap:12px; }
.basileia-social-links a { width:36px; height:36px; border-radius:50%; background:rgba(255,255,255,0.1); display:flex; align-items:center; justify-content:center; color:#fff; text-decoration:none; font-size:0.8rem; transition:background 0.2s; }
.basileia-social-links a:hover { background:var(--color-primary); }

/* Placeholder */
.basileia-placeholder { background:linear-gradient(135deg,#1F1346 0%,#5025d1 100%); min-height:60vh; display:flex; align-items:center; justify-content:center; text-align:center; padding:80px 40px; color:#fff; }
.basileia-placeholder h1 { font-size:3rem; font-weight:800; margin-bottom:16px; color:#fff; }
.basileia-placeholder p { font-size:1.2rem; opacity:0.8; max-width:500px; }

/* Responsive */
@media (max-width:768px) {
  .basileia-hero h1 { font-size:2rem; }
  .basileia-about-hero h2 { font-size:2rem; }
  .basileia-footer-grid { grid-template-columns:1fr; }
  .basileia-about-card > img { width:100%; height:200px; }
  .basileia-about-card:nth-child(even) { flex-direction:column; }
  .basileia-section, .basileia-hero, .basileia-about-hero, .basileia-testimonials-section, .basileia-placeholder { padding:50px 20px; }
}
';

wp_update_custom_css_post($custom_css);
fwrite(STDOUT, "Custom CSS saved\n");

// ============================================================
// THEME SETTINGS
// ============================================================
update_option('blogname', 'Basileia Life Transformation Ministries');
update_option('blogdescription', 'Transforming lives through faith and community support.');
set_theme_mod('custom_logo', 18); // logo image ID

// Astra specific
$astra = get_theme_mod('astra-settings', []);
$astra['header-bg-color'] = '#1F1346';
$astra['footer-bg-color'] = '#1F1346';
$astra['link-color'] = '#673de6';
$astra['link-h-color'] = '#5025d1';
set_theme_mod('astra-settings', $astra);

fwrite(STDOUT, "Theme settings updated\n");

// Remove default sample page from front
wp_update_post(['ID' => 2, 'post_status' => 'draft']);

fwrite(STDOUT, "\n=== SETUP COMPLETE ===\n");
fwrite(STDOUT, "Site: http://localhost:8080\n");
fwrite(STDOUT, "Admin: http://localhost:8080/wp-admin (admin / admin123)\n");
fwrite(STDOUT, "Pages created: " . implode(', ', array_keys($page_ids)) . "\n");
