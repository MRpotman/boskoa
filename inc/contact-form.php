<?php
function boskoa_handle_contact_form() {

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

    $name        = sanitize_text_field($_POST['contact_name']);
    $email       = sanitize_email($_POST['contact_email']);
    $message     = sanitize_textarea_field($_POST['contact_message']);
    $phone       = isset($_POST['contact_phone_full']) ? sanitize_text_field($_POST['contact_phone_full']) : '';
    $lang        = isset($_POST['current_lang']) ? sanitize_text_field($_POST['current_lang']) : 'en';
    $activity_id = isset($_POST['activity_id']) ? intval($_POST['activity_id']) : 0;
    $persons     = isset($_POST['persons']) ? intval($_POST['persons']) : 1;

    if (empty($phone)) {
        $phone = isset($_POST['contact_phone']) ? sanitize_text_field($_POST['contact_phone']) : '';
    }

    if ($persons < 1) $persons = 1;

    $activity_title  = get_the_title($activity_id);
    $post_type       = get_post_type($activity_id);
    $is_package      = ($post_type === 'tour_package');
    $item_label      = $is_package ? 'Package' : 'Activity'; // solo para email admin
    $real_base_price = floatval(get_field('precio', $activity_id));
    $real_total      = $real_base_price * $persons;

    $errors = [];
    if (empty($name))                       $errors[] = 'El nombre es requerido';
    if (empty($email) || !is_email($email)) $errors[] = 'Email inválido';
    if (empty($message))                    $errors[] = 'El mensaje es requerido';

    if (!empty($errors)) {
        wp_redirect(add_query_arg('contact', 'error', wp_get_referer()));
        exit;
    }

    // Traducciones Polylang en idioma del cliente
    if (function_exists('pll_switch_language')) {
        pll_switch_language($lang);
    }

    $t_thank_you      = pll__('Thank you for contacting us!');
    $t_received       = pll__('We have received your booking request and will get back to you shortly.');
    $t_summary        = pll__('Summary of your message:');
    $t_subject_label  = pll__('Subject');
    $t_persons        = pll__('Persons');
    $t_price_per      = pll__('Price per person');
    $t_total          = pll__('Estimated total');
    $t_item_label     = $is_package ? pll__('Package') : pll__('Activity');
    $t_regards        = pll__('Best regards,');
    $t_team           = pll__('The Boskoa Travels team');
    $t_conf_subject   = pll__('Booking confirmation - Boskoa Travels');

    $admin_email = 'uriu1206@gmail.com';
    $subject     = $item_label . ' Booking: ' . $activity_title;

    if (!$is_package) {
        $subject .= ' (' . $persons . ' personas)';
    }

    // --- Actividades del paquete (email admin, en español está bien) ---
    $package_activities_html = '';

    if ($is_package) {
        $included_activities = get_field('actividades_asociadas', $activity_id);

        if (!empty($included_activities) && is_array($included_activities)) {
            $package_activities_html .= '<div class="field">';
            $package_activities_html .= '<strong>Activities Incluidas:</strong><br><ul style="padding-left:15px;">';

            $activities_sum = 0;

            foreach ($included_activities as $activity) {
                if (is_object($activity))      $act_id = $activity->ID;
                elseif (is_array($activity))   $act_id = $activity['ID'];
                else                           $act_id = intval($activity);

                if (!$act_id) continue;

                $act_title = get_the_title($act_id);
                $act_price = floatval(get_field('precio', $act_id));
                $activities_sum += $act_price;

                $package_activities_html .= '<li>' . esc_html($act_title) . ' - $' . number_format($act_price, 2) . '</li>';
            }

            $package_activities_html .= '</ul>';
            $package_activities_html .= '<p><strong>Suma de actividades:</strong> $' . number_format($activities_sum, 2) . '</p>';
            $package_activities_html .= '<div class="field">
                <strong>' . $item_label . ' Detalles:</strong><br>
                Personas: ' . esc_html($persons) . ' - Total: $' . number_format($real_total, 2) . '
                <br><span>Precio del paquete por persona: $' . number_format($real_base_price, 2) . '</span>
            </div>';
            $package_activities_html .= '<p><strong>Total del paquete:</strong> $' . number_format($real_total, 2) . '</p>';
            $package_activities_html .= '</div>';
        }
    }

    // --- Email al admin (en español, interno) ---
    $body = '
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; background: #f4f4f4; }
        .content { background: #ffffff; padding: 30px; border-radius: 8px; }
        .header { background: #2D8A3E; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .field { padding: 10px; background: #f9f9f9; border-left: 4px solid #2D8A3E; margin-bottom: 10px; }
        .field strong { color: #2D8A3E; }
        .email { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Nuevo Mensaje de Contacto</h2>
            <p>Boskoa Travels - Costa Rica</p>
        </div>
        <div class="content">
            <p>Has recibido un nuevo mensaje desde el formulario de contacto:</p>
            <div class="field"><strong>Nombre:</strong><br>' . esc_html($name) . '</div>
            <div class="field"><strong>Email:</strong><br><a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a></div>
            <div class="field"><strong>Teléfono:</strong><br>' . esc_html($phone ?: 'No proporcionado') . '</div>
            <div class="field"><strong>Asunto:</strong><br>' . esc_html($subject) . '</div>
            <div class="field"><strong>Mensaje:</strong><br>' . nl2br(esc_html($message)) . '</div>
            <div class="field"><strong>Idioma del cliente:</strong><br>' . esc_html(strtoupper($lang)) . '</div>
            ' . ($is_package ? $package_activities_html : '
            <div class="field">
                <strong>' . $item_label . ' Detalles:</strong><br>
                Personas: ' . esc_html($persons) . ' - Total: $' . number_format($real_total, 2) . '
                <br><span>Precio por persona: $' . number_format($real_base_price, 2) . '</span>
            </div>') . '
            <div class="email">
                <p>Enviado desde: <a href="' . home_url() . '">' . get_bloginfo('name') . '</a></p>
                <p>Fecha: ' . date_i18n(get_option('date_format') . ' ' . get_option('time_format')) . '</p>
            </div>
        </div>
    </div>
</body>
</html>';

    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . get_bloginfo('name') . ' <' . $admin_email . '>',
        'Reply-To: ' . $name . ' <' . $email . '>'
    ];

    $sent = wp_mail($admin_email, $subject, $body, $headers);

    // --- Email de confirmación al cliente (en su idioma) ---
    if ($sent) {
        $confirmation_body = '
<html>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="background: #2D8A3E; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;">
            <h2>' . esc_html($t_thank_you) . '</h2>
        </div>
        <div style="background: #ffffff; padding: 30px; border: 1px solid #ddd; border-top: none; border-radius: 0 0 8px 8px;">
            <p>' . esc_html(pll__('Hello')) . ' <strong>' . esc_html($name) . '</strong>,</p>
            <p>' . esc_html($t_received) . '</p>
            <p><strong>' . esc_html($t_summary) . '</strong></p>
            <div style="background: #f9f9f9; padding: 15px; border-left: 4px solid #2D8A3E; margin: 20px 0;">
                <p><strong>' . esc_html($t_subject_label) . ':</strong> ' . esc_html($subject) . '</p>
                <p><strong>' . esc_html(pll__('Message')) . ':</strong><br>' . nl2br(esc_html($message)) . '</p>
                <p><strong>' . esc_html($t_item_label) . ':</strong> ' . esc_html($activity_title) . '</p>
                <p><strong>' . esc_html($t_persons) . ':</strong> ' . esc_html($persons) . '</p>
                <p><strong>' . esc_html($t_price_per) . ':</strong> $' . number_format($real_base_price, 2) . '</p>
                <p><strong>' . esc_html($t_total) . ':</strong> $' . number_format($real_total, 2) . '</p>
            </div>
            <p>' . esc_html($t_regards) . '<br><strong>' . esc_html($t_team) . '</strong></p>
        </div>
        <div style="text-align: center; margin-top: 20px; color: #666; font-size: 12px;">
            <p>Boskoa Travels - Costa Rica<br>
            <a href="' . home_url() . '">' . home_url() . '</a></p>
        </div>
    </div>
</body>
</html>';

        wp_mail($email, $t_conf_subject, $confirmation_body, [
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . get_bloginfo('name') . ' <' . $admin_email . '>'
        ]);
    }

    if ($sent) {
        wp_redirect(add_query_arg('contact', 'success', wp_get_referer()));
    } else {
        wp_redirect(add_query_arg('contact', 'error', wp_get_referer()));
    }
    exit;
}

add_action('admin_post_nopriv_boskoa_contact_form', 'boskoa_handle_contact_form');
add_action('admin_post_boskoa_contact_form', 'boskoa_handle_contact_form');

// ===============================
// TRANSPORT FORM HANDLER
// ===============================
