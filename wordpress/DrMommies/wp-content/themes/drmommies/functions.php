<?php
/**
 * Doctor Mommies Theme Functions
 */

if (!defined('ABSPATH')) exit;

// Theme setup
function drmommies_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
    add_theme_support('custom-logo');
    add_theme_support('automatic-feed-links');
    add_theme_support('woocommerce');

    register_nav_menus([
        'primary' => __('Primary Menu', 'drmommies'),
        'footer'  => __('Footer Menu', 'drmommies'),
    ]);

    add_image_size('recipe-card', 600, 440, true);
    add_image_size('blog-card', 600, 400, true);
    add_image_size('hero-thumb', 400, 400, true);
}
add_action('after_setup_theme', 'drmommies_setup');

// Enqueue styles and scripts
function drmommies_scripts() {
    wp_enqueue_style(
        'google-fonts',
        'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;800&family=Inter:wght@400;500;600;700&display=swap',
        [],
        null
    );
    wp_enqueue_style('drmommies-style', get_stylesheet_uri(), [], '1.0.0');
    wp_enqueue_script('drmommies-main', get_template_directory_uri() . '/js/main.js', [], '1.0.0', true);

    wp_localize_script('drmommies-main', 'drMommiesData', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('drmommies_nonce'),
        'siteUrl' => get_site_url(),
    ]);
}
add_action('wp_enqueue_scripts', 'drmommies_scripts');

// Register custom post types
function drmommies_register_post_types() {
    // Recipes CPT
    register_post_type('recipe', [
        'labels' => [
            'name'               => 'Recipes',
            'singular_name'      => 'Recipe',
            'add_new'            => 'Add New Recipe',
            'add_new_item'       => 'Add New Recipe',
            'edit_item'          => 'Edit Recipe',
            'view_item'          => 'View Recipe',
            'search_items'       => 'Search Recipes',
            'not_found'          => 'No recipes found',
            'menu_name'          => 'Recipes',
        ],
        'public'      => true,
        'has_archive' => true,
        'rewrite'     => ['slug' => 'recipes'],
        'supports'    => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'menu_icon'   => 'dashicons-food',
        'show_in_rest' => true,
    ]);
}
add_action('init', 'drmommies_register_post_types');

// Register custom taxonomies
function drmommies_register_taxonomies() {
    register_taxonomy('recipe_category', 'recipe', [
        'labels' => [
            'name'          => 'Recipe Categories',
            'singular_name' => 'Recipe Category',
            'menu_name'     => 'Categories',
        ],
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'recipe-category'],
        'show_in_rest' => true,
    ]);

    register_taxonomy('meal_type', 'recipe', [
        'labels' => [
            'name'          => 'Meal Types',
            'singular_name' => 'Meal Type',
            'menu_name'     => 'Meal Types',
        ],
        'hierarchical' => false,
        'rewrite'      => ['slug' => 'meal-type'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'drmommies_register_taxonomies');

// Add custom meta boxes for recipes
function drmommies_recipe_meta_boxes() {
    add_meta_box(
        'recipe_details',
        'Recipe Details',
        'drmommies_recipe_details_callback',
        'recipe',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'drmommies_recipe_meta_boxes');

function drmommies_recipe_details_callback($post) {
    wp_nonce_field('drmommies_recipe_nonce', 'recipe_nonce');
    $prep_time   = get_post_meta($post->ID, '_prep_time', true);
    $cook_time   = get_post_meta($post->ID, '_cook_time', true);
    $servings    = get_post_meta($post->ID, '_servings', true);
    $difficulty  = get_post_meta($post->ID, '_difficulty', true);
    $ingredients = get_post_meta($post->ID, '_ingredients', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label>Prep Time</label></th>
            <td><input type="text" name="prep_time" value="<?php echo esc_attr($prep_time); ?>" placeholder="e.g. 15 mins" class="regular-text"></td>
        </tr>
        <tr>
            <th><label>Cook Time</label></th>
            <td><input type="text" name="cook_time" value="<?php echo esc_attr($cook_time); ?>" placeholder="e.g. 30 mins" class="regular-text"></td>
        </tr>
        <tr>
            <th><label>Servings</label></th>
            <td><input type="text" name="servings" value="<?php echo esc_attr($servings); ?>" placeholder="e.g. 4 servings" class="regular-text"></td>
        </tr>
        <tr>
            <th><label>Difficulty</label></th>
            <td>
                <select name="difficulty">
                    <option value="easy" <?php selected($difficulty, 'easy'); ?>>Easy</option>
                    <option value="medium" <?php selected($difficulty, 'medium'); ?>>Medium</option>
                    <option value="hard" <?php selected($difficulty, 'hard'); ?>>Hard</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label>Ingredients</label></th>
            <td><textarea name="ingredients" rows="8" class="large-text" placeholder="List ingredients, one per line"><?php echo esc_textarea($ingredients); ?></textarea></td>
        </tr>
    </table>
    <h4 style="margin:20px 0 10px;padding-top:14px;border-top:1px solid #eee;">Nutrition Per Serving (optional)</h4>
    <table class="form-table">
        <?php
        $nut_fields = ['calories'=>'Calories','protein'=>'Protein (g)','carbs'=>'Carbs (g)','fat'=>'Fat (g)','fiber'=>'Fiber (g)'];
        foreach ($nut_fields as $key => $label) :
            $val = get_post_meta($post->ID, '_'.$key, true); ?>
        <tr>
            <th><label><?php echo $label; ?></label></th>
            <td><input type="text" name="<?php echo $key; ?>" value="<?php echo esc_attr($val); ?>" placeholder="e.g. 320" class="small-text"></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php
}

function drmommies_save_recipe_meta($post_id) {
    if (!isset($_POST['recipe_nonce']) || !wp_verify_nonce($_POST['recipe_nonce'], 'drmommies_recipe_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = ['prep_time', 'cook_time', 'servings', 'difficulty', 'ingredients', 'calories', 'protein', 'carbs', 'fat', 'fiber'];
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_textarea_field($_POST[$field]));
        }
    }
}
add_action('save_post_recipe', 'drmommies_save_recipe_meta');

// Newsletter signup AJAX handler
function drmommies_newsletter_signup() {
    check_ajax_referer('drmommies_nonce', 'nonce');
    $email = sanitize_email($_POST['email'] ?? '');
    if (!is_email($email)) {
        wp_send_json_error(['message' => 'Please enter a valid email address.']);
    }

    // Store in custom table
    global $wpdb;
    $table = $wpdb->prefix . 'newsletter_subscribers';
    $exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE email = %s", $email));
    if ($exists) {
        wp_send_json_success(['message' => 'You are already subscribed!']);
    }
    $wpdb->insert($table, [
        'email'      => $email,
        'created_at' => current_time('mysql'),
    ]);
    wp_send_json_success(['message' => 'Thank you for subscribing!']);
}
add_action('wp_ajax_newsletter_signup', 'drmommies_newsletter_signup');
add_action('wp_ajax_nopriv_newsletter_signup', 'drmommies_newsletter_signup');

// Create newsletter subscribers table on theme activation
function drmommies_create_tables() {
    global $wpdb;
    $charset = $wpdb->get_charset_collate();
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    // Newsletter subscribers table
    $table1 = $wpdb->prefix . 'newsletter_subscribers';
    dbDelta("CREATE TABLE IF NOT EXISTS $table1 (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        email VARCHAR(255) NOT NULL UNIQUE,
        created_at DATETIME NOT NULL,
        PRIMARY KEY (id)
    ) $charset;");

    // Recipe ratings table
    $table2 = $wpdb->prefix . 'recipe_ratings';
    dbDelta("CREATE TABLE IF NOT EXISTS $table2 (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        recipe_id BIGINT UNSIGNED NOT NULL,
        user_id BIGINT UNSIGNED NOT NULL,
        rating TINYINT UNSIGNED NOT NULL,
        review_text TEXT NULL,
        approved TINYINT UNSIGNED NOT NULL DEFAULT 1,
        created_at DATETIME NOT NULL,
        PRIMARY KEY (id),
        UNIQUE KEY recipe_user (recipe_id, user_id),
        KEY recipe_id (recipe_id),
        KEY approved (approved)
    ) $charset;");

    // Recipe FAQs table
    $table3 = $wpdb->prefix . 'recipe_faqs';
    dbDelta("CREATE TABLE IF NOT EXISTS $table3 (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        recipe_id BIGINT UNSIGNED NOT NULL,
        user_id BIGINT UNSIGNED NOT NULL,
        question TEXT NOT NULL,
        answer TEXT NULL,
        approved TINYINT UNSIGNED NOT NULL DEFAULT 0,
        created_at DATETIME NOT NULL,
        PRIMARY KEY (id),
        KEY recipe_id (recipe_id),
        KEY approved (approved)
    ) $charset;");
}
add_action('after_switch_theme', 'drmommies_create_tables');

// Widget areas
function drmommies_widgets_init() {
    register_sidebar([
        'name'          => 'Blog Sidebar',
        'id'            => 'blog-sidebar',
        'before_widget' => '<div class="widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ]);
}
add_action('widgets_init', 'drmommies_widgets_init');

// Flush rewrite rules on activation
function drmommies_flush_rewrite_rules() {
    drmommies_register_post_types();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'drmommies_flush_rewrite_rules');

// Custom excerpt length
function drmommies_excerpt_length($length) { return 20; }
add_filter('excerpt_length', 'drmommies_excerpt_length');

// Helper: get recipe meta
function drmommies_get_recipe_meta($post_id) {
    return [
        'prep_time'   => get_post_meta($post_id, '_prep_time', true) ?: '15 mins',
        'cook_time'   => get_post_meta($post_id, '_cook_time', true) ?: '30 mins',
        'servings'    => get_post_meta($post_id, '_servings', true) ?: '4 servings',
        'difficulty'  => get_post_meta($post_id, '_difficulty', true) ?: 'easy',
        'ingredients' => get_post_meta($post_id, '_ingredients', true) ?: '',
    ];
}

// Helper: get cached recipe rating data
function drmommies_get_recipe_rating($post_id) {
    return [
        'average' => (float) get_post_meta($post_id, '_rating_average', true) ?: 0,
        'count'   => (int) get_post_meta($post_id, '_rating_count', true) ?: 0,
        'review_count' => (int) get_post_meta($post_id, '_review_count', true) ?: 0,
    ];
}

// Helper: recalculate and cache rating meta from the ratings table
function drmommies_update_rating_cache($recipe_id) {
    global $wpdb;
    $table = $wpdb->prefix . 'recipe_ratings';

    $stats = $wpdb->get_row($wpdb->prepare(
        "SELECT AVG(rating) as avg_rating, COUNT(*) as total_count FROM $table WHERE recipe_id = %d",
        $recipe_id
    ));
    $review_count = (int) $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table WHERE recipe_id = %d AND review_text IS NOT NULL AND review_text != '' AND approved = 1",
        $recipe_id
    ));

    $average = $stats->avg_rating ? round((float) $stats->avg_rating, 1) : 0;
    $count = (int) $stats->total_count;

    update_post_meta($recipe_id, '_rating_average', $average);
    update_post_meta($recipe_id, '_rating_count', $count);
    update_post_meta($recipe_id, '_review_count', $review_count);

    return ['average' => $average, 'count' => $count, 'review_count' => $review_count];
}

// Helper: get approved reviews for a recipe
function drmommies_get_approved_reviews($post_id) {
    global $wpdb;
    $table = $wpdb->prefix . 'recipe_ratings';

    return $wpdb->get_results($wpdb->prepare(
        "SELECT r.rating, r.review_text, r.created_at, u.display_name
         FROM $table r
         JOIN {$wpdb->users} u ON r.user_id = u.ID
         WHERE r.recipe_id = %d AND r.review_text IS NOT NULL AND r.review_text != '' AND r.approved = 1
         ORDER BY r.created_at DESC",
        $post_id
    ));
}

// Helper: get approved FAQs for a recipe
function drmommies_get_approved_faqs($post_id) {
    global $wpdb;
    $table = $wpdb->prefix . 'recipe_faqs';

    return $wpdb->get_results($wpdb->prepare(
        "SELECT f.question, f.answer, f.created_at, u.display_name
         FROM $table f
         JOIN {$wpdb->users} u ON f.user_id = u.ID
         WHERE f.recipe_id = %d AND f.approved = 1
         ORDER BY f.created_at DESC",
        $post_id
    ));
}

// Helper: render read-only stars HTML
function drmommies_render_stars_html($average, $count) {
    $html = '<span class="recipe-stars-display">';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= round($average)) {
            $html .= '<span class="star filled">&#9733;</span>';
        } else {
            $html .= '<span class="star empty">&#9733;</span>';
        }
    }
    $html .= ' <span class="rating-count">(' . intval($count) . ')</span></span>';
    return $html;
}

// AJAX: Rate a recipe (logged-in users only)
function drmommies_rate_recipe() {
    check_ajax_referer('drmommies_nonce', 'nonce');

    $recipe_id = absint($_POST['recipe_id'] ?? 0);
    $rating = intval($_POST['rating'] ?? 0);
    $review_text = sanitize_textarea_field($_POST['review_text'] ?? '');

    if (!$recipe_id || get_post_type($recipe_id) !== 'recipe' || get_post_status($recipe_id) !== 'publish') {
        wp_send_json_error(['message' => 'Invalid recipe.']);
    }
    if ($rating < 1 || $rating > 5) {
        wp_send_json_error(['message' => 'Rating must be between 1 and 5.']);
    }

    global $wpdb;
    $table = $wpdb->prefix . 'recipe_ratings';
    $user_id = get_current_user_id();

    $exists = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table WHERE recipe_id = %d AND user_id = %d",
        $recipe_id, $user_id
    ));
    if ($exists) {
        wp_send_json_error(['message' => 'You have already rated this recipe.']);
    }

    $has_review = !empty($review_text);
    $approved = $has_review ? 0 : 1;

    $wpdb->insert($table, [
        'recipe_id'   => $recipe_id,
        'user_id'     => $user_id,
        'rating'      => $rating,
        'review_text' => $has_review ? $review_text : null,
        'approved'    => $approved,
        'created_at'  => current_time('mysql'),
    ]);

    $stats = drmommies_update_rating_cache($recipe_id);

    wp_send_json_success([
        'average'       => $stats['average'],
        'count'         => $stats['count'],
        'review_count'  => $stats['review_count'],
        'needsApproval' => $has_review,
    ]);
}
add_action('wp_ajax_rate_recipe', 'drmommies_rate_recipe');

// AJAX: Submit a FAQ question (logged-in users only)
function drmommies_submit_faq() {
    check_ajax_referer('drmommies_nonce', 'nonce');

    $recipe_id = absint($_POST['recipe_id'] ?? 0);
    $question = sanitize_textarea_field($_POST['question'] ?? '');

    if (!$recipe_id || get_post_type($recipe_id) !== 'recipe' || get_post_status($recipe_id) !== 'publish') {
        wp_send_json_error(['message' => 'Invalid recipe.']);
    }
    if (empty($question)) {
        wp_send_json_error(['message' => 'Please enter a question.']);
    }

    global $wpdb;
    $table = $wpdb->prefix . 'recipe_faqs';

    $wpdb->insert($table, [
        'recipe_id'  => $recipe_id,
        'user_id'    => get_current_user_id(),
        'question'   => $question,
        'approved'   => 0,
        'created_at' => current_time('mysql'),
    ]);

    wp_send_json_success(['message' => 'Your question has been submitted and is pending approval.']);
}
add_action('wp_ajax_submit_faq', 'drmommies_submit_faq');

// Admin: Recipe moderation page
function drmommies_admin_moderation_menu() {
    add_submenu_page(
        'edit.php?post_type=recipe',
        'Reviews & FAQ Moderation',
        'Moderation',
        'manage_options',
        'recipe-moderation',
        'drmommies_moderation_page_callback'
    );
}
add_action('admin_menu', 'drmommies_admin_moderation_menu');

function drmommies_moderation_page_callback() {
    global $wpdb;
    $ratings_table = $wpdb->prefix . 'recipe_ratings';
    $faqs_table = $wpdb->prefix . 'recipe_faqs';

    // Handle actions
    if (isset($_POST['moderation_action']) && check_admin_referer('drmommies_moderation')) {
        $action = sanitize_text_field($_POST['moderation_action']);
        $item_id = absint($_POST['item_id'] ?? 0);
        $item_type = sanitize_text_field($_POST['item_type'] ?? '');

        if ($item_type === 'review' && $item_id) {
            if ($action === 'approve') {
                $wpdb->update($ratings_table, ['approved' => 1], ['id' => $item_id]);
                $recipe_id = $wpdb->get_var($wpdb->prepare("SELECT recipe_id FROM $ratings_table WHERE id = %d", $item_id));
                if ($recipe_id) drmommies_update_rating_cache($recipe_id);
            } elseif ($action === 'delete') {
                $recipe_id = $wpdb->get_var($wpdb->prepare("SELECT recipe_id FROM $ratings_table WHERE id = %d", $item_id));
                $wpdb->delete($ratings_table, ['id' => $item_id]);
                if ($recipe_id) drmommies_update_rating_cache($recipe_id);
            }
        } elseif ($item_type === 'faq' && $item_id) {
            if ($action === 'approve') {
                $answer = sanitize_textarea_field($_POST['faq_answer'] ?? '');
                $wpdb->update($faqs_table, ['approved' => 1, 'answer' => $answer ?: null], ['id' => $item_id]);
            } elseif ($action === 'delete') {
                $wpdb->delete($faqs_table, ['id' => $item_id]);
            }
        }

        echo '<div class="notice notice-success"><p>Action completed.</p></div>';
    }

    // Fetch pending items
    $pending_reviews = $wpdb->get_results(
        "SELECT r.*, u.display_name, p.post_title
         FROM $ratings_table r
         JOIN {$wpdb->users} u ON r.user_id = u.ID
         JOIN {$wpdb->posts} p ON r.recipe_id = p.ID
         WHERE r.review_text IS NOT NULL AND r.review_text != '' AND r.approved = 0
         ORDER BY r.created_at DESC"
    );

    $pending_faqs = $wpdb->get_results(
        "SELECT f.*, u.display_name, p.post_title
         FROM $faqs_table f
         JOIN {$wpdb->users} u ON f.user_id = u.ID
         JOIN {$wpdb->posts} p ON f.recipe_id = p.ID
         WHERE f.approved = 0
         ORDER BY f.created_at DESC"
    );

    $approved_faqs = $wpdb->get_results(
        "SELECT f.*, u.display_name, p.post_title
         FROM $faqs_table f
         JOIN {$wpdb->users} u ON f.user_id = u.ID
         JOIN {$wpdb->posts} p ON f.recipe_id = p.ID
         WHERE f.approved = 1
         ORDER BY f.created_at DESC"
    );

    ?>
    <div class="wrap">
        <h1>Reviews &amp; FAQ Moderation</h1>

        <h2>Pending Reviews (<?php echo count($pending_reviews); ?>)</h2>
        <?php if (empty($pending_reviews)) : ?>
            <p>No pending reviews.</p>
        <?php else : ?>
            <table class="widefat striped">
                <thead><tr><th>Recipe</th><th>User</th><th>Rating</th><th>Review</th><th>Date</th><th>Actions</th></tr></thead>
                <tbody>
                <?php foreach ($pending_reviews as $review) : ?>
                    <tr>
                        <td><a href="<?php echo get_edit_post_link($review->recipe_id); ?>"><?php echo esc_html($review->post_title); ?></a></td>
                        <td><?php echo esc_html($review->display_name); ?></td>
                        <td><?php echo str_repeat('&#9733;', $review->rating) . str_repeat('&#9734;', 5 - $review->rating); ?></td>
                        <td><?php echo esc_html($review->review_text); ?></td>
                        <td><?php echo esc_html($review->created_at); ?></td>
                        <td>
                            <form method="post" style="display:inline;">
                                <?php wp_nonce_field('drmommies_moderation'); ?>
                                <input type="hidden" name="item_id" value="<?php echo esc_attr($review->id); ?>">
                                <input type="hidden" name="item_type" value="review">
                                <button type="submit" name="moderation_action" value="approve" class="button button-primary button-small">Approve</button>
                                <button type="submit" name="moderation_action" value="delete" class="button button-small" onclick="return confirm('Delete this review?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <hr>
        <h2>Pending Questions (<?php echo count($pending_faqs); ?>)</h2>
        <?php if (empty($pending_faqs)) : ?>
            <p>No pending questions.</p>
        <?php else : ?>
            <table class="widefat striped">
                <thead><tr><th>Recipe</th><th>User</th><th>Question</th><th>Date</th><th>Answer &amp; Actions</th></tr></thead>
                <tbody>
                <?php foreach ($pending_faqs as $faq) : ?>
                    <tr>
                        <td><a href="<?php echo get_edit_post_link($faq->recipe_id); ?>"><?php echo esc_html($faq->post_title); ?></a></td>
                        <td><?php echo esc_html($faq->display_name); ?></td>
                        <td><?php echo esc_html($faq->question); ?></td>
                        <td><?php echo esc_html($faq->created_at); ?></td>
                        <td>
                            <form method="post">
                                <?php wp_nonce_field('drmommies_moderation'); ?>
                                <input type="hidden" name="item_id" value="<?php echo esc_attr($faq->id); ?>">
                                <input type="hidden" name="item_type" value="faq">
                                <textarea name="faq_answer" rows="2" style="width:100%;margin-bottom:6px;" placeholder="Write an answer (optional)..."></textarea>
                                <button type="submit" name="moderation_action" value="approve" class="button button-primary button-small">Approve</button>
                                <button type="submit" name="moderation_action" value="delete" class="button button-small" onclick="return confirm('Delete this question?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <hr>
        <h2>Approved FAQs (<?php echo count($approved_faqs); ?>)</h2>
        <?php if (empty($approved_faqs)) : ?>
            <p>No approved FAQs yet.</p>
        <?php else : ?>
            <table class="widefat striped">
                <thead><tr><th>Recipe</th><th>User</th><th>Question</th><th>Answer</th><th>Actions</th></tr></thead>
                <tbody>
                <?php foreach ($approved_faqs as $faq) : ?>
                    <tr>
                        <td><a href="<?php echo get_edit_post_link($faq->recipe_id); ?>"><?php echo esc_html($faq->post_title); ?></a></td>
                        <td><?php echo esc_html($faq->display_name); ?></td>
                        <td><?php echo esc_html($faq->question); ?></td>
                        <td>
                            <form method="post">
                                <?php wp_nonce_field('drmommies_moderation'); ?>
                                <input type="hidden" name="item_id" value="<?php echo esc_attr($faq->id); ?>">
                                <input type="hidden" name="item_type" value="faq">
                                <textarea name="faq_answer" rows="2" style="width:100%;"><?php echo esc_textarea($faq->answer); ?></textarea>
                                <button type="submit" name="moderation_action" value="approve" class="button button-primary button-small" style="margin-top:4px;">Update Answer</button>
                                <button type="submit" name="moderation_action" value="delete" class="button button-small" style="margin-top:4px;" onclick="return confirm('Delete this FAQ?');">Delete</button>
                            </form>
                        </td>
                        <td></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <?php
}
