<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Serial Number Configuration
    |--------------------------------------------------------------------------
    |
    | Controls how serial numbers are generated for CAs and certificates.
    |
    */
    'serial_number' => [
        'bytes' => 20,
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Values
    |--------------------------------------------------------------------------
    |
    | Default values used when creating CAs and certificates if not
    | explicitly provided.
    |
    */
    'defaults' => [
        'key_algorithm' => 'rsa-4096',
        'hash_algorithm' => 'sha256',

        'validity' => [
            'root_ca' => 3650,        // 10 years
            'intermediate_ca' => 1825, // 5 years
            'end_entity' => 365,       // 1 year
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Storage
    |--------------------------------------------------------------------------
    |
    | Configure where keys and certificates are stored.
    | For this demo, we use the database directly.
    |
    */
    'storage' => [
        'driver' => 'database',
        'disk' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Encryption
    |--------------------------------------------------------------------------
    |
    | How private keys are encrypted at rest.
    | The 'app_key' strategy uses Laravel's APP_KEY.
    |
    */
    'encryption' => [
        'strategy' => 'app_key',
    ],

    /*
    |--------------------------------------------------------------------------
    | Multi-Tenancy
    |--------------------------------------------------------------------------
    |
    | Enable or disable multi-tenant support.
    | Disabled for this demo application.
    |
    */
    'multi_tenant' => [
        'enabled' => false,
    ],

];
