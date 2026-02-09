<?php
function boskoa_fonts() {
    wp_enqueue_style(
        'boskoa-google-fonts',
        'https://fonts.googleapis.com/css2?family=Architects+Daughter&family=Poppins:wght@100;300;400;500;700;900&display=swap',
        [],
        null
    );
}
add_action('wp_enqueue_scripts', 'boskoa_fonts');
