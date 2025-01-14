<?php

return [
    'paths' => ['api/*', 'auth/*', 'open/*', 'wallet/*', 'users/*', '*/*'],
    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'], 
    'allowed_origins' => ['http://localhost:8080'], 
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['Content-Type', 'Authorization', 'Accept', 'X-Requested-With', 'X-Auth-Token', 'Origin'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];




