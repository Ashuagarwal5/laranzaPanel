<?php
return [
    'mode'    => 'live', // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
    'sandbox' => [
        'username'    => 'payment-facilitator@codespur.com',
        'password'    => 'WF9525LGS27GMURZ',
        'secret'      => 'EFwtokFQ9ySWvJ5oeSZtIj5x_NrE_qVOFtVLfcf84OB_1XN629HldW4OmtdmQUiOlZos18tHWMdN7yFD',
        'certificate' => '',
        'app_id'      => '', // Used for testing Adaptive Payments API in sandbox mode
    ],
    'live' => [
        'username'    => 'careerhacks00_api1.gmail.com',//'codespur_api1.gmail.com',
        'password'    => 'NERT5AMWP3U3ELF5',//'ZP3EA4SFKDCQR4EJ',
        'secret'      => 'AjvTWoxX8Rv9wYiQPBw5J5xRvX.HAnE8yfaRSLmMoeeNmCODz22TAz-z',
        'certificate' => '',
        'app_id'      => '', // Used for Adaptive Payments API
    ],

    'payment_action' => 'Sale', // Can only be 'Sale', 'Authorization' or 'Order'
    'currency'       => 'INR',
    'notify_url'     => 'http://www.careerhacks.in/ipn/notify', // Change this accordingly for your application.
    'locale'         => 'en_US', // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
    'validate_ssl'   => true, // Validate SSL when creating api client.
];
