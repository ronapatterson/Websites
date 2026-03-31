<?php
/**
 * Template Name: Contact Page
 */
get_header(); ?>

<section class="page-hero">
    <div class="container">
        <span style="display:inline-block;background:rgba(103,61,230,0.3);color:#ebe4ff;padding:6px 18px;border-radius:25px;font-size:13px;font-weight:600;letter-spacing:1px;text-transform:uppercase;margin-bottom:16px;border:1px solid rgba(103,61,230,0.5);">✉ Reach Out</span>
        <h1>Contact Us</h1>
        <p>We would love to hear from you. Questions, collaborations, or just want to share your cooking wins!</p>
    </div>
</section>

<section class="contact-section">
    <div class="container">
        <div class="contact-grid">

            <!-- Contact Info -->
            <div class="contact-info-col">
                <h2>Get in Touch</h2>
                <p>Whether you have a question about a recipe, want to share your family's story, or are interested in collaborating with us, we are here and happy to help.</p>

                <div class="contact-details">
                    <div class="contact-detail-item">
                        <div class="contact-icon">✉</div>
                        <div>
                            <strong>Email</strong>
                            <p>nourishments@doctormommies.com</p>
                        </div>
                    </div>
                    <div class="contact-detail-item">
                        <div class="contact-icon">📞</div>
                        <div>
                            <strong>Phone</strong>
                            <p>123-456-7890</p>
                        </div>
                    </div>
                    <div class="contact-detail-item">
                        <div class="contact-icon">🕐</div>
                        <div>
                            <strong>Response Time</strong>
                            <p>Within 2 business days</p>
                        </div>
                    </div>
                </div>

                <div class="contact-social">
                    <h4>Follow Us</h4>
                    <div class="social-links">
                        <a href="#" class="social-link" aria-label="Facebook">f</a>
                        <a href="#" class="social-link" aria-label="Instagram">📷</a>
                        <a href="#" class="social-link" aria-label="TikTok">♪</a>
                        <a href="#" class="social-link" aria-label="Twitter/X">𝕏</a>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="contact-form-col">
                <div class="contact-form-card">
                    <h3>Send Us a Message</h3>
                    <form id="contact-form" class="contact-form" novalidate>
                        <?php wp_nonce_field('drmommies_contact_nonce', 'contact_nonce'); ?>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="contact-name">Your Name <span class="required">*</span></label>
                                <input type="text" id="contact-name" name="name" placeholder="Jane Smith" required>
                            </div>
                            <div class="form-group">
                                <label for="contact-email">Email Address <span class="required">*</span></label>
                                <input type="email" id="contact-email" name="email" placeholder="jane@example.com" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="contact-subject">Subject</label>
                            <select id="contact-subject" name="subject">
                                <option value="">Select a topic...</option>
                                <option value="recipe-question">Recipe Question</option>
                                <option value="nutrition-advice">Nutrition Advice</option>
                                <option value="collaboration">Collaboration / Partnership</option>
                                <option value="newsletter">Newsletter</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="contact-message">Message <span class="required">*</span></label>
                            <textarea id="contact-message" name="message" rows="6" placeholder="Tell us how we can help..." required></textarea>
                        </div>

                        <div id="contact-result" class="form-result" aria-live="polite"></div>

                        <button type="submit" class="btn btn-primary" style="width:100%;">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Banner -->
<section class="newsletter-section">
    <div class="container">
        <div class="newsletter-content">
            <span class="section-label">Stay Connected</span>
            <h2>Join Our Community</h2>
            <p>Subscribe for weekly organic recipes, seasonal meal plans, and holistic nutrition guidance.</p>
            <form class="newsletter-form" id="newsletter-form-contact">
                <input type="email" id="newsletter-email-contact" placeholder="Enter your email address" required>
                <button type="submit">Subscribe</button>
            </form>
        </div>
    </div>
</section>

<script>
document.getElementById('contact-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const resultEl = document.getElementById('contact-result');
    const name = document.getElementById('contact-name').value.trim();
    const email = document.getElementById('contact-email').value.trim();
    const message = document.getElementById('contact-message').value.trim();

    if (!name || !email || !message) {
        resultEl.className = 'form-result error';
        resultEl.textContent = 'Please fill in all required fields.';
        return;
    }

    resultEl.className = 'form-result success';
    resultEl.textContent = 'Thank you, ' + name + '! Your message has been received. We will be in touch within 2 business days.';
    this.reset();
});

document.getElementById('newsletter-form-contact').addEventListener('submit', function(e) {
    e.preventDefault();
    const email = document.getElementById('newsletter-email-contact').value;
    if (!email) return;
    fetch(drMommiesData.ajaxUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=newsletter_signup&nonce=' + drMommiesData.nonce + '&email=' + encodeURIComponent(email)
    }).then(r => r.json()).then(data => {
        this.innerHTML = '<p style="color:#ebe4ff;font-weight:600;">' + (data.data?.message || 'Thank you for subscribing!') + '</p>';
    });
});
</script>

<?php get_footer(); ?>
