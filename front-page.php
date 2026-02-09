<?php get_header(); ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<main class="home">

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
  

  <section  class="general-info">
    <?php
      $infoPost = new WP_Query([
        'post_type' => 'texto',
        'name' => 'texto-costa-rica'
      ]);
    
      if($infoPost -> have_posts()) :
        while ($infoPost->have_posts()) : $infoPost->the_post();
        $img = get_field('imagen');
        $titulo = get_field('titulo');
        $contenido = get_field('contenido');
    ?>


    <div class="general-info-costaRica">
      <h2><?php echo esc_html($titulo); ?></h2>
        <p><?php echo esc_html($contenido); ?></p>
    </div>
    <div class="general-info-minicard">

        <div class="general-info-img"
            style="background-image: url('<?php echo esc_url($img); ?>')">
        </div>

        <div class = "general-info-second">
          <div class = "general-info-second-card">
            <h2>+2 MILLIONS</h2>
            <p>Many people fjdisofjiso fjndsofl paradise</p>
          </div>
          <button class="button">
            <span class="button-content">Contact us >></span>
          </button>
        </div>
    </div>

    <?php
        endwhile;
        wp_reset_postdata();
      endif;
    ?>

  </section>
    
  
    <?php get_template_part('carrusel-tour'); ?>
  <?php get_template_part('section-comments'); ?>
</main>
<?php get_footer(); ?>
