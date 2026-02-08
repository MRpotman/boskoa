<?php

add_filter('manage_tour_package_posts_columns', function ($columns) {
    return [
        'cb'        => $columns['cb'],
        'thumbnail' => 'Imagen',
        'title'     => 'Nombre del Paquete',
        'price'     => 'Precio',
        'locations' => 'Ubicaciones',
        'family'    => 'Familiar',
        'order'     => 'Orden',
        'date'      => 'Fecha',
    ];
});


add_action(
    'manage_tour_package_posts_custom_column',
    function ($column, $post_id) {

        if ($column === 'thumbnail') {
            echo get_the_post_thumbnail($post_id, [40, 40]) 
                ?: '<span style="color:#999">Sin imagen</span>';
        }

        if ($column === 'price') {
            echo get_post_meta($post_id, 'price', true) ?: '—';
        }

    },
    10,
    2
);
