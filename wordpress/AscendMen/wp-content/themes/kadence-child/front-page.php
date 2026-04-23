<?php
/**
 * AscendMen homepage — mockup-matched layout.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

$img = get_stylesheet_directory_uri() . '/assets/images';
?>

<section class="am-hero" style="background-image: url('<?php echo esc_url( $img . '/hero-campfire.jpg' ); ?>');">
  <div class="am-hero__overlay"></div>
  <div class="am-hero__content am-container">
    <h1 class="am-hero__headline">Empowering Men to Embrace Their Greatness</h1>
    <p class="am-hero__subhead">Join us in discovering your God-given purpose and unleashing your true potential.</p>
    <div class="am-hero__ctas">
      <a class="am-btn am-btn--outline am-hero__cta--learn" href="#purpose">Learn</a>
      <a class="am-btn am-btn--solid am-hero__cta--join" href="<?php echo esc_url( home_url( '/register/' ) ); ?>">Join</a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
