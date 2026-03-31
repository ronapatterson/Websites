<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <div class="footer-logo">
                    <div class="logo-icon">🌿</div>
                    <div class="logo-text">Doctor Mommies</div>
                </div>
                <p>Nourishing families holistically through organic, earth-based recipes and wellness guidance rooted in 15+ years of expertise.</p>
                <div class="social-links">
                    <a href="#" class="social-link" aria-label="Facebook">f</a>
                    <a href="#" class="social-link" aria-label="Instagram">📷</a>
                    <a href="#" class="social-link" aria-label="TikTok">♪</a>
                    <a href="#" class="social-link" aria-label="Twitter/X">𝕏</a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Quick Links</h4>
                <div class="footer-links">
                    <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                    <a href="<?php echo esc_url(home_url('/blog')); ?>">Blog</a>
                    <a href="<?php echo esc_url(home_url('/recipes')); ?>">Recipes</a>
                    <a href="<?php echo esc_url(home_url('/about')); ?>">About Us</a>
                    <a href="<?php echo esc_url(home_url('/contact')); ?>">Contact</a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Recipes</h4>
                <div class="footer-links">
                    <a href="<?php echo esc_url(home_url('/recipe-category/breakfast')); ?>">Breakfast</a>
                    <a href="<?php echo esc_url(home_url('/recipe-category/lunch')); ?>">Lunch</a>
                    <a href="<?php echo esc_url(home_url('/recipe-category/dinner')); ?>">Dinner</a>
                    <a href="<?php echo esc_url(home_url('/recipe-category/snacks')); ?>">Snacks</a>
                    <a href="<?php echo esc_url(home_url('/recipe-category/smoothies')); ?>">Smoothies</a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Contact Us</h4>
                <div class="contact-list">
                    <div class="contact-item">
                        <span class="icon">✉</span>
                        <span>nourishments@doctormommies.com</span>
                    </div>
                    <div class="contact-item">
                        <span class="icon">📞</span>
                        <span>123-456-7890</span>
                    </div>
                    <div class="contact-item">
                        <span class="icon">🌿</span>
                        <span>Serving families holistically since 2009</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> Doctor Mommies. All rights reserved.</p>
            <div class="footer-bottom-links">
                <a href="<?php echo esc_url(home_url('/privacy-policy')); ?>">Privacy Policy</a>
                <a href="<?php echo esc_url(home_url('/terms')); ?>">Terms of Use</a>
            </div>
        </div>
    </div>
</footer>

<!-- WhatsApp Bubble -->
<a href="https://wa.me/11234567890" class="whatsapp-bubble" target="_blank" rel="noopener noreferrer" aria-label="Chat on WhatsApp">
    💬
</a>

<?php wp_footer(); ?>
</body>
</html>
