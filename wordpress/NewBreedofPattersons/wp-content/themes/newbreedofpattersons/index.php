<?php get_header(); ?>

<section class="archive-header">
    <h1>The Blog</h1>
    <p>Stories of faith, family, and love from our home to yours.</p>
</section>

<section class="posts-section">
    <div class="posts-grid">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post();
                $categories = get_the_category();
                $cat_slug   = ! empty( $categories ) ? $categories[0]->slug : '';
                $cat_name   = ! empty( $categories ) ? $categories[0]->name : '';
                $badge_class = nbop_category_badge_class( $cat_slug );
            ?>
                <article class="post-card">
                    <div class="post-card-image">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'nbop-card' ); ?>
                        <?php endif; ?>
                    </div>
                    <div class="post-card-body">
                        <?php if ( $cat_name ) : ?>
                            <span class="category-badge <?php echo esc_attr( $badge_class ); ?>"><?php echo esc_html( $cat_name ); ?></span>
                        <?php endif; ?>
                        <div class="post-card-meta"><?php echo get_the_date(); ?></div>
                        <h3 class="post-card-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        <p class="post-card-excerpt"><?php echo get_the_excerpt(); ?></p>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php else : ?>
            <p style="text-align:center;grid-column:1/-1;padding:4rem 0;color:var(--nbop-text-light);">No posts found. Check back soon!</p>
        <?php endif; ?>
    </div>

    <?php if ( have_posts() ) : ?>
    <div class="pagination">
        <?php
        the_posts_pagination( array(
            'mid_size'  => 2,
            'prev_text' => '&larr; Prev',
            'next_text' => 'Next &rarr;',
        ) );
        ?>
    </div>
    <?php endif; ?>
</section>

<?php get_footer(); ?>
