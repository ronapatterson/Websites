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

<section id="greatness" class="am-mission am-section am-section--navy">
  <div class="am-container">
    <p class="am-mission__copy">
      At Ascend Men, we inspire and empower men to embrace their God-given gifts,
      unlocking their true potential and purpose in life.
    </p>
  </div>
</section>

<section class="am-stats am-section am-section--dark">
  <div class="am-container am-stats__grid">
    <div class="am-stats__item">
      <div class="am-stats__number">150+</div>
      <div class="am-stats__label">Members</div>
    </div>
    <div class="am-stats__item">
      <div class="am-stats__number">15</div>
      <div class="am-stats__label">Programs</div>
    </div>
  </div>
</section>

<section id="purpose" class="am-pillars am-section">
  <div class="am-container">
    <h2 class="am-pillars__heading">Lead with Purpose</h2>
    <div class="am-pillars__grid">

      <article class="am-pillar">
        <img class="am-pillar__image" src="<?php echo esc_url( $img . '/pillar-coaching.jpg' ); ?>" alt="One-on-one coaching conversation" />
        <h3 class="am-pillar__title">Purposeful Life Coaching</h3>
        <p class="am-pillar__body">Developing confidence through identifying unique abilities.</p>
      </article>

      <article class="am-pillar">
        <img class="am-pillar__image" src="<?php echo esc_url( $img . '/pillar-community.jpg' ); ?>" alt="Men gathered in community" />
        <h3 class="am-pillar__title">Community Support Network</h3>
        <p class="am-pillar__body">Peer accountability and shared purpose.</p>
      </article>

      <article class="am-pillar">
        <img class="am-pillar__image" src="<?php echo esc_url( $img . '/pillar-leadership.jpg' ); ?>" alt="Men engaged in leadership activity" />
        <h3 class="am-pillar__title">Leadership Development &amp; Community Initiatives</h3>
        <p class="am-pillar__body">Programs fostering growth and shared experiences.</p>
      </article>

    </div>
  </div>
</section>

<?php get_footer(); ?>
