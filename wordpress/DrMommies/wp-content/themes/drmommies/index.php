<?php get_header(); ?>

<!-- HERO SECTION -->
<section class="hero-section">
    <div class="hero-content">
        <div class="hero-text">
            <span class="hero-badge">🌿 Holistic Family Nutrition</span>
            <h1>Nourishing <span class="highlight">Families</span><br>Holistically</h1>
            <p>Discover wholesome, organic recipes and wellness wisdom crafted by doctors who are also moms. Real food. Real health. Real love.</p>
            <div class="hero-buttons">
                <a href="<?php echo esc_url(home_url('/recipes')); ?>" class="btn btn-primary">Explore Recipes</a>
                <a href="<?php echo esc_url(home_url('/blog')); ?>" class="btn btn-outline btn-white-outline" style="color:rgba(255,255,255,0.9);border-color:rgba(255,255,255,0.4);">Read Our Blog</a>
            </div>
            <div class="hero-stats">
                <div class="hero-stat">
                    <div class="number">150+</div>
                    <div class="label">Organic Recipes</div>
                </div>
                <div class="hero-stat">
                    <div class="number">15</div>
                    <div class="label">Years of Service</div>
                </div>
                <div class="hero-stat">
                    <div class="number">10K+</div>
                    <div class="label">Happy Families</div>
                </div>
            </div>
        </div>
        <div class="hero-image-grid">
            <div class="hero-img-card large">
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/hero-main.svg"
                     alt="Wholesome family meal"
                     onerror="this.style.background='rgba(103,61,230,0.3)';this.style.display='block';this.style.height='100%'">
            </div>
            <div class="hero-img-card">
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/hero-2.svg"
                     alt="Fresh ingredients"
                     onerror="this.style.background='rgba(103,61,230,0.2)';this.style.display='block';this.style.height='100%'">
            </div>
            <div class="hero-img-card">
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/hero-3.svg"
                     alt="Family cooking"
                     onerror="this.style.background='rgba(103,61,230,0.15)';this.style.display='block';this.style.height='100%'">
            </div>
        </div>
    </div>
</section>

<!-- ABOUT SECTION -->
<section class="about-section">
    <div class="container">
        <div class="about-grid">
            <div class="about-image">
                <div style="height:420px;background:linear-gradient(135deg,#ebe4ff,#d5dfff);border-radius:20px;display:flex;align-items:center;justify-content:center;font-size:80px;">🥗</div>
                <div class="badge-card">
                    <div class="number">15+</div>
                    <div class="text">Years of Expertise</div>
                </div>
            </div>
            <div class="about-text">
                <span class="section-label">About Doctor Mommies</span>
                <h2>Holistic Nutrition for the Whole Family</h2>
                <p>We are a community of physician-mothers passionate about nourishing families through the healing power of whole, organic foods. Our approach blends modern medical knowledge with earth-based wisdom.</p>
                <p>Every recipe we share has been lovingly tested in our own kitchens, approved by our kids, and backed by nutritional science.</p>
                <div class="feature-list">
                    <div class="feature-item">
                        <div class="icon">🌱</div>
                        <span>100% Organic & Whole Food Ingredients</span>
                    </div>
                    <div class="feature-item">
                        <div class="icon">👨‍⚕️</div>
                        <span>Doctor-Approved Nutritional Guidance</span>
                    </div>
                    <div class="feature-item">
                        <div class="icon">👶</div>
                        <span>Kid-Friendly & Family-Tested Recipes</span>
                    </div>
                    <div class="feature-item">
                        <div class="icon">🥦</div>
                        <span>Seasonal & Sustainable Eating</span>
                    </div>
                </div>
                <a href="<?php echo esc_url(home_url('/about')); ?>" class="btn btn-primary">Learn More About Us</a>
            </div>
        </div>
    </div>
</section>

<!-- STATS SECTION -->
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="number">150+</div>
                <div class="label">Organic Recipes</div>
            </div>
            <div class="stat-card">
                <div class="number">15</div>
                <div class="label">Years of Service</div>
            </div>
            <div class="stat-card">
                <div class="number">10K+</div>
                <div class="label">Happy Families</div>
            </div>
            <div class="stat-card">
                <div class="number">5⭐</div>
                <div class="label">Community Rating</div>
            </div>
        </div>
    </div>
</section>

<!-- RECIPES SECTION -->
<section class="recipes-section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Our Recipes</span>
            <h2>Wholesome Recipes for Healthy Families</h2>
            <p>Nourishing meals made with love and organic ingredients, designed to delight even the pickiest eaters.</p>
        </div>

        <div class="recipes-grid">
            <?php
            $recipes = new WP_Query([
                'post_type'      => 'recipe',
                'posts_per_page' => 6,
                'post_status'    => 'publish',
            ]);

            if ($recipes->have_posts()) :
                while ($recipes->have_posts()) : $recipes->the_post();
                    $meta = drmommies_get_recipe_meta(get_the_ID());
                    $terms = get_the_terms(get_the_ID(), 'recipe_category');
                    $category = $terms ? $terms[0]->name : 'Recipe';
                    ?>
                    <article class="recipe-card">
                        <div class="card-image">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('recipe-card', ['alt' => get_the_title()]); ?>
                            <?php else : ?>
                                <div style="height:100%;display:flex;align-items:center;justify-content:center;font-size:60px;background:linear-gradient(135deg,#ebe4ff,#d5dfff);">🥘</div>
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
                            <p><?php echo wp_trim_words(get_the_excerpt(), 18); ?></p>
                        </div>
                        <div class="card-footer">
                            <span style="font-size:13px;color:#6c757d;">Difficulty: <strong><?php echo ucfirst(esc_html($meta['difficulty'])); ?></strong></span>
                            <a href="<?php the_permalink(); ?>">View Recipe →</a>
                        </div>
                    </article>
                    <?php
                endwhile;
                wp_reset_postdata();
            else :
                // Placeholder cards when no recipes exist
                $sample_recipes = [
                    ['🥗', 'Rainbow Veggie Power Bowl', 'A vibrant, nutrient-dense bowl packed with colorful organic vegetables.', '10 mins', '5 mins', '2 servings', 'Easy', 'Lunch'],
                    ['🥤', 'Green Immunity Smoothie', 'Spinach, banana, and mango blend that kids actually love.', '5 mins', '0 mins', '3 servings', 'Easy', 'Breakfast'],
                    ['🍲', 'Lentil & Sweet Potato Soup', 'Warming, protein-rich soup perfect for the whole family.', '15 mins', '40 mins', '6 servings', 'Easy', 'Dinner'],
                    ['🥞', 'Oat Banana Pancakes', 'Gluten-free, sweetener-free pancakes loved by toddlers and adults alike.', '10 mins', '15 mins', '4 servings', 'Easy', 'Breakfast'],
                    ['🥕', 'Hidden Veggie Pasta Sauce', 'Sneaks in 5 vegetables — kids will never know!', '10 mins', '30 mins', '5 servings', 'Medium', 'Dinner'],
                    ['🍓', 'Berry Chia Pudding', 'Omega-3 rich overnight pudding with fresh berries.', '5 mins', '0 mins', '2 servings', 'Easy', 'Snacks'],
                ];
                foreach ($sample_recipes as $r) :
                    [$emoji, $title, $desc, $prep, $cook, $servings, $diff, $cat] = $r;
                    ?>
                    <article class="recipe-card">
                        <div class="card-image">
                            <div style="height:100%;display:flex;align-items:center;justify-content:center;font-size:70px;background:linear-gradient(135deg,#ebe4ff,#d5dfff);"><?php echo $emoji; ?></div>
                            <span class="card-badge"><?php echo $cat; ?></span>
                        </div>
                        <div class="card-body">
                            <div class="card-meta">
                                <span>⏱ <?php echo $prep; ?></span>
                                <span>🔥 <?php echo $cook; ?></span>
                                <span>🍽 <?php echo $servings; ?></span>
                            </div>
                            <h3><?php echo $title; ?></h3>
                            <p><?php echo $desc; ?></p>
                        </div>
                        <div class="card-footer">
                            <span style="font-size:13px;color:#6c757d;">Difficulty: <strong><?php echo $diff; ?></strong></span>
                            <a href="<?php echo esc_url(home_url('/recipes')); ?>">View Recipe →</a>
                        </div>
                    </article>
                <?php endforeach;
            endif; ?>
        </div>

        <div style="text-align:center;margin-top:48px;">
            <a href="<?php echo esc_url(home_url('/recipes')); ?>" class="btn btn-primary">View All Recipes</a>
        </div>
    </div>
</section>

<!-- TESTIMONIALS SECTION -->
<section class="testimonials-section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Community Love</span>
            <h2>What Families Are Saying</h2>
            <p>Real stories from real families who have transformed their health through holistic nutrition.</p>
        </div>
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="quote-icon">"</div>
                <div class="stars">★★★★★</div>
                <p>Doctor Mommies has completely transformed how my family eats. The recipes are not only delicious but incredibly nourishing. My kids actually ask for the veggie bowls now!</p>
                <div class="reviewer">
                    <div class="reviewer-avatar">E</div>
                    <div class="reviewer-info">
                        <div class="name">Emily R.</div>
                        <div class="role">Mom of 3, Chicago</div>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="quote-icon">"</div>
                <div class="stars">★★★★★</div>
                <p>As a pediatrician myself, I'm selective about nutrition advice. Doctor Mommies hits the perfect balance of evidence-based guidance and practical, family-friendly recipes.</p>
                <div class="reviewer">
                    <div class="reviewer-avatar">S</div>
                    <div class="reviewer-info">
                        <div class="name">Dr. Sarah M.</div>
                        <div class="role">Pediatrician & Mom, NYC</div>
                    </div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="quote-icon">"</div>
                <div class="stars">★★★★★</div>
                <p>I was overwhelmed trying to feed my toddler healthy foods. The meal planning tips here made it so manageable. Within a month, mealtimes became something we all look forward to!</p>
                <div class="reviewer">
                    <div class="reviewer-avatar">A</div>
                    <div class="reviewer-info">
                        <div class="name">Amanda K.</div>
                        <div class="role">First-time Mom, Austin</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- NEWSLETTER SECTION -->
<section class="newsletter-section">
    <div class="container">
        <div class="newsletter-content">
            <span class="section-label">Stay Connected</span>
            <h2>Get Weekly Recipes & Nutrition Tips</h2>
            <p>Join thousands of families receiving our free weekly newsletter with seasonal recipes, wellness tips, and holistic nutrition guidance.</p>
            <form class="newsletter-form" id="newsletter-form">
                <input type="email" id="newsletter-email" placeholder="Enter your email address" required>
                <button type="submit">Subscribe</button>
            </form>
            <p style="margin-top:16px;font-size:13px;opacity:0.6;">No spam ever. Unsubscribe anytime.</p>
        </div>
    </div>
</section>

<!-- BLOG PREVIEW SECTION -->
<section class="blog-section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">From The Blog</span>
            <h2>Nourishment &amp; Wellness Insights</h2>
            <p>Practical wisdom on holistic nutrition, family wellness, and mindful eating.</p>
        </div>
        <div class="blog-grid">
            <?php
            $posts = new WP_Query([
                'post_type'      => 'post',
                'posts_per_page' => 3,
                'post_status'    => 'publish',
            ]);

            if ($posts->have_posts()) :
                while ($posts->have_posts()) : $posts->the_post();
                    $cats = get_the_category();
                    $cat_name = $cats ? $cats[0]->name : 'Wellness';
                    ?>
                    <article class="blog-card">
                        <div class="card-image">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('blog-card', ['alt' => get_the_title()]); ?>
                            <?php else : ?>
                                <div style="height:100%;display:flex;align-items:center;justify-content:center;font-size:60px;background:linear-gradient(135deg,#d5dfff,#ebe4ff);">📝</div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <span class="tag"><?php echo esc_html($cat_name); ?></span>
                            <h3><?php the_title(); ?></h3>
                            <p><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                            <div class="card-footer">
                                <span><?php echo get_the_date('M j, Y'); ?></span>
                                <a href="<?php the_permalink(); ?>" class="read-more">Read More →</a>
                            </div>
                        </div>
                    </article>
                    <?php
                endwhile;
                wp_reset_postdata();
            else :
                $sample_posts = [
                    ['🌿', 'Holistic Nutrition', '5 Superfoods Every Toddler Needs', 'Discover the powerhouse foods that support brain development, immunity, and healthy growth in young children.'],
                    ['🥦', 'Meal Planning', 'How to Meal Prep for a Family of 5 in 2 Hours', 'Our doctor-mommies share their time-saving strategies for preparing a week of wholesome family meals.'],
                    ['💚', 'Wellness', 'Understanding Organic Labels: What Really Matters', 'Navigate the grocery store confidently with our guide to organic certifications and what they mean for your family.'],
                ];
                foreach ($sample_posts as $idx => [$emoji, $cat, $title, $excerpt]) :
                    ?>
                    <article class="blog-card">
                        <div class="card-image">
                            <div style="height:100%;display:flex;align-items:center;justify-content:center;font-size:60px;background:linear-gradient(135deg,#d5dfff,#ebe4ff);"><?php echo $emoji; ?></div>
                        </div>
                        <div class="card-body">
                            <span class="tag"><?php echo $cat; ?></span>
                            <h3><?php echo $title; ?></h3>
                            <p><?php echo $excerpt; ?></p>
                            <div class="card-footer">
                                <span>Mar <?php echo 15 + $idx * 3; ?>, 2024</span>
                                <a href="<?php echo esc_url(home_url('/blog')); ?>" class="read-more">Read More →</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach;
            endif; ?>
        </div>
        <div style="text-align:center;margin-top:48px;">
            <a href="<?php echo esc_url(home_url('/blog')); ?>" class="btn btn-primary">View All Posts</a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
