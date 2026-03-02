<?php
/**
 * Hero Banner para la página de About Us
 */

// Valores iniciales
$hero_image = '';
$hero_titulo = '';
$hero_subtitulo = '';

$post = get_page_by_path('hero-about', OBJECT, 'texto');

if ($post) {

    if (function_exists('pll_get_post')) {
        $translated_id = pll_get_post($post->ID);
        if ($translated_id) {
            $post = get_post($translated_id);
        }
    }

    $hero_image     = get_field('imagen', $post->ID);
    $hero_titulo    = get_field('titulo', $post->ID) ?: get_the_title($post->ID);
    $hero_subtitulo = get_field('contenido', $post->ID) ?: get_the_excerpt($post->ID);
}

if (is_array($hero_image)) {
    $hero_image = $hero_image['url'] ?? '';
}

$hero_image = $hero_image ?: get_template_directory_uri() . '/assets/img/placeholder-hero.jpg';

if (!$hero_titulo) {
    $hero_titulo = function_exists('pll__') 
        ? pll__('Who We Are') 
        : 'Who We Are';
}

if (!$hero_subtitulo) {
    $hero_subtitulo = function_exists('pll__') 
        ? pll__('Discover our passion for Costa Rica and sustainable tourism') 
        : 'Discover our passion for Costa Rica and sustainable tourism';
}
?>

<div class="hero-about" style="background-image: url('<?php echo esc_url($hero_image); ?>');">
    <div class="hero-about-overlay">
        <div class="hero-about-content">
            <h1 class="hero-about-titulo">
                <?php echo esc_html($hero_titulo); ?>
            </h1>
            <p class="hero-about-subtitulo">
                <?php echo esc_html($hero_subtitulo); ?>
            </p>
        </div>
    </div>
</div>