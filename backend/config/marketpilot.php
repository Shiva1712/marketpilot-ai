
<?php

return [
    'social_providers' => [
        'facebook',
        'linkedin',
        'google',
    ],

    'frontend_url' => env(
        'FRONTEND_URL',
        'http://localhost:5173'
    ),

    'social_scopes' => [
        'facebook' => ['email', 'public_profile'],
        'linkedin' => ['openid', 'profile', 'email'],
        'google' => ['openid', 'profile', 'email'],
    ],
];
