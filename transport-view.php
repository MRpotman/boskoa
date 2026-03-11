<?php
/**
 * Template Name: Transport View
 *
 * Vista detallada de una opción de transporte.
 * URL: /transport-view/?transport_id=123
 */

get_header();

$transport_id = isset($_GET['transport_id']) ? intval($_GET['transport_id']) : 0;

if (function_exists('pll_get_post') && $transport_id) {
    $current_lang  = pll_current_language();
    $translated_id = pll_get_post($transport_id, $current_lang);
    if ($translated_id) {
        $transport_id = $translated_id;
    }
}

if (!$transport_id) {
    echo '<h2 style="text-align:center;padding:80px 20px;">' . esc_html(pll__('Transport option not found.')) . '</h2>';
    get_footer();
    return;
}

// ── Campos ACF ──────────────────────────────────────────────────────────────
$title       = get_field('titulo', $transport_id) ?: get_the_title($transport_id);
$description = get_field('descripcion', $transport_id);
$origin      = get_field('origen', $transport_id);
$destination = get_field('destino', $transport_id);
$route_type  = get_field('tipo_ruta', $transport_id);
$included    = get_field('articulos_incluidos', $transport_id);
$add_info    = get_field('informacion_adicional', $transport_id);
$meeting_pt  = get_field('punto_de_encuentro', $transport_id);
$meeting_lnk = get_field('encuentro_link', $transport_id);

// Imagen
$image = get_field('imagen', $transport_id);
if (is_array($image))       { $image = $image['url']; }
elseif (is_numeric($image)) { $image = wp_get_attachment_image_url($image, 'large'); }
if (empty($image))          { $image = get_template_directory_uri() . '/assets/img/placeholder-package.svg'; }

// Etiqueta del badge según tipo de ruta
// one_way    → solo Arrival
// round_trip → solo Round Trip
// both       → el cliente puede elegir entre los tres
$route_labels = [
    'one_way'    => pll__('Arrival'),
    'round_trip' => pll__('Round Trip'),
    'both'       => pll__('Arrival / Departure / Round Trip'),
];
$route_label = $route_labels[$route_type ?? ''] ?? '';
?>

<!-- data-route-type lo lee el JS inline para el sync hero → modal -->
<section class="product-view-main-section light-section" data-route-type="<?php echo esc_attr($route_type ?? ''); ?>">

    <!-- ── HERO ─────────────────────────────────────────────────────────── -->
    <section class="product-hero">
        <div class="product-hero-inner">

            <!-- Imagen izquierda -->
            <div class="product-hero-left">
                <div class="product-hero-image-wrap">
                    <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>">

                    <?php if (!empty($route_label)): ?>
                    <div class="pv-family-badge transport-route-badge">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h11a2 2 0 012 2v3m-3 9h8m-8 0a2 2 0 100 4 2 2 0 000-4zm8 0a2 2 0 100 4 2 2 0 000-4zm-4-3V9a2 2 0 00-2-2h-1" />
                        </svg>
                        <?php echo esc_html($route_label); ?>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if ($description): ?>
                <div class="product-hero-description">
                    <h2><?php echo esc_html(pll__('Description')); ?></h2>
                    <p><?php echo esc_html($description); ?></p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Card derecha -->
            <div class="product-hero-right">
                <div class="product-hero-card">

                    <p class="pv-label"><?php echo esc_html(pll__('Reserve your transport:')); ?></p>
                    <h1 class="product-hero-title"><?php echo esc_html($title); ?></h1>

                    <?php if ($origin && $destination): ?>
                    <p class="product-hero-location">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M21 3L3 10.5l6.75 2.25L12 21l2.25-6.75L21 3z" />
                        </svg>
                        <?php echo esc_html($origin); ?> → <?php echo esc_html($destination); ?>
                    </p>
                    <?php endif; ?>

                    <!-- Selector en el hero: solo visible cuando route_type === 'both' -->
                    <?php if ($route_type === 'both'): ?>
                    <div class="product-hero-divider"></div>
                    <div class="transport-trip-selector">
                        <p class="pv-label"><?php echo esc_html(pll__('Trip type')); ?></p>
                        <div class="transport-trip-options">
                            <label class="transport-trip-option">
                                <input type="radio" name="trip_type" value="arrival" checked>
                                <span><?php echo esc_html(pll__('Arrival')); ?></span>
                            </label>
                            <label class="transport-trip-option">
                                <input type="radio" name="trip_type" value="departure">
                                <span><?php echo esc_html(pll__('Departure')); ?></span>
                            </label>
                            <label class="transport-trip-option">
                                <input type="radio" name="trip_type" value="round_trip">
                                <span><?php echo esc_html(pll__('Round Trip')); ?></span>
                            </label>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="product-hero-divider"></div>

                    <p class="transport-price-note">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M12 16v-4M12 8h.01" />
                        </svg>
                        <?php echo esc_html(pll__('Pricing will be provided upon contact.')); ?>
                    </p>

                    <div class="product-hero-buttons">
                        <button class="product-hero-book" id="open-transport-modal">
                            <?php echo esc_html(pll__('Book Now')); ?>
                        </button>
                        <a href="<?php echo esc_url(site_url('/transport')); ?>" class="product-hero-secondary">
                            <?php echo esc_html(pll__('View other routes')); ?>
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </section>


    <!-- ── ACORDEONES ───────────────────────────────────────────────────── -->
    <section class="product-view-main-section">
        <div class="product-view-main-div">

            <?php if ($meeting_pt || $meeting_lnk): ?>
            <div class="meeting-point-card">
                <div class="meeting-point-icon">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" />
                        <circle cx="12" cy="10" r="3" />
                    </svg>
                </div>
                <div class="meeting-point-info">
                    <?php if ($meeting_pt): ?>
                    <span class="meeting-point-label"><?php echo esc_html(pll__('Meeting Point')); ?></span>
                    <span class="meeting-point-name"><?php echo esc_html($meeting_pt); ?></span>
                    <?php endif; ?>
                </div>
                <?php if ($meeting_lnk): ?>
                <a href="<?php echo esc_url($meeting_lnk); ?>" target="_blank" rel="noopener noreferrer" class="meeting-point-btn">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3" />
                    </svg>
                    <?php echo esc_html(pll__('Open Maps')); ?>
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="accordion-wrapper">

                <?php if ($included): ?>
                <div class="accordion-item">
                    <button class="accordion-header" aria-expanded="false">
                        <span><?php echo esc_html(pll__('INCLUDED')); ?></span>
                        <svg class="accordion-icon" width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div class="accordion-body">
                        <ul class="product-view-included-list">
                            <?php foreach (explode("\n", $included) as $line): $line = trim($line); if (!empty($line)): ?>
                            <li><?php echo esc_html($line); ?></li>
                            <?php endif; endforeach; ?>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($add_info): ?>
                <div class="accordion-item">
                    <button class="accordion-header" aria-expanded="false">
                        <span><?php echo esc_html(pll__('ADDITIONAL INFORMATION')); ?></span>
                        <svg class="accordion-icon" width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <div class="accordion-body">
                        <ul class="product-view-aditional-list">
                            <?php foreach (explode("\n", $add_info) as $line): $line = trim($line); if (!empty($line)): ?>
                            <li><?php echo esc_html($line); ?></li>
                            <?php endif; endforeach; ?>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </section>

</section>


<!-- ── MODAL DE RESERVA ─────────────────────────────────────────────────── -->
<div id="transport-modal" class="booking-modal">
    <div class="booking-modal-content transport-modal-content">

        <span class="booking-modal-close" id="close-transport-modal">&times;</span>

        <h2 class="booking-modal-title"><?php echo esc_html(pll__('Book Transport')); ?></h2>
        <p class="booking-modal-subtitle">
            <?php if ($origin && $destination): ?>
                <?php echo esc_html($origin); ?> → <?php echo esc_html($destination); ?>
            <?php else: ?>
                <?php echo esc_html($title); ?>
            <?php endif; ?>
        </p>

        <form id="transport-booking-form" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">

            <input type="hidden" name="action"          value="boskoa_transport_form">
            <input type="hidden" name="contact_nonce"   value="<?php echo wp_create_nonce('boskoa_transport_form'); ?>">
            <input type="hidden" name="transport_id"    value="<?php echo esc_attr($transport_id); ?>">
            <input type="hidden" name="recaptcha_token" id="transportRecaptchaToken">

            <!-- ── Tipo de viaje ──────────────────────────────────────────── -->
            <!--
                Reglas de disponibilidad por route_type en ACF:
                  one_way    → Arrival ✅  Departure ✅  Round Trip ❌
                  round_trip → Arrival ❌  Departure ❌  Round Trip ✅
                  both       → Arrival ✅  Departure ✅  Round Trip ✅
            -->
            <div class="booking-form-group">
                <label><?php echo esc_html(pll__('Trip Type')); ?> *</label>
                <div class="transport-modal-trip-selector">

                    <?php
                    // Arrival & Departure: habilitados salvo que la ruta sea exclusivamente round_trip
                    $oneway_disabled   = ($route_type === 'round_trip') ? 'disabled' : '';
                    // Round Trip: habilitado salvo que la ruta sea exclusivamente one_way
                    $round_disabled    = ($route_type === 'one_way') ? 'disabled' : '';

                    // Pre-selección inicial
                    $arrival_checked   = ($route_type !== 'round_trip') ? 'checked' : '';
                    $round_checked     = ($route_type === 'round_trip') ? 'checked' : '';
                    ?>

                    <!-- Arrival -->
                    <label class="transport-modal-trip-option <?php echo $arrival_checked ? 'active' : ''; ?>">
                        <input type="radio" name="trip_type" value="arrival"
                            <?php echo $arrival_checked; ?>
                            <?php echo $oneway_disabled; ?>>
                        <span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                            <?php echo esc_html(pll__('Arrival')); ?>
                        </span>
                    </label>

                    <!-- Departure -->
                    <label class="transport-modal-trip-option">
                        <input type="radio" name="trip_type" value="departure"
                            <?php echo $oneway_disabled; ?>>
                        <span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 12H5M12 19l-7-7 7-7" />
                            </svg>
                            <?php echo esc_html(pll__('Departure')); ?>
                        </span>
                    </label>

                    <!-- Round Trip -->
                    <label class="transport-modal-trip-option <?php echo $round_checked ? 'active' : ''; ?>">
                        <input type="radio" name="trip_type" value="round_trip"
                            <?php echo $round_checked; ?>
                            <?php echo $round_disabled; ?>>
                        <span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 12h16M4 12l4-4M4 12l4 4M20 12l-4-4M20 12l-4 4" />
                            </svg>
                            <?php echo esc_html(pll__('Round Trip')); ?>
                        </span>
                    </label>

                </div>
            </div>

            <!-- ── Datos personales ───────────────────────────────────────── -->
            <div class="booking-form-group">
                <label for="t_name"><?php echo esc_html(pll__('Full Name')); ?> *</label>
                <input type="text" id="t_name" name="contact_name" required maxlength="100"
                    placeholder="<?php echo esc_attr(pll__('Your full name')); ?>">
            </div>

            <div class="booking-form-group">
                <label for="t_email"><?php echo esc_html(pll__('Email')); ?> *</label>
                <input type="email" id="t_email" name="contact_email" required maxlength="150"
                    placeholder="your@email.com">
            </div>

            <div class="booking-form-group">
                <label for="contact_phone"><?php echo esc_html(pll__('Phone (optional)')); ?></label>
                <input type="tel" id="contact_phone" name="contact_phone" maxlength="20">
                <input type="hidden" id="contact_phone_full" name="contact_phone_full">
            </div>

            <div class="booking-form-row">
                <div class="booking-form-group">
                    <label for="t_passengers"><?php echo esc_html(pll__('Number of Passengers')); ?> *</label>
                    <input type="number" id="t_passengers" name="passengers" min="1" value="1" required>
                </div>
                <div class="booking-form-group">
                    <label for="t_luggage"><?php echo esc_html(pll__('Luggage Pieces')); ?></label>
                    <input type="number" id="t_luggage" name="luggage" min="0" value="1">
                </div>
            </div>

            <!-- ── Bloque ARRIVAL FLIGHT ──────────────────────────────────── -->
            <!-- Visible para: arrival, round_trip -->
            <div class="transport-form-section" id="flight-section-arrival">
                <p class="transport-form-section-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                    <?php echo esc_html(pll__('Arrival Flight')); ?>
                </p>
                <div class="booking-form-row">
                    <div class="booking-form-group">
                        <label for="t_flight_number"><?php echo esc_html(pll__('Flight Number')); ?></label>
                        <input type="text" id="t_flight_number" name="flight_number"
                            placeholder="e.g. AA 1234" maxlength="50">
                    </div>
                    <div class="booking-form-group">
                        <label for="t_airline"><?php echo esc_html(pll__('Airline')); ?></label>
                        <input type="text" id="t_airline" name="airline"
                            placeholder="e.g. American Airlines" maxlength="100">
                    </div>
                </div>
                <div class="booking-form-row">
                    <div class="booking-form-group">
                        <label for="t_arrival_date"><?php echo esc_html(pll__('Arrival Date')); ?> *</label>
                        <input type="date" id="t_arrival_date" name="t_arrival_date" required
                            min="<?php echo date('Y-m-d'); ?>"
                            max="<?php echo date('Y-m-d', strtotime('+1 year')); ?>">
                    </div>
                    <div class="booking-form-group">
                        <label for="t_arrival_time"><?php echo esc_html(pll__('Arrival Time')); ?> *</label>
                        <input type="time" id="t_arrival_time" name="t_arrival_time" required
                            min="00:00" max="23:59">
                    </div>
                </div>
            </div>

            <!-- ── Bloque DEPARTURE FLIGHT ────────────────────────────────── -->
            <!-- Visible para: departure, round_trip -->
            <div class="transport-form-section" id="flight-section-departure" style="display:none;">
                <p class="transport-form-section-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7" />
                    </svg>
                    <?php echo esc_html(pll__('Departure Flight')); ?>
                </p>
                <div class="booking-form-row">
                    <div class="booking-form-group">
                        <label for="t_dep_flight_number"><?php echo esc_html(pll__('Flight Number')); ?></label>
                        <input type="text" id="t_dep_flight_number" name="dep_flight_number"
                            placeholder="e.g. AA 5678" maxlength="50">
                    </div>
                    <div class="booking-form-group">
                        <label for="t_dep_airline"><?php echo esc_html(pll__('Airline')); ?></label>
                        <input type="text" id="t_dep_airline" name="dep_airline"
                            placeholder="e.g. American Airlines" maxlength="100">
                    </div>
                </div>
                <div class="booking-form-row">
                    <div class="booking-form-group">
                        <label for="t_dep_date"><?php echo esc_html(pll__('Departure Date')); ?> *</label>
                        <input type="date" id="t_dep_date" name="t_dep_date"
                            min="<?php echo date('Y-m-d'); ?>"
                            max="<?php echo date('Y-m-d', strtotime('+1 year')); ?>">
                    </div>
                    <div class="booking-form-group">
                        <label for="t_dep_time"><?php echo esc_html(pll__('Departure Time')); ?> *</label>
                        <input type="time" id="t_dep_time" name="t_dep_time"
                            min="00:00" max="23:59">
                    </div>
                </div>
            </div>

            <!-- ── Notas adicionales ──────────────────────────────────────── -->
            <div class="booking-form-group">
                <label for="t_message"><?php echo esc_html(pll__('Additional Notes')); ?></label>
                <textarea id="t_message" name="contact_message" rows="3" maxlength="500"
                    placeholder="<?php echo esc_attr(pll__('Special requests, wheelchair access, child seats...')); ?>"></textarea>
            </div>

            <button type="submit" class="booking-submit-btn">
                <?php echo esc_html(pll__('Send Booking Request')); ?>
            </button>

        </form>
    </div>
</div>


<?php get_footer(); ?>