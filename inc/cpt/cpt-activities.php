<?php
function boskoa_register_cpt_activitie() {

    register_post_type('activitie', [
        'labels' => [
            'name' => 'Activities',
            'singular_name' => 'activitie'
        ],
        'public' => true,
        'menu_icon' => 'dashicons-location-alt',
        'supports' => ['title'],
        'show_in_rest' => true
    ]);
}
add_action('init', 'boskoa_register_cpt_activitie');
