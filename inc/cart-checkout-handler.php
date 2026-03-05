<?php
/**
 * Handler para el checkout del carrito
 * SEGURIDAD: Los precios se obtienen desde la BD usando el ID,
 * ignorando cualquier precio enviado desde el frontend.
 */
function boskoa_handle_cart_checkout() {

    if (!isset($_POST['contact_nonce']) || !wp_verify_nonce($_POST['contact_nonce'], 'boskoa_contact_form')) {
        wp_die('Error de seguridad. Por favor, intenta nuevamente.');
    }

    if (!isset($_POST['recaptcha_token']) || empty($_POST['recaptcha_token'])) {
        wp_redirect(add_query_arg('cart_contact', 'error', wp_get_referer()));
        exit;
    }

    if (!boskoa_verify_recaptcha($_POST['recaptcha_token'])) {
        error_log('reCAPTCHA: Verificación fallida para IP: ' . $_SERVER['REMOTE_ADDR']);
        wp_redirect(add_query_arg('cart_contact', 'error', wp_get_referer()));
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        wp_die('Método no permitido');
    }

    $name    = sanitize_text_field($_POST['contact_name']);
    $email   = sanitize_email($_POST['contact_email']);
    $message = sanitize_textarea_field($_POST['contact_message']);
    $phone   = isset($_POST['contact_phone_full']) ? sanitize_text_field($_POST['contact_phone_full']) : '';
    $lang    = isset($_POST['current_lang']) ? sanitize_text_field($_POST['current_lang']) : 'en';

    if (empty($phone)) {
        $phone = isset($_POST['contact_phone']) ? sanitize_text_field($_POST['contact_phone']) : '';
    }

    $cart_items_raw = isset($_POST['cart_items']) ? $_POST['cart_items'] : '[]';
    $cart_items     = json_decode(stripslashes($cart_items_raw), true);

    if (!is_array($cart_items)) {
        $cart_items = [];
    }

    $errors = [];
    if (empty($name))                       $errors[] = 'El nombre es requerido';
    if (empty($email) || !is_email($email)) $errors[] = 'Email inválido';
    if (empty($message))                    $errors[] = 'El mensaje es requerido';
    if (empty($cart_items))                 $errors[] = 'El carrito está vacío';

    if (!empty($errors)) {
        wp_redirect(add_query_arg('cart_contact', 'error', wp_get_referer()));
        exit;
    }

    $verified_items = [];
    $grand_total    = 0;

    foreach ($cart_items as $item) {
        $item_id       = intval($item['id'] ?? 0);
        $item_quantity = intval($item['quantity'] ?? 1);

        if ($item_id <= 0) continue;
        if ($item_quantity < 1) $item_quantity = 1;

        $real_price = floatval(get_field('precio', $item_id));
        $real_title = get_the_title($item_id);

        if (!$real_title || $real_price <= 0) continue;

        $subtotal = $real_price * $item_quantity;
        $grand_total += $subtotal;

        $verified_items[] = [
            'id'       => $item_id,
            'title'    => $real_title,
            'price'    => $real_price,
            'quantity' => $item_quantity,
            'subtotal' => $subtotal,
        ];
    }

    if (empty($verified_items)) {
        wp_redirect(add_query_arg('cart_contact', 'error', wp_get_referer()));
        exit;
    }

    // Cambiar idioma de Polylang al idioma del cliente para las traducciones
    if (function_exists('pll_switch_language')) {
        pll_switch_language($lang);
    }

    $subject_email = pll__('Booking confirmation - Boskoa Travels');
    $greeting      = pll__('Hello');
    $received      = pll__('We have received your booking request and will get back to you shortly.');
    $summary       = pll__('Your order summary:');
    $total_label   = pll__('Estimated total');
    $regards       = pll__('Best regards,');
    $team          = pll__('The Boskoa Travels team');

    $admin_email = 'uriu1206@gmail.com';
    $subject     = 'Cart Booking Request - ' . $name . ' (' . count($verified_items) . ' items)';

    $items_html = '';
    foreach ($verified_items as $item) {
        $items_html .= '
        <tr>
            <td style="padding: 8px; border-bottom: 1px solid #eee;">' . esc_html($item['title']) . '</td>
            <td style="padding: 8px; border-bottom: 1px solid #eee; text-align:center;">' . $item['quantity'] . '</td>
            <td style="padding: 8px; border-bottom: 1px solid #eee; text-align:right;">$' . number_format($item['price'], 2) . '</td>
            <td style="padding: 8px; border-bottom: 1px solid #eee; text-align:right;"><strong>$' . number_format($item['subtotal'], 2) . '</strong></td>
        </tr>';
    }

    $admin_body = '
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; background: #f4f4f4; }
        .content { background: #ffffff; padding: 30px; border-radius: 8px; }
        .header { background: #2D8A3E; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .field { padding: 10px; background: #f9f9f9; border-left: 4px solid #2D8A3E; margin-bottom: 10px; }
        .field strong { color: #2D8A3E; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th { background: #2D8A3E; color: white; padding: 10px; text-align: left; }
        .total-row td { font-size: 1.1em; background: #f0f8f1; }
        .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>🛒 Nueva Reserva desde el Carrito</h2>
            <p>Boskoa Travels - Costa Rica</p>
        </div>
        <div class="content">
            <p>Has recibido una nueva solicitud de reserva desde el carrito:</p>
            <div class="field"><strong>Nombre:</strong><br>' . esc_html($name) . '</div>
            <div class="field"><strong>Email:</strong><br><a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a></div>
            <div class="field"><strong>Teléfono:</strong><br>' . esc_html($phone ?: 'No proporcionado') . '</div>
            <div class="field"><strong>Mensaje:</strong><br>' . nl2br(esc_html($message)) . '</div>
            <div class="field"><strong>Idioma del cliente:</strong><br>' . esc_html(strtoupper($lang)) . '</div>

            <h3 style="color:#2D8A3E; margin-top:20px;">Items del Carrito</h3>
            <table>
                <thead>
                    <tr>
                        <th>Actividad</th>
                        <th style="text-align:center;">Personas</th>
                        <th style="text-align:right;">Precio/persona</th>
                        <th style="text-align:right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    ' . $items_html . '
                    <tr class="total-row">
                        <td colspan="3" style="padding:10px; text-align:right;"><strong>TOTAL:</strong></td>
                        <td style="padding:10px; text-align:right;"><strong>$' . number_format($grand_total, 2) . '</strong></td>
                    </tr>
                </tbody>
            </table>

            <div class="footer">
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

    $sent = wp_mail($admin_email, $subject, $admin_body, $headers);

    if ($sent) {

        $items_list = '';
        foreach ($verified_items as $item) {
            $items_list .= '<p>• ' . esc_html($item['title']) . ' × ' . $item['quantity']
                . ' = $' . number_format($item['subtotal'], 2) . '</p>';
        }

        $confirmation_body = '
<html>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="background: #2D8A3E; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;">
            <h2>' . esc_html($subject_email) . '</h2>
        </div>
        <div style="background: #ffffff; padding: 30px; border: 1px solid #ddd; border-top: none; border-radius: 0 0 8px 8px;">
            <p>' . esc_html($greeting) . ' <strong>' . esc_html($name) . '</strong>,</p>
            <p>' . esc_html($received) . '</p>
            <p><strong>' . esc_html($summary) . '</strong></p>
            <div style="background: #f9f9f9; padding: 15px; border-left: 4px solid #2D8A3E; margin: 20px 0;">
                ' . $items_list . '
                <p><strong>' . esc_html($total_label) . ': $' . number_format($grand_total, 2) . '</strong></p>
            </div>
            <p>' . esc_html($regards) . '<br><strong>' . esc_html($team) . '</strong></p>
        </div>
        <div style="text-align: center; margin-top: 20px; color: #666; font-size: 12px;">
            <p>Boskoa Travels - Costa Rica<br>
            <a href="' . home_url() . '">' . home_url() . '</a></p>
        </div>
    </div>
</body>
</html>';

        wp_mail($email, $subject_email, $confirmation_body, [
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . get_bloginfo('name') . ' <' . $admin_email . '>'
        ]);
    }

    if ($sent) {
        wp_redirect(add_query_arg('cart_contact', 'success', wp_get_referer()));
    } else {
        wp_redirect(add_query_arg('cart_contact', 'error', wp_get_referer()));
    }
    exit;
}

add_action('admin_post_nopriv_boskoa_cart_checkout', 'boskoa_handle_cart_checkout');
add_action('admin_post_boskoa_cart_checkout', 'boskoa_handle_cart_checkout');