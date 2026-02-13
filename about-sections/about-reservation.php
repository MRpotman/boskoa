<?php
/**
 * Reservation Process Section
 * Muestra el título/descripción de la sección y 4 pasos del proceso de reservación
 */

// 1. Obtener título y contenido principal de la sección
$main_query = new WP_Query([
    'post_type' => 'texto',
    'name' => 'reservation-process', // Post principal con título y descripción
    'posts_per_page' => 1,
]);

$section_title = 'Make an easy reservation';
$section_description = '';

if ($main_query->have_posts()) {
    while ($main_query->have_posts()) {
        $main_query->the_post();
        $section_title = get_field('titulo') ?: get_field('Titulo') ?: get_the_title();
        $section_description = get_field('contenido') ?: get_field('Contenido') ?: '';
    }
    wp_reset_postdata();
}

// 2. Obtener los 4 pasos del proceso
$steps = array();

// Buscar posts con slug pattern "reservation-step-1", "reservation-step-2", etc.
for ($i = 1; $i <= 4; $i++) {
    $step_query = new WP_Query([
        'post_type' => 'texto',
        'name' => 'reservation-step-' . $i,
        'posts_per_page' => 1,
    ]);
    
    if ($step_query->have_posts()) {
        while ($step_query->have_posts()) {
            $step_query->the_post();
            
            $step_title = get_field('titulo') ?: get_field('Titulo') ?: get_the_title();
            $step_image = get_field('imagen') ?: get_field('Imagen') ?: '';
            
            // Procesar imagen
            $image_url = '';
            if (!empty($step_image)) {
                $image_url = is_array($step_image) ? $step_image['url'] : $step_image;
            }
            
            $steps[] = array(
                'title' => $step_title,
                'image' => $image_url,
                'number' => $i,
            );
        }
        wp_reset_postdata();
    }
}

// Si no hay pasos, no mostrar la sección
if (empty($steps)) {
    return;
}
?>

<section class="reservation-process-section">
    <div class="container-reservation">
        
        <!-- Título y descripción principal -->
        <div class="reservation-process-header">
            <h2 class="reservation-process-title"><?php echo esc_html($section_title); ?></h2>
            <?php if (!empty($section_description)) : ?>
                <div class="reservation-process-description">
                    <?php echo wp_kses_post($section_description); ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Pasos del proceso -->
        <div class="reservation-steps-wrapper">
            <?php foreach ($steps as $index => $step) : ?>
                <div class="reservation-step">
                    
                    <!-- Icono/Imagen -->
                    <div class="reservation-step-icon">
                        <?php if (!empty($step['image'])) : ?>
                            <img 
                                src="<?php echo esc_url($step['image']); ?>" 
                                alt="<?php echo esc_attr($step['title']); ?>"
                                loading="lazy"
                            />
                        <?php else : ?>
                            <!-- Placeholder si no hay imagen -->
                            <div class="reservation-step-placeholder">
                                <span><?php echo $step['number']; ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Título del paso -->
                    <h3 class="reservation-step-title"><?php echo esc_html($step['title']); ?></h3>
                    
                    <!-- Flecha conectora (no se muestra en el último paso) -->
                    <?php if ($index < count($steps) - 1) : ?>
                        <div class="reservation-step-arrow">
                            <svg width="60" height="40" viewBox="0 0 60 40" fill="none" xmlns="http://www.w3.org/2000/svg" style="transform: scale(1.5);
">
                                <path d="M5 20C5 20 20 5 30 20C40 35 55 20 55 20" stroke="#7dd3c0" stroke-width="3" stroke-linecap="round"/>
                                <path d="M 50 17 L 56.337 18.898 L 56 26" stroke="#7dd3c0" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                    
                </div>
            <?php endforeach; ?>
        </div>
        
    </div>
</section>