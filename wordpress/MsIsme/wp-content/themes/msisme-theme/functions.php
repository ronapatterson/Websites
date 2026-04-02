<?php
/**
 * Ms. Isme Theme Functions
 */

// Theme Setup
function msisme_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    register_nav_menus(array(
        'primary'   => __('Primary Menu', 'msisme-theme'),
        'footer'    => __('Footer Menu', 'msisme-theme'),
    ));
}
add_action('after_setup_theme', 'msisme_theme_setup');

// Enqueue Styles & Scripts
function msisme_enqueue_assets() {
    // Google Fonts
    wp_enqueue_style(
        'msisme-google-fonts',
        'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Poppins:wght@300;400;500;600;700&display=swap',
        array(),
        null
    );

    // Theme stylesheet
    wp_enqueue_style('msisme-style', get_stylesheet_uri(), array('msisme-google-fonts'), '1.0');

    // Theme JavaScript
    wp_enqueue_script('msisme-script', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0', true);
}
add_action('wp_enqueue_scripts', 'msisme_enqueue_assets');

// Add body class for page templates
function msisme_body_classes($classes) {
    if (is_front_page()) {
        $classes[] = 'home-page';
    }
    if (is_page('about')) {
        $classes[] = 'about-page';
    }
    if (is_page('services')) {
        $classes[] = 'services-page';
    }
    if (is_page('contact')) {
        $classes[] = 'contact-page';
    }
    return $classes;
}
add_filter('body_class', 'msisme_body_classes');

// Fallback menu if no menu is assigned
function msisme_fallback_menu() {
    echo '<ul>';
    echo '<li><a href="' . esc_url(home_url('/')) . '">Home</a></li>';
    echo '<li><a href="' . esc_url(get_permalink(get_page_by_path('about'))) . '">About</a></li>';
    echo '<li><a href="' . esc_url(get_permalink(get_page_by_path('services'))) . '">Services</a></li>';
    echo '<li><a href="' . esc_url(get_permalink(get_page_by_path('contact'))) . '">Contact</a></li>';
    echo '</ul>';
}
