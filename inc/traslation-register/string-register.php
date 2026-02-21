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
        'No activities available.',
        'We are working on new activities. Please come back soon.',
        'Loading packages...',
        'No packages available.',
        'We are working on new tour packages. Please come back soon.',
        'Add First Package',
        'Previous page',
        'Go to page',
        'Next page',
        'Enjoy your vacation with our tour:',
        'Book Now',
        'View other activities',
        'DESCRIPTION',
        'ADDITIONAL INFORMATION',
        'Host languages',
        'MEETING POINT',
        'Open in Google Maps',
        'ITINERARY',
        'The Team',
        'Message sent!',
        'Thank you for contacting us. We will get back to you soon.',
        'Error sending.',
        'Please check the information and try again.',
        'Book Know',
        'Contact Us',
        'Loading activities...'

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
