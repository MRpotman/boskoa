<?php
function boskoa_fonts() {
    wp_enqueue_style(
        'boskoa-google-fonts',
        'https://fonts.googleapis.com/css2?family=Architects+Daughter&family=Climate+Crisis:YEAR@1979&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap',
        [],
        null
    );
}
add_action('wp_enqueue_scripts', 'boskoa_fonts');
