<?php
/**
 * Template Name: Blog Page
 */
get_header(); ?>

<section class="page-hero">
    <div class="container">
        <span style="display:inline-block;background:rgba(103,61,230,0.3);color:#ebe4ff;padding:6px 18px;border-radius:25px;font-size:13px;font-weight:600;letter-spacing:1px;text-transform:uppercase;margin-bottom:16px;border:1px solid rgba(103,61,230,0.5);">📝 Knowledge &amp; Wellness</span>
        <h1>Nourishment Blog</h1>
        <p>Holistic nutrition insights, family wellness tips, and evidence-based guidance from physician-mommies.</p>
    </div>
</section>

<!-- Blog Posts -->
<section class="blog-section" style="background:white;">
    <div class="container">
        <div class="blog-grid">
            <?php
            $paged = get_query_var('paged') ? get_query_var('paged') : 1;
            $posts = new WP_Query([
                'post_type'      => 'post',
                'posts_per_page' => 9,
                'post_status'    => 'publish',
                'paged'          => $paged,
            ]);

            if ($posts->have_posts()) :
                while ($posts->have_posts()) : $posts->the_post();
                    $cats = get_the_category();
                    $cat_name = $cats ? $cats[0]->name : 'Wellness'; ?>
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
                            <p><?php echo wp_trim_words(get_the_excerpt(), 22); ?></p>
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
                    ['🌿', 'Holistic Nutrition', '5 Superfoods Every Toddler Needs for Brain Development', 'Discover the powerhouse foods that support brain development, immunity, and healthy growth in young children.', 'Mar 15, 2024'],
                    ['🥦', 'Meal Planning', 'How to Meal Prep for a Family of 5 in Just 2 Hours', 'Our doctor-mommies share their time-saving strategies for preparing a week of wholesome family meals.', 'Mar 18, 2024'],
                    ['💚', 'Organic Living', 'Understanding Organic Labels: What Really Matters', 'Navigate the grocery store confidently with our guide to organic certifications.', 'Mar 21, 2024'],
                    ['🧘', 'Family Wellness', 'Creating Mindful Mealtimes: Tips for the Whole Family', 'Transform chaotic dinner tables into peaceful, connected family experiences through mindful eating practices.', 'Mar 25, 2024'],
                    ['🌱', 'Sustainability', 'Growing Your Own Herbs: A Beginner\'s Guide for Busy Moms', 'Even with the busiest schedule, you can grow fresh herbs that elevate every home-cooked meal.', 'Mar 28, 2024'],
                    ['🍼', 'Baby Nutrition', 'Introducing Solids: A Holistic Approach for Babies 6-12 Months', 'Evidence-based guidance on starting solids with organic, allergen-friendly first foods.', 'Apr 1, 2024'],
                    ['🫐', 'Antioxidants', 'The Anti-Inflammatory Kitchen: Foods That Heal', 'Learn which everyday foods have powerful anti-inflammatory properties and how to incorporate them daily.', 'Apr 5, 2024'],
                    ['🏃', 'Active Families', 'Fueling Active Kids: Pre and Post-Workout Nutrition', 'What your little athletes need to perform their best and recover quickly.', 'Apr 8, 2024'],
                    ['🫀', 'Heart Health', 'Heart-Healthy Habits to Start With Your Kids Today', 'Simple daily practices that set your children up for a lifetime of cardiovascular health.', 'Apr 12, 2024'],
                ];
                foreach ($sample_posts as [$emoji, $cat, $title, $excerpt, $date]) : ?>
                    <article class="blog-card">
                        <div class="card-image">
                            <div style="height:100%;display:flex;align-items:center;justify-content:center;font-size:60px;background:linear-gradient(135deg,#d5dfff,#ebe4ff);"><?php echo $emoji; ?></div>
                        </div>
                        <div class="card-body">
                            <span class="tag"><?php echo $cat; ?></span>
                            <h3><?php echo $title; ?></h3>
                            <p><?php echo $excerpt; ?></p>
                            <div class="card-footer">
                                <span><?php echo $date; ?></span>
                                <a href="#" class="read-more">Read More →</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach;
            endif; ?>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="faq-section" style="background:#f8f9fa;">
    <div class="container">
        <div class="section-header">
            <span class="section-label">FAQ</span>
            <h2>Frequently Asked Questions</h2>
            <p>Answers to the most common questions about holistic family nutrition.</p>
        </div>
        <div class="faq-grid">
            <?php
            $faqs = [
                ['What is holistic nutrition?', 'Holistic nutrition considers the whole person — body, mind, and spirit — when making food choices. It focuses on whole, unprocessed foods that nourish all systems of the body, while also considering lifestyle factors like stress, sleep, and emotional well-being.'],
                ['How do I get my kids to eat more vegetables?', 'Start by involving children in meal preparation — kids are more likely to eat what they help make. Introduce vegetables gradually, pair them with familiar foods they enjoy, and try different preparation methods. Hidden veggie recipes are also a great starting point!'],
                ['Are all of your recipes gluten-free?', 'Not all recipes are gluten-free, but we clearly label those that are. We also provide gluten-free variations for many of our most popular recipes. Look for the GF tag in our recipe filters.'],
                ['Do you offer meal planning services?', 'Yes! We offer personalized meal planning consultations via our newsletter subscribers. Subscribers get exclusive access to weekly meal plans, shopping lists, and one-on-one nutrition Q&A sessions.'],
                ['How do I know which organic products to buy?', 'Focus on the "Dirty Dozen" — the 12 fruits and vegetables highest in pesticide residue — for organic purchases. For everything else, conventional is generally fine. We publish annual updates on our blog.'],
                ['Can your recipes help with food allergies?', 'Many of our recipes are designed to be allergen-friendly or include easy swaps. Each recipe notes the top 8 allergens and suggests alternatives. Always consult your allergist for personalized guidance.'],
            ];
            foreach ($faqs as $i => [$q, $a]) : ?>
                <div class="faq-item">
                    <div class="faq-question" onclick="toggleFaq(this)">
                        <span><?php echo $q; ?></span>
                        <span class="toggle" id="toggle-<?php echo $i; ?>">+</span>
                    </div>
                    <div class="faq-answer" id="faq-<?php echo $i; ?>">
                        <p><?php echo $a; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script>
function toggleFaq(el) {
    const answer = el.nextElementSibling;
    const toggle = el.querySelector('.toggle');
    const isOpen = answer.classList.contains('open');
    document.querySelectorAll('.faq-answer.open').forEach(a => {
        a.classList.remove('open');
        a.previousElementSibling.querySelector('.toggle').textContent = '+';
    });
    if (!isOpen) {
        answer.classList.add('open');
        toggle.textContent = '−';
    }
}
</script>

<?php get_footer(); ?>
