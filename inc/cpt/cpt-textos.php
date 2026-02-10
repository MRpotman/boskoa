<?php
function boskoa_register_cpt_textos() {
    register_post_type('texto', [
        'labels' => [
            'name' => 'Textos',
            'singular_name' => 'Texto'
        ],
        'public' => true,
        'menu_icon' => 'dashicons-editor-textcolor',
        'supports' => ['title'],
        'show_in_rest' => true
    ]);
}
add_action('init', 'boskoa_register_cpt_textos');
