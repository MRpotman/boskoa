<?php
/**
 * Template Name: About Us
 * 
 * Página para mostrar la información sobre nosotros (Quiénes Somos)
 */

get_header();

// Hero de About Us
get_template_part('about-sections/hero-about');

// Secciones de About Us
get_template_part('about-sections/about-sections');

// Sección de Equipo
get_template_part('about-sections/about-team');

// sección de mapa
get_template_part('about-sections/map-section');

// Sección de Reservas
get_template_part('about-sections/about-reservation');

get_footer();
?>
