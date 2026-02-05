<?php

function mi_theme_assets() {
  wp_enqueue_style(
    'mi-theme-style',
    get_stylesheet_uri(),
    [],
    '1.0'
  );
}

add_action('wp_enqueue_scripts', 'mi_theme_assets');
