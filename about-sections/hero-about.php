<?php
/**
 * Hero Banner para la página de About Us (Quiénes Somos)
 */

// Obtener datos mediante una consulta
$hero_image = '';
$hero_titulo = '';
$hero_subtitulo = '';

$hero_query = new WP_Query([
    'post_type' => 'texto',
    'name' => 'hero-about', // crea un post con este slug y añade los campos ACF
    'posts_per_page' => 1,
]);

if ($hero_query->have_posts()) {
    while ($hero_query->have_posts()) {
        $hero_query->the_post();
        $hero_image = get_field('imagen') ?: get_field('Imagen') ?: get_field('hero_image') ?: '';
        $hero_titulo = get_field('titulo') ?: get_field('Titulo') ?: get_field('hero_title') ?: get_the_title();
        $hero_subtitulo = get_field('contenido') ?: get_field('Contenido') ?: get_field('hero_subtitulo') ?: get_the_excerpt();
    }
    wp_reset_postdata();
} else {
    // Fallback
    $hero_image = get_field('imagen') ?: get_field('Imagen') ?: get_field('hero_about_imagen');
    $hero_titulo = get_field('titulo') ?: get_field('Titulo') ?: get_field('hero_about_titulo');
    $hero_subtitulo = get_field('contenido') ?: get_field('Contenido') ?: get_field('hero_about_subtitulo');
}

// Valores por defecto si están vacíos
if (empty($hero_image)) {
    $hero_image = get_template_directory_uri() . '/assets/img/placeholder-hero.jpg';
}
if (empty($hero_titulo)) {
    $hero_titulo = 'Who We Are';
}
if (empty($hero_subtitulo)) {
    $hero_subtitulo = 'Discover our passion for Costa Rica and sustainable tourism';
}
?>

<div class="hero-about" style="background-image: url('<?php echo esc_url($hero_image); ?>');">
    <div class="hero-about-overlay">
        <div class="hero-about-content">
            <h1 class="hero-about-titulo"><?php echo esc_html($hero_titulo); ?></h1>
            <p class="hero-about-subtitulo"><?php echo esc_html($hero_subtitulo); ?></p>
        </div>
    </div>
</div>
