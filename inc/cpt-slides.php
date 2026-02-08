<?php
function boskoa_register_cpt_slides() {

    register_post_type('slide', [
        'labels' => [
            'name' => 'Slides',
            'singular_name' => 'Slide'
        ],
        'public' => true,
        'menu_icon' => 'dashicons-images-alt2',
        'supports' => ['title', 'editor', 'thumbnail'],
        'show_in_rest' => true
    ]);
}
add_action('init', 'boskoa_register_cpt_slides');
