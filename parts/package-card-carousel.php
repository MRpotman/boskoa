<?php
$package = $args['package'];
?>

<div class="package-card-carousel" data-package-id="<?php echo esc_attr($package['id']); ?>">
    <div class="package-image-carousel">
        <img 
            src="<?php echo esc_url($package['image']); ?>" 
            alt="<?php echo esc_attr($package['title']); ?>" 
            loading="lazy"
        >

        <div class="package-overlay-carousel">
            <h3 class="package-title-carousel">
                <?php echo esc_html($package['title']); ?>
            </h3>

            <div class="package-info-carousel">
                <div class="package-meta-carousel">
                    <span class="package-location-carousel">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M8 0C5.243 0 3 2.243 3 5c0 4.5 5 11 5 11s5-6.5 5-11c0-2.757-2.243-5-5-5zm0 7c-1.105 0-2-.895-2-2s.895-2 2-2 2 .895 2 2-.895 2-2 2z"
                                fill="currentColor"
                            />
                        </svg>
                        <?php echo esc_html($package['location']); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
