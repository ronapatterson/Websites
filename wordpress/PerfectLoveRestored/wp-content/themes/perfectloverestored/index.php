<?php
/**
 * Main Index Template
 *
 * @package PerfectLoveRestored
 */

get_header(); ?>

<div class="page-header">
    <h1>Perfect Love</h1>
    <p class="subtitle">Reflections on God's love, grace, and the finished work of the cross</p>
</div>

<section class="section">
    <div class="container">
        <div class="posts-grid">
            <?php
            if ( have_posts() ) :
                while ( have_posts() ) :
                    the_post();
                    ?>
                    <article class="post-card">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <img class="post-card-image" src="<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'plr-card' ) ); ?>" alt="<?php the_title_attribute(); ?>">
                        <?php else : ?>
                            <div class="post-card-image"></div>
                        <?php endif; ?>
                        <div class="post-card-content">
                            <div class="post-card-meta"><?php echo get_the_date( 'F j, Y' ); ?></div>
                            <h3 class="post-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <p class="post-card-excerpt"><?php echo get_the_excerpt(); ?></p>
                            <a href="<?php the_permalink(); ?>" class="read-more">Continue Reading &rarr;</a>
                        </div>
                    </article>
                    <?php
                endwhile;
            else :
                ?>
                <p style="text-align: center; grid-column: 1 / -1; padding: var(--plr-spacing-xl) 0; color: var(--plr-text-light);">
                    New blog posts are coming soon. Stay tuned for reflections on God's perfect love.
                </p>
            <?php endif; ?>
        </div>

        <?php
        the_posts_pagination( array(
            'mid_size'  => 2,
            'prev_text' => '&laquo;',
            'next_text' => '&raquo;',
        ) );
        ?>
    </div>
</section>

<?php get_footer(); ?>
