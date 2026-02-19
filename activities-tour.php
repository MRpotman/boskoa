<?php
/**
 * Template Name: activities-tour
 * 
 * Página para mostrar la informacion detallada  de los productos
 */

get_header(); 

// Hero banner
get_template_part('parts/hero-package');


// ===============================
// CONFIGURAR PAGINACIÓN
// ===============================

$items_per_page = 16;

// Detectar página actual correctamente
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
// TOTAL DE ACTIVIDADES
// ===============================

$total_query = new WP_Query([
    'post_type'      => 'activity',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'fields'         => 'ids',
]);

$total_packages = $total_query->found_posts;
$total_pages = ceil($total_packages / $items_per_page);


// ===============================
// QUERY PRINCIPAL (ACF)
// ===============================

$packages_query = new WP_Query([
    'post_type'      => 'activity',
    'posts_per_page' => $items_per_page,
    'paged'          => $current_page,
    'post_status'    => 'publish',
]);


// ===============================
// TEXTOS DESDE CPT TEXTO
// ===============================

$post = get_page_by_path('packages-title', OBJECT, 'texto');

if ($post) {
    $package_title = get_field('titulo', $post->ID);
}

$post = get_page_by_path('package-main-text', OBJECT, 'texto');

if ($post) {
    $package_title2 = get_field('titulo', $post->ID);
    $package_description = get_field('contenido', $post->ID);
}

?>


<section class="tours-pack" id="packages">

<div class="container-packages">

    <div class="tours-header">

        <h2 class="tours-subtitle">
            <?php echo esc_html($package_title ?? ''); ?>
        </h2>

        <h1 class="tours-title">
            <?php echo esc_html($package_title2 ?? ''); ?>
        </h1>

        <p class="tours-description">
            <?php echo esc_html($package_description ?? ''); ?>
        </p>

    </div>



    <div class="tours-grid">

    <?php
    if ($packages_query->have_posts()) :

        while ($packages_query->have_posts()) :

            $packages_query->the_post();


            // ===============================
            // OBTENER DATOS DESDE ACF
            // ===============================

            $image = get_field('imagen');

            // Compatibilidad con distintos formatos de imagen ACF
            if (is_array($image)) {

                $image = $image['url'];

            } elseif (is_numeric($image)) {

                $image = wp_get_attachment_image_url($image, 'large');

            }


            $package = [

                'id' => get_the_ID(),

                'title' => get_field('titulo') ?: get_the_title(),

                'description' => get_field('descripcion'),

                'price' => get_field('precio'),

                'image' => $image,

                'location' => get_field('ubicacion'),

                'host' => get_field('anfitrion'),

                'itinerary' => get_field('itinerario'),

                'included' => get_field('articulos_incluidos'),

                'family' => get_field('familiar'),

                'link' => site_url('/product-view/?activity_id=' . get_the_ID()),

            ];


            // ===============================
            // VALORES POR DEFECTO
            // ===============================

            if (empty($package['price'])) {

                $package['price'] = '$500';

            }

            if (empty($package['location'])) {

                $package['location'] = '#';

            }

            if (empty($package['image'])) {

                $package['image'] = get_template_directory_uri() . '/assets/img/placeholder-package.jpg';

            }



            // ===============================
            // ENVIAR A TEMPLATE CARD
            // ===============================

            get_template_part(
                'parts/activities-card',
                null,
                [
                    'package' => $package
                ]
            );


        endwhile;

        wp_reset_postdata();

    else :
    ?>

        <div class="no-packages">

            <div class="no-packages-content">

                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>

                <h3><?php echo esc_html(pll__('No hay actividades disponibles')); ?></h3>

                <p><?php echo esc_html(pll__('Estamos trabajando en nuevas actividades. Por favor vuelve pronto.')); ?></p>

            </div>

        </div>

    <?php endif; ?>

    </div>



    <!-- ===============================
         PAGINACIÓN
    =============================== -->

    <?php if ($total_pages > 1) : ?>

    <div class="pagination-container">

        <div class="pagination-wrapper">


            <!-- Previous -->

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



            <!-- Numbers -->

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



            <!-- Next -->

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