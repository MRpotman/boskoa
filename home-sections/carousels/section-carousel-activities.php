<section class="ourActivities">

    <h2 class="divisor-activities"><?php echo esc_html(pll__('Our Activities')); ?></h2>

    <div class="info-activities-home">
        <?php
        $post = get_page_by_path('info-actividades-home', OBJECT, 'texto');

        if ($post) :
            $descripcion = get_field('contenido', $post->ID);
        ?>
            <h2 class="info-activities-home-text">
                <?php echo esc_html($descripcion); ?>
            </h2>

            <button class="info-activities-home-button">
                <?php echo esc_html(pll__('See all activities')); ?>
            </button>
        <?php endif; ?>
    </div>


    <div class="carousel-activities">
        <?php
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
                    'link' => site_url('/product-view/?activity_id=' . get_the_ID()),
                ];

                if (empty($package['image'])) {
                    $package['image'] = get_template_directory_uri() . '/assets/img/placeholder-package.jpg';
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

</section>
