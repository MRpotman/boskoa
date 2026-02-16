<?php
/**
 * Template Name: Tour Packages
 * 
 * Página para mostrar todos los paquetes turísticos desde WordPress
 */

get_header(); 

// Va aqui el hero banner de paquetes

get_template_part('parts/hero-package');


// Configurar paginación
$items_per_page = 16;

// Determinar página actual (compatible con permalinks y query var)
$paged_var = get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : null);
$current_page = null;
if ($paged_var) {
    $current_page = intval($paged_var);
} elseif (isset($_GET['paged'])) {
    $current_page = intval($_GET['paged']);
} else {
    $current_page = 1;
}
$current_page = max(1, $current_page);

// Query para obtener el total de paquetes
$total_query = new WP_Query([
    'post_type'      => 'tour_package',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'fields'         => 'ids',
]);

$total_packages = $total_query->found_posts;
$total_pages = ceil($total_packages / $items_per_page);

// Query para obtener paquetes de la página actual
$packages_query = new WP_Query([
    'post_type'      => 'tour_package',
    'posts_per_page' => $items_per_page,
    'paged'          => $current_page,
    'post_status'    => 'publish',
    'orderby'        => 'meta_value_num',
    'meta_key'       => '_package_order',
    'order'          => 'ASC',
]);

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
            <h2 class="tours-subtitle"><?php echo esc_html($package_title); ?></h2>
            <h1 class="tours-title"><?php echo esc_html($package_title2); ?></h1>
            <p class="tours-description"><?php echo esc_html($package_description); ?></p>
        </div>

        <!-- Loading Spinner SOBRE LAS CARDS -->
        <div class="tours-grid-container">
            <div class="tours-loading-overlay" aria-hidden="true">
                <div class="tours-loading-spinner">
                    <!-- NUEVO: Spinner de puntos animados en verde -->
                    <svg fill="#2FB468" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" width="60" height="60">
                        <circle cx="4" cy="12" r="0">
                            <animate begin="0;spinner_z0Or.end" attributeName="r" calcMode="spline" dur="0.5s" keySplines=".36,.6,.31,1" values="0;3" fill="freeze"/>
                            <animate begin="spinner_OLMs.end" attributeName="cx" calcMode="spline" dur="0.5s" keySplines=".36,.6,.31,1" values="4;12" fill="freeze"/>
                            <animate begin="spinner_UHR2.end" attributeName="cx" calcMode="spline" dur="0.5s" keySplines=".36,.6,.31,1" values="12;20" fill="freeze"/>
                            <animate id="spinner_lo66" begin="spinner_Aguh.end" attributeName="r" calcMode="spline" dur="0.5s" keySplines=".36,.6,.31,1" values="3;0" fill="freeze"/>
                            <animate id="spinner_z0Or" begin="spinner_lo66.end" attributeName="cx" dur="0.001s" values="20;4" fill="freeze"/>
                        </circle>
                        <circle cx="4" cy="12" r="3">
                            <animate begin="0;spinner_z0Or.end" attributeName="cx" calcMode="spline" dur="0.5s" keySplines=".36,.6,.31,1" values="4;12" fill="freeze"/>
                            <animate begin="spinner_OLMs.end" attributeName="cx" calcMode="spline" dur="0.5s" keySplines=".36,.6,.31,1" values="12;20" fill="freeze"/>
                            <animate id="spinner_JsnR" begin="spinner_UHR2.end" attributeName="r" calcMode="spline" dur="0.5s" keySplines=".36,.6,.31,1" values="3;0" fill="freeze"/>
                            <animate id="spinner_Aguh" begin="spinner_JsnR.end" attributeName="cx" dur="0.001s" values="20;4" fill="freeze"/>
                            <animate begin="spinner_Aguh.end" attributeName="r" calcMode="spline" dur="0.5s" keySplines=".36,.6,.31,1" values="0;3" fill="freeze"/>
                        </circle>
                        <circle cx="12" cy="12" r="3">
                            <animate begin="0;spinner_z0Or.end" attributeName="cx" calcMode="spline" dur="0.5s" keySplines=".36,.6,.31,1" values="12;20" fill="freeze"/>
                            <animate id="spinner_hSjk" begin="spinner_OLMs.end" attributeName="r" calcMode="spline" dur="0.5s" keySplines=".36,.6,.31,1" values="3;0" fill="freeze"/>
                            <animate id="spinner_UHR2" begin="spinner_hSjk.end" attributeName="cx" dur="0.001s" values="20;4" fill="freeze"/>
                            <animate begin="spinner_UHR2.end" attributeName="r" calcMode="spline" dur="0.5s" keySplines=".36,.6,.31,1" values="0;3" fill="freeze"/>
                            <animate begin="spinner_Aguh.end" attributeName="cx" calcMode="spline" dur="0.5s" keySplines=".36,.6,.31,1" values="4;12" fill="freeze"/>
                        </circle>
                        <circle cx="20" cy="12" r="3">
                            <animate id="spinner_4v5M" begin="0;spinner_z0Or.end" attributeName="r" calcMode="spline" dur="0.5s" keySplines=".36,.6,.31,1" values="3;0" fill="freeze"/>
                            <animate id="spinner_OLMs" begin="spinner_4v5M.end" attributeName="cx" dur="0.001s" values="20;4" fill="freeze"/>
                            <animate begin="spinner_OLMs.end" attributeName="r" calcMode="spline" dur="0.5s" keySplines=".36,.6,.31,1" values="0;3" fill="freeze"/>
                            <animate begin="spinner_UHR2.end" attributeName="cx" calcMode="spline" dur="0.5s" keySplines=".36,.6,.31,1" values="4;12" fill="freeze"/>
                            <animate begin="spinner_Aguh.end" attributeName="cx" calcMode="spline" dur="0.5s" keySplines=".36,.6,.31,1" values="12;20" fill="freeze"/>
                        </circle>
                    </svg>
                    <p class="tours-loading-text">Cargando paquetes...</p>
                </div>
            </div>

            <div class="tours-grid">
                <?php
                if ($packages_query->have_posts()) :
                    while ($packages_query->have_posts()) : $packages_query->the_post();
                        
                        // Preparar datos del paquete
                        $package = [
                            'id'       => get_the_ID(),
                            'title'    => get_the_title(),
                            'image'    => get_the_post_thumbnail_url(get_the_ID(), 'large'),
                            'price'    => get_post_meta(get_the_ID(), '_package_price', true),
                            'location' => get_post_meta(get_the_ID(), '_package_locations', true),
                            'family'   => get_post_meta(get_the_ID(), '_package_family_friendly', true) === 'yes',
                            'link'     => get_permalink(),
                            'excerpt'  => get_the_excerpt(),
                        ];
                        
                        // Valores por defecto si están vacíos
                        if (empty($package['price'])) {
                            $package['price'] = '$500';
                        }
                        if (empty($package['location'])) {
                            $package['location'] = '0 Location';
                        }
                        if (empty($package['image'])) {
                            $package['image'] = get_template_directory_uri() . '/assets/img/placeholder-package.jpg';
                        }
                        
                        // Incluir template de tarjeta
                        set_query_var('package', $package);
                        get_template_part(
                            'parts/package-card',
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
                        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5">
                            <path
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <h3>No hay paquetes disponibles</h3>
                        <p>Estamos trabajando en nuevos paquetes turísticos. Por favor, vuelve pronto.</p>
                        <?php if (current_user_can('edit_posts')) : ?>
                        <a href="<?php echo admin_url('post-new.php?post_type=tour_package'); ?>" class="btn-add-package">
                            Añadir Primer Paquete
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
                endif;
                ?>
            </div>
        </div>

        <!-- Animated Pagination Section -->
        <?php if ($total_pages > 1) : ?>
        <div class="pagination-container">
            <div class="pagination-wrapper">

                <!-- Previous Button -->
                <div class="pagination-nav">
                    <?php if ($current_page > 1) : ?>
                    <a href="<?php echo esc_url(get_pagenum_link($current_page - 1)); ?>"
                        class="pagination-nav-button pagination-prev" aria-label="Página anterior">
                        <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                        <span class="visually-hidden">Página anterior</span>
                    </a>
                    <?php else: ?>
                    <span class="pagination-nav-button pagination-prev disabled" aria-hidden="true"
                        aria-disabled="true">
                        <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                    </span>
                    <?php endif; ?>
                </div>

                <!-- Page Numbers with Animated Indicator -->
                <div class="pagination-indexes">
                    <nav class="pagination" aria-label="Paginación de paquetes"
                        style="--index: <?php echo max(0, $current_page - 1); ?>;">
                        <?php for ($page = 1; $page <= $total_pages; $page++) : ?>
                        <a href="<?php echo esc_url(get_pagenum_link($page)); ?>"
                            class="pagination-button<?php echo $page === $current_page ? ' active' : ''; ?>"
                            aria-label="Ir a la página <?php echo $page; ?>"
                            <?php echo $page === $current_page ? 'aria-current="page"' : ''; ?>>
                            <span class="visually-hidden">Ir a la página <?php echo $page; ?></span>
                        </a>
                        <?php endfor; ?>

                        <!-- Animated current page indicator -->
                        <span class="pagination-current" aria-hidden="true"></span>
                    </nav>
                </div>

                <!-- Next Button -->
                <div class="pagination-nav">
                    <?php if ($current_page < $total_pages) : ?>
                    <a href="<?php echo esc_url(get_pagenum_link($current_page + 1)); ?>"
                        class="pagination-nav-button pagination-next" aria-label="Próxima página">
                        <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
                        <span class="visually-hidden">Próxima página</span>
                    </a>
                    <?php else: ?>
                    <span class="pagination-nav-button pagination-next disabled" aria-hidden="true"
                        aria-disabled="true">
                        <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
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