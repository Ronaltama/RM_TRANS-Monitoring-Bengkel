<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure all of the cross-origin resource sharing settings
    | for your application. An asterisk (*) means that any origin is allowed
    | to access the resource, but you should change this to a specific origin
    | when deploying for production.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['http://localhost:5173', 'http://localhost:3000', 'http://localhost:8080'],

    'allowed_origins_patterns' => ['127.0.0.1:*'],

    'allowed_headers' => ['*'],

    'exposed_headers' => ['Content-Length', 'X-JSON-Response'],

    'max_age' => 0,

    'supports_credentials' => true,

];
