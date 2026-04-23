<?php
/**
 * AscendMen child header — short mockup-style nav.
 * Overrides Kadence parent header. Keeps required WP hooks.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$logo_path = ABSPATH . 'ASCEND MEN PNG TRANSPARENT.png';
$has_logo  = file_exists( $logo_path );

$is_logged_in = is_user_logged_in();
if ( $is_logged_in ) {
    $user      = wp_get_current_user();
    $cta_label = esc_html( $user->first_name ?: $user->display_name );
    $cta_href  = home_url( '/account/' );
} else {
    $cta_label = 'JOIN / LOGIN';
    $cta_href  = home_url( '/register/' );
}
?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#am-content"><?php esc_html_e( 'Skip to content', 'kadence' ); ?></a>

<header class="am-header" role="banner">
  <div class="am-header__inner am-container">
    <a class="am-header__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="AscendMen home">
      <?php if ( $has_logo ) : ?>
        <img src="<?php echo esc_url( home_url( '/ASCEND%20MEN%20PNG%20TRANSPARENT.png' ) ); ?>" alt="AscendMen" />
      <?php else : ?>
        <span><?php bloginfo( 'name' ); ?></span>
      <?php endif; ?>
    </a>

    <button id="am-nav-toggle" class="am-header__toggle" aria-label="Toggle menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>

    <nav class="am-header__nav" aria-label="Primary">
      <ul>
        <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
        <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a></li>
        <li><a href="<?php echo esc_url( home_url( '/#purpose' ) ); ?>">Purpose</a></li>
        <li><a href="<?php echo esc_url( home_url( '/#greatness' ) ); ?>">Greatness</a></li>
      </ul>
    </nav>

    <a class="am-btn am-btn--solid am-header__cta" href="<?php echo esc_url( $cta_href ); ?>">
      <?php echo $cta_label; ?>
    </a>
  </div>
</header>

<main id="am-content" class="am-main">
