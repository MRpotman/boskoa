<?php
define('BOSKOA_RECAPTCHA_SITE_KEY', '6LdS9IIsAAAAAJwa2UjP7qhIyB_r_0BvqK3Bedg2');
define('BOSKOA_RECAPTCHA_SECRET_KEY', '6LdS9IIsAAAAADZ2VwdDWLC5CpbRykkYadD2aQ1I');

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

    if (is_wp_error($response)) {
        error_log('reCAPTCHA wp_error: ' . $response->get_error_message());
        return false;
    }

    $result = json_decode(wp_remote_retrieve_body($response));

    return $result->success && $result->score >= 0.5;
}