<?php
/**
 * Template Name: Product View
 */

get_header();

// Obtener ID desde URL
$activity_id = isset($_GET['activity_id']) ? intval($_GET['activity_id']) : 0;

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
    $image = get_template_directory_uri() . '/assets/img/placeholder-package.jpg';
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
        <h2><?php echo esc_html(pll__('Descripción')); ?></h2>
        <p><?php echo esc_html($description); ?></p>
    </div>
    <?php endif; ?>
        </div>

        <div class="product-hero-right">
            <div class="product-hero-card">
                <p class="pv-label">Enjoy your vacation with our activities:</p>
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
                    <input 
                        type="number" 
                        id="person-count" 
                        min="1" 
                        value="1">
                </div>
                <div class="product-hero-price">
                    <span class="product-hero-price-label">
                        <?php echo esc_html(pll__('Price:')); ?>
                    </span>
                    <span class="product-hero-price-value" id="dynamic-price" data-base-price ="<?php echo esc_attr($price); ?>">
                         $<?php echo esc_html($price); ?>
                    </span>
                </div>

                <div class="product-hero-buttons">
                    <button class="product-hero-book" id="open-booking-modal">
                        <?php echo esc_html(pll__('Book Now')); ?>
                    </button>

                    <a href="<?php echo esc_url(site_url('/activities')); ?>" class="product-hero-secondary">
                        <?php echo esc_html(pll__('View other activities')); ?>
                    </a>
                </div>

            </div>

        </div>

    </div>

</section>
<section class="product-view-main-section">
    <div class="product-view-main-div product-view-info-grid">

        <?php
        $included        = get_field('articulos_incluidos', $activity_id);
        $aditional_info  = get_field('informacion__adicional', $activity_id);
        $hosts           = get_field('anfitrion', $activity_id);
        $meeting_point   = get_field('punto_de_encuentro', $activity_id);
        $meeting_link    = get_field('encuentro_link', $activity_id);
        ?>

        <!-- INCLUIDO -->
        <?php if ($included): ?>
            <div class="info-card">
                <h2>INCLUIDO</h2>
                <ul class="product-view-included-list">
                    <?php
                    $lines = explode("\n", $included);
                    foreach ($lines as $line):
                        $line = trim($line);
                        if (!empty($line)):
                    ?>
                        <li><?php echo esc_html($line); ?></li>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- INFORMACIÓN ADICIONAL -->
        <?php if ($aditional_info): ?>
            <div class="info-card">
                <h2><?php echo esc_html(pll__('ADDITIONAL INFORMATION')); ?></h2>
                <ul class="product-view-aditional-list">
                    <?php
                    $lines = explode("\n", $aditional_info);
                    foreach ($lines as $line):
                        $line = trim($line);
                        if (!empty($line)):
                    ?>
                        <li><?php echo esc_html($line); ?></li>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- IDIOMAS -->
        <?php if ($hosts): ?>
            <div class="info-card">
                <h2><?php echo esc_html(pll__('Host languages')); ?></h2>
                <div class="language-badges">
                    <?php
                    foreach ($hosts as $language):
                    ?>
                        <span class="language-badge">
                            <?php echo esc_html($language['label']); ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- PUNTO DE ENCUENTRO -->
        <?php if ($meeting_point || $meeting_link): ?>
            <div class="info-card">
                <h2><?php echo esc_html(pll__('MEETING POINT')); ?></h2>

                <?php if ($meeting_point): ?>
                    <p><?php echo esc_html($meeting_point); ?></p>
                <?php endif; ?>

                <?php if ($meeting_link): ?>
                <span>
                    <a href="<?php echo esc_url($meeting_link); ?>"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="product-view-map-link">
                        <?php echo esc_html(pll__('Open in Google Maps')); ?>
                    </a>
                </span>

                <?php endif; ?>
            </div>
        <?php endif; ?>

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
        <h2 class="booking-modal-title">Book Your Tour</h2>
        <p class="booking-modal-subtitle" ><?php echo esc_html($title); ?> - $<?php echo esc_html($price); ?></p>
        
        <form id="booking-form" method="POST" action="<?php echo admin_url('admin-post.php'); ?>">
            <input type="hidden" name="action" value="boskoa_contact_form">
            <input type="hidden" name="contact_nonce" value="<?php echo wp_create_nonce('boskoa_contact_form'); ?>">
            <input type="hidden" name="activity_id" value="<?php echo esc_attr($activity_id); ?>">
            <input type="hidden" name="contact_matters" value="Booking: <?php echo esc_attr($title); ?> - $<?php echo esc_attr($price); ?>">
            
            <div class="booking-form-group">
                <label for="contact_name">Name *</label>
                <input type="text" id="contact_name" name="contact_name" required placeholder="Your full name">
            </div>
            
            <div class="booking-form-group">
                <label for="contact_email">Email *</label>
                <input type="email" id="contact_email" name="contact_email" required placeholder="your@email.com">
            </div>
            
            <div class="booking-form-group">
                <label for="contact_phone">Phone (optional)</label>
                <input type="tel" id="contact_phone" name="contact_phone" placeholder="Your phone number">
            </div>
            
            <div class="booking-form-group">
                <label for="contact_message">Message *</label>
                <textarea id="contact_message" name="contact_message" rows="4" required placeholder="I would like to book this tour...">I would like to book the tour "<?php echo esc_attr($title); ?>" for $<?php echo esc_attr($price); ?>. Please contact me with more information.</textarea>
            </div>
            
            <button type="submit" class="booking-submit-btn">Send Booking Request</button>
        </form>
    </div>
</div>



<?php get_footer(); ?>
