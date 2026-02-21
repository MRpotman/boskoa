<section class="ourPackets">

    <h2 class="divisor-paquetes"><?php echo esc_html(pll__('Our packets')); ?></h2>

    <div class="info-paquetes-home">
        <?php
        $post = get_page_by_path('info-paquetes-home', OBJECT, 'texto');

        if ($post) :
            $descripcion = get_field('contenido', $post->ID);
        ?>
            <h2 class="info-paquetes-home-text">
                <?php echo esc_html($descripcion); ?>
            </h2>

            <a href="<?php echo esc_url(get_permalink(get_page_by_path('package'))); ?>" class="info-paquetes-home-button">
                <?php echo esc_html(pll__('See all tours')); ?>
            </a>
        <?php endif; ?>
    </div>


    <div class="carousel-paquetes">
        <?php
        $packages = new WP_Query([
            'post_type'      => 'tour_package',
            'posts_per_page' => 8,
            'post_status'    => 'publish',
        ]);

        if ($packages->have_posts()) :
            while ($packages->have_posts()) : $packages->the_post();

                $package = [
                    'id'       => get_the_ID(),
                    'image'    => get_field('imagen'),
                    'title'    => get_field('titulo') ?: get_the_title(),
                    'location' => get_field('ubicacion'),
                    'link'     => site_url('/package-view/?package_id=' . get_the_ID()),
                ];

                if (empty($package['location'])) {
                    $package['location'] = '0 Location';
                }

                if (empty($package['image'])) {
                    $package['image'] = get_template_directory_uri() . '/assets/img/placeholder-package.jpg';
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

</section>