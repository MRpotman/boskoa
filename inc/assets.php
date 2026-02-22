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
        'boskoa-pagination',
        get_template_directory_uri() . '/assets/css/pagination.css'
    );

    wp_enqueue_style(
        'boskoa-hero-packages',
        get_template_directory_uri() . '/assets/css/hero-package.css'
    );

    wp_enqueue_style(
        'boskoa-hero-about',
        get_template_directory_uri() . '/assets/css/hero-about.css'
    );

    wp_enqueue_style(
        'boskoa-about-sections',
        get_template_directory_uri() . '/assets/css/about-sections.css'
    );

    wp_enqueue_style(
        'boskoa-about-team',
        get_template_directory_uri() . '/assets/css/about-team.css'
    );

    wp_enqueue_style(
        'boskoa-about-reservation',
        get_template_directory_uri() . '/assets/css/about-reservation.css'
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
        'boskoa-product-view',
        get_template_directory_uri() . '/assets/css/product-view.css'
    );

    wp_enqueue_style(
        'boskoa-activities-tour',
        get_template_directory_uri() . '/assets/css/activities-tour.css'
    );

    wp_enqueue_style(
        'boskoa-404-page',
        get_template_directory_uri() . '/assets/css/404.css'
    );

    wp_enqueue_style(
        'boskoa-package-view',
        get_template_directory_uri() . '/assets/css/package-view.css'
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
        'boskoa-person-per-price',
        get_template_directory_uri() . '/assets/js/person-per-price.js',
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

    wp_enqueue_script(
        'boskoa-pagination',
        get_template_directory_uri() . '/assets/js/pagination.js',
        [],
        null,
        true
    );

        // Booking Modal JS
        wp_enqueue_script(
            'boskoa-booking-modal',
            get_template_directory_uri() . '/assets/js/booking-modal.js',
            [],
            null,
            true
        );
    
    wp_enqueue_script(
            'boskoa-404',
            get_template_directory_uri() . '/assets/js/404.js',
            [],
            null,
            true
        );
}
add_action('wp_enqueue_scripts', 'boskoa_enqueue_assets');
