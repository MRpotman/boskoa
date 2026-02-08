<?php
add_action('save_post_tour_package', function($post_id) {

    // Guardar detalles
    if (isset($_POST['package_details_nonce']) && wp_verify_nonce($_POST['package_details_nonce'], 'boskoa_save_package_details')) {
        if (isset($_POST['package_price'])) {
            update_post_meta($post_id, '_package_price', sanitize_text_field($_POST['package_price']));
        }
        if (isset($_POST['package_locations'])) {
            update_post_meta($post_id, '_package_locations', sanitize_text_field($_POST['package_locations']));
        }
        update_post_meta($post_id, '_package_family_friendly', isset($_POST['package_family_friendly']) ? 'yes' : 'no');
    }

    // Guardar orden
    if (isset($_POST['package_order_nonce']) && wp_verify_nonce($_POST['package_order_nonce'], 'boskoa_save_package_order')) {
        if (isset($_POST['package_order'])) {
            update_post_meta($post_id, '_package_order', intval($_POST['package_order']));
        }
    }

});
