<?php
/**
 * 404 Page Template
 *
 * @package PerfectLoveRestored
 */

get_header(); ?>

<section class="section" style="text-align: center; padding: var(--plr-spacing-xxl) var(--plr-spacing-md);">
    <div class="container content-narrow">
        <h1 style="font-size: 4rem; color: var(--plr-gold); margin-bottom: var(--plr-spacing-xs);">404</h1>
        <h2>Page Not Found</h2>
        <p style="font-size: 1.1rem; margin: var(--plr-spacing-md) auto; max-width: 500px;">
            The page you're looking for doesn't seem to exist. But God's love for you?
            That's always right where you are.
        </p>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">Return Home</a>
    </div>
</section>

<?php get_footer(); ?>
