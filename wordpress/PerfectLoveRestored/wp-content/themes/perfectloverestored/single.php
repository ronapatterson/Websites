<?php
/**
 * Single Post Template
 *
 * @package PerfectLoveRestored
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

<div class="single-post-header">
    <h1><?php the_title(); ?></h1>
    <div class="single-post-meta">
        <?php echo get_the_date( 'F j, Y' ); ?> &middot; <?php echo get_the_category_list( ', ' ); ?>
    </div>
</div>

<article class="single-post-content">
    <?php if ( has_post_thumbnail() ) : ?>
        <img src="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'large' ) ); ?>" alt="<?php the_title_attribute(); ?>" style="width: 100%; border-radius: var(--plr-border-radius-lg); margin-bottom: var(--plr-spacing-lg);">
    <?php endif; ?>

    <?php the_content(); ?>

    <div style="margin-top: var(--plr-spacing-xl); padding-top: var(--plr-spacing-md); border-top: 1px solid var(--plr-border);">
        <?php
        the_post_navigation( array(
            'prev_text' => '<span style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; color: var(--plr-text-lighter);">Previous</span><br>%title',
            'next_text' => '<span style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; color: var(--plr-text-lighter);">Next</span><br>%title',
        ) );
        ?>
    </div>

    <?php
    if ( comments_open() || get_comments_number() ) :
        comments_template();
    endif;
    ?>
</article>

<?php endwhile; ?>

<?php get_footer(); ?>
