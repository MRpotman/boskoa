<?php
/**
 * Template Name: Package View
 * Vista de detalles de un paquete turístico
 */

get_header();

// Obtener ID desde URL
$package_id = isset($_GET['package_id']) ? intval($_GET['package_id']) : 0;

if (!$package_id) {
    echo "<h2>Paquete no encontrado</h2>";
    get_footer();
    return;
}

// Obtener datos del paquete desde ACF
$title           = get_field('titulo', $package_id) ?: get_the_title($package_id);
$description     = get_field('descripcion', $package_id);
$price           = get_field('precio', $package_id);
$locations       = get_field('punto_de_encuentro', $package_id);
$family_friendly = get_field('familiar', $package_id);
$image           = get_field('imagen', $package_id);

if (is_array($image)) {
    $image = $image['url'];
} elseif (is_numeric($image)) {
    $image = wp_get_attachment_image_url($image, 'large');
}

$included_activities = get_field('actividades_asociadas', $package_id);

if (empty($image)) {
    $image = get_template_directory_uri() . '/assets/img/placeholder-package.jpg';
}
if (empty($price)) {
    $price = 0;
}
if (empty($locations)) {
    $locations = '';
}

// Calcular sumatoria de actividades
$activities_total = 0;
$activities_data  = [];

if (!empty($included_activities) && is_array($included_activities)) {
    foreach ($included_activities as $activity) {
        if (empty($activity)) continue;

        if (is_object($activity)) {
            $act_id = $activity->ID;
        } elseif (is_array($activity)) {
            $act_id = $activity['ID'];
        } else {
            $act_id = intval($activity);
        }
        if (!$act_id) continue;

        $act_image = get_field('imagen', $act_id);
        if (is_array($act_image)) {
            $act_image = $act_image['url'];
        } elseif (is_numeric($act_image)) {
            $act_image = wp_get_attachment_image_url($act_image, 'medium_large');
        }
        if (empty($act_image)) {
            $act_image = get_template_directory_uri() . '/assets/img/placeholder-package.jpg';
        }

        $act_price = floatval(get_field('precio', $act_id));
        $activities_total += $act_price;

        $activities_data[] = [
            'id'          => $act_id,
            'title'       => get_the_title($act_id),
            'image'       => $act_image,
            'price'       => $act_price,
            'location'    => get_field('ubicacion', $act_id),
            'description' => get_field('descripcion', $act_id),
        ];
    }
}

$package_price_num = floatval($price);
$savings = $activities_total > 0 ? ($activities_total - $package_price_num) : 0;
?>

<!-- ========================= -->
<!-- SECCIÓN HERO: Imagen + Info -->
<!-- ========================= -->
<section class="pv-hero light-section">
    <div class="pv-hero-inner">

        <!-- COLUMNA IZQUIERDA: imagen grande + descripción -->
        <div class="pv-hero-left">
            <div class="pv-hero-image-wrap">
                <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>">
                <?php if ($family_friendly): ?>
                <div class="pv-family-badge">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M5.5 4.5c0 1.105-.895 2-2 2s-2-.895-2-2 .895-2 2-2 2 .895 2 2zm9 0c0 1.105-.895 2-2 2s-2-.895-2-2 .895-2 2-2 2 .895 2 2zM6 14v-3c0-.828-.672-1.5-1.5-1.5S3 10.172 3 11v3h3zm7 0v-3c0-.828-.672-1.5-1.5-1.5S10 10.172 10 11v3h3zm-5.5-6c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM10 14v-3c0-.828-.672-1.5-1.5-1.5S7 10.172 7 11v3h3z" fill="currentColor"/>
                    </svg>
                    <?php echo esc_html(pll__('Family Friendly')); ?>
                </div>
                <?php endif; ?>
            </div>

            <?php if ($description): ?>
            <div class="pv-description">
                <h2><?php echo esc_html(pll__('Description')); ?></h2>
                <p><?php echo wp_kses_post($description); ?></p>
            </div>
            <?php endif; ?>
        </div>

        <!-- COLUMNA DERECHA: título + sumatoria + botones -->
        <div class="pv-hero-right">

            <div class="pv-hero-sticky">

                <p class="pv-label"><?php echo esc_html(pll__('Enjoy your vacation with our package:')); ?></p>
                <h1 class="pv-title"><?php echo esc_html($title); ?></h1>

                <?php if ($locations): ?>
                <p class="pv-location">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none">
                        <path d="M8 0C5.243 0 3 2.243 3 5c0 4.5 5 11 5 11s5-6.5 5-11c0-2.757-2.243-5-5-5zm0 7c-1.105 0-2-.895-2-2s.895-2 2-2 2 .895 2 2-.895 2-2 2z" fill="currentColor"/>
                    </svg>
                    <?php echo esc_html($locations); ?>
                </p>
                <?php endif; ?>

                <div class="pv-divider"></div>

                <!-- Sumatoria de actividades -->
                <?php if (!empty($activities_data)): ?>
                <div class="pv-summary">
                    <h3 class="pv-summary-title"><?php echo esc_html(pll__('Included Activities')); ?></h3>
                    <ul class="pv-summary-list">
                        <?php foreach ($activities_data as $act): ?>
                        <li class="pv-summary-item">
                            <span class="pv-summary-name"><?php echo esc_html($act['title']); ?></span>
                            <span class="pv-summary-price">$<?php echo number_format($act['price'], 0); ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>

                    <?php if ($savings > 0): ?>
                    <div class="pv-savings">
                        <span><?php echo esc_html(pll__('Individual total')); ?></span>
                        <span class="pv-savings-original">$<?php echo number_format($activities_total, 0); ?></span>
                    </div>
                    <div class="pv-savings pv-savings-highlight">
                        <span><?php echo esc_html(pll__('You save')); ?></span>
                        <span class="pv-savings-amount">-$<?php echo number_format($savings, 0); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="pv-divider"></div>
                <?php endif; ?>

                <!-- Precio total del paquete -->
                <div class="pv-price-row">
                    <span class="pv-price-label"><?php echo esc_html(pll__('Package price')); ?></span>
                    <span class="pv-price-total">$<?php echo number_format($package_price_num, 0); ?></span>
                </div>

                <!-- Botones -->
                <div class="pv-buttons">
                    <button class="pv-btn-book" id="open-booking-modal">
                        <?php echo esc_html(pll__('Book Now')); ?>
                    </button>
                    <a href="<?php echo esc_url(site_url('/packages')); ?>" class="pv-btn-secondary">
                        <?php echo esc_html(pll__('View other packages')); ?>
                    </a>
                </div>

            </div>
        </div>

    </div>
</section>

<!-- ========================= -->
<!-- SECCIÓN DE ACTIVIDADES DETALLADAS -->
<!-- ========================= -->
<?php if (!empty($activities_data)): ?>
<section class="pv-activities-section">
    <div class="pv-activities-container">
        <h2 class="pv-activities-title">
            <?php echo esc_html(pll__('Activities Included in This Package')); ?>
        </h2>

        <div class="pv-activities-list">
            <?php foreach ($activities_data as $act): ?>
            <div class="pv-act-card">
                <a href="<?php echo esc_url(site_url('/product-view/?activity_id=' . $act['id'])); ?>" class="pv-act-link">

                    <!-- Imagen -->
                    <div class="pv-act-image">
                        <img src="<?php echo esc_url($act['image']); ?>" alt="<?php echo esc_attr($act['title']); ?>" loading="lazy">
                    </div>

                    <!-- Info -->
                    <div class="pv-act-info">
                        <h3 class="pv-act-title"><?php echo esc_html($act['title']); ?></h3>

                        <?php if ($act['description']): ?>
                        <p class="pv-act-desc"><?php echo esc_html($act['description']); ?></p>
                        <?php endif; ?>

                        <?php if ($act['location']): ?>
                        <span class="pv-act-location">
                            <svg width="13" height="13" viewBox="0 0 16 16" fill="none">
                                <path d="M8 0C5.243 0 3 2.243 3 5c0 4.5 5 11 5 11s5-6.5 5-11c0-2.757-2.243-5-5-5zm0 7c-1.105 0-2-.895-2-2s.895-2 2-2 2 .895 2 2-.895 2-2 2z" fill="currentColor"/>
                            </svg>
                            <?php echo esc_html($act['location']); ?>
                        </span>
                        <?php endif; ?>
                    </div>

                    <!-- Precio -->
                    <div class="pv-act-price-col">
                        <span class="pv-act-price">$<?php echo number_format($act['price'], 0); ?></span>
                    </div>

                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ========================= -->
<!-- INFO ADICIONAL DEL PAQUETE -->
<!-- ========================= -->
<?php
$included       = get_field('articulos_incluidos', $package_id);
$aditional_info = get_field('informacion_adicional', $package_id);
?>
<?php if ($included || $aditional_info): ?>
<section class="pv-info-section">
    <div class="pv-info-grid">

        <?php if ($included): ?>
        <div class="pv-info-card">
            <h2><?php echo esc_html(pll__('INCLUDED')); ?></h2>
            <ul class="pv-info-list">
                <?php foreach (explode("\n", $included) as $line): ?>
                    <?php $line = trim($line); if (!empty($line)): ?>
                    <li><?php echo esc_html($line); ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if ($aditional_info): ?>
        <div class="pv-info-card">
            <h2><?php echo esc_html(pll__('ADDITIONAL INFORMATION')); ?></h2>
            <ul class="pv-info-list">
                <?php foreach (explode("\n", $aditional_info) as $line): ?>
                    <?php $line = trim($line); if (!empty($line)): ?>
                    <li><?php echo esc_html($line); ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

    </div>
</section>
<?php endif; ?>

<!-- Booking Modal -->
<div id="booking-modal" class="booking-modal">
    <div class="booking-modal-content">
        <span class="booking-modal-close">&times;</span>
        <h2 class="booking-modal-title"><?php echo esc_html(pll__('Book Your Package')); ?></h2>
        <p class="booking-modal-subtitle"><?php echo esc_html($title); ?> - $<?php echo number_format($package_price_num, 0); ?></p>

        <form id="booking-form" method="POST" action="<?php echo admin_url('admin-post.php'); ?>">
            <input type="hidden" name="action" value="boskoa_contact_form">
            <input type="hidden" name="contact_nonce" value="<?php echo wp_create_nonce('boskoa_contact_form'); ?>">
            <input type="hidden" name="activity_id" value="<?php echo esc_attr($package_id); ?>">
            <input type="hidden" name="contact_matters" value="Booking Package: <?php echo esc_attr($title); ?> - $<?php echo esc_attr($price); ?>">

            <div class="booking-form-group">
                <label for="contact_name"><?php echo esc_html(pll__('Name')); ?> *</label>
                <input type="text" id="contact_name" name="contact_name" required placeholder="<?php echo esc_attr(pll__('Your full name')); ?>">
            </div>
            <div class="booking-form-group">
                <label for="contact_email"><?php echo esc_html(pll__('Email')); ?> *</label>
                <input type="email" id="contact_email" name="contact_email" required placeholder="your@email.com">
            </div>
            <div class="booking-form-group">
                <label for="contact_phone"><?php echo esc_html(pll__('Phone')); ?> (optional)</label>
                <input type="tel" id="contact_phone" name="contact_phone" placeholder="<?php echo esc_attr(pll__('Your phone number')); ?>">
            </div>
            <div class="booking-form-group">
                <label for="contact_message"><?php echo esc_html(pll__('Message')); ?> *</label>
                <textarea id="contact_message" name="contact_message" rows="4" required
                    placeholder="<?php echo esc_attr(pll__('I would like to book this package...')); ?>"><?php echo esc_html(pll__('I would like to book the package "')); ?><?php echo esc_attr($title); ?><?php echo esc_html(pll__('" for $')); ?><?php echo number_format($package_price_num, 0); ?>. <?php echo esc_html(pll__('Please contact me with more information.')); ?></textarea>
            </div>
            <button type="submit" class="booking-submit-btn"><?php echo esc_html(pll__('Send Booking Request')); ?></button>
        </form>
    </div>
</div>

<?php get_footer(); ?>