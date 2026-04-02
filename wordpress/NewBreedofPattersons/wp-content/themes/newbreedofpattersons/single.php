<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<section class="post-header">
    <?php
    $categories = get_the_category();
    if ( ! empty( $categories ) ) :
        $cat_slug   = $categories[0]->slug;
        $badge_class = nbop_category_badge_class( $cat_slug );
    ?>
        <span class="category-badge <?php echo esc_attr( $badge_class ); ?>"><?php echo esc_html( $categories[0]->name ); ?></span>
    <?php endif; ?>
    <h1><?php the_title(); ?></h1>
    <p class="post-meta"><?php echo get_the_date(); ?></p>
</section>

<?php if ( has_post_thumbnail() ) : ?>
<div class="post-featured-image">
    <?php the_post_thumbnail( 'nbop-hero' ); ?>
</div>
<?php endif; ?>

<article class="post-content">
    <?php the_content(); ?>
</article>

<nav class="post-navigation">
    <div>
        <?php previous_post_link( '%link', '&larr; %title' ); ?>
    </div>
    <div>
        <?php next_post_link( '%link', '%title &rarr;' ); ?>
    </div>
</nav>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
