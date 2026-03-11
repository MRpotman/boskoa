<?php
/**
 * Handler para el formulario de reserva de transporte
 * Action: boskoa_transport_form
 *
 * trip_type values:
 *   arrival    → solo recogida en aeropuerto  (campos: t_arrival_date, t_arrival_time, flight_number, airline)
 *   departure  → solo llevar al aeropuerto    (campos: t_dep_date, t_dep_time, dep_flight_number, dep_airline)
 *   round_trip → ambos servicios              (todos los campos anteriores)
 */

function boskoa_handle_transport_form() {

    // ── Seguridad ────────────────────────────────────────────────────────────
    if ( ! isset( $_POST['contact_nonce'] ) ||
         ! wp_verify_nonce( $_POST['contact_nonce'], 'boskoa_transport_form' ) ) {
        wp_die( 'Error de seguridad. Por favor, intenta nuevamente.' );
    }

    if ( $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
        wp_die( 'Método no permitido' );
    }

    if ( defined('WP_ENVIRONMENT_TYPE') && WP_ENVIRONMENT_TYPE === 'local' ) {
        // reCAPTCHA omitido en entorno local
    } elseif ( empty( $_POST['recaptcha_token'] ) ||
               ! boskoa_verify_recaptcha( $_POST['recaptcha_token'] ) ) {
        wp_redirect( add_query_arg( 'contact', 'error', wp_get_referer() ) );
        exit;
    }

    // ── Sanitización de datos ────────────────────────────────────────────────
    $transport_id      = isset( $_POST['transport_id'] ) ? intval( $_POST['transport_id'] ) : 0;
    $name              = sanitize_text_field( $_POST['contact_name']       ?? '' );
    $email             = sanitize_email(      $_POST['contact_email']      ?? '' );
    $phone             = sanitize_text_field( $_POST['contact_phone_full'] ?? $_POST['contact_phone'] ?? '' );
    $passengers        = max( 1, intval(      $_POST['passengers']         ?? 1 ) );
    $luggage           = intval(              $_POST['luggage']            ?? 0 );
    $trip_type         = sanitize_text_field( $_POST['trip_type']          ?? 'arrival' );
    $message           = sanitize_textarea_field( $_POST['contact_message'] ?? '' );
    $lang              = sanitize_text_field( $_POST['current_lang']       ?? 'en' );

    // Campos Arrival Flight
    $flight_number     = sanitize_text_field( $_POST['flight_number']      ?? '' );
    $airline           = sanitize_text_field( $_POST['airline']            ?? '' );
    $arrival_date      = sanitize_text_field( $_POST['t_arrival_date']     ?? '' );
    $arrival_time      = sanitize_text_field( $_POST['t_arrival_time']     ?? '' );

    // Campos Departure Flight
    $dep_flight_number = sanitize_text_field( $_POST['dep_flight_number']  ?? '' );
    $dep_airline       = sanitize_text_field( $_POST['dep_airline']        ?? '' );
    $dep_date          = sanitize_text_field( $_POST['t_dep_date']         ?? '' );
    $dep_time          = sanitize_text_field( $_POST['t_dep_time']         ?? '' );

    // ── Validación básica ────────────────────────────────────────────────────
    // Se requiere al menos la fecha correspondiente al tipo de viaje elegido
    $has_arrival_date   = ! empty( $arrival_date );
    $has_departure_date = ! empty( $dep_date );

    $valid_arrival   = ( $trip_type === 'arrival'   && $has_arrival_date );
    $valid_departure = ( $trip_type === 'departure' && $has_departure_date );
    $valid_round     = ( $trip_type === 'round_trip' && $has_arrival_date && $has_departure_date );

    if ( empty( $name ) || empty( $email ) || ! is_email( $email ) ||
         ( ! $valid_arrival && ! $valid_departure && ! $valid_round ) ) {
        wp_redirect( add_query_arg( 'contact', 'error', wp_get_referer() ) );
        exit;
    }

    // ── Datos del transporte ─────────────────────────────────────────────────
    $transport_title = get_the_title( $transport_id );
    $origin          = get_field( 'origen', $transport_id );
    $destination     = get_field( 'destino', $transport_id );
    $route_label     = ( $origin && $destination ) ? "$origin → $destination" : $transport_title;

    // ── Traducciones ─────────────────────────────────────────────────────────
    if ( $lang === 'es' ) {
        $t_subject         = 'Reserva de Transporte';
        $t_thank_you       = '¡Gracias por tu reserva!';
        $t_received        = 'Hemos recibido tu solicitud y pronto nos pondremos en contacto.';
        $t_regards         = 'Saludos cordiales,';
        $t_team            = 'El equipo de Boskoa Travels';
        $t_conf_subject    = 'Confirmación de reserva de transporte - Boskoa Travels';
        $t_hello           = 'Hola';
        $t_trip_type       = 'Tipo de viaje';
        $t_passengers      = 'Pasajeros';
        $t_luggage_lbl     = 'Equipajes';
        $t_arrival_lbl     = 'Llegada';
        $t_departure_lbl   = 'Salida';
        $t_flight          = 'Vuelo';
        $t_notes           = 'Notas';
        $t_arrival_type    = 'Llegada (recogida)';
        $t_departure_type  = 'Salida (llevar al aeropuerto)';
        $t_round_trip_type = 'Ida y vuelta';
    } else {
        $t_subject         = 'Transport Booking';
        $t_thank_you       = 'Thank you for your booking!';
        $t_received        = 'We have received your request and will get back to you shortly.';
        $t_regards         = 'Best regards,';
        $t_team            = 'The Boskoa Travels team';
        $t_conf_subject    = 'Transport booking confirmation - Boskoa Travels';
        $t_hello           = 'Hello';
        $t_trip_type       = 'Trip type';
        $t_passengers      = 'Passengers';
        $t_luggage_lbl     = 'Luggage';
        $t_arrival_lbl     = 'Arrival';
        $t_departure_lbl   = 'Departure';
        $t_flight          = 'Flight';
        $t_notes           = 'Notes';
        $t_arrival_type    = 'Arrival (pickup)';
        $t_departure_type  = 'Departure (drop-off)';
        $t_round_trip_type = 'Round Trip';
    }

    $trip_labels = [
        'arrival'    => $t_arrival_type,
        'departure'  => $t_departure_type,
        'round_trip' => $t_round_trip_type,
    ];
    $trip_label = $trip_labels[ $trip_type ] ?? $trip_type;

    // ── Construye los bloques de vuelo para el email ─────────────────────────
    $arrival_block   = '';
    $departure_block = '';

    if ( $trip_type === 'arrival' || $trip_type === 'round_trip' ) {
        $arrival_block = "
        <div class='field'>
            <strong>{$t_arrival_lbl}:</strong><br>
            " . esc_html( $arrival_date ) . " " . esc_html( $arrival_time ) . "<br>
            {$t_flight}: " . esc_html( $flight_number ) . " – " . esc_html( $airline ) . "
        </div>";
    }

    if ( $trip_type === 'departure' || $trip_type === 'round_trip' ) {
        $departure_block = "
        <div class='field'>
            <strong>{$t_departure_lbl}:</strong><br>
            " . esc_html( $dep_date ) . " " . esc_html( $dep_time ) . "<br>
            {$t_flight}: " . esc_html( $dep_flight_number ) . " – " . esc_html( $dep_airline ) . "
        </div>";
    }

    // ── Email al administrador ───────────────────────────────────────────────
    $admin_email = 'camiloredondo5@gmail.com';
    $subject     = "$t_subject: $route_label ($trip_label)";

    $body = "
<html>
<head>
<style>
    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
    .container { max-width: 600px; margin: 0 auto; padding: 20px; background: #f4f4f4; }
    .content { background: #fff; padding: 30px; border-radius: 8px; }
    .header { background: #2D8A3E; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
    .field { padding: 10px; background: #f9f9f9; border-left: 4px solid #2D8A3E; margin-bottom: 10px; }
    .field strong { color: #2D8A3E; }
    .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
</style>
</head>
<body>
<div class='container'>
    <div class='header'>
        <h2>New Transport Booking</h2>
        <p>Boskoa Travels – Costa Rica</p>
    </div>
    <div class='content'>
        <div class='field'><strong>Name:</strong><br>" . esc_html( $name ) . "</div>
        <div class='field'><strong>Email:</strong><br><a href='mailto:" . esc_attr( $email ) . "'>" . esc_html( $email ) . "</a></div>
        <div class='field'><strong>Phone:</strong><br>" . esc_html( $phone ?: 'Not provided' ) . "</div>
        <div class='field'><strong>Route:</strong><br>" . esc_html( $route_label ) . "</div>
        <div class='field'>
            <strong>{$t_trip_type}:</strong> " . esc_html( $trip_label ) . " &nbsp;|&nbsp;
            <strong>{$t_passengers}:</strong> " . esc_html( $passengers ) . " &nbsp;|&nbsp;
            <strong>{$t_luggage_lbl}:</strong> " . esc_html( $luggage ) . "
        </div>
        {$arrival_block}
        {$departure_block}
        <div class='field'><strong>{$t_notes}:</strong><br>" . nl2br( esc_html( $message ?: '–' ) ) . "</div>
        <div class='field'><strong>Lang:</strong><br>" . esc_html( strtoupper( $lang ) ) . "</div>
    </div>
    <div class='footer'>
        <p>Sent from: <a href='" . home_url() . "'>" . home_url() . "</a></p>
        <p>" . date_i18n( get_option('date_format') . ' ' . get_option('time_format') ) . "</p>
    </div>
</div>
</body>
</html>";

    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . get_bloginfo('name') . ' <' . $admin_email . '>',
        'Reply-To: ' . $name . ' <' . $email . '>',
    ];

    $sent = wp_mail( $admin_email, $subject, $body, $headers );

    // ── Email de confirmación al cliente ─────────────────────────────────────
    if ( $sent ) {

        // Bloque de resumen para el cliente (solo la fecha relevante)
        $client_flight_summary = '';
        if ( $trip_type === 'arrival' || $trip_type === 'round_trip' ) {
            $client_flight_summary .= "<p><strong>{$t_arrival_lbl}:</strong> " . esc_html( $arrival_date ) . " " . esc_html( $arrival_time ) . "</p>";
        }
        if ( $trip_type === 'departure' || $trip_type === 'round_trip' ) {
            $client_flight_summary .= "<p><strong>{$t_departure_lbl}:</strong> " . esc_html( $dep_date ) . " " . esc_html( $dep_time ) . "</p>";
        }

        $conf_body = "
<html>
<body style='font-family:Arial,sans-serif;line-height:1.6;color:#333;'>
<div style='max-width:600px;margin:0 auto;padding:20px;'>
    <div style='background:#2D8A3E;color:white;padding:20px;text-align:center;border-radius:8px 8px 0 0;'>
        <h2>" . esc_html( $t_thank_you ) . "</h2>
    </div>
    <div style='background:#fff;padding:30px;border:1px solid #ddd;border-top:none;border-radius:0 0 8px 8px;'>
        <p>" . esc_html( $t_hello ) . " <strong>" . esc_html( $name ) . "</strong>,</p>
        <p>" . esc_html( $t_received ) . "</p>
        <div style='background:#f9f9f9;padding:15px;border-left:4px solid #2D8A3E;margin:20px 0;'>
            <p><strong>Route:</strong> " . esc_html( $route_label ) . "</p>
            <p><strong>{$t_trip_type}:</strong> " . esc_html( $trip_label ) . "</p>
            <p><strong>{$t_passengers}:</strong> " . esc_html( $passengers ) . "</p>
            {$client_flight_summary}
        </div>
        <p>" . esc_html( $t_regards ) . "<br><strong>" . esc_html( $t_team ) . "</strong></p>
    </div>
    <div style='text-align:center;margin-top:20px;color:#666;font-size:12px;'>
        <p>Boskoa Travels – Costa Rica<br>
        <a href='" . home_url() . "'>" . home_url() . "</a></p>
    </div>
</div>
</body>
</html>";

        wp_mail( $email, $t_conf_subject, $conf_body, [
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . get_bloginfo('name') . ' <' . $admin_email . '>',
        ]);
    }

    wp_redirect( add_query_arg( 'contact', $sent ? 'success' : 'error', wp_get_referer() ) );
    exit;
}

add_action( 'admin_post_nopriv_boskoa_transport_form', 'boskoa_handle_transport_form' );
add_action( 'admin_post_boskoa_transport_form',        'boskoa_handle_transport_form' );