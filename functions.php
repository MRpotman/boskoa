<?php
add_theme_support('post-thumbnails');

function mi_theme_assets() {
  wp_enqueue_style(
    'mi-theme-style',
    get_stylesheet_uri(),
    [],
    '1.0'
  );
}

add_action('wp_enqueue_scripts', 'mi_theme_assets');


function theme_scripts() {
  wp_enqueue_script(
    'hero-carousel',
    get_template_directory_uri() . '/assets/js/hero-carousel.js',
    [],
    null,
    true
  );
}
add_action('wp_enqueue_scripts', 'theme_scripts');

function register_cpt_slide() {

    $labels = [
        'name'               => 'Slides',
        'singular_name'      => 'Slide',
        'menu_name'          => 'Slides',
        'name_admin_bar'     => 'Slide',
        'add_new'            => 'Añadir Nuevo',
        'add_new_item'       => 'Añadir Nuevo Slide',
        'new_item'           => 'Nuevo Slide',
        'edit_item'          => 'Editar Slide',
        'view_item'          => 'Ver Slide',
        'all_items'          => 'Todos los Slides',
        'search_items'       => 'Buscar Slides',
        'not_found'          => 'No se encontraron Slides',
        'not_found_in_trash' => 'No se encontraron Slides en la papelera',
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-images-alt2',
        'supports'           => ['title', 'editor', 'thumbnail'],
        'show_in_rest'       => true, // para Gutenberg
    ];

    register_post_type('slide', $args);
}
add_action('init', 'register_cpt_slide');

/**
 * Procesar formulario de contacto del footer
 */
function boskoa_handle_contact_form() {
    // Verificar nonce
    if (!isset($_POST['contact_nonce']) || !wp_verify_nonce($_POST['contact_nonce'], 'boskoa_contact_form')) {
        wp_die('Error de seguridad');
    }

    // Sanitizar datos
    $name = sanitize_text_field($_POST['contact_name']);
    $email = sanitize_email($_POST['contact_email']);
    $matters = sanitize_text_field($_POST['contact_matters']);
    $message = sanitize_textarea_field($_POST['contact_message']);

    // Email del administrador
    $admin_email = get_option('admin_email');
    
    // Asunto del email
    $subject = 'Nuevo mensaje de contacto: ' . $matters;
    
    // Cuerpo del email
    $body = "Nuevo mensaje de contacto desde el sitio web:\n\n";
    $body .= "Nombre: " . $name . "\n";
    $body .= "Email: " . $email . "\n";
    $body .= "Asunto: " . $matters . "\n\n";
    $body .= "Mensaje:\n" . $message . "\n";
    
    // Headers
    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . get_bloginfo('name') . ' <' . $admin_email . '>',
        'Reply-To: ' . $name . ' <' . $email . '>'
    );

    // Enviar email
    $sent = wp_mail($admin_email, $subject, $body, $headers);

    // Redirigir con mensaje
    if ($sent) {
        wp_redirect(add_query_arg('contact', 'success', wp_get_referer()));
    } else {
        wp_redirect(add_query_arg('contact', 'error', wp_get_referer()));
    }
    exit;
}
add_action('admin_post_nopriv_boskoa_contact_form', 'boskoa_handle_contact_form');
add_action('admin_post_boskoa_contact_form', 'boskoa_handle_contact_form');
