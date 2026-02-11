<?php
add_theme_support('post-thumbnails');

/**
 * Load theme files
 */
$includes = [
    'inc/assets.php',
    'inc/extras/fonts.php',
    'inc/menus.php',
    'inc/cpt/cpt-slides.php',
    'inc/cpt/cpt-textos.php',
    'inc/cpt/cpt-activities.php',
    'inc/cpt/cpt-payment-methods.php',
    'inc/payment-method-columns.php',
    'inc/payment-method-order.php',
    'inc/recaptcha.php',
    'inc/contact-form.php',
    'inc/cpt/cpt-reviews.php',
    'inc/cpt/cpt-tour-package.php',
    'inc/sortable-tour-package-columns.php',
    'inc/meta-boxes.php',
    'inc/save-meta.php',
    'inc/messages.php',
    'inc/help-text.php',
    'inc/tour-package-columns.php',
    'inc/tour-package-custom-column.php',
    'inc/acf-about-fields.php'
];

foreach ($includes as $file) {
    $path = get_template_directory() . '/' . $file;
    if (file_exists($path)) {
        require_once $path;
    }
}


