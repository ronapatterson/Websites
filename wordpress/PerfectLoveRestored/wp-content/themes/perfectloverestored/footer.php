</main><!-- .site-main -->

<footer class="site-footer" role="contentinfo">
    <div class="footer-inner">
        <div class="footer-about">
            <h3><?php bloginfo( 'name' ); ?></h3>
            <p><?php bloginfo( 'description' ); ?></p>
        </div>
        <div class="footer-links">
            <h4>Navigate</h4>
            <?php
            wp_nav_menu( array(
                'theme_location' => 'footer',
                'container'      => false,
                'fallback_cb'    => false,
                'depth'          => 1,
            ) );
            ?>
        </div>
        <div class="footer-connect">
            <h4>Connect</h4>
            <p>Join us in discovering the depths of God's perfect love.</p>
            <p><a href="mailto:hello@perfectloverestored.com">hello@perfectloverestored.com</a></p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. All rights reserved.</p>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
