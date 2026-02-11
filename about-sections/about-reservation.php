<?php
/**
 * Reservation Process Section
 */

// -----------------------------
// 1️⃣ Obtener sección principal
// -----------------------------

$section_title = 'Make an easy reservation';
$section_description = '';

$main_post = get_page_by_path('reservation-process', OBJECT, 'texto');

if ($main_post) {
    $section_title = get_field('titulo', $main_post->ID) ?: get_the_title($main_post->ID);
    $section_description = get_field('contenido', $main_post->ID) ?: '';
}

$steps = [];

for ($i = 1; $i <= 4; $i++) {

    $step_post = get_page_by_path('reservation-step-' . $i, OBJECT, 'texto');

    if ($step_post) {

        $step_title = get_field('titulo', $step_post->ID) ?: get_the_title($step_post->ID);
        $step_image = get_field('imagen', $step_post->ID);

        
        $image_url = '';
        if (!empty($step_image)) {
            $image_url = is_array($step_image) ? $step_image['url'] : $step_image;
        }

        $steps[] = [
            'title'  => $step_title,
            'image'  => $image_url,
            'number' => $i,
        ];
    }
}


if (empty($steps)) {
    return;
}
?>

<section class="reservation-process-section">
    <div class="container-reservation">

        
        <div class="reservation-process-header">
            <h2 class="reservation-process-title">
                <?php echo esc_html($section_title); ?>
            </h2>

            <?php if (!empty($section_description)) : ?>
                <div class="reservation-process-description">
                    <?php echo wp_kses_post($section_description); ?>
                </div>
            <?php endif; ?>
        </div>

       
        <div class="reservation-steps-wrapper">
            <?php foreach ($steps as $index => $step) : ?>
                <div class="reservation-step">

                   
                    <div class="reservation-step-icon">
                        <?php if (!empty($step['image'])) : ?>
                            <img 
                                src="<?php echo esc_url($step['image']); ?>" 
                                alt="<?php echo esc_attr($step['title']); ?>"
                                loading="lazy"
                            />
                        <?php else : ?>
                            <div class="reservation-step-placeholder">
                                <span><?php echo esc_html($step['number']); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                   
                    <h3 class="reservation-step-title">
                        <?php echo esc_html($step['title']); ?>
                    </h3>

                   
                    <?php if ($index < count($steps) - 1) : ?>
                        <div class="reservation-step-arrow">
                            <svg width="60" height="40" viewBox="0 0 60 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 20C5 20 20 5 30 20C40 35 55 20 55 20" stroke="#7dd3c0" stroke-width="3" stroke-linecap="round"/>
                                <path d="M50 15L55 20L50 25" stroke="#7dd3c0" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    <?php endif; ?>

                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
