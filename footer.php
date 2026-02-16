<footer class="site-footer" id="contact">
    <div class="footer-container">
        <!-- Sección principal del footer -->
        <div class="footer-main">
            <!-- Columna 1: Logo y descripción -->
            <!-- Columna 1: Logo -->
            <div class="footer-column footer-logo">
                <!-- ================= LOGO ================= -->
                <div class="site-branding">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="logo">
                        <div class="logo-content">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/boskoa-logo.png"
                                alt="Boskoa Travel Logo" class="logo-image">
                            <div>
                                <span>Boskoa Travel</span>
                                <div class="tiquicia">
                                    <div class="logo-divider">
                                        <svg width="90" height="28" viewBox="-10 -10 200 80"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path class="spiral-path"
                                                d="M13.109 29.63C9.07 6.576 45.923 6.913 39.498 34.027 33.215 57.413-.858 58.069-6.2 32.85-9.535 7.479 7.051-9.915 46.596-1.501 109.531 13.812 147.35 36.05 175.65 0"
                                                fill="none" stroke="#846E59" stroke-width="4" />
                                        </svg>
                                    </div>
                                    <span class="subtitle">Costa Rica</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <!-- Columna 2: Formulario de contacto -->
            <div class="footer-column footer-contact-form">
                <h3>To learn more about our products and services,<br>write to us and we will gladly assist you.</h3>

                <form class="contact-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
                    id="contactForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="contact-name">Name</label>
                            <input type="text" id="contact-name" name="contact_name" required>
                        </div>
                        <div class="form-group">
                            <label for="contact-email">Email</label>
                            <input type="email" id="contact-email" name="contact_email" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="contact-matters">Matters</label>
                        <input type="text" id="contact-matters" name="contact_matters" required>
                    </div>

                    <div class="form-group">
                        <label for="contact-message">Message</label>
                        <textarea id="contact-message" name="contact_message" rows="5" required></textarea>
                    </div>

                    <div class="form-submit">
                        <button type="submit" class="btn-send" id="submitBtn">
                            <span class="btn-text">Send now</span>
                            <i class="fas fa-arrow-right btn-icon"></i>
                        </button>
                    </div>

                    <input type="hidden" name="recaptcha_token" id="recaptchaToken">
                    <input type="hidden" name="action" value="boskoa_contact_form">
                    <?php wp_nonce_field('boskoa_contact_form', 'contact_nonce'); ?>
                </form>
            </div>

            <!-- Columna 3: Información de contacto -->
            <div class="footer-column footer-contact-info">
                <h3>Contact Us</h3>

                <div class="contact-item">
                    <i class="fas fa-phone icon"></i>
                    <a href="tel:+50688888888">(+506) 8888-8888</a>
                </div>

                <div class="contact-item">
                    <i class="fas fa-envelope icon"></i>
                    <a href="mailto:ejemplocorreo@gmail.com">ejemplocorreo@gmail.com</a>
                </div>

                <div class="contact-item">
                    <i class="fab fa-whatsapp icon"></i>
                    <a href="https://wa.me/50688888888" target="_blank">(+506) 8888-8888</a>
                </div>

                <!-- Redes sociales -->
                <div class="social-links">
                    <a href="https://instagram.com" target="_blank" aria-label="Instagram">
                        <i class="fab fa-instagram" style="font-size: 26px;"></i>
                    </a>
                    <a href="https://facebook.com" target="_blank" aria-label="Facebook">
                        <i class="fab fa-facebook-f" style="font-size: 26px;"></i>
                    </a>
                    <a href="https://wa.me/50688888888" target="_blank" aria-label="WhatsApp">
                        <i class="fab fa-whatsapp" style="font-size: 26px;"></i>
                    </a>
                    <a href="https://linkedin.com" target="_blank" aria-label="LinkedIn">
                        <i class="fab fa-linkedin-in" style="font-size: 26px;"></i>
                    </a>
                </div>
            </div>

        </div>

        <!-- Línea divisoria -->
        <hr class="footer-divider">

        <!-- Footer bottom -->
        <div class="footer-bottom">
            <p class="copyright">
                Copyright © <?php echo date('Y'); ?>, BoskoaTravel.com, All Right Reserved
            </p>
            <div class="payment-methods">
                <?php
                    // Query para obtener los métodos de pago
                    $payment_methods = new WP_Query([
                        'post_type'      => 'payment_method',
                        'posts_per_page' => -1,
                        'post_status'    => 'publish',
                        'orderby'        => 'meta_value_num',
                        'meta_key'       => '_payment_order',
                        'order'          => 'ASC',
                    ]);

                    if ($payment_methods->have_posts()) :
                        while ($payment_methods->have_posts()) : $payment_methods->the_post();
                            if (has_post_thumbnail()) :
                                $logo_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                                $payment_name = get_the_title();
                                ?>
                <div class="payment-logo-wrapper" title="<?php echo esc_attr($payment_name); ?>">
                    <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($payment_name); ?>"
                        title="<?php echo esc_attr($payment_name); ?>" class="payment-logo">
                </div>
                <?php
                            endif;
                        endwhile;
                        wp_reset_postdata();
                    else :
                        // Métodos de pago por defecto si no hay ninguno creado
                        ?>
                <?php
                    endif;
                    ?>
            </div>
        </div>
    </div>

    </div>

    <!-- reCAPTCHA v3 Script -->
    <?php if (defined('BOSKOA_RECAPTCHA_SITE_KEY') && BOSKOA_RECAPTCHA_SITE_KEY): ?>
    <script>
    window.boskoaRecaptchaSiteKey = '<?php echo esc_js(BOSKOA_RECAPTCHA_SITE_KEY); ?>';
    </script>
    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo esc_attr(BOSKOA_RECAPTCHA_SITE_KEY); ?>">
    </script>
    <?php endif; ?>
</footer>
<?php get_template_part('parts/contact-notification'); ?>
<?php wp_footer(); ?>
</body>

</html>