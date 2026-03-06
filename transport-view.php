<?php
/**
 * Template Name: Transport View
 * 
 * Vista detallada de una opción de transporte.
 * URL: /transport-view/?transport_id=123
 */

get_header();

$transport_id = isset($_GET['transport_id']) ? intval($_GET['transport_id']) : 0;

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

// Etiqueta del tipo de ruta
$route_labels = [
    'one_way'    => pll__('One Way'),
    'round_trip' => pll__('Round Trip'),
    'both'       => pll__('One Way / Round Trip'),
];
$route_label = $route_labels[$route_type ?? ''] ?? '';
?>

<!-- data-route-type lo lee transport-view.js para el sync hero → modal -->
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
                            <path
                                d="M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h11a2 2 0 012 2v3m-3 9h8m-8 0a2 2 0 100 4 2 2 0 000-4zm8 0a2 2 0 100 4 2 2 0 000-4zm-4-3V9a2 2 0 00-2-2h-1" />
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

                    <!-- Tipo de ruta (solo si es 'both') -->
                    <?php if ($route_type === 'both'): ?>
                    <div class="product-hero-divider"></div>
                    <div class="transport-trip-selector">
                        <p class="pv-label"><?php echo esc_html(pll__('Trip type')); ?></p>
                        <div class="transport-trip-options">
                            <label class="transport-trip-option">
                                <input type="radio" name="trip_type" value="one_way" checked>
                                <span><?php echo esc_html(pll__('One Way')); ?></span>
                            </label>
                            <label class="transport-trip-option">
                                <input type="radio" name="trip_type" value="round_trip">
                                <span><?php echo esc_html(pll__('Round Trip')); ?></span>
                            </label>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="product-hero-divider"></div>

                    <!-- Sin precio visible -->
                    <p class="transport-price-note">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
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
                <a href="<?php echo esc_url($meeting_lnk); ?>" target="_blank" rel="noopener noreferrer"
                    class="meeting-point-btn">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5">
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
                            <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round" />
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
                            <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round" />
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

            <input type="hidden" name="action" value="boskoa_transport_form">
            <input type="hidden" name="contact_nonce" value="<?php echo wp_create_nonce('boskoa_transport_form'); ?>">
            <input type="hidden" name="transport_id" value="<?php echo esc_attr($transport_id); ?>">
            <input type="hidden" name="recaptcha_token" id="transportRecaptchaToken">

            <!-- ── Tipo de viaje ────────────────────────────── -->
            <div class="booking-form-group">
                <label><?php echo esc_html(pll__('Trip Type')); ?> *</label>
                <div class="transport-modal-trip-selector">
                    <label
                        class="transport-modal-trip-option <?php echo ($route_type !== 'round_trip') ? 'active' : ''; ?>">
                        <input type="radio" name="trip_type" value="one_way"
                            <?php echo ($route_type !== 'round_trip') ? 'checked' : ''; ?>
                            <?php echo ($route_type === 'round_trip') ? 'disabled' : ''; ?>>
                        <span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M5 12h14M12 5l7 7-7 7" />
                            </svg>
                            <?php echo esc_html(pll__('One Way')); ?>
                        </span>
                    </label>
                    <label
                        class="transport-modal-trip-option <?php echo ($route_type === 'round_trip') ? 'active' : ''; ?>">
                        <input type="radio" name="trip_type" value="round_trip"
                            <?php echo ($route_type === 'round_trip') ? 'checked' : ''; ?>
                            <?php echo ($route_type === 'one_way') ? 'disabled' : ''; ?>>
                        <span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M4 12h16M4 12l4-4M4 12l4 4M20 12l-4-4M20 12l-4 4" />
                            </svg>
                            <?php echo esc_html(pll__('Round Trip')); ?>
                        </span>
                    </label>
                </div>
            </div>

            <!-- ── Datos personales ─────────────────────────── -->
            <div class="booking-form-group">
                <label for="t_name"><?php echo esc_html(pll__('Full Name')); ?> *</label>
                <input type="text" id="t_name" name="contact_name" required maxlength="10"
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

            <!-- ── Vuelo de llegada ─────────────────────────── -->
            <div class="transport-form-section">
                <p class="transport-form-section-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path
                            d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.67A2 2 0 012 1h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z" />
                    </svg>
                    <?php echo esc_html(pll__('Arrival Flight')); ?>
                </p>
                <div class="booking-form-row">
                    <div class="booking-form-group">
                        <label for="t_flight_number"><?php echo esc_html(pll__('Flight Number')); ?></label>
                        <input type="text" id="t_flight_number" name="flight_number" placeholder="e.g. AA 1234"
                            maxlength="50">
                    </div>
                    <div class="booking-form-group">
                        <label for="t_airline"><?php echo esc_html(pll__('Airline')); ?></label>
                        <input type="text" id="t_airline" name="airline" placeholder="e.g. American Airlines"
                            maxlength="100">
                    </div>
                </div>
                <div class="booking-form-row">
                    <div class="booking-form-group">
                        <label for="t_arrival_date"><?php echo esc_html(pll__('Arrival Date')); ?> *</label>
                        <input type="date" id="t_arrival_date" name="t_arrival_date" required
                            max="<?php echo date('Y-m-d', strtotime('+1 year')); ?>">
                    </div>
                    <div class="booking-form-group">
                        <label for="t_arrival_time"><?php echo esc_html(pll__('Arrival Time')); ?> *</label>
                        <input type="time" id="t_arrival_time" name="t_arrival_time" required min="00:00" max="23:59">
                    </div>
                </div>
            </div>

            <!-- ── Vuelo de regreso (solo round trip) ──────── -->
            <div class="transport-form-section" id="return-flight-section" style="display:none;">
                <p class="transport-form-section-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path
                            d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.67A2 2 0 012 1h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z" />
                    </svg>
                    <?php echo esc_html(pll__('Return Flight')); ?>
                </p>
                <div class="booking-form-row">
                    <div class="booking-form-group">
                        <label for="t_return_flight"><?php echo esc_html(pll__('Return Flight Number')); ?></label>
                        <input type="text" id="t_return_flight" name="return_flight_number" placeholder="e.g. AA 5678"
                            maxlength="50">
                    </div>
                    <div class="booking-form-group">
                        <label for="t_return_airline"><?php echo esc_html(pll__('Airline')); ?></label>
                        <input type="text" id="t_return_airline" name="return_airline"
                            placeholder="e.g. American Airlines" maxlength="100">
                    </div>
                </div>
                <div class="booking-form-row">
                    <div class="booking-form-group">
                        <label for="t_return_date"><?php echo esc_html(pll__('Departure Date')); ?></label>
                        <input type="date" id="t_return_date" name="t_return_date" min="<?php echo date('Y-m-d'); ?>"
                            max="<?php echo date('Y-m-d', strtotime('+1 year')); ?>">
                    </div>
                    <div class="booking-form-group">
                        <label for="t_return_time"><?php echo esc_html(pll__('Departure Time')); ?></label>
                        <input type="time" id="t_return_time" name="t_return_time" min="00:00" max="23:59">
                    </div>
                </div>
            </div>

            <!-- ── Notas adicionales ────────────────────────── -->
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