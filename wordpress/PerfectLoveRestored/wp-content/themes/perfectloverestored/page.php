<?php
/**
 * Default Page Template
 *
 * @package PerfectLoveRestored
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

<div class="page-header">
    <h1><?php the_title(); ?></h1>
</div>

<div class="page-content">
    <?php the_content(); ?>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>
