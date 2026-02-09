<?php
/**
 * Template Name: Tour Packages
 * 
 * Página para mostrar todos los paquetes turísticos desde WordPress
 */

get_header(); 
?>

<section class="tours-pack" id="packages">
    <div class="container-packages">
        <div class="tours-header">
            <h2 class="tours-subtitle">Tours Pack</h2>
            <h1 class="tours-title">We offer the best packages so you can focus solely on enjoying yourself.</h1>
            <p class="tours-description">
                In this section we offer packages that consist of several activities, with a set price; this helps you easily decide where you want to take your adventures.
            </p>
        </div>

        <div class="tours-grid">
            <?php
            // Query para obtener los paquetes turísticos desde WordPress
            $packages_query = new WP_Query([
                'post_type'      => 'tour_package',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'orderby'        => 'meta_value_num',
                'meta_key'       => '_package_order',
                'order'          => 'ASC',
            ]);

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
                    // Pasar la variable $package al template para evitar warnings
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
                        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
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
</section>

<?php get_footer(); ?>