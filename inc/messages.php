<?php
add_filter('post_updated_messages', function($messages) {
    $post = get_post();
    $messages['tour_package'] = [
        0  => '',
        1  => 'Paquete actualizado.',
        2  => 'Campo personalizado actualizado.',
        3  => 'Campo personalizado eliminado.',
        4  => 'Paquete actualizado.',
        5  => isset($_GET['revision']) ? sprintf('Paquete restaurado desde la revisión del %s', wp_post_revision_title((int) $_GET['revision'], false)) : false,
        6  => 'Paquete publicado.',
        7  => 'Paquete guardado.',
        8  => 'Paquete enviado.',
        9  => sprintf('Paquete programado para: <strong>%1$s</strong>.', date_i18n('M j, Y @ G:i', strtotime($post->post_date))),
        10 => 'Borrador de paquete actualizado.',
    ];
    return $messages;
});
