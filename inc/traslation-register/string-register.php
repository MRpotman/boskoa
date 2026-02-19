<?php
function boskoa_register_strings() {

    $strings = [
        'SEE PACKS →',
        '+2 MILLIONS',
        'Many people visit this natural paradise',
        'Contact us >>',
        'What our customer say!!',
        'See all activities',
        'Our Activities',
        'Our packets',
        'See all tours',
        'Costa Rica',
        'To learn more about our products and services, write to us and we will gladly assist you.',
        'Name',
        'Email',
        'Matters',
        'Message',
        'Send now',
        'All Rights Reserved',
        'No hay actividades disponibles',
        'Estamos trabajando en nuevas actividades. Por favor vuelve pronto.',
        'Cargando paquetes...',
        'No hay paquetes disponibles',
        'Estamos trabajando en nuevos paquetes turísticos. Por favor, vuelve pronto.',
        'Añadir Primer Paquete',
        'Página anterior',
        'Ir a la página',
        'Próxima página',
        

    ];

    foreach ($strings as $string) {

        pll_register_string(
            sanitize_title($string),
            $string,
            'Boskoa Theme'
        );

    }

}

add_action('init', 'boskoa_register_strings');
