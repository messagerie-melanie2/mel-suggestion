<?php

return [
  'external_connector' => explode(',', env('EXTERNAL_CONNECTOR')),
  'external_proxy' => env('EXTERNAL_PROXY'),

  'google' => [
    'client_url' => env('GOOGLE_URL'),
    'client_id' => env('GOOGLE_ID'),
    'client_secret' => env('GOOGLE_SECRET'),
    'client_fields' => explode(',', env('GOOGLE_FIELDS'))
  ],

  'microsoft' => [
    'client_url' => env('MICROSOFT_URL'),
    'client_id' => env('MICROSOFT_ID'),
    'client_secret' => env('MICROSOFT_SECRET'),
    'client_fields' => explode(',', env('MICROSOFT_FIELDS'))
  ],

  'cerbere' => [
    'client_url' => env('CERBERE_URL'),
    'client_id' => env('CERBERE_ID'),
    'client_secret' => env('CERBERE_SECRET'),
    'client_fields' => explode(',', env('CERBERE_FIELDS'))
  ]
];
