<?php
add_action('edit_form_after_title', function() {
    $screen = get_current_screen();
    if ($screen->post_type !== 'tour_package') return;
    echo '<div class="notice notice-info" style="margin-top:20px;">
        <p><strong>💡 Instrucciones:</strong></p>
        <ul style="list-style:disc;margin-left:20px;">
            <li>El <strong>título</strong> es el nombre del paquete</li>
            <li>La <strong>imagen destacada</strong> es la foto principal</li>
            <li>El <strong>contenido</strong> es la descripción detallada (opcional)</li>
            <li>Completa <strong>precio</strong>, <strong>ubicaciones</strong> y marca si es <strong>family-friendly</strong></li>
            <li>Usa el <strong>orden</strong> para controlar la posición (0 = primero)</li>
        </ul>
    </div>';
});
