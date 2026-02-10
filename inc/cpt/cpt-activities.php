<?php
function boskoa_register_cpt_activity() {

    $labels = [
        'name'          => 'Activities',
        'singular_name' => 'Activity',
        'menu_name'     => 'Activities',
        'add_new_item'  => 'Add New Activity',
        'edit_item'     => 'Edit Activity',
        'all_items'     => 'All Activities',
    ];

    $args = [
        'labels'        => $labels,
        'public'        => true,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'menu_icon'     => 'dashicons-location-alt',
        'supports'      => ['title', 'editor', 'thumbnail'],
        'show_in_rest'  => true,
    ];

    register_post_type('activity', $args);
}
add_action('init', 'boskoa_register_cpt_activity');
