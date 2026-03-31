<?php
/**
 * AscendMen Programs Custom Post Type
 */

function ascendmen_register_programs_cpt() {
    register_post_type( 'am_program', [
        'labels' => [
            'name'               => 'Programs',
            'singular_name'      => 'Program',
            'add_new_item'       => 'Add New Program',
            'edit_item'          => 'Edit Program',
            'view_item'          => 'View Program',
            'search_items'       => 'Search Programs',
            'not_found'          => 'No programs found.',
            'menu_name'          => 'Programs',
        ],
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => [ 'slug' => 'programs' ],
        'supports'     => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
        'menu_icon'    => 'dashicons-groups',
        'show_in_rest' => true,
    ]);

    register_taxonomy( 'am_program_type', 'am_program', [
        'labels' => [
            'name'          => 'Program Types',
            'singular_name' => 'Program Type',
            'menu_name'     => 'Program Types',
        ],
        'hierarchical'  => true,
        'public'        => true,
        'rewrite'       => [ 'slug' => 'program-type' ],
        'show_in_rest'  => true,
    ]);
}
add_action( 'init', 'ascendmen_register_programs_cpt' );

/**
 * Add program meta: price_type (free|paid), members_only (yes|no)
 */
function ascendmen_register_program_meta() {
    foreach ( ['am_price_type', 'am_members_only', 'am_woo_product_id'] as $key ) {
        register_post_meta( 'am_program', $key, [
            'show_in_rest'  => true,
            'single'        => true,
            'type'          => 'string',
            'auth_callback' => function() { return current_user_can('edit_posts'); },
        ]);
    }
}
add_action( 'init', 'ascendmen_register_program_meta' );
