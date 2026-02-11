<?php
function boskoa_register_cpt_slides() {
    register_post_type('slide', [
        'labels' => [
            'name' => 'Slides',
            'singular_name' => 'Slide'
        ],
        'public' => false,
        'show_ui' => true,
        'publicly_queryable' => false,
        'exclude_from_search' => true,
        'show_in_rest' => true,
        'supports' => ['title'],
        'menu_icon' => 'dashicons-images-alt2',
    ]);
}
add_action('init', 'boskoa_register_cpt_slides');
