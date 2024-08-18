<?php


return [
    'app' => [
        'format' => [
            'date' => c::env('FORMAT_DATE', 'Y-m-d'),
            'datetime' => c::env('FORMAT_DATETIME', 'Y-m-d H:i:s'),
            'thousand_separator' => c::env('FORMAT_THOUSAND_SEPARATOR', ','),
            'decimal_separator' => c::env('FORMAT_DECIMAL_SEPARATOR', '.'),
            'decimal_digit' => c::env('FORMAT_DECIMAL_DIGIT', 0),
            'currency_decimal_digit' => c::env('FORMAT_CURRENCY_DECIMAL_DIGIT', 0),
            'currency_prefix' => c::env('FORMAT_CURRENCY_PREFIX', ''),
            'currency_suffix' => c::env('FORMAT_CURRENCY_SUFFIX', ''),
        ],
    ],
    'ajax'=>[
        'expiration' => 60,
    ],
    'auth'=>[

    ],

    'notification'=>[
        'web' => [
            'enable' => false,
            'debug' => c::env('ENVIRONMENT')!='PRODUCTION',
            'start_url' => '/',
            'driver' => 'firebase',
            'options' => c::env('GOOGLE_FIREBASE_WEB_JS_CONFIG'),
            // 'groups' => [
            //     'admin' => [
            //         'enable' => true,
            //         'start_url' => '/admin/',
            //     ],
            //     'app' => [
            //         'enable' => true,
            //         'start_url' => '/app/',
            //     ],
            // ],
        ]
    ]
];
