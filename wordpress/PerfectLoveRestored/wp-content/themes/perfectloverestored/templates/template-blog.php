<?php
/**
 * Template Name: Blog Page (PerfectLove)
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
            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
            $blog_posts = new WP_Query( array(
                'post_type'      => 'post',
                'post_status'    => 'publish',
                'posts_per_page' => 9,
                'paged'          => $paged,
            ) );

            if ( $blog_posts->have_posts() ) :
                while ( $blog_posts->have_posts() ) :
                    $blog_posts->the_post();
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

                // Pagination
                echo '<div class="pagination" style="grid-column: 1 / -1;">';
                echo paginate_links( array(
                    'total'     => $blog_posts->max_num_pages,
                    'current'   => $paged,
                    'prev_text' => '&laquo;',
                    'next_text' => '&raquo;',
                ) );
                echo '</div>';

                wp_reset_postdata();
            else :
                ?>
                <div style="text-align: center; grid-column: 1 / -1; padding: var(--plr-spacing-xl) 0;">
                    <h3>Coming Soon</h3>
                    <p style="color: var(--plr-text-light); margin-top: var(--plr-spacing-sm);">
                        We're preparing new reflections on God's perfect love. Check back soon!
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
