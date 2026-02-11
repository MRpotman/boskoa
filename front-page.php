<?php get_header(); ?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<main class="home">

  <?php get_template_part('home-sections/hero'); ?>
  <div class="centrado">
      <?php get_template_part('home-sections/general-info'); ?>
  </div>
  <div class="centrado">
      <?php get_template_part('home-sections/carousels/section-carousel-package'); ?>
  </div>
<div class="centrado">
      <?php get_template_part('home-sections/carousels/section-carousel-activities'); ?>
</div>

  <?php get_template_part('home-sections/section-comments'); ?>

</main>

<?php get_footer(); ?>
