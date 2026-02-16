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
$title       = get_field('titulo', $activity_id) ?: get_the_title($activity_id);
$description = get_field('descripcion', $activity_id);
$price       = get_field('precio', $activity_id);
$image       = get_field('imagen', $activity_id);

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

        <div class="product-view-upper-site">

            <div class="product-view-left-text">

                <h2 class="product-view-first-text">
                    Disfruta de tus vacaciones con nuestro tour:
                </h2>

                <h1 class="product-view-product-name">
                    <?php echo esc_html($title); ?>
                </h1>

                <div class="product-view-price">
                    $<?php echo esc_html($price); ?>
                </div>

                <div class="product-view-buttons">

                    <button class="product-view-book-now">
                        Book Now
                    </button>

                    <a href="<?php echo esc_url(site_url('/activities-tour')); ?>">
                        View other packages
                    </a>

                </div>

            </div>

            <div class="product-view-rigth-img">
                <img src="<?php echo esc_url($image); ?>" 
                     alt="<?php echo esc_attr($title); ?>">
            </div>

        </div>

        <div class="product-view-divisor"></div>

        <div class="product-view-down-side">

            <h2>DESCRIPCIÓN</h2>
            <p class="product-view-short-description">
                <?php echo esc_html($description); ?>
            </p>

            <!-- INCLUIDO -->
            <div class="product-view-included-wrapper">
                <div class="product-view-included-column">

                    <h2>INCLUIDO</h2>

                    <?php
                    $included = get_field('articulos_incluidos', $activity_id);

                    if ($included) {

                        $lines = explode("\n", $included);

                        echo "<ul class='product-view-included-list'>";

                        foreach ($lines as $line) {
                            $line = trim($line);
                            if (!empty($line)) {
                                echo "<li>" . esc_html($line) . "</li>";
                            }
                        }

                        echo "</ul>";
                    }
                    ?>

                </div>
            </div>

            <!-- IDIOMAS -->
            <?php
            $hosts = get_field('anfitrion', $activity_id);

            if ($hosts) {

                echo "<div class='product-view-host-languages'>";
                echo "<h2>Idiomas del anfitrión</h2>";
                echo "<div class='language-badges'>";

                $languages = array();

                foreach ($hosts as $language) {
                    $languages[] = $language['label'];
                }

                echo "<p>" . esc_html(implode(', ', $languages)) . "</p>";

                echo "</div>";
                echo "</div>";
            }
            ?>

            <!-- ITINERARIO -->
            <h2>ITINERARIO</h2>

            <?php
            $itinerary = get_field('itinerario', $activity_id);

            if ($itinerary) {

                $lines = explode("\n", $itinerary);

                echo "<ol class='product-view-itinerary'>";

                foreach ($lines as $line) {
                    $line = trim($line);
                    if (!empty($line)) {
                        echo "<li>" . esc_html($line) . "</li>";
                    }
                }

                echo "</ol>";
            }
            ?>

            <!-- INFORMACIÓN ADICIONAL -->
            <h2>INFORMACIÓN ADICIONAL</h2>

            <?php
            $aditional_info = get_field('informacion__adicional', $activity_id);

            if ($aditional_info) {

                $lines = explode("\n", $aditional_info);

                echo "<ul class='product-view-aditional-info'>";

                foreach ($lines as $line) {
                    $line = trim($line);
                    if (!empty($line)) {
                        echo "<li>" . esc_html($line) . "</li>";
                    }
                }

                echo "</ul>";
            }
            ?>

            <!-- PUNTO DE ENCUENTRO -->
            <h2>PUNTO DE ENCUENTRO</h2>

            <?php
                $meeting_point = get_field('punto_de_encuentro', $activity_id);
                $meeting_link  = get_field('encuentro_link', $activity_id);
                ?>

                <?php if ($meeting_point): ?>
                    <h3><?php echo esc_html($meeting_point); ?></h3>
                <?php endif; ?>

                <?php if ($meeting_link): ?>
                    <a href="<?php echo esc_url($meeting_link); ?>" 
                    target="_blank"
                    rel="noopener noreferrer"
                    class="product-view-map-link">
                        Abrir en Google Maps
                    </a>
                <?php endif; ?>

        </div>

    </div>
</section>

<?php get_footer(); ?>