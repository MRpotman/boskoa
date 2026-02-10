<?php get_header(); ?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<main class="home">

  <?php get_template_part('home-sections/hero'); ?>
  <?php get_template_part('home-sections/general-info'); ?>
  <?php get_template_part('home-sections/carousels/section-carousel-package'); ?>
  <?php get_template_part('home-sections/carousels/section-carousel-activities'); ?>
  <?php get_template_part('home-sections/section-comments'); ?>

</main>

<?php get_footer(); ?>
