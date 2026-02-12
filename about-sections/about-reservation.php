<?php
/**
 * Reservation Process Section
 * Muestra el título/descripción de la sección y 4 pasos del proceso de reservación
 * Con flechas curvas verdes alternadas (abajo-arriba-abajo)
 */

// 1. Obtener título y contenido principal de la sección
$main_query = new WP_Query([
    'post_type' => 'texto',
    'name' => 'reservation-process',
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
                            <div class="reservation-step-placeholder">
                                <span><?php echo $step['number']; ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Título del paso -->
                    <h3 class="reservation-step-title"><?php echo esc_html($step['title']); ?></h3>
                    
                    <!-- Flecha conectora con patrón alternado -->
                    <?php if ($index < count($steps) - 1) : ?>
                        <?php 
                        // Determinar tipo de flecha: 0=abajo, 1=arriba, 2=abajo
                        $arrow_type = $index % 2 === 0 ? 'down' : 'up';
                        ?>
                        
                        <div class="reservation-step-arrow arrow-<?php echo $arrow_type; ?>">
                            <?php if ($arrow_type === 'down') : ?>
                                <!-- Flecha curva hacia ABAJO -->
                                <svg width="140" height="100" viewBox="0 0 140 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 30 Q70 80, 130 50" 
                                          stroke="#7dd3c0" 
                                          stroke-width="4" 
                                          stroke-linecap="round" 
                                          fill="none"
                                          opacity="0.85"/>
                                    <path d="M123 45 L133 50 L123 55 Z" 
                                          fill="#7dd3c0" 
                                          opacity="0.85"/>
                                </svg>
                            <?php else : ?>
                                <!-- Flecha curva hacia ARRIBA -->
                                <svg width="140" height="100" viewBox="0 0 140 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 70 Q70 20, 130 50" 
                                          stroke="#7dd3c0" 
                                          stroke-width="4" 
                                          stroke-linecap="round" 
                                          fill="none"
                                          opacity="0.85"/>
                                    <path d="M123 45 L133 50 L123 55 Z" 
                                          fill="#7dd3c0" 
                                          opacity="0.85"/>
                                </svg>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                </div>
            <?php endforeach; ?>
        </div>
        
    </div>
</section>