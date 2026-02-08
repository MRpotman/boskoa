<?php
add_action(
    'manage_tour_package_posts_custom_column',
    function ($column, $post_id) {

        switch ($column) {
            case 'thumbnail':
                echo get_the_post_thumbnail($post_id, [60, 60])
                    ?: '<span style="color:#999">Sin imagen</span>';
                break;

            case 'price':
                $price = get_post_meta($post_id, '_package_price', true);
                echo $price
                    ? '<strong>' . esc_html($price) . '</strong>'
                    : '<span style="color:#999">Sin precio</span>';
                break;

            case 'locations':
                echo get_post_meta($post_id, '_package_locations', true)
                    ?: '<span style="color:#999">0 Location</span>';
                break;

            case 'family':
                echo get_post_meta($post_id, '_package_family_friendly', true) === 'yes'
                    ? '<span style="color:#2D8A3E">✓ Sí</span>'
                    : '<span style="color:#999">No</span>';
                break;

            case 'order':
                $order = get_post_meta($post_id, '_package_order', true);
                echo $order !== '' ? esc_html($order) : '0';
                break;
        }

    },
    10,
    2
);
