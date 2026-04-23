<?php
/**
 * AscendMen child footer — 4-column mockup-style layout.
 * Overrides Kadence parent footer. Keeps required WP hooks.
 *
 * Intentional trade-off: this override drops Kadence parent footer
 * action hooks (`kadence_before_footer`, `kadence_footer`,
 * `kadence_after_footer`, `kadence_after_content`, `kadence_after_wrapper`).
 * Re-evaluate when adding Kadence-family plugins that rely on them.
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
        <li><a href="#" aria-label="Facebook"><span aria-hidden="true">FB</span></a></li>
        <li><a href="#" aria-label="Instagram"><span aria-hidden="true">IG</span></a></li>
        <li><a href="#" aria-label="TikTok"><span aria-hidden="true">TT</span></a></li>
        <li><a href="#" aria-label="X"><span aria-hidden="true">X</span></a></li>
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
      <form class="am-footer__subscribe" aria-label="Subscribe to updates (coming soon)">
        <label class="screen-reader-text" for="am-subscribe-email">Email</label>
        <input id="am-subscribe-email" type="email" placeholder="you@example.com" autocomplete="off" disabled aria-disabled="true" />
        <button type="submit" class="am-btn am-btn--solid" disabled aria-disabled="true" title="Coming soon">Subscribe</button>
      </form>
      <p>contact@ascendmen.org</p>
      <p>123-456-7890</p>
    </div>

  </div>

  <div class="am-footer__bottom">
    <div class="am-container">
      <p>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> Ascend Men. All rights reserved.</p>
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
