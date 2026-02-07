<footer class="site-footer" id="contact">
    <div class="footer-container">

        <!-- Sección principal del footer -->
        <div class="footer-main">

            <!-- Columna 1: Logo y descripción -->
            <!-- Columna 1: Logo -->
            <div class="footer-column footer-logo">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="logo-link">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/boskoalogo.png"
                        alt="Boskoa Travels Costa Rica" class="footer-logo-img">
                </a>
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
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="btn-icon">
                                <path d="M4 10h12M12 6l4 4-4 4" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>

                    <!-- Campos ocultos -->
                    <input type="hidden" name="recaptcha_token" id="recaptchaToken">
                    <input type="hidden" name="action" value="boskoa_contact_form">
                    <?php wp_nonce_field('boskoa_contact_form', 'contact_nonce'); ?>
                </form>

                <!-- Aviso de reCAPTCHA -->
                <p class="recaptcha-notice">
                    This site is protected by reCAPTCHA and the Google
                    <a href="https://policies.google.com/privacy" target="_blank">Privacy Policy</a> and
                    <a href="https://policies.google.com/terms" target="_blank">Terms of Service</a> apply.
                </p>
            </div>

            <!-- Columna 3: Información de contacto -->
            <div class="footer-column footer-contact-info">
                <h3>Contact Us</h3>

                <div class="contact-item">
                    <svg class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path
                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <a href="tel:+50688888888">(+506) 8888-8888</a>
                </div>

                <div class="contact-item">
                    <svg class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="m22 6-10 7L2 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <a href="mailto:ejemplocorreo@gmail.com">ejemplocorreo@gmail.com</a>
                </div>

                <div class="contact-item">
                    <svg class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <a href="https://wa.me/50688888888" target="_blank">(+506) 8888-8888</a>
                </div>

                <!-- Redes sociales -->
                <div class="social-links">
                    <a href="https://instagram.com" target="_blank" aria-label="Instagram">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                        </svg>
                    </a>
                    <a href="https://facebook.com" target="_blank" aria-label="Facebook">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </a>
                    <a href="https://wa.me/50688888888" target="_blank" aria-label="WhatsApp">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                        </svg>
                    </a>
                    <a href="https://linkedin.com" target="_blank" aria-label="LinkedIn">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                            <path
                                d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                        </svg>
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
    <!-- Script de reCAPTCHA v3 -->
    <?php if (defined('BOSKOA_RECAPTCHA_SITE_KEY') && BOSKOA_RECAPTCHA_SITE_KEY): ?>
    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo BOSKOA_RECAPTCHA_SITE_KEY; ?>"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('contactForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = submitBtn.querySelector('.btn-text');

        if (!form) return;

        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Evita envío normal

            // Deshabilitar botón durante el proceso
            submitBtn.disabled = true;
            const originalText = btnText.textContent;
            btnText.textContent = 'Verifying...';

            // Ejecutar reCAPTCHA
            grecaptcha.ready(function() {
                grecaptcha.execute('<?php echo BOSKOA_RECAPTCHA_SITE_KEY; ?>', {
                        action: 'contact_form'
                    })
                    .then(function(token) {
                        // Añadir token al campo oculto
                        document.getElementById('recaptchaToken').value = token;

                        // Cambiar texto del botón
                        btnText.textContent = 'Sending...';

                        // Enviar el formulario
                        form.submit();
                    })
                    .catch(function(error) {
                        // Error en reCAPTCHA
                        console.error('reCAPTCHA error:', error);
                        alert(
                            'Error de verificación. Por favor, recarga la página e intenta nuevamente.'
                        );

                        // Rehabilitar botón
                        submitBtn.disabled = false;
                        btnText.textContent = originalText;
                    });
            });
        });
    });
    // Función para mostrar notificaciones
    function showNotification(type, title, message) {
        // Crear notificación
        const notification = document.createElement('div');
        notification.className = `contact-notification ${type}`;
        notification.innerHTML = `
                                <div class="notification-content">
                                    ${type === 'success' ? `
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <polyline points="22 4 12 14.01 9 11.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    ` : `
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                                            <line x1="12" y1="8" x2="12" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                            <line x1="12" y1="16" x2="12.01" y2="16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                        </svg>
                                    `}
                                    <div>
                                        <strong>${title}</strong>
                                        <p>${message}</p>
                                    </div>
                                    <button class="notification-close" onclick="this.parentElement.parentElement.remove()">&times;</button>
                                </div>
                            `;

        document.body.appendChild(notification);

        // Auto-cerrar después de 5 segundos
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }
    </script>
    <?php endif; ?>
</footer>
<?php get_template_part('parts/contact-notification'); ?>
<?php wp_footer(); ?>
</body>

</html>