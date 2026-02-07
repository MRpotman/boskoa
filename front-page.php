<?php get_header(); ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<main class="home">

  <!-- HERO dinámico -->
  <section class="hero hero-carousel">
    <div class="hero-slide-social-container">
      <a href="#" class="face"><i class="fa-brands fa-facebook"></i></a>
      <a href="#" class="insta"><i class="fa-brands fa-instagram"></i></a>
       <a href="#" class="x"><i class="fa-brands fa-twitter"></i></a>
   
  </div>
    <div class="slides">
      <?php
        $slides = new WP_Query(['post_type' => 'slide', 'posts_per_page' => -1]);
        if ($slides->have_posts()) :
          while ($slides->have_posts()) : $slides->the_post();
            $img = get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>
            <div class="slide <?php echo $slides->current_post === 0 ? 'active' : ''; ?>" style="background-image: url('<?php echo esc_url($img); ?>')">
              <div class="hero-slide-title-container">
                <h1 class= "hero-slide-title"><?php the_title(); ?></h1>
              </div>

                <div class="button-container">
                  <button class="button-hero-slider">SEE PACKS -> </button>
                </div>
              <div class="hero-slide-content-container">
                <h2 class="hero-slide-content"><?php the_content(); ?></h2>
              </div>
            </div>
          <?php endwhile;
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
  

  <!-- TOURS -->
  <section id="tours" class="tours">
    <h2>Featured Tours</h2>

    <div class="tours-grid">
      <article class="tour-card">Tour 1</article>
      <article class="tour-card">Tour 2</article>
      <article class="tour-card">Tour 3</article>
    </div>
  </section>

</main>


<?php get_footer(); ?>
