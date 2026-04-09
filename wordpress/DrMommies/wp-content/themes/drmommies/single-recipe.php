<?php get_header(); ?>

<?php while (have_posts()) : the_post();
    $meta = drmommies_get_recipe_meta(get_the_ID());
    $terms = get_the_terms(get_the_ID(), 'recipe_category');
    $category = $terms ? $terms[0]->name : 'Recipe';
    $ingredients = array_filter(array_map('trim', explode("\n", $meta['ingredients'])));
    $nutrition = [
        'calories'  => get_post_meta(get_the_ID(), '_calories', true) ?: '',
        'protein'   => get_post_meta(get_the_ID(), '_protein', true) ?: '',
        'carbs'     => get_post_meta(get_the_ID(), '_carbs', true) ?: '',
        'fat'       => get_post_meta(get_the_ID(), '_fat', true) ?: '',
        'fiber'     => get_post_meta(get_the_ID(), '_fiber', true) ?: '',
    ];

    $rating_data = drmommies_get_recipe_rating(get_the_ID());
    $user_rating = null;
    $user_can_rate = false;
    if (is_user_logged_in()) {
        global $wpdb;
        $ratings_table = $wpdb->prefix . 'recipe_ratings';
        $user_rating = $wpdb->get_var($wpdb->prepare(
            "SELECT rating FROM $ratings_table WHERE recipe_id = %d AND user_id = %d",
            get_the_ID(), get_current_user_id()
        ));
        $user_can_rate = !$user_rating;
    }

    // JSON-LD structured data for SEO
    $ld = [
        '@context'     => 'https://schema.org',
        '@type'        => 'Recipe',
        'name'         => get_the_title(),
        'description'  => get_the_excerpt(),
        'prepTime'     => 'PT' . preg_replace('/[^0-9]/', '', $meta['prep_time']) . 'M',
        'cookTime'     => 'PT' . preg_replace('/[^0-9]/', '', $meta['cook_time']) . 'M',
        'recipeYield'  => $meta['servings'],
        'recipeCategory' => $category,
        'keywords'     => 'organic, holistic nutrition, family recipe, healthy',
        'author'       => ['@type' => 'Organization', 'name' => 'Doctor Mommies'],
        'publisher'    => ['@type' => 'Organization', 'name' => 'Doctor Mommies', 'url' => home_url()],
        'recipeIngredient' => array_values($ingredients),
        'datePublished' => get_the_date('c'),
        'image'        => has_post_thumbnail() ? get_the_post_thumbnail_url(null, 'large') : '',
    ];
    if ($rating_data['count'] > 0) {
        $ld['aggregateRating'] = [
            '@type'       => 'AggregateRating',
            'ratingValue' => (string) $rating_data['average'],
            'ratingCount' => (string) $rating_data['count'],
        ];
    }
?>

<script type="application/ld+json"><?php echo json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>

<section class="page-hero" style="padding:60px 0;">
    <div class="container">
        <div style="margin-bottom:12px;">
            <a href="<?php echo esc_url(home_url('/recipes')); ?>" style="color:rgba(255,255,255,0.7);font-size:14px;">← Back to Recipes</a>
        </div>
        <span class="recipe-category-badge"><?php echo esc_html($category); ?></span>
        <h1 class="recipe-single-title"><?php the_title(); ?></h1>
        <div class="recipe-hero-meta">
            <span>⏱ Prep: <?php echo esc_html($meta['prep_time']); ?></span>
            <span>🔥 Cook: <?php echo esc_html($meta['cook_time']); ?></span>
            <span>🍽 <?php echo esc_html($meta['servings']); ?></span>
            <span>📊 <?php echo ucfirst(esc_html($meta['difficulty'])); ?></span>
        </div>
    </div>
</section>

<section class="recipe-single-section">
    <div class="container">
        <div class="recipe-single-grid">

            <!-- LEFT: Image + Content -->
            <div class="recipe-main">
                <div class="recipe-print-bar">
                    <button onclick="window.print()" class="btn-print" aria-label="Print recipe">
                        🖨 Print Recipe
                    </button>
                    <button class="btn-save-recipe" data-id="<?php the_ID(); ?>" aria-label="Save recipe">
                        ♡ Save
                    </button>
                    <div class="recipe-rating-widget" data-recipe-id="<?php the_ID(); ?>">
                        <?php if ($user_can_rate) : ?>
                            <div class="rating-interactive">
                                <?php for ($i = 1; $i <= 5; $i++) : ?>
                                    <span class="star interactive" data-value="<?php echo $i; ?>">&#9733;</span>
                                <?php endfor; ?>
                            </div>
                        <?php elseif ($user_rating) : ?>
                            <div class="rating-user-voted">
                                <?php for ($i = 1; $i <= 5; $i++) : ?>
                                    <span class="star <?php echo $i <= $user_rating ? 'filled' : 'empty'; ?>">&#9733;</span>
                                <?php endfor; ?>
                                <span class="rating-label">You rated this <?php echo intval($user_rating); ?> star<?php echo $user_rating > 1 ? 's' : ''; ?></span>
                            </div>
                        <?php else : ?>
                            <div class="rating-login-prompt">
                                <?php echo drmommies_render_stars_html($rating_data['average'], $rating_data['count']); ?>
                                <a href="<?php echo esc_url(wp_login_url(get_permalink())); ?>">Log in to rate</a>
                            </div>
                        <?php endif; ?>
                        <div class="rating-average">
                            <?php echo drmommies_render_stars_html($rating_data['average'], $rating_data['count']); ?>
                        </div>
                        <div class="rating-message" style="display:none;"></div>
                    </div>
                    <?php if ($user_can_rate) : ?>
                    <div class="review-form-inline" id="review-form-inline" style="display:none;">
                        <textarea id="review-text" rows="3" placeholder="Add a written review (optional)..." maxlength="1000"></textarea>
                        <button type="button" class="btn-submit-rating" id="btn-submit-rating">Submit Rating</button>
                        <button type="button" class="btn-skip-review" id="btn-skip-review">Skip Review, Just Rate</button>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="recipe-featured-image">
                        <?php the_post_thumbnail('large', ['alt' => get_the_title(), 'loading' => 'eager']); ?>
                    </div>
                <?php else : ?>
                    <div class="recipe-image-placeholder">🥘</div>
                <?php endif; ?>

                <div class="recipe-content">
                    <?php the_content(); ?>
                </div>
            </div>

            <!-- RIGHT: Sidebar -->
            <aside class="recipe-sidebar">

                <!-- Ingredients -->
                <?php if (!empty($ingredients)) : ?>
                <div class="recipe-ingredients-card">
                    <h3>🧂 Ingredients</h3>
                    <ul>
                        <?php foreach ($ingredients as $ingredient) : ?>
                            <li>
                                <label class="ingredient-row">
                                    <input type="checkbox" class="ingredient-check">
                                    <span><?php echo esc_html($ingredient); ?></span>
                                </label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- Recipe Info -->
                <div class="recipe-info-card">
                    <h4>Recipe Details</h4>
                    <dl class="recipe-details-list">
                        <div><dt>Prep Time</dt><dd><?php echo esc_html($meta['prep_time']); ?></dd></div>
                        <div><dt>Cook Time</dt><dd><?php echo esc_html($meta['cook_time']); ?></dd></div>
                        <div><dt>Servings</dt><dd><?php echo esc_html($meta['servings']); ?></dd></div>
                        <div><dt>Difficulty</dt><dd><?php echo ucfirst(esc_html($meta['difficulty'])); ?></dd></div>
                        <div><dt>Category</dt><dd><?php echo esc_html($category); ?></dd></div>
                    </dl>
                </div>

                <!-- Nutrition (if set) -->
                <?php if (array_filter($nutrition)) : ?>
                <div class="recipe-nutrition-card">
                    <h4>Nutrition Per Serving</h4>
                    <div class="nutrition-grid">
                        <?php if ($nutrition['calories']) : ?>
                        <div class="nutrition-item"><div class="nut-value"><?php echo esc_html($nutrition['calories']); ?></div><div class="nut-label">Calories</div></div>
                        <?php endif; ?>
                        <?php if ($nutrition['protein']) : ?>
                        <div class="nutrition-item"><div class="nut-value"><?php echo esc_html($nutrition['protein']); ?>g</div><div class="nut-label">Protein</div></div>
                        <?php endif; ?>
                        <?php if ($nutrition['carbs']) : ?>
                        <div class="nutrition-item"><div class="nut-value"><?php echo esc_html($nutrition['carbs']); ?>g</div><div class="nut-label">Carbs</div></div>
                        <?php endif; ?>
                        <?php if ($nutrition['fat']) : ?>
                        <div class="nutrition-item"><div class="nut-value"><?php echo esc_html($nutrition['fat']); ?>g</div><div class="nut-label">Fat</div></div>
                        <?php endif; ?>
                        <?php if ($nutrition['fiber']) : ?>
                        <div class="nutrition-item"><div class="nut-value"><?php echo esc_html($nutrition['fiber']); ?>g</div><div class="nut-label">Fiber</div></div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Newsletter CTA -->
                <div class="recipe-newsletter-cta">
                    <div class="cta-icon">💌</div>
                    <h4>Love this recipe?</h4>
                    <p>Get weekly organic recipes and nutrition tips in your inbox.</p>
                    <a href="<?php echo esc_url(home_url('/')); ?>#newsletter" class="btn btn-primary" style="width:100%;display:block;text-align:center;">Subscribe Free</a>
                </div>
            </aside>
        </div>

        <!-- Reviews Section -->
        <?php
        $approved_reviews = drmommies_get_approved_reviews(get_the_ID());
        ?>
        <div class="recipe-reviews-section">
            <h2>Reviews (<?php echo count($approved_reviews); ?>)</h2>
            <?php if (!empty($approved_reviews)) : ?>
                <div class="reviews-list">
                    <?php foreach ($approved_reviews as $review) : ?>
                        <div class="review-card">
                            <div class="review-header">
                                <span class="review-author"><?php echo esc_html($review->display_name); ?></span>
                                <span class="review-stars">
                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                        <span class="star <?php echo $i <= $review->rating ? 'filled' : 'empty'; ?>">&#9733;</span>
                                    <?php endfor; ?>
                                </span>
                                <span class="review-date"><?php echo esc_html(date('M j, Y', strtotime($review->created_at))); ?></span>
                            </div>
                            <p class="review-text"><?php echo esc_html($review->review_text); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p class="no-reviews">No reviews yet. Be the first to review this recipe!</p>
            <?php endif; ?>
        </div>

        <!-- FAQ Section -->
        <?php
        $approved_faqs = drmommies_get_approved_faqs(get_the_ID());
        ?>
        <div class="recipe-faq-section">
            <h2>Questions &amp; Answers</h2>
            <?php if (!empty($approved_faqs)) : ?>
                <div class="faq-list">
                    <?php foreach ($approved_faqs as $faq) : ?>
                        <div class="faq-item">
                            <button class="faq-question" aria-expanded="false">
                                <span class="faq-q-label">Q:</span>
                                <span><?php echo esc_html($faq->question); ?></span>
                                <span class="faq-toggle">+</span>
                            </button>
                            <div class="faq-answer" hidden>
                                <?php if ($faq->answer) : ?>
                                    <p><strong>A:</strong> <?php echo esc_html($faq->answer); ?></p>
                                <?php else : ?>
                                    <p class="faq-pending-answer"><em>Answer pending</em></p>
                                <?php endif; ?>
                                <span class="faq-meta">Asked by <?php echo esc_html($faq->display_name); ?> on <?php echo esc_html(date('M j, Y', strtotime($faq->created_at))); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (is_user_logged_in()) : ?>
                <div class="faq-submit-form" data-recipe-id="<?php the_ID(); ?>">
                    <h4>Ask a Question</h4>
                    <textarea id="faq-question-input" rows="3" placeholder="What would you like to know about this recipe?" maxlength="500"></textarea>
                    <button type="button" class="btn btn-primary" id="btn-submit-faq">Submit Question</button>
                    <div class="faq-form-message" style="display:none;"></div>
                </div>
            <?php else : ?>
                <p class="faq-login-prompt"><a href="<?php echo esc_url(wp_login_url(get_permalink())); ?>">Log in</a> to ask a question.</p>
            <?php endif; ?>
        </div>

        <!-- Related Recipes -->
        <?php
        $related = new WP_Query([
            'post_type'      => 'recipe',
            'posts_per_page' => 3,
            'post_status'    => 'publish',
            'post__not_in'   => [get_the_ID()],
            'tax_query'      => [[
                'taxonomy' => 'recipe_category',
                'field'    => 'term_id',
                'terms'    => $terms ? array_column($terms, 'term_id') : [],
            ]],
        ]);
        if (!$related->have_posts()) {
            $related = new WP_Query([
                'post_type'      => 'recipe',
                'posts_per_page' => 3,
                'post_status'    => 'publish',
                'post__not_in'   => [get_the_ID()],
                'orderby'        => 'rand',
            ]);
        }
        if ($related->have_posts()) : ?>
        <div class="related-recipes">
            <h2>You Might Also Love</h2>
            <div class="recipes-grid">
                <?php while ($related->have_posts()) : $related->the_post();
                    $rmeta = drmommies_get_recipe_meta(get_the_ID());
                    $rterms = get_the_terms(get_the_ID(), 'recipe_category');
                    $rcat = $rterms ? $rterms[0]->name : 'Recipe'; ?>
                    <article class="recipe-card">
                        <div class="card-image">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('recipe-card', ['alt' => get_the_title()]); ?>
                            <?php else : ?>
                                <div style="height:100%;display:flex;align-items:center;justify-content:center;font-size:60px;background:linear-gradient(135deg,#ebe4ff,#d5dfff);">🥘</div>
                            <?php endif; ?>
                            <span class="card-badge"><?php echo esc_html($rcat); ?></span>
                        </div>
                        <div class="card-body">
                            <div class="card-meta">
                                <span>⏱ <?php echo esc_html($rmeta['prep_time']); ?></span>
                                <span>🍽 <?php echo esc_html($rmeta['servings']); ?></span>
                            </div>
                            <?php
                            $rel_rating = drmommies_get_recipe_rating(get_the_ID());
                            if ($rel_rating['count'] > 0) : ?>
                                <div class="card-rating"><?php echo drmommies_render_stars_html($rel_rating['average'], $rel_rating['count']); ?></div>
                            <?php endif; ?>
                            <h3><?php the_title(); ?></h3>
                            <p><?php echo wp_trim_words(get_the_excerpt(), 14); ?></p>
                        </div>
                        <div class="card-footer">
                            <span style="font-size:13px;color:#6c757d;">Difficulty: <strong><?php echo ucfirst(esc_html($rmeta['difficulty'])); ?></strong></span>
                            <a href="<?php the_permalink(); ?>">View Recipe →</a>
                        </div>
                    </article>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>
