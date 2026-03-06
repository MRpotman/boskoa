<?php
/**
 * Template Name: Product View
 */

get_header();

// Obtener ID desde URL
$activity_id = isset($_GET['activity_id']) ? intval($_GET['activity_id']) : 0;

if (function_exists('pll_get_post')) {
    $activity_id = pll_get_post($activity_id);
}

if (!$activity_id) {
    echo "<h2>Actividad no encontrada</h2>";
    get_footer();
    return;
}

// Obtener datos
$title = get_field('titulo', $activity_id) ?: get_the_title($activity_id);
$description = get_field('descripcion', $activity_id);
$locations = get_field('ubicacion', $activity_id);
$price = get_field('precio', $activity_id);
$image = get_field('imagen', $activity_id);

// Compatibilidad imagen ACF
if (is_array($image)) {
    $image = $image['url'];
} elseif (is_numeric($image)) {
    $image = wp_get_attachment_image_url($image, 'large');
}

// Imagen por defecto
if (empty($image)) {
    $image = get_template_directory_uri() . '/assets/img/placeholder-package.svg';
}
?>

<section class="product-view-main-section light-section">
<section class="product-hero">

    <div class="product-hero-inner">
        <div class="product-hero-left">
            <div class="product-hero-image-wrap">
                <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>">
                <div class="pv-family-badge">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M5.5 4.5c0 1.105-.895 2-2 2s-2-.895-2-2 .895-2 2-2 2 .895 2 2zm9 0c0 1.105-.895 2-2 2s-2-.895-2-2 .895-2 2-2 2 .895 2 2zM6 14v-3c0-.828-.672-1.5-1.5-1.5S3 10.172 3 11v3h3zm7 0v-3c0-.828-.672-1.5-1.5-1.5S10 10.172 10 11v3h3zm-5.5-6c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM10 14v-3c0-.828-.672-1.5-1.5-1.5S7 10.172 7 11v3h3z" fill="currentColor"/>
                    </svg>
                    <?php echo esc_html(pll__('Family Friendly')); ?>
                </div>
            </div>
            <?php if ($description): ?>
            <div class="product-hero-description">
                <h2><?php echo esc_html(pll__('Description')); ?></h2>
                <p><?php echo esc_html($description); ?></p>
            </div>
            <?php endif; ?>
        </div>

        <div class="product-hero-right">
            <div class="product-hero-card">
                <p class="pv-label"><?php echo esc_html(pll__('Enjoy your vacation with our activities:')); ?></p>
                <h1 class="product-hero-title">
                    <?php echo esc_html($title); ?>
                </h1>
                <?php if ($locations): ?>
                <p class="product-hero-location">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none">
                        <path d="M8 0C5.243 0 3 2.243 3 5c0 4.5 5 11 5 11s5-6.5 5-11c0-2.757-2.243-5-5-5zm0 7c-1.105 0-2-.895-2-2s.895-2 2-2 2 .895 2 2-.895 2-2 2z" fill="currentColor"/>
                    </svg>
                    <?php echo esc_html($locations); ?>
                </p>
                <?php endif; ?>

                <div class="product-hero-divider"></div>
                <div class="product-hero-quantity">
                    <label for="person-count"><?php echo esc_html(pll__('Persons')); ?></label>
                    <input type="number" id="person-count" min="1" value="1">
                </div>
                <div class="product-hero-price">
                    <span class="product-hero-price-label">
                        <?php echo esc_html(pll__('Price:')); ?>
                    </span>
                    <span class="product-hero-price-value" id="dynamic-price" data-base-price="<?php echo esc_attr($price); ?>">
                        $<?php echo esc_html($price); ?>
                    </span>
                </div>

                <div class="product-hero-buttons">
                    <button class="product-hero-book" id="open-booking-modal">
                        <?php echo esc_html(pll__('Book Now')); ?>
                    </button>

                    <button class="product-hero-secondary add-to-cart-btn"
                        data-id="<?php echo esc_attr($activity_id); ?>"
                        data-title="<?php echo esc_attr($title); ?>"
                        data-price="<?php echo esc_attr($price); ?>"
                        data-image="<?php echo esc_attr($image); ?>">
                        <?php echo esc_html(pll__('Add to cart')); ?>
                    </button>
                </div>

            </div>
        </div>

    </div>

</section>
<section class="product-view-main-section">
    <div class="product-view-main-div">
        <?php
        $included        = get_field('articulos_incluidos', $activity_id);
        $aditional_info  = get_field('informacion__adicional', $activity_id);
        $hosts           = get_field('anfitrion', $activity_id);
        $meeting_point   = get_field('punto_de_encuentro', $activity_id);
        $meeting_link    = get_field('encuentro_link', $activity_id);
        ?>

        <div class="accordion-wrapper">

            <?php if ($included): ?>
            <div class="accordion-item">
                <button class="accordion-header" aria-expanded="false">
                    <span><?php echo esc_html(pll__('INCLUIDO')); ?></span>
                    <svg class="accordion-icon" width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
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

            <?php if ($aditional_info): ?>
            <div class="accordion-item">
                <button class="accordion-header" aria-expanded="false">
                    <span><?php echo esc_html(pll__('ADDITIONAL INFORMATION')); ?></span>
                    <svg class="accordion-icon" width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="accordion-body">
                    <ul class="product-view-aditional-list">
                        <?php foreach (explode("\n", $aditional_info) as $line): $line = trim($line); if (!empty($line)): ?>
                            <li><?php echo esc_html($line); ?></li>
                        <?php endif; endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($hosts): ?>
            <div class="accordion-item">
                <button class="accordion-header" aria-expanded="false">
                    <span><?php echo esc_html(pll__('Host languages')); ?></span>
                    <svg class="accordion-icon" width="20" height="20" viewBox="0 0 24 24" fill="none">
                        <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div class="accordion-body">
                    <div class="language-badges">
                        <?php foreach ($hosts as $language): ?>
                            <span class="language-badge"><?php echo esc_html($language['label']); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($meeting_point || $meeting_link): ?>
                <div class="meeting-point-card">
                    <div class="meeting-point-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                    </div>
                    <div class="meeting-point-info">
                        <?php if ($meeting_point): ?>
                        <span class="meeting-point-label"><?php echo esc_html(pll__('Meeting Point')); ?></span>
                        <span class="meeting-point-name"><?php echo esc_html($meeting_point); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if ($meeting_link): ?>
                    <a href="<?php echo esc_url($meeting_link); ?>" target="_blank" rel="noopener noreferrer" class="meeting-point-btn">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6M15 3h6v6M10 14L21 3" />
                        </svg>
                        <?php echo esc_html(pll__('Open Maps')); ?>
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

        </div>
    </div>
</section>



<section class="itinerary-section">

    <?php
    $itinerary = get_field('itinerario', $activity_id);

    if (!empty($itinerary)) {

        $lines = array_filter(array_map('trim', explode("\n", $itinerary)));

        if (!empty($lines)) {
    ?>

            <h2 class="itinerary-title">
                <?php echo esc_html(pll__('ITINERARY')); ?>
            </h2>

            <div class="timeline">
                <?php
                $count = 1;

                foreach ($lines as $line) {

                    $side = ($count % 2 == 0) ? 'right' : 'left';
                ?>
                    <div class="timeline-item <?php echo esc_attr($side); ?>">
                        <div class="timeline-content">
                            <span class="timeline-number">
                                <?php echo esc_html($count); ?>
                            </span>
                            <p><?php echo esc_html($line); ?></p>
                        </div>
                    </div>
                <?php
                    $count++;
                }
                ?>
            </div>

    <?php
        }
    }
    ?>
</section>
</section>

<div id="booking-modal" class="booking-modal">
    <div class="booking-modal-content">
        <span class="booking-modal-close">&times;</span>
        <h2 class="booking-modal-title"><?php echo esc_html(pll__('Book Your Tour')); ?></h2>
        <p class="booking-modal-subtitle">
            <?php echo esc_html($title); ?> -
            <span id="modal-dynamic-price">$<?php echo esc_html($price); ?></span>
        </p>

        <form id="booking-form" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="boskoa_contact_form">
            <input type="hidden" name="contact_nonce" value="<?php echo wp_create_nonce('boskoa_contact_form'); ?>">
            <input type="hidden" name="activity_id" value="<?php echo esc_attr($activity_id); ?>">
            <input type="hidden" name="persons" id="modal-persons" value="1">
            <input type="hidden" name="recaptcha_token" id="recaptchaToken">

            <div class="booking-form-group">
                <label for="contact_name"><?php echo esc_html(pll__('Name')); ?> *</label>
                <input type="text" id="contact_name" name="contact_name" required placeholder="<?php echo esc_attr(pll__('Your full name')); ?>">
            </div>

            <div class="booking-form-group">
                <label for="contact_email"><?php echo esc_html(pll__('Email')); ?> *</label>
                <input type="email" id="contact_email" name="contact_email" required placeholder="your@email.com">
            </div>

            <div class="booking-form-group">
                <label for="contact_phone"><?php echo esc_html(pll__('Phone (optional)')); ?></label>
                <input type="tel" id="contact_phone" name="contact_phone">
                <input type="hidden" id="contact_phone_full" name="contact_phone_full">
            </div>

            <div class="booking-form-group">
                <label for="contact_message"><?php echo esc_html(pll__('Message')); ?> *</label>
                <textarea id="contact_message" name="contact_message" rows="4" required placeholder="<?php echo esc_attr(pll__('I would like to book these activities...')); ?>"><?php echo esc_html(pll__('I would like to book the following activities:')); ?> "<?php echo esc_attr($title); ?>" $<?php echo esc_attr($price); ?>. <?php echo esc_html(pll__('Please contact me with more information.')); ?></textarea>
            </div>

            <button type="submit" class="booking-submit-btn"><?php echo esc_html(pll__('Send Booking Request')); ?></button>
        </form>
    </div>
</div>



<?php get_footer(); ?>