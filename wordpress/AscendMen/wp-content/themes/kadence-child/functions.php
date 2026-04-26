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
        '1.2.0'
    );
    wp_enqueue_style(
        'am-google-fonts',
        'https://fonts.googleapis.com/css2?family=Oswald:wght@500;700&family=Inter:wght@400;500;700&display=swap',
        [],
        null
    );

    if ( is_front_page() ) {
        wp_enqueue_script(
            'am-testimonial-carousel',
            get_stylesheet_directory_uri() . '/assets/js/testimonial-carousel.js',
            [],
            '1.0.0',
            true
        );
        wp_add_inline_script(
            'am-testimonial-carousel',
            "document.addEventListener('DOMContentLoaded', function(){
                var root = document.querySelector('.am-carousel');
                if (root && window.AMTestimonialCarousel) {
                    window.AMTestimonialCarousel.initFromDOM(root);
                }
            });"
        );
    }
});

require_once get_stylesheet_directory() . '/includes/programs-cpt.php';
