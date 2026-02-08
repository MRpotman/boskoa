<?php
define('BOSKOA_RECAPTCHA_SITE_KEY', 'TU_SITE_KEY');
define('BOSKOA_RECAPTCHA_SECRET_KEY', 'TU_SECRET_KEY');

function boskoa_verify_recaptcha($token) {

    $response = wp_remote_post(
        'https://www.google.com/recaptcha/api/siteverify',
        [
            'body' => [
                'secret' => BOSKOA_RECAPTCHA_SECRET_KEY,
                'response' => $token
            ]
        ]
    );

    if (is_wp_error($response)) return false;

    $result = json_decode(wp_remote_retrieve_body($response));

    return $result->success && $result->score >= 0.5;
}
