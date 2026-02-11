<?php
/**
 * Home Sections para About Us
 * Renderiza 3 secciones buscando posts de tipo "texto" por slug
 * Con layouts específicos por sección
 */

// Array de secciones fijas
$sections = array(
    1 => array('slug' => 'section-1', 'section_type' => 'general', 'layout_type' => 'text-only'),
    2 => array('slug' => 'section-2', 'section_type' => 'vision', 'layout_type' => 'text-image'),
    3 => array('slug' => 'section-3', 'section_type' => 'mission', 'layout_type' => 'image-text'),
);

foreach ($sections as $section) {
    $slug = $section['slug'];
    $section_type = $section['section_type'];
    $layout_type = $section['layout_type'];
    
    // Buscar el post de tipo "texto" por slug
    $section_query = new WP_Query([
        'post_type' => 'texto',
        'name' => $slug,
        'posts_per_page' => 1,
    ]);
    
    // Variables por defecto
    $section_title = '';
    $section_content = '';
    $section_image = '';
    
    if ($section_query->have_posts()) {
        while ($section_query->have_posts()) {
            $section_query->the_post();
            
            // Obtener los campos ACF del post
            $section_title = get_field('titulo') ?: get_field('Titulo') ?: get_the_title();
            $section_content = get_field('contenido') ?: get_field('Contenido') ?: get_the_excerpt();
            $section_image = get_field('imagen') ?: get_field('Imagen') ?: '';
        }
        wp_reset_postdata();
    }
    
    // Saltar si la sección no tiene contenido
    if (empty($section_title) && empty($section_content)) {
        continue;
    }
    
    ?>
    <section class="about-section about-section-<?php echo esc_attr($section_type); ?>">
        <div class="container-about">
            <div class="about-section-wrapper about-layout-<?php echo esc_attr($layout_type); ?>">
                
                <!-- LAYOUT 1: Solo texto (Título izq, Contenido der) -->
                <?php if ($layout_type === 'text-only') : ?>
                    <div class="about-section-title-col">
                        <?php if (!empty($section_title)) : ?>
                            <h2 class="about-section-title"><?php echo esc_html($section_title); ?></h2>
                        <?php endif; ?>
                    </div>
                    <div class="about-section-content">
                        <?php if (!empty($section_content)) : ?>
                            <div class="about-section-text">
                                <?php echo wp_kses_post($section_content); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                
                <!-- LAYOUT 2: Texto (izquierda) + Imagen (derecha) -->
                <?php elseif ($layout_type === 'text-image') : ?>
                    <div class="about-section-content">
                        <?php if (!empty($section_title)) : ?>
                            <h2 class="about-section-title"><?php echo esc_html($section_title); ?></h2>
                        <?php endif; ?>
                        <?php if (!empty($section_content)) : ?>
                            <div class="about-section-text">
                                <?php echo wp_kses_post($section_content); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($section_image)) : 
                        $image_url = is_array($section_image) ? $section_image['url'] : $section_image;
                        $image_alt = is_array($section_image) ? $section_image['alt'] : $section_title;
                    ?>
                        <div class="about-section-image">
                            <img 
                                src="<?php echo esc_url($image_url); ?>" 
                                alt="<?php echo esc_attr($image_alt); ?>"
                                loading="lazy"
                            />
                        </div>
                    <?php endif; ?>
                
                <!-- LAYOUT 3: Imagen (izquierda) + Texto (derecha) -->
                <?php elseif ($layout_type === 'image-text') : ?>
                    <?php if (!empty($section_image)) : 
                        $image_url = is_array($section_image) ? $section_image['url'] : $section_image;
                        $image_alt = is_array($section_image) ? $section_image['alt'] : $section_title;
                    ?>
                        <div class="about-section-image">
                            <img 
                                src="<?php echo esc_url($image_url); ?>" 
                                alt="<?php echo esc_attr($image_alt); ?>"
                                loading="lazy"
                            />
                        </div>
                    <?php endif; ?>
                    <div class="about-section-content">
                        <?php if (!empty($section_title)) : ?>
                            <h2 class="about-section-title"><?php echo esc_html($section_title); ?></h2>
                        <?php endif; ?>
                        <?php if (!empty($section_content)) : ?>
                            <div class="about-section-text">
                                <?php echo wp_kses_post($section_content); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </section>
    <?php
}
?>
