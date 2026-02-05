<?php
add_theme_support('post-thumbnails');

function mi_theme_assets() {
  wp_enqueue_style(
    'mi-theme-style',
    get_stylesheet_uri(),
    [],
    '1.0'
  );
}

add_action('wp_enqueue_scripts', 'mi_theme_assets');


function theme_scripts() {
  wp_enqueue_script(
    'hero-carousel',
    get_template_directory_uri() . '/assets/js/hero-carousel.js',
    [],
    null,
    true
  );
}
add_action('wp_enqueue_scripts', 'theme_scripts');

function register_cpt_slide() {

    $labels = [
        'name'               => 'Slides',
        'singular_name'      => 'Slide',
        'menu_name'          => 'Slides',
        'name_admin_bar'     => 'Slide',
        'add_new'            => 'Añadir Nuevo',
        'add_new_item'       => 'Añadir Nuevo Slide',
        'new_item'           => 'Nuevo Slide',
        'edit_item'          => 'Editar Slide',
        'view_item'          => 'Ver Slide',
        'all_items'          => 'Todos los Slides',
        'search_items'       => 'Buscar Slides',
        'not_found'          => 'No se encontraron Slides',
        'not_found_in_trash' => 'No se encontraron Slides en la papelera',
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-images-alt2',
        'supports'           => ['title', 'editor', 'thumbnail'],
        'show_in_rest'       => true, // para Gutenberg
    ];

    register_post_type('slide', $args);
}
add_action('init', 'register_cpt_slide');
