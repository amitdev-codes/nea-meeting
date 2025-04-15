<?php

return [
    'paths' => ['api/*',
    'storage/fonts/*',
    'public/frontend/assets/vendors/font-awesome/webfonts/*', // Add specific path to font files if in public directory
], // Adjust as needed
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'], // You can restrict this to specific domains
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
