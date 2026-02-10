<!-- HERO dinámico -->
  <section class="hero hero-carousel">
      <div class="hero-bottom-fade"></div>
    <div class="hero-slide-social-container">
      <a href="#" class="face"><i class="fa-brands fa-facebook"></i></a>
      <a href="#" class="insta"><i class="fa-brands fa-instagram"></i></a>
      <a href="#" class="x"><i class="fa-brands fa-twitter"></i></a>
  </div>
  
    <div class="slides">
      <?php
        $slides = new WP_Query([
          'post_type' => 'slide',
          'posts_per_page' => -1
        ]);

        if ($slides->have_posts()) :
          while ($slides->have_posts()) : $slides->the_post();
            $img         = get_field('imagen_de_fondo');
            $titulo      = get_field('texto_izquierda');
            $descripcion = get_field('texto_derecha');
      ?>
            <div class="slide <?php echo $slides->current_post === 0 ? 'active' : ''; ?>"
                style="background-image: url('<?php echo esc_url($img); ?>')">

              <div class="hero-slide-title-container">
                <h1 class="hero-slide-title">
                  <?php echo esc_html($titulo); ?>
                </h1>
              </div>

              <!-- BOTÓN IGUAL QUE ANTES -->
              <div class="button-container">
                <button class="button-hero-slider">SEE PACKS →</button>
              </div>

              <div class="hero-slide-content-container">
                <h2 class="hero-slide-content">
                  <?php echo esc_html($descripcion); ?>
                </h2>
              </div>

            </div>
      <?php
          endwhile;
          wp_reset_postdata();
        endif;
      ?>
      </div>

    <div class="slider-dots">
      <?php
        $count = $slides->post_count ?? 0;
        for ($i = 0; $i < $count; $i++) :
      ?>
        <span class="dot <?php echo $i === 0 ? 'active' : ''; ?>" data-index="<?php echo $i; ?>"></span>
      <?php endfor; ?>
    </div>

  </section>