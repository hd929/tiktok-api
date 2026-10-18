<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // Token-based auth: frontend gửi Authorization header nên phải liệt kê
    // ở allowed_headers (dấu * không luôn được browser preflight chấp nhận).
    'allowed_origins' => ['http://localhost:3000', 'http://localhost:8080'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // SPA dùng cookie Sanctum thì cần true; với token-based cũng giữ true
    // để các request same-origin có thể dùng cookie khi cần.
    'supports_credentials' => true,

];
