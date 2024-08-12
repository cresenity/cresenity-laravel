<?php


return [
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
