<?php
define('BOSKOA_RECAPTCHA_SITE_KEY', '6LfgF2MsAAAAAI9p2MMRZB6attYxL4Mmbim1cl_5');
define('BOSKOA_RECAPTCHA_SECRET_KEY', '6LfgF2MsAAAAAJ6r5hLBZ7cyK_ueqogL29Y8sjXr');

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
