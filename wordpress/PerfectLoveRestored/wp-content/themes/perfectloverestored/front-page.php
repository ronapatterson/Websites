<?php
/**
 * Front Page Template
 *
 * @package PerfectLoveRestored
 */

get_header(); ?>

<section class="hero-section">
    <div class="hero-content">
        <div class="hero-scripture">1 John 4:18</div>
        <h1 class="hero-title">Perfect Love Restored</h1>
        <p class="hero-subtitle">
            Discovering the fullness of God's unconditional love through relationship
            and the finished work of Christ on the cross.
        </p>
        <div class="hero-cta">
            <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'perfectlove' ) ) ); ?>" class="btn btn-primary">Explore the Blog</a>
            <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'about' ) ) ); ?>" class="btn btn-outline">Our Story</a>
        </div>
    </div>
</section>

<section class="scripture-banner">
    <div class="container">
        <p class="scripture-text">
            &ldquo;There is no fear in love. But perfect love drives out fear, because fear has to do with punishment.
            The one who fears is not made perfect in love.&rdquo;
        </p>
        <span class="scripture-ref">&mdash; 1 John 4:18 (NIV)</span>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Latest from the Blog</h2>
            <p>Reflections on God's love, grace, and the beauty of His finished work.</p>
        </div>
        <div class="posts-grid">
            <?php
            $recent_posts = new WP_Query( array(
                'posts_per_page' => 3,
                'post_status'    => 'publish',
            ) );

            if ( $recent_posts->have_posts() ) :
                while ( $recent_posts->have_posts() ) :
                    $recent_posts->the_post();
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
                wp_reset_postdata();
            else :
                ?>
                <p style="text-align: center; grid-column: 1 / -1; color: var(--plr-text-light);">
                    New blog posts coming soon. Stay tuned for reflections on God's perfect love.
                </p>
            <?php endif; ?>
        </div>
        <?php if ( $recent_posts->found_posts > 3 ) : ?>
            <div style="text-align: center; margin-top: var(--plr-spacing-lg);">
                <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'perfectlove' ) ) ); ?>" class="btn btn-outline">View All Posts</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="section section-cream">
    <div class="container">
        <div class="section-header">
            <h2>What We Believe</h2>
        </div>
        <div style="max-width: 700px; margin: 0 auto; text-align: center;">
            <p style="font-size: 1.1rem; line-height: 1.8;">
                We believe that God's love for us is not based on our performance but on His nature.
                Through the finished work of the cross, Jesus restored us to a place of perfect union
                with the Father. Our journey is one of discovering what has already been accomplished
                &mdash; not striving for what we think we must earn.
            </p>
            <div style="margin-top: var(--plr-spacing-md);">
                <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'about' ) ) ); ?>" class="btn btn-primary">Learn More</a>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
