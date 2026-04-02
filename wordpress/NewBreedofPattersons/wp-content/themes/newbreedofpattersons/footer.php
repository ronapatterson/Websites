<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-about">
            <div class="footer-title"><span>New</span>Breed<span>of</span>Pattersons</div>
            <p>A Christ-centered family blog sharing real stories about faith, marriage, parenting, and building a home rooted in God's love.</p>
        </div>

        <div class="footer-nav">
            <h3>Navigate</h3>
            <?php
            wp_nav_menu( array(
                'theme_location' => 'footer',
                'container'      => false,
                'fallback_cb'    => false,
            ) );
            ?>
        </div>

        <div class="footer-connect">
            <h3>Connect</h3>
            <p>We'd love to hear from you.<br>Share your story with us.</p>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; <?php echo date( 'Y' ); ?> New Breed of Pattersons. All rights reserved.</p>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
