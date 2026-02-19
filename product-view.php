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

<section class="product-view-main-section">

    <div class="product-view-main-div">

        <!-- ========================= -->
        <!-- COLUMNA IZQUIERDA -->
        <!-- ========================= -->
        <div class="product-view-left-text">

            <div class="product-view-upper-site">

                <h2 class="product-view-first-text">
                    <?php echo esc_html(pll__('Disfruta de tus vacaciones con nuestro tour:')); ?>
                </h2>

                <h1 class="product-view-product-name">
                    <?php echo esc_html($title); ?>  $<?php echo esc_html($price); ?>
                </h1>

                <div class="product-view-buttons">
                    <button class="product-view-book-now">
                        Book Now
                    </button>

                    <a href="<?php echo esc_url(site_url('/activities-tour')); ?>">
                        <?php echo esc_html(pll__('View other packages')); ?>
                    </a>
                </div>

            </div>

            <div class="product-view-divisor"></div>

            <div class="product-view-down-side">
                <h2><?php echo esc_html(pll__('DESCRIPCIÓN')); ?></h2>
                <p >
                    <?php echo esc_html($description); ?>
                </p>
            </div>

        </div>
        <div class="product-view-rigth-img">
            <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>">
        </div>

    </div>

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
                <h2><?php echo esc_html(pll__('INFORMACIÓN ADICIONAL')); ?></h2>
                <ul class="product-view-included-list">
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
                <h2><?php echo esc_html(pll__('Idiomas del anfitrión')); ?></h2>
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
                <h2><?php echo esc_html(pll__('PUNTO DE ENCUENTRO')); ?></h2>

                <?php if ($meeting_point): ?>
                    <p><?php echo esc_html($meeting_point); ?></p>
                <?php endif; ?>

                <?php if ($meeting_link): ?>
                    <a href="<?php echo esc_url($meeting_link); ?>"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="product-view-map-link">
                        <?php echo esc_html(pll__('Abrir en Google Maps')); ?>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</section>



    <section class="itinerary-section">
        <h2 class="itinerary-title"><?php echo esc_html(pll__('ITINERARIO')); ?></h2>

        <?php
        $itinerary = get_field('itinerario', $activity_id);

        if ($itinerary) {

            $lines = explode("\n", $itinerary);
            echo "<div class='timeline'>";
            $count = 1;

            foreach ($lines as $line) {
                $line = trim($line);

                if (!empty($line)) {

                    $side = ($count % 2 == 0) ? 'right' : 'left';

                    echo "
                        <div class='timeline-item {$side}'>
                            <div class='timeline-content'>
                                <span class='timeline-number'>{$count}</span>
                                <p>" . esc_html($line) . "</p>
                            </div>
                        </div>
                    ";

                    $count++;
                }
            }

            echo "</div>";
        }
        ?>
    </section>
</section>

<!-- Booking Modal -->
<div id="booking-modal" class="booking-modal">
    <div class="booking-modal-content">
        <span class="booking-modal-close">&times;</span>
        <h2 class="booking-modal-title">Book Your Tour</h2>
        <p class="booking-modal-subtitle"><?php echo esc_html($title); ?> - $<?php echo esc_html($price); ?></p>
        
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
