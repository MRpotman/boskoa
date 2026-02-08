<?php
add_theme_support('post-thumbnails');

function mi_theme_assets() {
  wp_enqueue_style(
    'mi-theme-style',
    get_stylesheet_uri(),
    [],
    '1.0'
  );
   wp_enqueue_style(
        'boskoa-hero', get_template_directory_uri() . '/assets/css/hero.css');
    wp_enqueue_style(
        'boskoa-footer', get_template_directory_uri() . '/assets/css/footer.css');
    wp_enqueue_style(
        'boskoa-notifications', get_template_directory_uri() . '/assets/css/notifications.css');
    wp_enqueue_style(
        'boskoa-header', get_template_directory_uri() . '/assets/css/header.css');
}

add_action('wp_enqueue_scripts', 'mi_theme_assets');

function boskoa_scripts() {
  wp_enqueue_style('boskoa-style', get_stylesheet_uri());

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

  wp_enqueue_style(
        'boskoa-fontawesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        array(),
        '6.4.0'
    );

}
add_action('wp_enqueue_scripts', 'boskoa_scripts');

/*---fonts--*/
function boskoa_fonts() {
  wp_enqueue_style(
    'boskoa-google-fonts',
    'https://fonts.googleapis.com/css2?family=Architects+Daughter&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap',
    false
  );
}
add_action('wp_enqueue_scripts', 'boskoa_fonts');
/******* */
/*the funtion of navbar*/
function tema_menus() {
  register_nav_menus([
    'menu-principal' => 'Menú Principal'
  ]);
}
add_action('after_setup_theme', 'tema_menus');


/*------ */
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
    <input type="number" id="payment_order" name="payment_order" value="<?php echo esc_attr($order ?: 0); ?>" min="0"
        style="width: 100%;">
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



/**
 * Verificar reCAPTCHA v3
 */
function boskoa_verify_recaptcha($token) {
    // Verificar que exista la clave secreta
    if (!defined('BOSKOA_RECAPTCHA_SECRET_KEY') || empty(BOSKOA_RECAPTCHA_SECRET_KEY)) {
        error_log('reCAPTCHA: Secret key no definida');
        return false;
    }
    
    $secret_key = BOSKOA_RECAPTCHA_SECRET_KEY;
    
    // Hacer petición a Google
    $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
        'body' => [
            'secret' => $secret_key,
            'response' => $token
        ]
    ]);
    
    // Verificar si hubo error en la petición
    if (is_wp_error($response)) {
        error_log('reCAPTCHA: Error en la petición - ' . $response->get_error_message());
        return false;
    }
    
    // Obtener el cuerpo de la respuesta
    $body = wp_remote_retrieve_body($response);
    $result = json_decode($body);
    
    // Log para debugging (opcional - puedes comentar en producción)
    error_log('reCAPTCHA Response: ' . print_r($result, true));
    
    // Verificar que:
    // 1. La verificación fue exitosa
    // 2. El score es mayor a 0.5 (0.0 = bot, 1.0 = humano)
    if (!$result->success) {
        error_log('reCAPTCHA: Verificación fallida');
        return false;
    }
    
    if ($result->score < 0.5) {
        error_log('reCAPTCHA: Score muy bajo - ' . $result->score);
        return false;
    }
    
    return true;
}

/**
 * Procesar formulario de contacto con reCAPTCHA
 */
function boskoa_handle_contact_form() {
    // Verificar nonce de seguridad
    if (!isset($_POST['contact_nonce']) || !wp_verify_nonce($_POST['contact_nonce'], 'boskoa_contact_form')) {
        wp_die('Error de seguridad. Por favor, intenta nuevamente.');
    }

    // NUEVO: Verificar reCAPTCHA
    if (!isset($_POST['recaptcha_token']) || empty($_POST['recaptcha_token'])) {
        wp_redirect(add_query_arg('contact', 'error', wp_get_referer()));
        exit;
    }
    
    if (!boskoa_verify_recaptcha($_POST['recaptcha_token'])) {
        error_log('reCAPTCHA: Verificación fallida para IP: ' . $_SERVER['REMOTE_ADDR']);
        wp_redirect(add_query_arg('contact', 'error', wp_get_referer()));
        exit;
    }

    // Verificar que sea una petición POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        wp_die('Método no permitido');
    }

    // Sanitizar y validar datos
    $name = sanitize_text_field($_POST['contact_name']);
    $email = sanitize_email($_POST['contact_email']);
    $matters = sanitize_text_field($_POST['contact_matters']);
    $message = sanitize_textarea_field($_POST['contact_message']);

    // Validaciones
    $errors = [];
    
    if (empty($name)) {
        $errors[] = 'El nombre es requerido';
    }
    
    if (empty($email) || !is_email($email)) {
        $errors[] = 'Email inválido';
    }
    
    if (empty($matters)) {
        $errors[] = 'El asunto es requerido';
    }
    
    if (empty($message)) {
        $errors[] = 'El mensaje es requerido';
    }

    // Si hay errores, redirigir con mensaje de error
    if (!empty($errors)) {
        $error_message = implode(', ', $errors);
        wp_redirect(add_query_arg('contact', 'error', wp_get_referer()));
        exit;
    }

    // Email del administrador
    $admin_email = get_option('admin_email');
    
    // Asunto del email
    $subject = 'Nuevo mensaje de contacto: ' . $matters;
    
    // Cuerpo del email (HTML)
    $body = '
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; background: #f4f4f4; }
            .content { background: #ffffff; padding: 30px; border-radius: 8px; }
            .header { background: #2D8A3E; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
            .field { margin: 15px 0; padding: 10px; background: #f9f9f9; border-left: 4px solid #2D8A3E; }
            .field strong { color: #2D8A3E; }
            .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h2>Nuevo Mensaje de Contacto</h2>
                <p>Boskoa Travels - Costa Rica</p>
            </div>
            <div class="content">
                <p>Has recibido un nuevo mensaje desde el formulario de contacto de tu sitio web:</p>
                
                <div class="field">
                    <strong>Nombre:</strong><br>
                    ' . esc_html($name) . '
                </div>
                
                <div class="field">
                    <strong>Email:</strong><br>
                    <a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>
                </div>
                
                <div class="field">
                    <strong>Asunto:</strong><br>
                    ' . esc_html($matters) . '
                </div>
                
                <div class="field">
                    <strong>Mensaje:</strong><br>
                    ' . nl2br(esc_html($message)) . '
                </div>
                
                <div class="footer">
                    <p>Este mensaje fue enviado desde: <a href="' . home_url() . '">' . get_bloginfo('name') . '</a></p>
                    <p>Fecha: ' . date_i18n(get_option('date_format') . ' ' . get_option('time_format')) . '</p>
                    <p style="color: #2D8A3E;"><strong>✓ Verificado por reCAPTCHA</strong></p>
                </div>
            </div>
        </div>
    </body>
    </html>
    ';
    
    // Headers para email HTML
    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . get_bloginfo('name') . ' <' . $admin_email . '>',
        'Reply-To: ' . $name . ' <' . $email . '>'
    ];

    // Enviar email
    $sent = wp_mail($admin_email, $subject, $body, $headers);

    // Email de confirmación al remitente (opcional)
    if ($sent) {
        $confirmation_subject = 'Confirmación: Hemos recibido tu mensaje - Boskoa Travels';
        $confirmation_body = '
        <html>
        <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
            <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
                <div style="background: #2D8A3E; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;">
                    <h2>¡Gracias por contactarnos!</h2>
                </div>
                <div style="background: #ffffff; padding: 30px; border: 1px solid #ddd; border-top: none; border-radius: 0 0 8px 8px;">
                    <p>Hola <strong>' . esc_html($name) . '</strong>,</p>
                    <p>Hemos recibido tu mensaje y te responderemos a la brevedad posible.</p>
                    <p><strong>Resumen de tu mensaje:</strong></p>
                    <div style="background: #f9f9f9; padding: 15px; border-left: 4px solid #2D8A3E; margin: 20px 0;">
                        <p><strong>Asunto:</strong> ' . esc_html($matters) . '</p>
                        <p><strong>Mensaje:</strong><br>' . nl2br(esc_html($message)) . '</p>
                    </div>
                    <p>Saludos,<br>El equipo de <strong>Boskoa Travels</strong></p>
                </div>
                <div style="text-align: center; margin-top: 20px; color: #666; font-size: 12px;">
                    <p>Boskoa Travels - Costa Rica<br>
                    <a href="' . home_url() . '">' . home_url() . '</a></p>
                </div>
            </div>
        </body>
        </html>
        ';
        
        $confirmation_headers = [
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . get_bloginfo('name') . ' <' . $admin_email . '>'
        ];
        
        wp_mail($email, $confirmation_subject, $confirmation_body, $confirmation_headers);
    }

    // Redirigir con mensaje de éxito o error
    if ($sent) {
        wp_redirect(add_query_arg('contact', 'success', wp_get_referer()));
    } else {
        wp_redirect(add_query_arg('contact', 'error', wp_get_referer()));
    }
    exit;
}
add_action('admin_post_nopriv_boskoa_contact_form', 'boskoa_handle_contact_form');
add_action('admin_post_boskoa_contact_form', 'boskoa_handle_contact_form');

// reCAPTCHA v3 Keys
if (!defined('BOSKOA_RECAPTCHA_SITE_KEY')) {
    define('BOSKOA_RECAPTCHA_SITE_KEY', '6LfgF2MsAAAAAI9p2MMRZB6attYxL4Mmbim1cl_5');
}
if (!defined('BOSKOA_RECAPTCHA_SECRET_KEY')) {
    define('BOSKOA_RECAPTCHA_SECRET_KEY', '6LfgF2MsAAAAAJ6r5hLBZ7cyK_ueqogL29Y8sjXr');
}




/**
 * Registrar Custom Post Type para Paquetes Turísticos
 */
function boskoa_register_cpt_tour_package() {
    $labels = [
        'name'               => 'Paquetes Turísticos',
        'singular_name'      => 'Paquete',
        'menu_name'          => 'Tour Packages',
        'name_admin_bar'     => 'Paquete',
        'add_new'            => 'Añadir Nuevo',
        'add_new_item'       => 'Añadir Nuevo Paquete',
        'new_item'           => 'Nuevo Paquete',
        'edit_item'          => 'Editar Paquete',
        'view_item'          => 'Ver Paquete',
        'all_items'          => 'Todos los Paquetes',
        'search_items'       => 'Buscar Paquetes',
        'not_found'          => 'No se encontraron paquetes',
        'not_found_in_trash' => 'No hay paquetes en la papelera',
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => ['slug' => 'packages'],
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-palmtree',
        'supports'           => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest'       => true,
    ];

    register_post_type('tour_package', $args);
}
add_action('init', 'boskoa_register_cpt_tour_package');

/**
 * Personalizar columnas en el listado de paquetes
 */
function boskoa_tour_packages_columns($columns) {
    $new_columns = [
        'cb'          => $columns['cb'],
        'thumbnail'   => 'Imagen',
        'title'       => 'Nombre del Paquete',
        'price'       => 'Precio',
        'locations'   => 'Ubicaciones',
        'family'      => 'Familiar',
        'order'       => 'Orden',
        'date'        => 'Fecha',
    ];
    return $new_columns;
}
add_filter('manage_tour_package_posts_columns', 'boskoa_tour_packages_columns');

/**
 * Mostrar datos en las columnas personalizadas
 */
function boskoa_tour_packages_custom_column($column, $post_id) {
    switch ($column) {
        case 'thumbnail':
            $thumbnail = get_the_post_thumbnail($post_id, [60, 60]);
            echo $thumbnail ?: '<span style="color: #999;">Sin imagen</span>';
            break;
            
        case 'price':
            $price = get_post_meta($post_id, '_package_price', true);
            echo $price ? '<strong>' . esc_html($price) . '</strong>' : '<span style="color: #999;">Sin precio</span>';
            break;
            
        case 'locations':
            $locations = get_post_meta($post_id, '_package_locations', true);
            echo $locations ? esc_html($locations) : '<span style="color: #999;">0 Location</span>';
            break;
            
        case 'family':
            $is_family = get_post_meta($post_id, '_package_family_friendly', true);
            if ($is_family === 'yes') {
                echo '<span style="color: #2D8A3E;">✓ Sí</span>';
            } else {
                echo '<span style="color: #999;">No</span>';
            }
            break;
            
        case 'order':
            $order = get_post_meta($post_id, '_package_order', true);
            echo $order !== '' ? esc_html($order) : '0';
            break;
    }
}
add_action('manage_tour_package_posts_custom_column', 'boskoa_tour_packages_custom_column', 10, 2);

/**
 * Hacer las columnas ordenables
 */
function boskoa_tour_packages_sortable_columns($columns) {
    $columns['price'] = 'price';
    $columns['order'] = 'order';
    return $columns;
}
add_filter('manage_edit-tour_package_sortable_columns', 'boskoa_tour_packages_sortable_columns');

/**
 * Añadir Meta Boxes para información del paquete
 */
function boskoa_add_tour_package_metaboxes() {
    add_meta_box(
        'package_details',
        'Detalles del Paquete',
        'boskoa_package_details_callback',
        'tour_package',
        'normal',
        'high'
    );
    
    add_meta_box(
        'package_order',
        'Orden de Visualización',
        'boskoa_package_order_callback',
        'tour_package',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'boskoa_add_tour_package_metaboxes');

/**
 * Callback para Meta Box de detalles
 */
function boskoa_package_details_callback($post) {
    wp_nonce_field('boskoa_save_package_details', 'package_details_nonce');
    
    $price = get_post_meta($post->ID, '_package_price', true);
    $locations = get_post_meta($post->ID, '_package_locations', true);
    $family_friendly = get_post_meta($post->ID, '_package_family_friendly', true);
    ?>
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="package_price">Precio</label>
            </th>
            <td>
                <input type="text" 
                       id="package_price" 
                       name="package_price" 
                       value="<?php echo esc_attr($price); ?>" 
                       placeholder="$500"
                       class="regular-text">
                <p class="description">Ejemplo: $500, $1,200, etc.</p>
            </td>
        </tr>
        
        <tr>
            <th scope="row">
                <label for="package_locations">Ubicaciones</label>
            </th>
            <td>
                <input type="text" 
                       id="package_locations" 
                       name="package_locations" 
                       value="<?php echo esc_attr($locations ?: '0 Location'); ?>" 
                       placeholder="0 Location"
                       class="regular-text">
                <p class="description">Ejemplo: 3 Locations, 5 Locations, etc.</p>
            </td>
        </tr>
        
        <tr>
            <th scope="row">
                <label for="package_family_friendly">¿Apto para familias?</label>
            </th>
            <td>
                <label>
                    <input type="checkbox" 
                           id="package_family_friendly" 
                           name="package_family_friendly" 
                           value="yes"
                           <?php checked($family_friendly, 'yes'); ?>>
                    Marcar si este paquete es family-friendly
                </label>
            </td>
        </tr>
    </table>
    
    <style>
        .form-table th { width: 200px; }
        .form-table input[type="text"] { width: 100%; max-width: 400px; }
    </style>
    <?php
}

/**
 * Callback para Meta Box de orden
 */
function boskoa_package_order_callback($post) {
    wp_nonce_field('boskoa_save_package_order', 'package_order_nonce');
    $order = get_post_meta($post->ID, '_package_order', true);
    ?>
    <p>
        <label for="package_order">Orden (número):</label>
        <input type="number" 
               id="package_order" 
               name="package_order" 
               value="<?php echo esc_attr($order !== '' ? $order : 0); ?>" 
               min="0"
               style="width: 100%;">
        <small style="display: block; margin-top: 5px; color: #666;">
            Los paquetes se ordenan de menor a mayor. 0 = primero.
        </small>
    </p>
    <?php
}

/**
 * Guardar metadata del paquete
 */
function boskoa_save_tour_package_meta($post_id) {
    // Verificar nonce de detalles
    if (isset($_POST['package_details_nonce']) && wp_verify_nonce($_POST['package_details_nonce'], 'boskoa_save_package_details')) {
        if (isset($_POST['package_price'])) {
            update_post_meta($post_id, '_package_price', sanitize_text_field($_POST['package_price']));
        }
        
        if (isset($_POST['package_locations'])) {
            update_post_meta($post_id, '_package_locations', sanitize_text_field($_POST['package_locations']));
        }
        
        // Checkbox para family friendly
        if (isset($_POST['package_family_friendly'])) {
            update_post_meta($post_id, '_package_family_friendly', 'yes');
        } else {
            update_post_meta($post_id, '_package_family_friendly', 'no');
        }
    }
    
    // Verificar nonce de orden
    if (isset($_POST['package_order_nonce']) && wp_verify_nonce($_POST['package_order_nonce'], 'boskoa_save_package_order')) {
        if (isset($_POST['package_order'])) {
            update_post_meta($post_id, '_package_order', intval($_POST['package_order']));
        }
    }
}
add_action('save_post_tour_package', 'boskoa_save_tour_package_meta');

/**
 * Mensajes personalizados para el CPT
 */
function boskoa_tour_package_updated_messages($messages) {
    $post = get_post();
    
    $messages['tour_package'] = [
        0  => '',
        1  => 'Paquete actualizado.',
        2  => 'Campo personalizado actualizado.',
        3  => 'Campo personalizado eliminado.',
        4  => 'Paquete actualizado.',
        5  => isset($_GET['revision']) ? sprintf('Paquete restaurado desde la revisión del %s', wp_post_revision_title((int) $_GET['revision'], false)) : false,
        6  => 'Paquete publicado.',
        7  => 'Paquete guardado.',
        8  => 'Paquete enviado.',
        9  => sprintf('Paquete programado para: <strong>%1$s</strong>.', date_i18n('M j, Y @ G:i', strtotime($post->post_date))),
        10 => 'Borrador de paquete actualizado.',
    ];
    
    return $messages;
}
add_filter('post_updated_messages', 'boskoa_tour_package_updated_messages');

/**
 * Texto de ayuda en la pantalla de edición
 */
function boskoa_tour_package_help_text() {
    $screen = get_current_screen();
    
    if ($screen->post_type === 'tour_package') {
        echo '<div class="notice notice-info" style="margin-top: 20px;">
            <p><strong>💡 Instrucciones:</strong></p>
            <ul style="list-style: disc; margin-left: 20px;">
                <li>El <strong>título</strong> es el nombre del paquete (ej: "Relax Tour", "Adventure")</li>
                <li>La <strong>imagen destacada</strong> es la foto principal del paquete</li>
                <li>El <strong>contenido</strong> es la descripción detallada del paquete (opcional)</li>
                <li>Completa el <strong>precio</strong>, <strong>ubicaciones</strong> y marca si es <strong>family-friendly</strong></li>
                <li>Usa el <strong>orden</strong> para controlar en qué posición aparece (0 = primero)</li>
            </ul>
        </div>';
    }
}
add_action('edit_form_after_title', 'boskoa_tour_package_help_text');