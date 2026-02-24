<?php
function boskoa_enqueue_assets() {

    $theme_uri = get_template_directory_uri();

    /*
    ==========================================
    GLOBAL STYLES (Siempre cargan)
    ==========================================
    */

    wp_enqueue_style('boskoa-base', get_stylesheet_uri(), [], '1.0');

    wp_enqueue_style('boskoa-header', $theme_uri . '/assets/css/header.css', ['boskoa-base']);
    wp_enqueue_style('boskoa-footer', $theme_uri . '/assets/css/footer.css', ['boskoa-base']);
    wp_enqueue_style('boskoa-notifications', $theme_uri . '/assets/css/notifications.css', ['boskoa-base']);
    wp_enqueue_style('boskoa-comment', $theme_uri . '/assets/css/comment.css', ['boskoa-base']);

    wp_enqueue_style(
        'boskoa-fontawesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        [],
        '6.4.0'
    );


    /*
    ==========================================
    GLOBAL SCRIPTS
    ==========================================
    */

    wp_enqueue_script('boskoa-header-js', $theme_uri . '/assets/js/header.js', [], null, true);
    wp_enqueue_script('boskoa-footer-js', $theme_uri . '/assets/js/footer.js', [], null, true);
    wp_enqueue_script('boskoa-comments-js', $theme_uri . '/assets/js/comments.js', [], null, true);


    /*
    ==========================================
    HOME PAGE
    ==========================================
    */

    if ( is_front_page() ) {

        wp_enqueue_style('boskoa-hero', $theme_uri . '/assets/css/hero.css', ['boskoa-base']);
        wp_enqueue_style('boskoa-front-page', $theme_uri . '/assets/css/front-page-general.css', ['boskoa-base']);
        wp_enqueue_style('boskoa-carousel-card', $theme_uri . '/assets/css/carousel-card.css', ['boskoa-base']);
        wp_enqueue_style('boskoa-home-package', $theme_uri . '/assets/css/package-home.css', ['boskoa-base']);
        wp_enqueue_style('boskoa-home-activities', $theme_uri . '/assets/css/activities-home.css', ['boskoa-base']);

        wp_enqueue_script('hero-carousel', $theme_uri . '/assets/js/hero-carousel.js', [], null, true);
    }


    /*
    ==========================================
    PACKAGES TEMPLATE
    ==========================================
    */

    if ( is_page_template('packages.php') ) {

        wp_enqueue_style('boskoa-packages', $theme_uri . '/assets/css/packages.css', ['boskoa-base']);
        wp_enqueue_style('boskoa-pagination', $theme_uri . '/assets/css/pagination.css', ['boskoa-base']);
        wp_enqueue_style('boskoa-hero-packages', $theme_uri . '/assets/css/hero-package.css', ['boskoa-base']);

        wp_enqueue_script('boskoa-pagination-js', $theme_uri . '/assets/js/pagination.js', [], null, true);
    }


    /*
    ==========================================
    ACTIVITIES TOUR TEMPLATE
    ==========================================
    */

    if ( is_page_template('activities-tour.php') ) {

        wp_enqueue_style('boskoa-packages', $theme_uri . '/assets/css/packages.css', ['boskoa-base']);
        wp_enqueue_style('boskoa-pagination', $theme_uri . '/assets/css/pagination.css', ['boskoa-base']);
        wp_enqueue_style('boskoa-hero-packages', $theme_uri . '/assets/css/hero-package.css', ['boskoa-base']);
        wp_enqueue_style('boskoa-activities-tour', $theme_uri . '/assets/css/activities-tour.css', ['boskoa-base']);

        wp_enqueue_script('boskoa-pagination-js', $theme_uri . '/assets/js/pagination.js', [], null, true);
    }


    /*
    ==========================================
    PRODUCT / PACKAGE VIEW
    ==========================================
    */

    if ( is_page_template('product-view.php') || is_page_template('package-view.php') ) {

        wp_enqueue_style('boskoa-product-view', $theme_uri . '/assets/css/product-view.css', ['boskoa-base']);
        wp_enqueue_style('boskoa-package-view', $theme_uri . '/assets/css/package-view.css', ['boskoa-base']);

        wp_enqueue_script('boskoa-booking-modal', $theme_uri . '/assets/js/booking-modal.js', [], null, true);
        wp_enqueue_script('boskoa-person-per-price', $theme_uri . '/assets/js/person-per-price.js', [], null, true);

        // Intl Tel Input
        wp_enqueue_style(
            'intl-tel-input',
            'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css'
        );

        wp_enqueue_script(
            'intl-tel-input',
            'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js',
            [],
            '17.0.19',
            true
        );

        wp_enqueue_script(
            'boskoa-phone-init',
            $theme_uri . '/assets/js/phone-init.js',
            ['intl-tel-input'],
            null,
            true
        );

    // Carousel Drag JS
    wp_enqueue_script(
        'boskoa-carousel-drag',
        get_template_directory_uri() . '/assets/js/carousel-drag.js',
        [],
        null,
        true
    );

    }


    /*
    ==========================================
    ABOUT PAGE
    ==========================================
    */

    if ( is_page('about-us') ) {

        wp_enqueue_style('boskoa-hero-about', $theme_uri . '/assets/css/hero-about.css', ['boskoa-base']);
        wp_enqueue_style('boskoa-about-sections', $theme_uri . '/assets/css/about-sections.css', ['boskoa-base']);
        wp_enqueue_style('boskoa-about-team', $theme_uri . '/assets/css/about-team.css', ['boskoa-base']);
        wp_enqueue_style('boskoa-about-reservation', $theme_uri . '/assets/css/about-reservation.css', ['boskoa-base']);
    }


    /*
    ==========================================
    404 PAGE
    ==========================================
    */

    if ( is_404() ) {
        wp_enqueue_style('boskoa-404', $theme_uri . '/assets/css/404.css', ['boskoa-base']);
    }

}

add_action('wp_enqueue_scripts', 'boskoa_enqueue_assets');