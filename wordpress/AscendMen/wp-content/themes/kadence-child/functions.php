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
});

require_once get_stylesheet_directory() . '/includes/programs-cpt.php';
