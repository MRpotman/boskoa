<?php
/**
 * Part: transport-card
 * 
 * Card de transporte para el grid.
 * Uso: get_template_part('parts/transport-card', null, ['transport' => $transport]);
 */

$transport = $args['transport'] ?? null;

if (empty($transport) || !is_array($transport)) {
    return;
}

// Etiqueta visual del tipo de ruta
$route_labels = [
    'one_way'    => pll__('One Way'),
    'round_trip' => pll__('Round Trip'),
    'both'       => pll__('One Way / Round Trip'),
];

$route_label = $route_labels[$transport['route_type'] ?? ''] ?? '';

?>

<a href="<?php echo esc_url($transport['link']); ?>" class="package-card-link transport-card-link">

    <div class="package-card transport-card" data-transport-id="<?php echo esc_attr($transport['id']); ?>">

        <div class="package-image">

            <?php if (!empty($transport['image'])): ?>
                <img src="<?php echo esc_url($transport['image']); ?>"
                     alt="<?php echo esc_attr($transport['title']); ?>"
                     loading="lazy">
            <?php else: ?>
                <div class="package-placeholder">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h11a2 2 0 012 2v3m-3 9h8m-8 0a2 2 0 100 4 2 2 0 000-4zm8 0a2 2 0 100 4 2 2 0 000-4zm-4-3V9a2 2 0 00-2-2h-1"/>
                    </svg>
                </div>
            <?php endif; ?>

            <div class="package-overlay">

                <h3 class="package-title">
                    <?php echo esc_html($transport['title']); ?>
                </h3>

                <div class="package-info">

                    <div class="package-meta transport-meta">

                        <!-- Origen → Destino -->
                        <?php if (!empty($transport['origin']) && !empty($transport['destination'])): ?>
                        <span class="transport-route">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M21 3L3 10.5l6.75 2.25L12 21l2.25-6.75L21 3z"/>
                            </svg>
                            <?php echo esc_html($transport['origin']); ?>
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" style="margin:0 2px;">
                                <path d="M13 5l7 7-7 7M5 5l7 7-7 7" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round"/>
                            </svg>
                            <?php echo esc_html($transport['destination']); ?>
                        </span>
                        <?php endif; ?>

                        <!-- Tipo de ruta badge -->
                        <?php if (!empty($route_label)): ?>
                        <span class="transport-type-badge">
                            <?php echo esc_html($route_label); ?>
                        </span>
                        <?php endif; ?>

                    </div>

                    <div class="package-actions transport-actions">

                        <span class="transport-contact-label">
                            <?php echo esc_html(pll__('Contact for pricing')); ?>
                        </span>

                        <span class="package-btn">
                            <?php echo esc_html(pll__('Book Now')); ?>
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

</a>