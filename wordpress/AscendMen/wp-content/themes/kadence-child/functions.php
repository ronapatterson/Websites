<?php
add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'kadence-parent-style',
        get_template_directory_uri() . '/style.css'
    );
    wp_enqueue_style(
        'kadence-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        [ 'kadence-parent-style' ],
        '1.1.0'
    );
    wp_enqueue_style(
        'am-google-fonts',
        'https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Inter:wght@400;500;700&display=swap',
        [],
        null
    );

    // Inline brand tokens so they are always present in <head>,
    // independent of external stylesheet caching.
    $am_brand_tokens = ':root{'
        . '--am-flame-blue:#29ABE2;'
        . '--am-navy:#1B2A4A;'
        . '--am-dark-nav:#111d33;'
        . '--am-steel-blue:#4A7FC1;'
        . '--am-white:#FFFFFF;'
        . '--am-font-heading:"Oswald","Arial Narrow",sans-serif;'
        . '--am-font-body:"Inter",system-ui,-apple-system,sans-serif;'
        . '--am-container:1200px;'
        . '--am-section-pad:80px;'
        . '}';
    wp_add_inline_style( 'kadence-child-style', $am_brand_tokens );
});

require_once get_stylesheet_directory() . '/includes/programs-cpt.php';
