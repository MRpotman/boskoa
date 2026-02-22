<?php
$package = $args['package'] ?? null;

if (empty($package) || !is_array($package)) {
    return;
}

$show_price    = $args['show_price']    ?? true;
$show_button   = $args['show_button']   ?? true;
$show_location = $args['show_location'] ?? true;
$show_family   = $args['show_family']   ?? true;


?>
<a href="<?php echo esc_url($package['link']); ?>" class="package-card-link">

<div class="package-card" data-package-id="<?php echo esc_attr($package['id']); ?>">
    <div class="package-image">
        <?php if (!empty($package['image'])): ?>
            <img src="<?php echo esc_url($package['image']); ?>" alt="<?php echo esc_attr($package['title']); ?>"
                loading="lazy">

            <?php else: ?>
            <div class="package-placeholder">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path
                        d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z" />
                </svg>
            </div>
            <?php endif; ?>
        
        <div class="package-overlay">
            <h3 class="package-title"><?php echo esc_html($package['title']); ?></h3>

            <div class="package-info">
                <div class="package-meta">
                    <?php if ($show_location): ?>
                        <span class="package-location">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M8 0C5.243 0 3 2.243 3 5c0 4.5 5 11 5 11s5-6.5 5-11c0-2.757-2.243-5-5-5zm0 7c-1.105 0-2-.895-2-2s.895-2 2-2 2 .895 2 2-.895 2-2 2z"
                                fill="currentColor" />
                        </svg>
                           <?php echo esc_html($package['punto_de_encuentro']); ?>
                        </span>
                        <?php endif; ?>

                    <?php if ($show_family && $package['family']): ?>
                        <span class="package-family">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M5.5 4.5c0 1.105-.895 2-2 2s-2-.895-2-2 .895-2 2-2 2 .895 2 2zm9 0c0 1.105-.895 2-2 2s-2-.895-2-2 .895-2 2-2 2 .895 2 2zM6 14v-3c0-.828-.672-1.5-1.5-1.5S3 10.172 3 11v3h3zm7 0v-3c0-.828-.672-1.5-1.5-1.5S10 10.172 10 11v3h3zm-5.5-6c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM10 14v-3c0-.828-.672-1.5-1.5-1.5S7 10.172 7 11v3h3z"
                                fill="currentColor" />
                        </svg>
                            Family
                        </span>
                        <?php endif; ?>
                </div>

                <div class="package-actions">
                    <?php if ($show_price): ?>
                        <span class="package-price">
                            $ <?php echo esc_html($package['price']); ?>
                        </span>
                    <?php endif; ?>

                    <?php if ($show_button): ?>
                    <span class="package-btn">
                            Book Now
                        </span>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>
</a>