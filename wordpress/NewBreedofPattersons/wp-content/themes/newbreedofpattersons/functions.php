<?php
/**
 * New Breed of Pattersons — Theme Functions
 *
 * @package NewBreedOfPattersons
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'NBOP_VERSION', '1.0.0' );
define( 'NBOP_DIR', get_template_directory() );
define( 'NBOP_URI', get_template_directory_uri() );

/* ==========================================================================
   Theme Setup
   ========================================================================== */

function nbop_setup() {
    // Let WordPress manage the document title.
    add_theme_support( 'title-tag' );

    // Enable featured images.
    add_theme_support( 'post-thumbnails' );

    // Custom logo support.
    add_theme_support( 'custom-logo', array(
        'width'       => 250,
        'height'      => 80,
        'flex-width'  => true,
        'flex-height' => true,
    ) );

    // HTML5 markup support.
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );

    // Automatic feed links.
    add_theme_support( 'automatic-feed-links' );

    // Register navigation menus.
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'newbreedofpattersons' ),
        'footer'  => __( 'Footer Menu', 'newbreedofpattersons' ),
    ) );
}
add_action( 'after_setup_theme', 'nbop_setup' );

/* ==========================================================================
   Custom Image Sizes
   ========================================================================== */

add_image_size( 'nbop-card', 600, 400, true );
add_image_size( 'nbop-hero', 1600, 600, true );

/* ==========================================================================
   Enqueue Assets
   ========================================================================== */

function nbop_enqueue_assets() {
    // Google Fonts: Lora + Nunito.
    wp_enqueue_style(
        'nbop-google-fonts',
        'https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,700;1,400;1,700&family=Nunito:wght@400;600;700;800&display=swap',
        array(),
        null
    );

    // Main stylesheet.
    wp_enqueue_style(
        'nbop-style',
        get_stylesheet_uri(),
        array( 'nbop-google-fonts' ),
        NBOP_VERSION
    );

    // Main JavaScript.
    wp_enqueue_script(
        'nbop-main',
        NBOP_URI . '/assets/js/main.js',
        array(),
        NBOP_VERSION,
        true
    );

    // Lightbox — only on gallery template.
    if ( is_page_template( 'templates/gallery.php' ) ) {
        wp_enqueue_style(
            'nbop-lightbox',
            NBOP_URI . '/assets/css/lightbox.css',
            array(),
            NBOP_VERSION
        );

        wp_enqueue_script(
            'nbop-lightbox',
            NBOP_URI . '/assets/js/lightbox.js',
            array(),
            NBOP_VERSION,
            true
        );
    }
}
add_action( 'wp_enqueue_scripts', 'nbop_enqueue_assets' );

/* ==========================================================================
   Custom Post Type: Movie Pick
   ========================================================================== */

function nbop_register_movie_pick_cpt() {
    $labels = array(
        'name'               => __( 'Movie Picks', 'newbreedofpattersons' ),
        'singular_name'      => __( 'Movie Pick', 'newbreedofpattersons' ),
        'add_new'            => __( 'Add New', 'newbreedofpattersons' ),
        'add_new_item'       => __( 'Add New Movie Pick', 'newbreedofpattersons' ),
        'edit_item'          => __( 'Edit Movie Pick', 'newbreedofpattersons' ),
        'new_item'           => __( 'New Movie Pick', 'newbreedofpattersons' ),
        'view_item'          => __( 'View Movie Pick', 'newbreedofpattersons' ),
        'search_items'       => __( 'Search Movie Picks', 'newbreedofpattersons' ),
        'not_found'          => __( 'No movie picks found.', 'newbreedofpattersons' ),
        'not_found_in_trash' => __( 'No movie picks found in Trash.', 'newbreedofpattersons' ),
        'all_items'          => __( 'All Movie Picks', 'newbreedofpattersons' ),
    );

    $args = array(
        'labels'       => $labels,
        'public'       => false,
        'show_ui'      => true,
        'has_archive'  => false,
        'menu_icon'    => 'dashicons-video-alt2',
        'supports'     => array( 'title' ),
        'show_in_rest' => false,
    );

    register_post_type( 'movie_pick', $args );
}
add_action( 'init', 'nbop_register_movie_pick_cpt' );

/* ==========================================================================
   Meta Box: Movie Pick Details
   ========================================================================== */

function nbop_movie_pick_meta_box() {
    add_meta_box(
        'nbop_movie_pick_details',
        __( 'Movie Pick Details', 'newbreedofpattersons' ),
        'nbop_movie_pick_meta_box_callback',
        'movie_pick',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'nbop_movie_pick_meta_box' );

function nbop_movie_pick_meta_box_callback( $post ) {
    wp_nonce_field( 'nbop_movie_pick_nonce_action', 'nbop_movie_pick_nonce' );

    $review = get_post_meta( $post->ID, '_nbop_movie_review', true );
    $rating = get_post_meta( $post->ID, '_nbop_movie_rating', true );
    ?>
    <p>
        <label for="nbop_movie_review"><strong><?php esc_html_e( 'Review', 'newbreedofpattersons' ); ?></strong></label><br>
        <textarea id="nbop_movie_review" name="nbop_movie_review" rows="5" style="width:100%;"><?php echo esc_textarea( $review ); ?></textarea>
    </p>
    <p>
        <label for="nbop_movie_rating"><strong><?php esc_html_e( 'Rating', 'newbreedofpattersons' ); ?></strong></label><br>
        <select id="nbop_movie_rating" name="nbop_movie_rating">
            <option value=""><?php esc_html_e( 'Select Rating', 'newbreedofpattersons' ); ?></option>
            <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                <option value="<?php echo esc_attr( $i ); ?>" <?php selected( $rating, $i ); ?>>
                    <?php echo esc_html( $i ); ?> — <?php echo str_repeat( '&#9733;', $i ); ?>
                </option>
            <?php endfor; ?>
        </select>
    </p>
    <?php
}

function nbop_save_movie_pick_meta( $post_id ) {
    // Nonce verification.
    if ( ! isset( $_POST['nbop_movie_pick_nonce'] ) ||
         ! wp_verify_nonce( $_POST['nbop_movie_pick_nonce'], 'nbop_movie_pick_nonce_action' ) ) {
        return;
    }

    // Autosave check.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Capability check.
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Save review.
    if ( isset( $_POST['nbop_movie_review'] ) ) {
        update_post_meta( $post_id, '_nbop_movie_review', sanitize_textarea_field( $_POST['nbop_movie_review'] ) );
    }

    // Save rating.
    if ( isset( $_POST['nbop_movie_rating'] ) ) {
        $rating = absint( $_POST['nbop_movie_rating'] );
        if ( $rating >= 1 && $rating <= 5 ) {
            update_post_meta( $post_id, '_nbop_movie_rating', $rating );
        } else {
            delete_post_meta( $post_id, '_nbop_movie_rating' );
        }
    }
}
add_action( 'save_post_movie_pick', 'nbop_save_movie_pick_meta' );

/* ==========================================================================
   Helper: Get Movie Pick
   ========================================================================== */

function nbop_get_movie_pick() {
    $query = new WP_Query( array(
        'post_type'      => 'movie_pick',
        'posts_per_page' => 1,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    ) );

    if ( ! $query->have_posts() ) {
        wp_reset_postdata();
        return null;
    }

    $query->the_post();
    $post_id = get_the_ID();

    $data = array(
        'title'  => get_the_title(),
        'review' => get_post_meta( $post_id, '_nbop_movie_review', true ),
        'rating' => get_post_meta( $post_id, '_nbop_movie_rating', true ),
    );

    wp_reset_postdata();

    return $data;
}

/* ==========================================================================
   Helper: Category Badge Class
   ========================================================================== */

function nbop_category_badge_class( $slug ) {
    $map = array(
        'marriage'                   => 'cat-marriage',
        'children'                   => 'cat-children',
        'long-distance-relationships' => 'cat-long-distance',
        'family-finances'            => 'cat-family-finances',
    );

    return isset( $map[ $slug ] ) ? $map[ $slug ] : '';
}

/* ==========================================================================
   Excerpt Customization
   ========================================================================== */

function nbop_excerpt_length( $length ) {
    return 25;
}
add_filter( 'excerpt_length', 'nbop_excerpt_length' );

function nbop_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'nbop_excerpt_more' );

/* ==========================================================================
   Widget Areas
   ========================================================================== */

function nbop_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Footer Widget Area', 'newbreedofpattersons' ),
        'id'            => 'footer-widget-area',
        'description'   => __( 'Widgets displayed in the footer.', 'newbreedofpattersons' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );
}
add_action( 'widgets_init', 'nbop_widgets_init' );
