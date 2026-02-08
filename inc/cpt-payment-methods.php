<?php
function boskoa_register_cpt_payment_method() {

    register_post_type('payment_method', [
        'labels' => [
            'name' => 'Métodos de Pago',
            'singular_name' => 'Método de Pago'
        ],
        'public' => false,
        'show_ui' => true,
        'menu_icon' => 'dashicons-money-alt',
        'supports' => ['title', 'thumbnail']
    ]);
}
add_action('init', 'boskoa_register_cpt_payment_method');
