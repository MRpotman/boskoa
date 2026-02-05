<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );

if ( !function_exists( 'chld_thm_cfg_parent_css' ) ):
    function chld_thm_cfg_parent_css() {
        wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array(  ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'chld_thm_cfg_parent_css' );

// END ENQUEUE PARENT ACTION
// Registrar color azul personalizado
function twentytwentyfive_child_custom_colors() {
    add_theme_support('editor-color-palette', array(
        array(
            'name'  => __('Azul Personalizado', 'twentytwentyfive-child'),
            'slug'  => 'custom-blue',
            'color' => '#2563eb', // Azul vibrante
        ),
        array(
            'name'  => __('Azul Oscuro', 'twentytwentyfive-child'),
            'slug'  => 'custom-blue-dark',
            'color' => '#1e40af',
        ),
    ));
}
add_action('after_setup_theme', 'twentytwentyfive_child_custom_colors');

/**
 * Load editor styles so the pattern appearance matches in the Block Editor.
 * This ensures classes like `has-custom-blue-background-color` are visible in the editor.
 */
function tt5_child_editor_styles() {
    // Let the editor load theme styles
    add_theme_support( 'editor-styles' );
    // Add the main stylesheet to the editor (relative to the theme)
    add_editor_style( 'style.css' );
}
add_action( 'after_setup_theme', 'tt5_child_editor_styles' );

/**
 * Also enqueue the child stylesheet specifically for the block editor as a fallback.
 */
function tt5_child_enqueue_block_editor_assets() {
    if ( file_exists( get_stylesheet_directory() . '/style.css' ) ) {
        wp_enqueue_style( 'twentytwentyfive-child-editor', get_stylesheet_directory_uri() . '/style.css', array(), filemtime( get_stylesheet_directory() . '/style.css' ) );
    }
}
add_action( 'enqueue_block_editor_assets', 'tt5_child_enqueue_block_editor_assets' );
?>