<?php
add_filter('manage_payment_method_posts_columns', function ($columns) {
    return [
        'cb' => $columns['cb'],
        'thumbnail' => 'Logo',
        'title' => 'Nombre',
        'date' => 'Fecha'
    ];
});

add_action('manage_payment_method_posts_custom_column', function ($column, $post_id) {
    if ($column === 'thumbnail') {
        echo get_the_post_thumbnail($post_id, [40, 40]) ?: '<span style="color:#999">Sin logo</span>';
    }
}, 10, 2);
