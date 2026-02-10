<section class="ourActivities">
      <h2 class = "divisor-activities">Our Activities</h2>
      <div class = "info-activities-home">
        <?php
          $info = new WP_Query([
            'post_type' => 'texto',
            'name' => 'info-actividades-home'
          ]);

          if ($info->have_posts()) :
            while ($info->have_posts()) : $info->the_post();
              $descripcion = get_field('contenido');
        ?>
        <h2 class = "info-activities-home-text"><?php echo esc_html($descripcion); ?></h2>
        <button class = "info-activities-home-button">See all activities</button>
      </div>
           <?php
                endwhile;
                wp_reset_postdata();
              endif;
            ?>

      <div class="carousel-activities">
        <?php
          $info = new WP_Query([
            'post_type'      => 'tour_package',
            'posts_per_page' => 8,
            'post_status'    => 'publish'
          ]);

          if ($info->have_posts()) :
            while ($info->have_posts()) : $info->the_post();

              $package = [
                'id'       => get_the_ID(),
                'image'    => get_the_post_thumbnail_url(get_the_ID(), 'large'),
                'title'    => get_the_title(),
                'location' => get_post_meta(get_the_ID(), '_package_locations', true),
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