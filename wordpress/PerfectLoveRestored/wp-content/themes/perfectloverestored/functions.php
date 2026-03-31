<?php
/**
 * Perfect Love Restored Theme Functions
 *
 * @package PerfectLoveRestored
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'PLR_VERSION', '1.0.0' );

/**
 * Theme setup
 */
function plr_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', array(
        'height'      => 80,
        'width'       => 250,
        'flex-height' => true,
        'flex-width'  => true,
    ) );
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ) );
    add_theme_support( 'automatic-feed-links' );

    register_nav_menus( array(
        'primary'  => __( 'Primary Menu', 'perfectloverestored' ),
        'footer'   => __( 'Footer Menu', 'perfectloverestored' ),
    ) );

    add_image_size( 'plr-card', 600, 400, true );
    add_image_size( 'plr-hero', 1600, 600, true );
}
add_action( 'after_setup_theme', 'plr_setup' );

/**
 * Enqueue styles and scripts
 */
function plr_enqueue_assets() {
    // Google Fonts
    wp_enqueue_style(
        'plr-google-fonts',
        'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;600;700&display=swap',
        array(),
        null
    );

    // Theme stylesheet
    wp_enqueue_style( 'plr-style', get_stylesheet_uri(), array( 'plr-google-fonts' ), PLR_VERSION );

    // Theme script
    wp_enqueue_script( 'plr-script', get_template_directory_uri() . '/assets/js/main.js', array(), PLR_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'plr_enqueue_assets' );

/**
 * Add custom CSS class to Donate menu item
 */
function plr_menu_item_classes( $classes, $item ) {
    if ( strtolower( $item->title ) === 'donate' ) {
        $classes[] = 'menu-item-donate';
    }
    return $classes;
}
add_filter( 'nav_menu_css_class', 'plr_menu_item_classes', 10, 2 );

/**
 * Custom excerpt length
 */
function plr_excerpt_length( $length ) {
    return 25;
}
add_filter( 'excerpt_length', 'plr_excerpt_length' );

/**
 * Custom excerpt more
 */
function plr_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'plr_excerpt_more' );

/**
 * Register widget areas
 */
function plr_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Blog Sidebar', 'perfectloverestored' ),
        'id'            => 'sidebar-blog',
        'description'   => __( 'Sidebar for the blog page.', 'perfectloverestored' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );

    register_sidebar( array(
        'name'          => __( 'Footer Widget Area', 'perfectloverestored' ),
        'id'            => 'footer-widgets',
        'description'   => __( 'Widgets in the footer area.', 'perfectloverestored' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );
}
add_action( 'widgets_init', 'plr_widgets_init' );

/**
 * Customize "Read More" link
 */
function plr_content_more_link( $link ) {
    return '<a class="read-more" href="' . esc_url( get_permalink() ) . '">' . __( 'Continue Reading', 'perfectloverestored' ) . '</a>';
}
add_filter( 'the_content_more_link', 'plr_content_more_link' );
