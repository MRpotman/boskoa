<section class="ourActivities">

    <h2 class="divisor-activities"><?php echo esc_html(pll__('Our Activities')); ?></h2>

    <div class="info-activities-home">
        <?php
        $post = get_page_by_path('info-actividades-home', OBJECT, 'texto');

        if ($post && function_exists('pll_get_post')) {
            $translated_id = pll_get_post($post->ID);
            if ($translated_id) {
                $post = get_post($translated_id);
            }
        }
        if ($post) :
            $descripcion = get_field('contenido', $post->ID);
        ?>
            <h2 class="info-activities-home-text">
                <?php echo esc_html($descripcion); ?>
            </h2>
            <?php
            $activities_page = get_page_by_path('activities');
            $activities_id   = $activities_page ? $activities_page->ID : 0;

            if (function_exists('pll_get_post') && $activities_id) {
                $translated = pll_get_post($activities_id);
                $activities_id = $translated ?: $activities_id;
            }

            $activities_url = $activities_id ? get_permalink($activities_id) : home_url('/activities');
            ?>

            <a href="<?php echo esc_url($activities_url); ?>" class="info-activities-home-button">
                <?php echo esc_html(pll__('See all activities')); ?>
            </a>
        <?php endif; ?>
    </div>


    <div class="carousel-wrapper">

    <button class="carousel-arrow left">&#10094;</button>

    <div class="carousel-activities">
        <?php
        // Resolver product-view URL con Polylang FUERA del loop
        $product_view_page = get_page_by_path('product-view');
        $product_view_id   = $product_view_page ? $product_view_page->ID : 0;

        if (function_exists('pll_get_post') && $product_view_id) {
            $translated = pll_get_post($product_view_id);
            $product_view_id = $translated ?: $product_view_id;
        }

        $product_view_url = $product_view_id ? get_permalink($product_view_id) : site_url('/product-view/');

        $info = new WP_Query([
            'post_type'      => 'activity',
            'posts_per_page' => 8,
            'post_status'    => 'publish'
        ]);

        if ($info->have_posts()) :
            while ($info->have_posts()) : $info->the_post();

                $package = [
                    'id'       => get_the_ID(),
                    'image'    => get_field('imagen'),
                    'title'    => get_field('titulo') ?: get_the_title(),
                    'location' => get_field('ubicacion'),
                    'link'     => $product_view_url . '?activity_id=' . get_the_ID(),
                ];

                if (empty($package['image'])) {
                    $package['image'] = get_template_directory_uri() . '/assets/img/placeholder-package.svg';
                }

                if (empty($package['location'])) {
                    $package['location'] = '0 Location';
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