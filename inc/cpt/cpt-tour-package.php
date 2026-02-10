<?php

function boskoa_register_cpt_tour_package() {
    $labels = [
        'name'               => 'Paquetes Turísticos',
        'singular_name'      => 'Paquete',
        'menu_name'          => 'Tour Packages',
        'name_admin_bar'     => 'Paquete',
        'add_new'            => 'Añadir Nuevo',
        'add_new_item'       => 'Añadir Nuevo Paquete',
        'new_item'           => 'Nuevo Paquete',
        'edit_item'          => 'Editar Paquete',
        'view_item'          => 'Ver Paquete',
        'all_items'          => 'Todos los Paquetes',
        'search_items'       => 'Buscar Paquetes',
        'not_found'          => 'No se encontraron paquetes',
        'not_found_in_trash' => 'No hay paquetes en la papelera',
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => ['slug' => 'packages'],
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-palmtree',
        'supports'           => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest'       => true,
    ];

    register_post_type('tour_package', $args);
}
add_action('init', 'boskoa_register_cpt_tour_package');