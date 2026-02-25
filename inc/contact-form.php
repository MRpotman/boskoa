<?php
function boskoa_handle_contact_form() {
    // Verificar nonce de seguridad
    if (!isset($_POST['contact_nonce']) || !wp_verify_nonce($_POST['contact_nonce'], 'boskoa_contact_form')) {
        wp_die('Error de seguridad. Por favor, intenta nuevamente.');
    }

   
    if (!isset($_POST['recaptcha_token']) || empty($_POST['recaptcha_token'])) {
        wp_redirect(add_query_arg('contact', 'error', wp_get_referer()));
        exit;
    }
    
    if (!boskoa_verify_recaptcha($_POST['recaptcha_token'])) {
        error_log('reCAPTCHA: Verificación fallida para IP: ' . $_SERVER['REMOTE_ADDR']);
        wp_redirect(add_query_arg('contact', 'error', wp_get_referer()));
        exit;
    }


    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        wp_die('Método no permitido');
    }


$name    = sanitize_text_field($_POST['contact_name']);
$email   = sanitize_email($_POST['contact_email']);
$message = sanitize_textarea_field($_POST['contact_message']);
$phone   = isset($_POST['contact_phone_full']) ? sanitize_text_field($_POST['contact_phone_full']) : '';
$activity_id = isset($_POST['activity_id']) ? intval($_POST['activity_id']) : 0;
$post_type = get_post_type($activity_id);
$is_package = ($post_type === 'tour_package');
$persons     = isset($_POST['persons']) ? intval($_POST['persons']) : 1;
$title = get_the_title($activity_id);
$booking = "Booking: " . $title;

if (empty($phone)) {
    $phone = isset($_POST['contact_phone']) ? sanitize_text_field($_POST['contact_phone']) : '';
}

$activity_title = get_the_title($activity_id);
$post_type = get_post_type($activity_id);
$is_package = ($post_type === 'tour_package');
$item_label = $is_package ? 'Package' : 'Activity';


$persons     = isset($_POST['persons']) ? intval($_POST['persons']) : 1;

if ($persons < 1) {
    $persons = 1;
}
$real_base_price = get_field('precio', $activity_id);

if (!$real_base_price) {
    $real_base_price = 0;
}

$real_total = floatval($real_base_price) * $persons;

    // Validaciones
    $errors = [];
    
    if (empty($name)) {
        $errors[] = 'El nombre es requerido';
    }
    
    if (empty($email) || !is_email($email)) {
        $errors[] = 'Email inválido';
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

    $admin_email = 'uriu1206@gmail.com';
    

$subject = $item_label . ' Booking: ' . $activity_title;

if (!$is_package) {
    $subject .= ' (' . $persons . ' personas)';
}
$package_activities_html = '';

if ($is_package) {

    $included_activities = get_field('actividades_asociadas', $activity_id);

    if (!empty($included_activities) && is_array($included_activities)) {

        $package_activities_html .= '<div class="field">';
        $package_activities_html .= '<strong>Activities Incluye:</strong><br><ul style="padding-left:15px;">';

        $activities_sum = 0;

        foreach ($included_activities as $activity) {

            if (is_object($activity)) {
                $act_id = $activity->ID;
            } elseif (is_array($activity)) {
                $act_id = $activity['ID'];
            } else {
                $act_id = intval($activity);
            }

            if (!$act_id) continue;

            $act_title = get_the_title($act_id);
            $act_price = floatval(get_field('precio', $act_id));

            $activities_sum += $act_price;

            $package_activities_html .= '<li>'
                . esc_html($act_title)
                . ' - $' . number_format($act_price, 2)
                . '</li>';
        }

        $package_activities_html .= '</ul>';

        $package_activities_html .= '<p><strong>suma de actividades:</strong> $'
            . number_format($activities_sum, 2)
            . '</p>';
        
        $package_activities_html .= '<div class="field">
                    <strong>' . $item_label . ' Detalles:</strong><br>
                    Personas: ' . esc_html($persons) . ' - Total: $' . number_format($real_total, 2) . '
                    <br><span>Base price per person: $' . number_format($real_base_price, 2) . '</span>
                </div>';
        $package_activities_html .= '<p><strong>Precio de paquete:</strong> $'
            . number_format($real_base_price, 2)
            . '</p>';

        $package_activities_html .= '</div>';
    }
}

    $body = '
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: #f4f4f4;
        }

        .content {
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
        }

        .header {
            background: #2D8A3E;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .field {
            padding: 10px;
            background: #f9f9f9;
            border-left: 4px solid #2D8A3E;
        }

        .field strong {
            color: #2D8A3E;
        }

        .email {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 12px;
        }

        .email-logo .logo {
            font-weight: 400;
            font-style: normal;
            font-size: 2.2rem;
            text-decoration: none;
            display: flex;
            color: white;
            transition: color 0.3s ease;
            flex-direction: column;
            line-height: 1.2;
        }

        .email-logo .logo-content {
            z-index: 999;
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .email-logo .logo-image {
            height: 56px;
            width: auto;
        }

        .email-logo .tiquicia {
            display: flex;
        }

        .email-logo .logo-divider svg {
            width: 110px;
            height: 32px;
        }

        .email-logo .subtitle {
            font-size: 16px;
            color: rgb(255, 255, 255);
            transition: color 0.3s ease;
        }

        .email-logo {
            display: flex;
            align-items: flex-start;
                gap: 50px;
        }

        .email-logo .logo-link {
            display: block;
            text-decoration: none;
            transition: transform 0.3s ease;
        }

        .email-logo .logo-link:hover {
            transform: scale(1.05);
        }

        .email-logo-img {
            width: 100%;
            max-width: 420px;
            height: auto;
            display: block;
        }
    </style>
</head>

<body>
    <div class="container">

        <div class="header">
            <div class="email-column email-logo">
                <div class="site-branding">
                        <div class="logo-content">
                            <img src="assets/img/boskoa-logo.png"
                                alt="Boskoa Travel Logo" class="logo-image">
                        </div>
                </div>
            
            <h2>Nuevo Mensaje de Contacto</h2></div>
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
                <strong>Teléfono:</strong><br>
                ' . esc_html($phone) . '
            </div>
            <div class="field">
                <strong>Asunto:</strong><br>
                ' . esc_html($subject) . '
            </div>

            <div class="field">
                <strong>Mensaje:</strong><br>
                ' . nl2br(esc_html($message)) . '
            </div>
                ' . ($is_package ? $package_activities_html : '
                <div class="field">
                    <strong>' . $item_label . ' Detalles:</strong><br>
                    Personas: ' . esc_html($persons) . ' - Total: $' . number_format($real_total, 2) . '
                    <br><span>Precio por persona: $' . number_format($real_base_price, 2) . '</span>
                </div>
                ') . '

            <div class="email">
                <p>Este mensaje fue enviado desde: <a href="' . home_url() . '">' . get_bloginfo('name') . '</a></p>
                <p>Fecha: ' . date_i18n(get_option('date_format') . ' ' . get_option('time_format')) . '</p>
            </div>
        </div>
    </div>
</body>

</html>
    ';

    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . get_bloginfo('name') . ' <' . $admin_email . '>',
        'Reply-To: ' . $name . ' <' . $email . '>'
    ];


    $sent = wp_mail($admin_email, $subject, $body, $headers);

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
                        <p><strong>Asunto:</strong> ' . esc_html($subject) . '</p>
                        <p><strong>Mensaje:</strong><br>' . nl2br(esc_html($message)) . '</p>
                        <p><strong>' . $item_label . ':</strong> ' . esc_html($activity_title) . '</p>
                        <p><strong>Personas:</strong> ' . esc_html($persons) . '</p>
                        <p><strong>Precio por persona:</strong> $' . number_format($real_base_price, 2) . '</p>
                        <p><strong>Total estimado:</strong> $' . number_format($real_total, 2) . '</p>
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