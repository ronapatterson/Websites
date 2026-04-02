<?php get_header(); ?>

<!-- Hero -->
<section class="hero">
    <div class="hero-label">Faith &bull; Family &bull; Love</div>
    <h1>New Breed of Pattersons</h1>
    <p class="hero-description">Real stories about faith, marriage, parenting, and building a Christ-centered home — one day at a time.</p>
    <div class="hero-ctas">
        <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="btn btn-primary">Read the Blog</a>
        <?php
        $about_page = get_page_by_path( 'about' );
        if ( $about_page ) : ?>
            <a href="<?php echo esc_url( get_permalink( $about_page ) ); ?>" class="btn btn-outline">About Us</a>
        <?php endif; ?>
    </div>
</section>

<!-- Movie Pick of the Week -->
<?php $movie = nbop_get_movie_pick(); ?>
<?php if ( $movie ) : ?>
<section class="movie-pick">
    <div class="movie-pick-inner">
        <div class="movie-pick-badge">&#127916; Movie Pick</div>
        <div class="movie-pick-info">
            <div class="movie-pick-label">This Week's Family Movie</div>
            <div class="movie-pick-title"><?php echo esc_html( $movie['title'] ); ?></div>
        </div>
        <?php if ( $movie['review'] ) : ?>
            <div class="movie-pick-review"><?php echo esc_html( $movie['review'] ); ?></div>
        <?php endif; ?>
        <?php if ( $movie['rating'] ) : ?>
            <div class="movie-pick-rating"><?php echo str_repeat( '&#9733;', $movie['rating'] ) . str_repeat( '&#9734;', 5 - $movie['rating'] ); ?></div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<!-- Latest Blog Posts -->
<section class="posts-section">
    <div class="section-title">
        <h2>Latest from the Blog</h2>
        <div class="section-title-bar"></div>
    </div>

    <div class="posts-grid">
        <?php
        $latest = new WP_Query( array(
            'posts_per_page' => 6,
            'post_status'    => 'publish',
        ) );

        if ( $latest->have_posts() ) :
            while ( $latest->have_posts() ) : $latest->the_post();
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
                    <h3 class="post-card-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                    <p class="post-card-excerpt"><?php echo get_the_excerpt(); ?></p>
                </div>
            </article>
        <?php
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </div>
</section>

<!-- Scripture Banner -->
<section class="scripture-banner">
    <p class="scripture-text">"Train up a child in the way he should go: and when he is old, he will not depart from it."</p>
    <p class="scripture-ref">Proverbs 22:6</p>
</section>

<?php get_footer(); ?>
