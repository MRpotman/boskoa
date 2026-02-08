<?php
// Añadir meta boxes
add_action('add_meta_boxes', function () {

    // Detalles
    add_meta_box(
        'package_details',
        'Detalles del Paquete',
        function($post) {
            wp_nonce_field('boskoa_save_package_details', 'package_details_nonce');
            $price = get_post_meta($post->ID, '_package_price', true);
            $locations = get_post_meta($post->ID, '_package_locations', true);
            $family_friendly = get_post_meta($post->ID, '_package_family_friendly', true);
            ?>
            <table class="form-table">
                <tr>
                    <th><label for="package_price">Precio</label></th>
                    <td>
                        <input type="text" id="package_price" name="package_price" value="<?php echo esc_attr($price); ?>" class="regular-text" placeholder="$500">
                        <p class="description">Ejemplo: $500, $1,200, etc.</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="package_locations">Ubicaciones</label></th>
                    <td>
                        <input type="text" id="package_locations" name="package_locations" value="<?php echo esc_attr($locations ?: '0 Location'); ?>" class="regular-text">
                        <p class="description">Ejemplo: 3 Locations, 5 Locations, etc.</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="package_family_friendly">¿Apto para familias?</label></th>
                    <td>
                        <input type="checkbox" id="package_family_friendly" name="package_family_friendly" value="yes" <?php checked($family_friendly, 'yes'); ?>>
                        Marcar si este paquete es family-friendly
                    </td>
                </tr>
            </table>
            <style>
                .form-table th { width: 200px; }
                .form-table input[type="text"] { width: 100%; max-width: 400px; }
            </style>
            <?php
        },
        'tour_package',
        'normal',
        'high'
    );

    // Orden
    add_meta_box(
        'package_order',
        'Orden de Visualización',
        function($post) {
            wp_nonce_field('boskoa_save_package_order', 'package_order_nonce');
            $order = get_post_meta($post->ID, '_package_order', true);
            ?>
            <p>
                <label for="package_order">Orden (número):</label>
                <input type="number" id="package_order" name="package_order" value="<?php echo esc_attr($order !== '' ? $order : 0); ?>" min="0" style="width:100%;">
                <small style="display:block;margin-top:5px;color:#666;">
                    Los paquetes se ordenan de menor a mayor. 0 = primero.
                </small>
            </p>
            <?php
        },
        'tour_package',
        'side',
        'high'
    );

});
