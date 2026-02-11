<?php
/**
 * Template Name: About Us
 * 
 * Página para mostrar la información sobre nosotros (Quiénes Somos)
 */

get_header();

// Hero de About Us
get_template_part('about-sections/hero-about');

// Secciones ACF
get_template_part('about-sections/about-sections');

get_footer();
?>
