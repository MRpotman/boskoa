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

/**
 * Registrar Custom Post Type para Métodos de Pago
 */
function boskoa_register_cpt_payment_method() {
    $labels = [
        'name'               => 'Métodos de Pago',
        'singular_name'      => 'Método de Pago',
        'menu_name'          => 'Métodos de Pago',
        'name_admin_bar'     => 'Método de Pago',
        'add_new'            => 'Añadir Nuevo',
        'add_new_item'       => 'Añadir Nuevo Método de Pago',
        'new_item'           => 'Nuevo Método de Pago',
        'edit_item'          => 'Editar Método de Pago',
        'view_item'          => 'Ver Método de Pago',
        'all_items'          => 'Todos los Métodos',
        'search_items'       => 'Buscar Métodos de Pago',
        'not_found'          => 'No se encontraron métodos de pago',
        'not_found_in_trash' => 'No hay métodos de pago en la papelera',
    ];

    $args = [
        'labels'             => $labels,
        'public'             => false,
        'publicly_queryable' => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => false,
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 25,
        'menu_icon'          => 'dashicons-money-alt',
        'supports'           => ['title', 'thumbnail'],
        'show_in_rest'       => true,
    ];

    register_post_type('payment_method', $args);
}
add_action('init', 'boskoa_register_cpt_payment_method');

/**
 * Personalizar columnas en el listado de métodos de pago
 */
function boskoa_payment_methods_columns($columns) {
    $new_columns = [
        'cb'        => $columns['cb'],
        'thumbnail' => 'Logo',
        'title'     => 'Nombre',
        'date'      => 'Fecha',
    ];
    return $new_columns;
}
add_filter('manage_payment_method_posts_columns', 'boskoa_payment_methods_columns');

/**
 * Mostrar el logo en la columna
 */
function boskoa_payment_methods_custom_column($column, $post_id) {
    if ($column === 'thumbnail') {
        $thumbnail = get_the_post_thumbnail($post_id, [40, 40]);
        echo $thumbnail ?: '<span style="color: #999;">Sin logo</span>';
    }
}
add_action('manage_payment_method_posts_custom_column', 'boskoa_payment_methods_custom_column', 10, 2);

/**
 * Añadir orden personalizado para los métodos de pago
 */
function boskoa_add_payment_method_order_metabox() {
    add_meta_box(
        'payment_method_order',
        'Orden de visualización',
        'boskoa_payment_method_order_callback',
        'payment_method',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'boskoa_add_payment_method_order_metabox');

function boskoa_payment_method_order_callback($post) {
    wp_nonce_field('boskoa_save_payment_order', 'payment_order_nonce');
    $order = get_post_meta($post->ID, '_payment_order', true);
    ?>
    <p>
        <label for="payment_order">Orden (número):</label>
        <input type="number" id="payment_order" name="payment_order" value="<?php echo esc_attr($order ?: 0); ?>" min="0" style="width: 100%;">
        <small style="display: block; margin-top: 5px; color: #666;">
            Los métodos se ordenan de menor a mayor. 0 = primero.
        </small>
    </p>
    <?php
}

function boskoa_save_payment_method_order($post_id) {
    if (!isset($_POST['payment_order_nonce']) || !wp_verify_nonce($_POST['payment_order_nonce'], 'boskoa_save_payment_order')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['payment_order'])) {
        update_post_meta($post_id, '_payment_order', intval($_POST['payment_order']));
    }
}
add_action('save_post_payment_method', 'boskoa_save_payment_method_order');