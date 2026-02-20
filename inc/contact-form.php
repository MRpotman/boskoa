<?php
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
    $admin_email = 'uriu1206@gmail.com';
    

    $subject = 'Nuevo mensaje de contacto: ' . $matters;

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
                <strong>Asunto:</strong><br>
                ' . esc_html($matters) . '
            </div>

            <div class="field">
                <strong>Mensaje:</strong><br>
                ' . nl2br(esc_html($message)) . '
            </div>

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