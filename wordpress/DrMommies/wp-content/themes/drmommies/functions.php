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
    $table = $wpdb->prefix . 'newsletter_subscribers';
    $charset = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        email VARCHAR(255) NOT NULL UNIQUE,
        created_at DATETIME NOT NULL,
        PRIMARY KEY (id)
    ) $charset;";
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
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
