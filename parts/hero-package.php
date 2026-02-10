<?php
/**
 * Hero Banner para la página de Paquetes
 */

// Obtener datos mediante una consulta (igual que en general-info.php)
$hero_image = '';
$hero_titulo = '';
$hero_subtitulo = '';

$hero_query = new WP_Query([
    'post_type' => 'texto',
    'name' => 'hero-packages', // crea un post con este slug y añade los campos ACF
    'posts_per_page' => 1,
]);

if ($hero_query->have_posts()) {
    while ($hero_query->have_posts()) {
        $hero_query->the_post();
        // Intenta varios nombres de campo (mayúsculas/minúsculas)
        $hero_image = get_field('imagen') ?: get_field('Imagen') ?: get_field('hero_image') ?: '';
        $hero_titulo = get_field('titulo') ?: get_field('Titulo') ?: get_field('hero_title') ?: get_the_title();
        $hero_subtitulo = get_field('contenido') ?: get_field('Contenido') ?: get_field('hero_subtitulo') ?: get_the_excerpt();
    }
    wp_reset_postdata();
} else {
    // Fallback: intenta obtener desde campos globales (opciones o page-level)
    $hero_image = get_field('imagen') ?: get_field('Imagen') ?: get_field('hero_packages_imagen');
    $hero_titulo = get_field('titulo') ?: get_field('Titulo') ?: get_field('hero_packages_titulo');
    $hero_subtitulo = get_field('contenido') ?: get_field('Contenido') ?: get_field('hero_packages_subtitulo');
}

// Valores por defecto si están vacíos
if (empty($hero_image)) {
    $hero_image = get_template_directory_uri() . '/assets/img/placeholder-package.jpg';
}
if (empty($hero_titulo)) {
    $hero_titulo = 'Tours Pack';
}
if (empty($hero_subtitulo)) {
    $hero_subtitulo = 'In this section we offer packages that consist of several activities, with a set price';
}
?>

<div class="hero-packages" style="background-image: url('<?php echo esc_url($hero_image); ?>');">
    <div class="hero-packages-overlay">
        <div class="hero-packages-content">
            <h1 class="hero-packages-titulo"><?php echo esc_html($hero_titulo); ?></h1>
            <p class="hero-packages-subtitulo"><?php echo esc_html($hero_subtitulo); ?></p>
        </div>
    </div>

    <!-- Wave SVG divider -->
    <svg class="hero-packages-wave" viewBox="0 0 1440 100" preserveAspectRatio="none">
        <path fill="#FFFFFF" d="M0,50 Q360,0 720,50 T1440,50 L1440,100 L0,100 Z"></path>
    </svg>
</div>
