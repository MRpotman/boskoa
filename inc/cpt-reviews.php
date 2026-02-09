<?php
function boskoa_register_cpt_review() {

    register_post_type('review', [
        'labels' => [
            'name' => 'Reviews',
            'singular_name' => 'review'
        ],
        'public' => true,
        'menu_icon' => 'dashicons-star-filled',
        'supports' => ['title'],
        'show_in_rest' => true
    ]);
}
add_action('init', 'boskoa_register_cpt_review');
