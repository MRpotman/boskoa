<?php if (isset($_GET['contact'])): ?>
    <div class="contact-notification <?php echo $_GET['contact'] === 'success' ? 'success' : 'error'; ?>" id="contactNotification">
        <div class="notification-content">
            <?php if ($_GET['contact'] === 'success'): ?>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <polyline points="22 4 12 14.01 9 11.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div>
                    <strong><?php echo esc_html(pll__('¡Mensaje enviado!')); ?></strong>
                    <p><?php echo esc_html(pll__('Gracias por contactarnos. Te responderemos pronto.')); ?></p>
                </div>
            <?php else: ?>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                    <line x1="12" y1="8" x2="12" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <line x1="12" y1="16" x2="12.01" y2="16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <div>
                    <strong><?php echo esc_html(pll__('Error al enviar')); ?></strong>
                    <p><?php echo esc_html(pll__('Por favor, verifica los datos e intenta nuevamente.')); ?></p>
                </div>
            <?php endif; ?>
            <button class="notification-close" onclick="closeNotification()">&times;</button>
        </div>
    </div>

    <script>
        function closeNotification() {
            document.getElementById('contactNotification').style.display = 'none';
            // Limpiar URL
            const url = new URL(window.location);
            url.searchParams.delete('contact');
            window.history.replaceState({}, '', url);
        }
        
        // Auto-cerrar después de 5 segundos
        setTimeout(() => {
            const notification = document.getElementById('contactNotification');
            if (notification) {
                notification.style.opacity = '0';
                setTimeout(() => closeNotification(), 300);
            }
        }, 5000);
    </script>
<?php endif; ?>