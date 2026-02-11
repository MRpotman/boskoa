<?php
/**
 * Hero Banner para la página de Paquetes
 */

// Valores iniciales
$hero_image = '';
$hero_titulo = '';
$hero_subtitulo = '';

// Obtener el post directamente por slug (más eficiente que WP_Query)
$post = get_page_by_path('hero-packages', OBJECT, 'texto');

if ($post) {

    $hero_image     = get_field('imagen', $post->ID);
    $hero_titulo    = get_field('titulo', $post->ID);
    $hero_subtitulo = get_field('contenido', $post->ID);
}

// Valores por defecto si están vacíos
$hero_image     = $hero_image ?: get_template_directory_uri() . '/assets/img/placeholder-package.jpg';
$hero_titulo    = $hero_titulo ?: 'Tours Pack';
$hero_subtitulo = $hero_subtitulo ?: 'In this section we offer packages that consist of several activities, with a set price';
?>

<div class="hero-packages" style="background-image: url('<?php echo esc_url($hero_image); ?>');">
    
    <div class="hero-packages-overlay">
        <div class="hero-packages-content">
            <h1 class="hero-packages-titulo">
                <?php echo esc_html($hero_titulo); ?>
            </h1>

            <p class="hero-packages-subtitulo">
                <?php echo esc_html($hero_subtitulo); ?>
            </p>
        </div>
    </div>

    <!-- Wave SVG divider -->
    <svg class="hero-packages-wave" viewBox="0 0 1440 100" preserveAspectRatio="none">
        <path fill="#FFFFFF" d="M0,50 Q360,0 720,50 T1440,50 L1440,100 L0,100 Z"></path>
    </svg>

</div>
