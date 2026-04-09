<?php get_header(); ?>

<section class="page-hero">
    <div class="container">
        <h1><?php post_type_archive_title(); ?></h1>
        <p>Wholesome, organic recipes crafted for healthy families.</p>
    </div>
</section>

<section class="recipes-section" style="background:white;">
    <div class="container">
        <div class="recipes-grid">
            <?php if (have_posts()) : while (have_posts()) : the_post();
                $meta = drmommies_get_recipe_meta(get_the_ID());
                $terms = get_the_terms(get_the_ID(), 'recipe_category');
                $category = $terms ? $terms[0]->name : 'Recipe'; ?>
                <?php $card_rating = drmommies_get_recipe_rating(get_the_ID()); ?>
                <article class="recipe-card" data-rating="<?php echo esc_attr($card_rating['average']); ?>" data-rating-count="<?php echo esc_attr($card_rating['count']); ?>">
                    <div class="card-image">
                        <?php if (has_post_thumbnail()) :
                            the_post_thumbnail('recipe-card', ['alt' => get_the_title()]);
                        else : ?>
                            <div style="height:100%;display:flex;align-items:center;justify-content:center;font-size:70px;background:linear-gradient(135deg,#ebe4ff,#d5dfff);">🥘</div>
                        <?php endif; ?>
                        <span class="card-badge"><?php echo esc_html($category); ?></span>
                    </div>
                    <div class="card-body">
                        <div class="card-meta">
                            <span>⏱ <?php echo esc_html($meta['prep_time']); ?></span>
                            <span>🔥 <?php echo esc_html($meta['cook_time']); ?></span>
                            <span>🍽 <?php echo esc_html($meta['servings']); ?></span>
                        </div>
                        <?php if ($card_rating['count'] > 0) : ?>
                            <div class="card-rating"><?php echo drmommies_render_stars_html($card_rating['average'], $card_rating['count']); ?></div>
                        <?php endif; ?>
                        <h3><?php the_title(); ?></h3>
                        <p><?php echo wp_trim_words(get_the_excerpt(), 16); ?></p>
                    </div>
                    <div class="card-footer">
                        <span style="font-size:13px;color:#6c757d;">Difficulty: <strong><?php echo ucfirst(esc_html($meta['difficulty'])); ?></strong></span>
                        <a href="<?php the_permalink(); ?>">View Recipe →</a>
                    </div>
                </article>
            <?php endwhile; endif; ?>
        </div>
        <div style="margin-top:40px;text-align:center;">
            <?php the_posts_pagination(['mid_size' => 2]); ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
