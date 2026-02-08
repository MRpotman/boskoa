<?php
add_theme_support('post-thumbnails');

/**
 * Load theme files
 */
$includes = [
    'inc/assets.php',
    'inc/fonts.php',
    'inc/menus.php',
    'inc/cpt-slides.php',
    'inc/cpt-textos.php',
    'inc/cpt-payment-methods.php',
    'inc/payment-method-columns.php',
    'inc/payment-method-order.php',
    'inc/recaptcha.php',
    'inc/contact-form.php',
];

foreach ($includes as $file) {
    if (file_exists(get_template_directory() . '/' . $file)) {
        require_once get_template_directory() . '/' . $file;
    }
}