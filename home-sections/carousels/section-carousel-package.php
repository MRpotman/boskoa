<section class="ourPackets">

    <h2 class="divisor-paquetes"><?php echo esc_html(pll__('Our packages')); ?></h2>

    <div class="info-paquetes-home">
        <?php
        $post = get_page_by_path('info-paquetes-home', OBJECT, 'texto');

            if ($post && function_exists('pll_get_post')) {
                $translated_id = pll_get_post($post->ID);
                if ($translated_id) {
                    $post = get_post($translated_id);
                }
            }

            if ($post) :
                $descripcion = get_field('contenido', $post->ID);
            ?>
            <h2 class="info-paquetes-home-text">
                <?php echo esc_html($descripcion); ?>
            </h2>

            <?php
                $package_page = get_page_by_path('package');
                $package_id   = $package_page ? $package_page->ID : 0;

                if (function_exists('pll_get_post') && $package_id) {
                    $package_id = pll_get_post($package_id);
                }

                $package_url = $package_id ? get_permalink($package_id) : '#';
                ?>

                <a href="<?php echo esc_url($package_url); ?>" class="info-paquetes-home-button">
                    <?php echo esc_html(pll__('See all tours')); ?>
                </a>
        <?php endif; ?>
    </div>


    <div class="carousel-wrapper">

    <button class="carousel-arrow left">&#10094;</button>

    <div class="carousel-paquetes">
        <?php
        $packages = new WP_Query([
            'post_type'      => 'tour_package',
            'posts_per_page' => 8,
            'post_status'    => 'publish',
        ]);

        if ($packages->have_posts()) :
            while ($packages->have_posts()) : $packages->the_post();

                $package_view_page = get_page_by_path('package-view');
                    $package_view_id   = $package_view_page ? $package_view_page->ID : 0;

                    if (function_exists('pll_get_post') && $package_view_id) {
                        $package_view_id = pll_get_post($package_view_id);
                    }

                    $package_view_url = $package_view_id ? get_permalink($package_view_id) : '#';

                    $package = [
                        'id'       => get_the_ID(),
                        'image'    => get_field('imagen'),
                        'title'    => get_field('titulo') ?: get_the_title(),
                        'location' => get_field('ubicacion'),
                        'link'     => $package_view_url . '?package_id=' . get_the_ID(),
                    ];

                if (empty($package['location'])) {
                    $package['location'] = '0 Location';
                }

                if (empty($package['image'])) {
                    $package['image'] = get_template_directory_uri() . '/assets/img/placeholder-package.svg';
                }

                get_template_part(
                    'parts/package-card-carousel',
                    null,
                    ['package' => $package]
                );

            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </div>

    <button class="carousel-arrow right">&#10095;</button>

</div>

</section>