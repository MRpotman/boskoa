<?php get_header(); ?>

<main class="home">

  <!-- HERO dinámico -->
  <section class="hero hero-carousel">
    <div class="slides">
      <?php
        $slides = new WP_Query(['post_type' => 'slide', 'posts_per_page' => -1]);
        if ($slides->have_posts()) :
          while ($slides->have_posts()) : $slides->the_post();
            $img = get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>
            <div class="slide <?php echo $slides->current_post === 0 ? 'active' : ''; ?>" style="background-image: url('<?php echo esc_url($img); ?>')">
              <h1 class= "hero-slide-title"><?php the_title(); ?></h1>
              <p><?php the_content(); ?></p>
            </div>
          <?php endwhile;
          wp_reset_postdata();
        endif;
      ?>
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
