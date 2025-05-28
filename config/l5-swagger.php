<?php

return [
    'default' => 'v1', // This refers to the 'v1' key within 'documentations'

    // The 'connections' array is typically for *additional* specs, but your setup
    // seems to be looking for the primary one under 'documentations'.
    // We'll remove the redundant 'connections' key structure for clarity based on your error.
    // If you explicitly need to define other connections, you can add them back,
    // but the 'default' and the main API definition will be in 'documentations'.

    'documentations' => [
        'v1' => [ // Define your 'v1' API documentation here
            'api' => [
                'info' => [
                    'title' => 'Your App API v1', // Distinct title
                    'description' => 'API documentation for version 1 of your application.',
                    'version' => '1.0.0', // Set the version for this specific documentation
                    'contact' => [
                        'email' => 'api-support@example.com',
                    ],
                    'license' => [
                        'name' => 'Apache 2.0',
                        'url' => 'http://www.apache.org/licenses/LICENSE-2.0.html',
                    ],
                ],
                'server' => [
                    [
                        'url' => env('APP_URL') . '/api/v1', // Base URL for v1 endpoints
                        'description' => 'Your API v1 server',
                    ],
                ],
                'security' => [
                    [
                        'bearerAuth' => [], // Ensure this matches your @OA\SecurityScheme name
                    ],
                ],
            ],
            'paths' => [
                                'docs' => storage_path('api-docs/api-v1-docs'), // Output file for v1 spec
                'annotations' => [
                    base_path('app/Http/Controllers/API/V1'), // Point to V1 controllers
                    base_path('app/Docs/V1/Requests'),   // Point to V1 requests
                    base_path('app/Models/API/V1'),          // Point to V1 models
                    base_path('app/Docs/V1'),                   // General OpenAPI info (like @OA\Info, @OA\SecurityScheme)
                ],
                                'docs_json' => 'api-v1-docs.json', // The filename to be served by the /docs route
                'format_to_use_for_docs' => 'json', // Explicitly state JSON format
            ],
            /*
             * Optional: You can place 'scanOptions', 'generate_always', 'generate_yaml_copy' here
             * if you want them specific to this 'v1' documentation set,
             * otherwise they will inherit from the 'defaults' section below.
             */
            'scanOptions' => [
                'exclude' => [],
                'open_api_spec_version' => env('L5_SWAGGER_OPEN_API_SPEC_VERSION', \L5Swagger\Generator::OPEN_API_DEFAULT_SPEC_VERSION),
            ],
            'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', false),
            'generate_yaml_copy' => env('L5_SWAGGER_GENERATE_YAML_COPY', false),
        ],

        // If you were to add a v2 later, it would go here under 'documentations':
        // 'v2' => [ /* ... v2 configuration similar to v1, pointing to V2 paths and a different output file */ ],
    ],

    /*
     * Global defaults that apply to all documentation sets unless overridden within
     * a specific documentation entry (like 'v1' above).
     */
    'defaults' => [
        'routes' => [
            'api' => 'api/documentation',
            'docs' => 'docs', // Route for generated JSON/YAML docs (e.g., /docs/api-v1.json)
            'oauth2_callback' => 'api/oauth2-callback',
            'middleware' => [
                'api' => [],
                'asset' => [],
                'docs' => [],
                'oauth2_callback' => [],
            ],
            'group_options' => [],
        ],
        'paths' => [
            'docs' => storage_path('api-docs'), // Base path for generated documentation files
            'views' => base_path('resources/views/vendor/l5-swagger'),
            'base' => env('L5_SWAGGER_BASE_PATH', null),
            'excludes' => [],
        ],
        'scanOptions' => [
            'default_processors_configuration' => [],
            'analyser' => null,
            'analysis' => null,
            'processors' => [],
            'pattern' => null,
            // 'exclude' => [], // This would be the global exclude, but better defined per connection
            'open_api_spec_version' => env('L5_SWAGGER_OPEN_API_SPEC_VERSION', \L5Swagger\Generator::OPEN_API_DEFAULT_SPEC_VERSION),
        ],
        'securityDefinitions' => [
            'securitySchemes' => [
                'bearerAuth' => [
                    'type' => 'http',
                    'scheme' => 'bearer',
                    'bearerFormat' => 'JWT',
                    'description' => 'Enter token in format (Bearer <token>)',
                ],
            ],
            'security' => [],
        ],
        // Global generate flags (can be overridden by documentation-specific settings)
        'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', false),
        'generate_yaml_copy' => env('L5_SWAGGER_GENERATE_YAML_COPY', false),

        'proxy' => false,
        'additional_config_url' => null,
        'operations_sort' => env('L5_SWAGGER_OPERATIONS_SORT', null),
        'validator_url' => null,
        'ui' => [
            'display' => [
                'dark_mode' => env('L5_SWAGGER_UI_DARK_MODE', false),
                'doc_expansion' => env('L5_SWAGGER_UI_DOC_EXPANSION', 'none'),
                'filter' => env('L5_SWAGGER_UI_FILTERS', true),
            ],
            'authorization' => [
                'persist_authorization' => env('L5_SWAGGER_UI_PERSIST_AUTHORIZATION', false),
                'oauth2' => [
                    'use_pkce_with_authorization_code_grant' => false,
                ],
            ],
        ],
        'constants' => [
            'L5_SWAGGER_CONST_HOST' => env('L5_SWAGGER_CONST_HOST', 'http://my-default-host.com'),
        ],
    ],
];
