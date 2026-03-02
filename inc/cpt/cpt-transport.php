<?php
function boskoa_register_cpt_transport() {

    $labels = [
        'name'          => 'Transport',
        'singular_name' => 'Transport',
        'menu_name'     => 'Transport',
        'add_new_item'  => 'Add New Route',
        'edit_item'     => 'Edit Route',
        'all_items'     => 'All Routes',
    ];

    $args = [
        'labels'        => $labels,
        'public'        => true,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'menu_icon'     => 'dashicons-car',
        'supports'      => ['title', 'editor', 'thumbnail'],
        'show_in_rest'  => true,
    ];

    register_post_type('transport', $args);
}
add_action('init', 'boskoa_register_cpt_transport');