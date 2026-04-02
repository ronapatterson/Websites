<?php get_header(); ?>

<section class="error-404">
    <div class="error-404-code">404</div>
    <h1>Page Not Found</h1>
    <p>Oops! The page you're looking for doesn't exist. But don't worry — there's always a way back home.</p>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-solid">Back to Home</a>
</section>

<?php get_footer(); ?>
