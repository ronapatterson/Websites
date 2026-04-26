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

<?php
$am_testimonials = [
    'Ascend Men has truly empowered me to embrace my God-given gifts and pursue my purpose with confidence.',
    'The brotherhood I found at Ascend Men rekindled my drive to lead my family well.',
    'I came in looking for direction and left with a calling.',
    'Every man needs this in his life — it pulled me out of coasting and into purpose.',
    'The camps weren\'t a retreat — they were a reset.',
];
?>
<section class="am-testimonials am-section am-section--dark">
  <div class="am-container">
    <div class="am-testimonials__stars" aria-hidden="true">
      <?php for ( $s = 0; $s < 5; $s++ ) : ?>
        <svg width="22" height="22" viewBox="0 0 24 24" fill="#29ABE2" xmlns="http://www.w3.org/2000/svg"><path d="M12 2l2.9 6.9 7.4.6-5.6 4.9 1.7 7.2L12 17.8 5.6 21.6l1.7-7.2L1.7 9.5l7.4-.6L12 2z"/></svg>
      <?php endfor; ?>
    </div>

    <div class="am-carousel" aria-label="Member testimonials">
      <button class="am-carousel__prev" aria-label="Previous testimonial">&#8249;</button>
      <button class="am-carousel__next" aria-label="Next testimonial">&#8250;</button>

      <div class="am-carousel__track">
        <?php foreach ( $am_testimonials as $idx => $quote ) : ?>
          <blockquote class="am-carousel__slide<?php echo $idx === 0 ? ' is-active' : ''; ?>"
                      aria-hidden="<?php echo $idx === 0 ? 'false' : 'true'; ?>">
            <p>&ldquo;<?php echo esc_html( $quote ); ?>&rdquo;</p>
            <cite>&mdash; John Doe</cite>
          </blockquote>
        <?php endforeach; ?>
      </div>

      <div class="am-carousel__dots" role="tablist">
        <?php foreach ( $am_testimonials as $idx => $_q ) : ?>
          <button class="am-carousel__dot"
                  aria-label="Go to testimonial <?php echo esc_attr( $idx + 1 ); ?>"
                  aria-current="<?php echo $idx === 0 ? 'true' : 'false'; ?>"></button>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>
