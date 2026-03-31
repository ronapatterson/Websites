<?php
/**
 * Template Name: Store Page
 *
 * @package PerfectLoveRestored
 */

get_header(); ?>

<div class="page-header">
    <h1>Store</h1>
    <p class="subtitle">Resources to deepen your understanding of God's love</p>
</div>

<section class="section">
    <div class="container">
        <div class="store-notice">
            <h2>Coming Soon</h2>
            <p>
                We are preparing a collection of books, devotionals, and resources
                to help you grow in the revelation of God's perfect love.
            </p>
            <p style="color: var(--plr-text-lighter); font-size: 0.95rem;">
                In the meantime, explore our blog for free reflections and teachings.
            </p>
            <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'perfectlove' ) ) ); ?>" class="btn btn-primary">Visit the Blog</a>
        </div>
    </div>
</section>

<section class="scripture-banner">
    <div class="container">
        <p class="scripture-text">
            &ldquo;Freely you have received; freely give.&rdquo;
        </p>
        <span class="scripture-ref">&mdash; Matthew 10:8 (NIV)</span>
    </div>
</section>

<?php
// If WooCommerce content exists on this page, display it
$page_content = get_the_content();
if ( ! empty( $page_content ) ) : ?>
<section class="section">
    <div class="container">
        <?php
        while ( have_posts() ) :
            the_post();
            the_content();
        endwhile;
        ?>
    </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
