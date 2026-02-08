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

    'inc/cpt-tour-package.php',
    'inc/sortable-tour-package-columns.php',
    'inc/meta-boxes.php',
    'inc/save-meta.php',
    'inc/messages.php',
    'inc/help-text.php',
    'inc/tour-package-columns.php',
    'inc/tour-package-custom-column.php'
];

foreach ($includes as $file) {
    $path = get_template_directory() . '/' . $file;
    if (file_exists($path)) {
        require_once $path;
    }
}


