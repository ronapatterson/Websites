<?php
/**
 * Template Name: Recipes Page
 */
get_header(); ?>

<section class="page-hero">
    <div class="container">
        <span style="display:inline-block;background:rgba(103,61,230,0.3);color:#ebe4ff;padding:6px 18px;border-radius:25px;font-size:13px;font-weight:600;letter-spacing:1px;text-transform:uppercase;margin-bottom:16px;border:1px solid rgba(103,61,230,0.5);">🌿 Organic &amp; Wholesome</span>
        <h1>Wholesome Recipes</h1>
        <p>Nourishing, organic recipes crafted by doctor-mommies for happy, healthy families.</p>
    </div>
</section>

<!-- Search + Filter Bar -->
<section class="recipe-filter-bar">
    <div class="container">
        <div class="recipe-search-row">
            <div class="recipe-search-wrap">
                <input type="search" id="recipe-search" placeholder="Search recipes..." aria-label="Search recipes">
                <span class="search-icon">🔍</span>
            </div>
        </div>
        <div class="recipe-filter-row">
            <button class="filter-btn active" data-cat="">All Recipes</button>
            <?php
            $terms = get_terms(['taxonomy' => 'recipe_category', 'hide_empty' => false]);
            if ($terms && !is_wp_error($terms)) :
                foreach ($terms as $term) : ?>
                    <button class="filter-btn" data-cat="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></button>
                <?php endforeach;
            endif; ?>
        </div>
    </div>
</section>

<!-- Recipes Grid -->
<section class="recipes-section" style="background:white;">
    <div class="container">
        <div id="recipes-count" class="recipes-count-bar" aria-live="polite"></div>
        <div class="recipes-grid" id="recipes-grid">
            <?php
            $paged = get_query_var('paged') ? get_query_var('paged') : 1;
            $recipes = new WP_Query([
                'post_type'      => 'recipe',
                'posts_per_page' => 12,
                'post_status'    => 'publish',
                'paged'          => $paged,
            ]);

            if ($recipes->have_posts()) :
                while ($recipes->have_posts()) : $recipes->the_post();
                    $meta = drmommies_get_recipe_meta(get_the_ID());
                    $terms = get_the_terms(get_the_ID(), 'recipe_category');
                    $category = $terms ? $terms[0]->name : 'Recipe';
                    $cat_slug = $terms ? $terms[0]->slug : '';
                    ?>
                    <article class="recipe-card" data-category="<?php echo esc_attr($cat_slug); ?>" data-title="<?php echo esc_attr(strtolower(get_the_title())); ?>">
                        <div class="card-image">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('recipe-card', ['alt' => get_the_title(), 'loading' => 'lazy']); ?>
                            <?php else : ?>
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
                            <h3><?php the_title(); ?></h3>
                            <p><?php echo wp_trim_words(get_the_excerpt(), 16); ?></p>
                        </div>
                        <div class="card-footer">
                            <span style="font-size:13px;color:#6c757d;">Difficulty: <strong><?php echo ucfirst(esc_html($meta['difficulty'])); ?></strong></span>
                            <a href="<?php the_permalink(); ?>">View Recipe →</a>
                        </div>
                    </article>
                    <?php
                endwhile;
                wp_reset_postdata();
            endif; ?>
        </div>

        <div id="no-recipes" class="no-recipes-msg" style="display:none;">
            <div style="text-align:center;padding:60px 20px;">
                <div style="font-size:60px;margin-bottom:16px;">🔍</div>
                <h3 style="color:#2f1c6a;">No recipes found</h3>
                <p style="color:#6c757d;">Try a different search term or category.</p>
                <button class="btn btn-primary" onclick="clearSearch()" style="margin-top:16px;">Show All Recipes</button>
            </div>
        </div>
    </div>
</section>

<!-- Contact / Get in Touch (mirrors live site) -->
<section class="recipe-contact-section">
    <div class="container">
        <div class="recipe-contact-grid">
            <div class="recipe-contact-text">
                <span class="section-label">Have a Question?</span>
                <h2>Get in Touch</h2>
                <p>Have a recipe request? Want to share your wholesome family meal? We would love to hear from you.</p>
                <div class="contact-mini-list">
                    <div class="contact-mini-item">📞 <span>1234567890</span></div>
                    <div class="contact-mini-item">✉ <span>nourishments@doctormommies.com</span></div>
                </div>
            </div>
            <div class="recipe-contact-form-wrap">
                <form id="recipe-contact-form" class="contact-form">
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Your Name" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Your Email" required>
                    </div>
                    <div class="form-group">
                        <textarea name="message" rows="4" placeholder="Your message..." required></textarea>
                    </div>
                    <div id="recipe-contact-result" class="form-result" aria-live="polite"></div>
                    <button type="submit" class="btn btn-primary" style="width:100%;">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
(function() {
    var grid = document.getElementById('recipes-grid');
    var noResults = document.getElementById('no-recipes');
    var countBar = document.getElementById('recipes-count');
    var searchInput = document.getElementById('recipe-search');

    function filterRecipes() {
        var activeBtn = document.querySelector('.filter-btn.active');
        var activeCat = activeBtn ? activeBtn.getAttribute('data-cat') : '';
        var searchVal = searchInput ? searchInput.value.toLowerCase().trim() : '';
        var cards = grid.querySelectorAll('.recipe-card');
        var visible = 0;

        cards.forEach(function(card) {
            var catMatch = !activeCat || card.getAttribute('data-category') === activeCat;
            var titleMatch = !searchVal || card.getAttribute('data-title').includes(searchVal) || card.querySelector('p')?.textContent.toLowerCase().includes(searchVal);
            if (catMatch && titleMatch) {
                card.style.display = '';
                visible++;
            } else {
                card.style.display = 'none';
            }
        });

        noResults.style.display = visible === 0 ? 'block' : 'none';
        if (countBar) {
            countBar.textContent = visible + ' recipe' + (visible !== 1 ? 's' : '') + ' found';
        }
    }

    // Filter buttons
    document.querySelectorAll('.filter-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(function(b) { b.classList.remove('active'); });
            this.classList.add('active');
            filterRecipes();
        });
    });

    // Search
    if (searchInput) {
        searchInput.addEventListener('input', filterRecipes);
    }

    // Init count
    filterRecipes();
})();

function clearSearch() {
    document.getElementById('recipe-search').value = '';
    document.querySelector('.filter-btn[data-cat=""]').click();
}

// Mini contact form
document.getElementById('recipe-contact-form').addEventListener('submit', function(e) {
    e.preventDefault();
    var result = document.getElementById('recipe-contact-result');
    result.className = 'form-result success';
    result.textContent = 'Thank you! We will be in touch soon.';
    this.reset();
});
</script>

<?php get_footer(); ?>
