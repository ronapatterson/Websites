<?php get_header(); ?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

<section class="page-header">
    <h1><?php the_title(); ?></h1>
</section>

<div class="page-content">
    <?php the_content(); ?>
</div>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
