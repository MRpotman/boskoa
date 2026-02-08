<?php
function boskoa_register_menus() {
    register_nav_menus([
        'menu-principal' => 'Menú Principal'
    ]);
}
add_action('after_setup_theme', 'boskoa_register_menus');
