<?php
function boskoa_enqueue_assets() {

    wp_enqueue_style('boskoa-style', get_stylesheet_uri(), [], '1.0');

    wp_enqueue_style(
        'boskoa-hero',
        get_template_directory_uri() . '/assets/css/hero.css'
    );

     wp_enqueue_style(
        'boskoa-comment',
        get_template_directory_uri() . '/assets/css/comment.css'
    );
    
    wp_enqueue_style(
        'boskoa-footer',
        get_template_directory_uri() . '/assets/css/footer.css'
    );

    wp_enqueue_style(
        'boskoa-header',
        get_template_directory_uri() . '/assets/css/header.css'
    );

    wp_enqueue_style(
        'boskoa-notifications',
        get_template_directory_uri() . '/assets/css/notifications.css'
    );

    wp_enqueue_style(
        'boskoa-packages',
        get_template_directory_uri() . '/assets/css/packages.css'
    );

    wp_enqueue_style(
        'boskoa-front-page-general',
        get_template_directory_uri() . '/assets/css/front-page-general.css'
    );  

    wp_enqueue_style(
        'boskoa-carousel-card',
        get_template_directory_uri() . '/assets/css/carousel-card.css'
    );

    wp_enqueue_style(
        'boskoa-home-package',
        get_template_directory_uri() . '/assets/css/package-home.css'
    );

   wp_enqueue_style(
        'boskoa-home-activities',
        get_template_directory_uri() . '/assets/css/activities-home.css'
    );
    
    wp_enqueue_style(
        'boskoa-fontawesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        [],
        '6.4.0'
    );

    wp_enqueue_script(
        'boskoa-header',
        get_template_directory_uri() . '/assets/js/header.js',
        [],
        null,
        true
    );

    wp_enqueue_script(
        'boskoa-footer',
        get_template_directory_uri() . '/assets/js/footer.js',
        [],
        null,
        true
    );

    wp_enqueue_script(
        'hero-carousel',
        get_template_directory_uri() . '/assets/js/hero-carousel.js',
        [],
        null,
        true
    );

        wp_enqueue_script(
        'boskoa-comments',
        get_template_directory_uri() . '/assets/js/comments.js',
        [],
        null,
        true
    );
}
add_action('wp_enqueue_scripts', 'boskoa_enqueue_assets');
