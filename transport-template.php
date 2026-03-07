<?php
/**
 * Template Name: transport-page
 * 
 * Página para mostrar las opciones de transporte disponibles
 */

get_header();

// Hero banner
get_template_part('parts/hero-transport');


// ===============================
// CONFIGURAR PAGINACIÓN
// ===============================

$items_per_page = 16;

$paged_var = get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : null);

if ($paged_var) {
    $current_page = intval($paged_var);
} elseif (isset($_GET['paged'])) {
    $current_page = intval($_GET['paged']);
} else {
    $current_page = 1;
}

$current_page = max(1, $current_page);


// ===============================
// TOTAL DE TRANSPORTES
// ===============================

$total_query = new WP_Query([
    'post_type'      => 'transport',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'fields'         => 'ids',
]);

$total_packages = $total_query->found_posts;
$total_pages    = ceil($total_packages / $items_per_page);


// ===============================
// QUERY PRINCIPAL
// ===============================

$packages_query = new WP_Query([
    'post_type'      => 'transport',
    'posts_per_page' => $items_per_page,
    'paged'          => $current_page,
    'post_status'    => 'publish',
]);


// ===============================
// TEXTOS DESDE CPT TEXTO
// ===============================

$post = get_page_by_path('transport-subtitle', OBJECT, 'texto');
if ($post && function_exists('pll_get_post')) {
    $translated = pll_get_post($post->ID);
    if ($translated) $post = get_post($translated);
}
if ($post) {
    $transport_subtitle = get_field('titulo', $post->ID);
}

$post = get_page_by_path('transport-main-text', OBJECT, 'texto');
if ($post && function_exists('pll_get_post')) {
    $translated = pll_get_post($post->ID);
    if ($translated) $post = get_post($translated);
}
if ($post) {
    $transport_title       = get_field('titulo', $post->ID);
    $transport_description = get_field('contenido', $post->ID);
}


// ===============================
// RESOLVER transport-view URL CON POLYLANG
// ===============================

$transport_view_page = get_page_by_path('transport-view');
$transport_view_id   = $transport_view_page ? $transport_view_page->ID : 0;

if (function_exists('pll_get_post') && $transport_view_id) {
    $translated = pll_get_post($transport_view_id);
    $transport_view_id = $translated ?: $transport_view_id;
}

$transport_view_url = $transport_view_id ? get_permalink($transport_view_id) : site_url('/transport-view/');

?>


<section class="tours-pack transport-pack" id="transport">

<div class="container-packages">

    <div class="tours-header">

        <h2 class="tours-subtitle">
            <?php echo esc_html($transport_subtitle ?? ''); ?>
        </h2>

        <h1 class="tours-title">
            <?php echo esc_html($transport_title ?? ''); ?>
        </h1>

        <p class="tours-description">
            <?php echo esc_html($transport_description ?? ''); ?>
        </p>

    </div>


    <!-- GRID CONTAINER -->
    <div class="tours-grid-container">

        <!-- GRID -->
        <div class="tours-grid transport-grid">

        <?php
        if ($packages_query->have_posts()) :

            while ($packages_query->have_posts()) :

                $packages_query->the_post();

                $image = get_field('imagen');

                if (is_array($image)) {
                    $image = $image['url'];
                } elseif (is_numeric($image)) {
                    $image = wp_get_attachment_image_url($image, 'large');
                }

                if (empty($image)) {
                    $image = get_template_directory_uri() . '/assets/img/placeholder-package.svg';
                }

                // ===============================
                // ID DEL TRANSPORTE CON POLYLANG
                // ===============================

                $transport_id = get_the_ID();

                if (function_exists('pll_get_post')) {
                    $translated_transport = pll_get_post($transport_id);
                    if ($translated_transport) {
                        $transport_id = $translated_transport;
                    }
                }

                $transport = [
                    'id'          => $transport_id,
                    'title'       => get_field('titulo') ?: get_the_title(),
                    'description' => get_field('descripcion'),
                    'image'       => $image,
                    'origin'      => get_field('origen'),
                    'destination' => get_field('destino'),
                    'route_type'  => get_field('tipo_ruta'),
                    'link'        => $transport_view_url . '?transport_id=' . $transport_id,
                ];

                get_template_part(
                    'parts/transport-card',
                    null,
                    ['transport' => $transport]
                );

            endwhile;

            wp_reset_postdata();

        else :
        ?>

            <div class="no-packages">
                <div class="no-packages-content">
                    <h3><?php echo esc_html(pll__('No transport options available.')); ?></h3>
                    <p><?php echo esc_html(pll__('We are working on new routes. Please come back soon.')); ?></p>
                </div>
            </div>

        <?php endif; ?>

        </div>

    </div>


    <!-- PAGINACIÓN -->
    <?php if ($total_pages > 1) : ?>

    <div class="pagination-container">
        <div class="pagination-wrapper">

            <div class="pagination-nav">
            <?php if ($current_page > 1) : ?>
                <a href="<?php echo esc_url(get_pagenum_link($current_page - 1)); ?>"
                   class="pagination-nav-button pagination-prev">
                   <i class="fa-solid fa-chevron-left"></i>
                </a>
            <?php else : ?>
                <span class="pagination-nav-button pagination-prev disabled">
                    <i class="fa-solid fa-chevron-left"></i>
                </span>
            <?php endif; ?>
            </div>

            <div class="pagination-indexes">
                <nav class="pagination" style="--index: <?php echo max(0, $current_page - 1); ?>;">
                <?php for ($page = 1; $page <= $total_pages; $page++) : ?>
                    <a href="<?php echo esc_url(get_pagenum_link($page)); ?>"
                       class="pagination-button <?php echo $page === $current_page ? 'active' : ''; ?>">
                    </a>
                <?php endfor; ?>
                <span class="pagination-current"></span>
                </nav>
            </div>

            <div class="pagination-nav">
            <?php if ($current_page < $total_pages) : ?>
                <a href="<?php echo esc_url(get_pagenum_link($current_page + 1)); ?>"
                   class="pagination-nav-button pagination-next">
                   <i class="fa-solid fa-chevron-right"></i>
                </a>
            <?php else : ?>
                <span class="pagination-nav-button pagination-next disabled">
                    <i class="fa-solid fa-chevron-right"></i>
                </span>
            <?php endif; ?>
            </div>

        </div>
    </div>

    <?php endif; ?>

</div>

</section>


<?php get_template_part('home-sections/section-comments'); ?>

<?php get_footer(); ?>