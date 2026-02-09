<?php
$package = $args['package'];
?>

<div class="package-card" data-package-id="<?php echo esc_attr($package['id']); ?>">
    <div class="package-image">
        <img 
            src="<?php echo esc_url($package['image']); ?>" 
            alt="<?php echo esc_attr($package['title']); ?>" 
            loading="lazy"
        >

        <div class="package-overlay">
            <h3 class="package-title">
                <?php echo esc_html($package['title']); ?>
            </h3>

            <div class="package-info">
                <div class="package-meta">
                    <span class="package-location">
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
