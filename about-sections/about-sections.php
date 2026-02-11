<?php
/**
 * Home Sections para About Us
 * Renderiza 3 secciones buscando posts de tipo "texto" por slug
 */

// Secciones fijas
$sections = [
    1 => ['slug' => 'section-1', 'section_type' => 'general', 'layout_type' => 'text-only'],
    2 => ['slug' => 'section-2', 'section_type' => 'vision',  'layout_type' => 'text-image'],
    3 => ['slug' => 'section-3', 'section_type' => 'mission', 'layout_type' => 'image-text'],
];

foreach ($sections as $section) {

    $slug        = $section['slug'];
    $section_type = $section['section_type'];
    $layout_type  = $section['layout_type'];

    $post = get_page_by_path($slug, OBJECT, 'texto');

    if (!$post) {
        continue;
    }

    $section_title   = get_field('titulo', $post->ID) ?: get_the_title($post->ID);
    $section_content = get_field('contenido', $post->ID) ?: get_the_excerpt($post->ID);
    $section_image   = get_field('imagen', $post->ID);

    if (empty($section_title) && empty($section_content)) {
        continue;
    }


    $image_url = '';
    $image_alt = $section_title;

    if (!empty($section_image)) {
        $image_url = is_array($section_image) ? $section_image['url'] : $section_image;
        $image_alt = is_array($section_image) && !empty($section_image['alt'])
            ? $section_image['alt']
            : $section_title;
    }
    ?>

    <section class="about-section about-section-<?php echo esc_attr($section_type); ?>">
        <div class="container-about">
            <div class="about-section-wrapper about-layout-<?php echo esc_attr($layout_type); ?>">

                <?php if ($layout_type === 'text-only') : ?>

                    <div class="about-section-title-col">
                        <h2 class="about-section-title">
                            <?php echo esc_html($section_title); ?>
                        </h2>
                    </div>

                    <div class="about-section-content">
                        <div class="about-section-text">
                            <?php echo wp_kses_post($section_content); ?>
                        </div>
                    </div>

                <?php elseif ($layout_type === 'text-image') : ?>

                    <div class="about-section-content">
                        <h2 class="about-section-title">
                            <?php echo esc_html($section_title); ?>
                        </h2>

                        <div class="about-section-text">
                            <?php echo wp_kses_post($section_content); ?>
                        </div>
                    </div>

                    <?php if ($image_url) : ?>
                        <div class="about-section-image">
                            <img 
                                src="<?php echo esc_url($image_url); ?>"
                                alt="<?php echo esc_attr($image_alt); ?>"
                                loading="lazy"
                            />
                        </div>
                    <?php endif; ?>

                <?php elseif ($layout_type === 'image-text') : ?>

                    <?php if ($image_url) : ?>
                        <div class="about-section-image">
                            <img 
                                src="<?php echo esc_url($image_url); ?>"
                                alt="<?php echo esc_attr($image_alt); ?>"
                                loading="lazy"
                            />
                        </div>
                    <?php endif; ?>

                    <div class="about-section-content">
                        <h2 class="about-section-title">
                            <?php echo esc_html($section_title); ?>
                        </h2>

                        <div class="about-section-text">
                            <?php echo wp_kses_post($section_content); ?>
                        </div>
                    </div>

                <?php endif; ?>

            </div>
        </div>
    </section>

    <?php
}
?>
